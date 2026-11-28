<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pencairan extends CI_Controller
{
    private function respond($data, $status = 200)
    {
        return $this->output->set_status_header($status)->set_content_type('application/json')->set_output(json_encode($data));
    }

    private function allowed($action)
    {
        if (!$this->session->userdata('logged_in')) { $this->respond(array('message'=>'Sesi berakhir. Silakan login kembali.'),401); return FALSE; }
        if ($this->session->userdata('roleid') === 'ADM') return TRUE;
        $access=$this->db->get_where('mauth',array('roleid'=>$this->session->userdata('roleid'),'moid'=>'CI','mid'=>'T001'))->row();
        if (!$access || empty($access->$action)) { $this->respond(array('message'=>'Anda tidak memiliki akses pencairan (Pembayaran/Cash In).'),403); return FALSE; }
        return TRUE;
    }

    private function spr($projid,$sprno)
    {
        $context=$this->input->method()==='post' ? $this->input->post('pengajuan_id') : $this->input->get('pengajuan_id');
        if ($context!==NULL) {
            if (!is_string($context) || !ctype_digit($context) || (int)$context<1) return NULL;
            $this->load->model('mod_pengajuan_akad');
            if (!$this->mod_pengajuan_akad->permission($this->input->method()==='post' ? 'u' : 'r')) return NULL;
            return $this->db->query("SELECT h.sprno,h.kpr,h.nama,h.blok,h.no FROM tsprh h
                JOIN tpengajuan_akad_peserta m ON m.projid=h.projid AND m.sprno=h.sprno
                JOIN tpengajuan_akad a ON a.id=m.pengajuan_id
                WHERE m.pengajuan_id=? AND h.projid=? AND h.sprno=? AND h.status<>'BTL'
                AND (a.projid IS NULL OR a.projid=0 OR a.projid=h.projid)",array((int)$context,$projid,$sprno))->row();
        }
        return $this->db->query("SELECT h.sprno,h.kpr,h.nama,h.blok,h.no FROM tsprh h INNER JOIN mkav k ON k.projid=h.projid AND k.blok=h.blok AND k.no=h.no WHERE h.projid=? AND h.sprno=? AND h.status <> 'BTL' AND k.status IN ('AKD','AKS')",array($projid,$sprno))->row();
    }

    public function data($projid=NULL,$sprno=NULL)
    {
        if (!$this->allowed('r')) return;
        $spr=$this->spr((int)$projid,(int)$sprno);
        if (!$spr) { $this->respond(array('message'=>'SPR akad tidak ditemukan.'),404); return; }
        if (!$this->session->userdata('pencairan_token')) $this->session->set_userdata('pencairan_token',bin2hex(random_bytes(32)));
        $history=$this->db->order_by('id','DESC')->get_where('tpencairan',array('projid'=>$projid,'sprno'=>$sprno))->result();
        foreach ($history as $item) $item->retensi=$this->db->get_where('tpencairan_retensi',array('pencairan_id'=>$item->id))->result();
        $categories=$this->db->order_by('nama_retensi')->get('mretensi_kategori')->result();
        $plafon=(float)$spr->kpr;$kpp=0;$paid=0;
        $project=$this->db->select('kkp')->get_where('mproj',array('projid'=>(int)$projid))->row();
        $kkp_default=$project?(float)$project->kkp:0;
        $kpp=$kkp_default;
        if ($history) {
            $plafon=(float)$history[0]->plafon;
            foreach ($history as $item) $paid+=(float)$item->jumlah;
            $categories=array();
            foreach ($history[0]->retensi as $line) $categories[]=(object)array('id'=>$line->kategori_id,'nama_retensi'=>$line->nama_retensi,'sisa'=>$line->nilai);
        }
        $this->respond(array('spr'=>$spr,'kategori'=>$categories,'histori'=>$history,'plafon'=>$plafon,'kpp'=>$kpp,'kkp'=>$kkp_default,'sisa'=>max(0,$plafon-$kpp-$paid),'base_id'=>$history?$history[0]->id:0,'token'=>$this->session->userdata('pencairan_token'),'request_key'=>bin2hex(random_bytes(16))));
    }

    private function amount($value)
    {
        return is_scalar($value) && preg_match('/^\d{1,13}$/D',(string)$value) ? (int)$value : NULL;
    }

    public function save()
    {
        if (!$this->allowed('c')) return;
        if ($this->input->method()!=='post') { $this->respond(array('message'=>'Metode tidak diizinkan.'),405);return; }
        $token=$this->session->userdata('pencairan_token');
        if (!$token || !hash_equals($token,(string)$this->input->post('token'))) { $this->respond(array('message'=>'Form tidak valid. Buka ulang modal.'),403);return; }
        $projid=(int)$this->input->post('projid');$sprno=(int)$this->input->post('sprno');
        $plafon=$this->amount($this->input->post('plafon'));
        $key=(string)$this->input->post('request_key');
        if ($plafon===NULL || $plafon<=0 || !preg_match('/^[a-f0-9]{32}$/D',$key)) { $this->respond(array('message'=>'Masukkan plafon lebih dari nol berupa rupiah bulat, maksimal 13 digit.'),422);return; }
        $spr=$this->spr($projid,$sprno);
        if (!$spr) { $this->respond(array('message'=>'SPR akad tidak ditemukan.'),404);return; }
        $this->db->trans_begin();
        $this->db->query('SELECT sprno FROM tsprh WHERE projid=? AND sprno=? FOR UPDATE',array($projid,$sprno));
        $project=$this->db->query('SELECT kkp FROM mproj WHERE projid=? FOR UPDATE',array($projid))->row();
        if (!$project) { $this->db->trans_rollback();$this->respond(array('message'=>'Proyek tidak ditemukan.'),404);return; }
        $project_kkp=(int)$project->kkp;$kpp=$project_kkp;
        $retry=$this->db->get_where('tpencairan',array('request_key'=>$key))->row();
        if ($retry) {
            $this->db->trans_rollback();
            $same=(int)$retry->projid===$projid && (int)$retry->sprno===$sprno && $retry->created_by===$this->session->userdata('userid');
            $this->respond(array('message'=>$same?'Pencairan sudah berhasil disimpan.':'Referensi transaksi tidak valid.'),$same?200:409);return;
        }
        $history=$this->db->order_by('id','DESC')->get_where('tpencairan',array('projid'=>$projid,'sprno'=>$sprno))->result();
        $latest=$history?$history[0]:NULL;
        if ((int)$this->input->post('base_id')!==($latest?(int)$latest->id:0)) {
            $this->db->trans_rollback();$this->respond(array('message'=>'Data pencairan berubah. Buka ulang modal agar memakai sisa terbaru.'),409);return;
        }
        $categories=$this->db->order_by('id')->get('mretensi_kategori')->result();
        $available=$plafon-$kpp;
        if ($latest) {
            $plafon=(int)$latest->plafon;$kpp=0;$paid=0;
            foreach ($history as $item) $paid+=(int)$item->jumlah;
            $available=$plafon-$project_kkp-$paid;
            if ($available<=0) { $this->db->trans_rollback();$this->respond(array('message'=>'Pencairan sudah lengkap. Sisa pencairan Rp 0.'),409);return; }
            $categories=array();
            foreach ($this->db->get_where('tpencairan_retensi',array('pencairan_id'=>$latest->id))->result() as $line) $categories[]=(object)array('id'=>$line->kategori_id,'nama_retensi'=>$line->nama_retensi,'sisa'=>(int)$line->nilai);
        }
        $values=$this->input->post('retensi');$values=is_array($values)?$values:array();
        $total=0;$lines=array();$error='';
        if (count($values)!==count($categories)) $error='Kategori retensi berubah. Buka ulang modal sebelum menyimpan.';
        foreach ($categories as $category) {
            $value=isset($values[$category->id])?$this->amount($values[$category->id]):NULL;
            if ($value===NULL || ($latest && $value>$category->sisa)) { $error='Nilai sisa retensi tidak valid atau melebihi saldo kategori sebelumnya.';break; }
            if (strtoupper(trim($category->nama_retensi))!=='SBUM') $total+=$value;
            $lines[]=array('kategori_id'=>$category->id,'nama_retensi'=>$category->nama_retensi,'nilai'=>$value);
        }
        $net=$available-$total;
        // Subsequent payments release only the reduction in recorded category balances.
        if ($latest) {
            $previous_total=0;foreach ($categories as $category) if (strtoupper(trim($category->nama_retensi))!=='SBUM') $previous_total+=$category->sisa;
            $net=$previous_total-$total;
            if ($net>$available) $error='Pencairan melebihi sisa plafon setelah KMK proyek. Periksa saldo retensi.';
        }
        if ($net<=0) $error='Jumlah pencairan harus lebih dari nol. Kurangi sisa retensi yang masih ditahan untuk mencairkannya.';
        if ($error) { $this->db->trans_rollback();$this->respond(array('message'=>$error),422);return; }
        $this->db->insert('tpencairan',array('projid'=>$projid,'sprno'=>$sprno,'plafon'=>$plafon,'potongan_kpp'=>$kpp,'total_retensi'=>$total,'jumlah'=>$net,'request_key'=>$key,'created_by'=>$this->session->userdata('userid'),'created_at'=>date('Y-m-d H:i:s')));
        $id=$this->db->insert_id();
        foreach ($lines as &$line) $line['pencairan_id']=$id;
        unset($line);
        if ($lines) $this->db->insert_batch('tpencairan_retensi',$lines);
        if (!$this->db->trans_status()) { $this->db->trans_rollback();$this->respond(array('message'=>'Pencairan gagal disimpan. Silakan coba kembali.'),500);return; }
        $this->db->trans_commit();
        $this->respond(array('message'=>'Pencairan berhasil disimpan.','id'=>$id));
    }
}
