<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_pengajuan_retensi extends CI_Model
{
    public function permission($action)
    {
        if ($this->session->userdata('roleid')==='ADM') return TRUE;
        $user=$this->db->get_where('mpengajuan_retensi_akses_user',array('userid'=>$this->session->userdata('userid')))->row();
        if ($user) return !empty($user->$action);
        $role=$this->db->get_where('mauth',array('roleid'=>$this->session->userdata('roleid'),'moid'=>'PJ','mid'=>'T003'))->row();
        return $role && !empty($role->$action);
    }

    public function listing()
    {
        return $this->db->query('SELECT a.*,p.namproj,COALESCE(d.jumlah_konsumen,0) jumlah_konsumen,COALESCE(d.total,0) total,COALESCE(d.jumlah_cair,0) jumlah_cair
            FROM tpengajuan_retensi a LEFT JOIN mproj p ON p.projid=a.projid
            LEFT JOIN (SELECT pengajuan_id,COUNT(*) jumlah_konsumen,SUM(jumlah) total,SUM(pencairan_bayar_id IS NOT NULL) jumlah_cair FROM tpengajuan_retensi_detail GROUP BY pengajuan_id) d ON d.pengajuan_id=a.id
            ORDER BY a.tanggal_surat DESC,a.id DESC')->result();
    }

    public function find($id)
    {
        $row=$this->db->get_where('tpengajuan_retensi',array('id'=>(int)$id))->row();
        if (!$row) show_404();
        return $row;
    }

    public function details($id)
    {
        return $this->db->order_by('blok')->order_by('no_unit')->get_where('tpengajuan_retensi_detail',array('pengajuan_id'=>$id))->result();
    }

    public function print_details($id)
    {
        return $this->db->query('SELECT d.*,a.kode_akad,a.tanggal_akad,k.tipe,k.ltstd
            FROM tpengajuan_retensi_detail d
            LEFT JOIN mkav k ON k.projid=d.projid AND k.blok=d.blok AND k.no=d.no_unit
            LEFT JOIN tpengajuan_akad a ON a.id=(SELECT a2.id FROM tpengajuan_akad a2
                JOIN tpengajuan_akad_peserta m ON m.pengajuan_id=a2.id
                WHERE m.projid=d.projid AND m.sprno=d.sprno
                ORDER BY a2.tanggal_akad DESC,a2.id DESC LIMIT 1)
            WHERE d.pengajuan_id=? ORDER BY d.blok,d.no_unit',array($id))->result();
    }

    public function categories()
    {
        $categories=array();$names=array();
        foreach ($this->db->order_by('nama_retensi')->get('mretensi_kategori')->result() as $item) {
            $categories[$item->id]=$item->nama_retensi;$names[mb_strtoupper(trim($item->nama_retensi),'UTF-8')]=TRUE;
        }
        foreach ($this->db->select('kategori_id,nama_retensi')->distinct()->get('tpencairan_retensi')->result() as $item) {
            $key=mb_strtoupper(trim($item->nama_retensi),'UTF-8');
            if (!isset($categories[$item->kategori_id]) && !isset($names[$key])) { $categories[$item->kategori_id]=$item->nama_retensi;$names[$key]=TRUE; }
        }
        return $categories;
    }

    public function candidates($projid,$category)
    {
        $this->load->model('mod_akad');$report=$this->mod_akad->retensi_report($projid);$rows=array();
        foreach ($report['rows'] as $item) {
            $value=isset($item->balances[$category]) ? $item->balances[$category] : 0;
            if ($item->sprno===NULL || $value<=0) continue;
            $rows[]=array('projid'=>(int)$projid,'sprno'=>(int)$item->sprno,'nama'=>$item->nama,'blok'=>$item->blok,
                'no_unit'=>$item->no,'jumlah'=>$value,'pencairan_id'=>(int)$item->pencairan_id);
        }
        return $rows;
    }

    public function terbilang($value)
    {
        $value=(int)$value;$words=array('Nol','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh','Sebelas');
        if ($value<12) return $words[$value];
        if ($value<20) return $words[$value-10].' Belas';
        foreach (array(1000000000000=>'Triliun',1000000000=>'Miliar',1000000=>'Juta',1000=>'Ribu',100=>'Ratus',10=>'Puluh') as $unit=>$label) {
            if ($value<$unit) continue;
            $count=intdiv($value,$unit);$rest=$value%$unit;
            $prefix=$unit===100 && $count===1 ? 'Seratus' : ($unit===1000 && $count===1 ? 'Seribu' : $this->terbilang($count).' '.$label);
            return $prefix.($rest ? ' '.$this->terbilang($rest) : '');
        }
    }

    public function save_request($id,$header,$keys)
    {
        $debug=$this->db->db_debug;$this->db->db_debug=FALSE;$this->db->trans_begin();$error='';
        if ($id && !$this->db->query('SELECT id FROM tpengajuan_retensi WHERE id=? FOR UPDATE',array($id))->row()) $error='Pengajuan tidak ditemukan.';
        if ($id && $this->db->where('pengajuan_id',$id)->where('pencairan_bayar_id IS NOT NULL',NULL,FALSE)->count_all_results('tpengajuan_retensi_detail')) $error='Pengajuan sudah memiliki pembayaran dan tidak dapat diedit.';
        // Serialize against disbursements before reading the latest category balances.
        $this->db->query('SELECT sprno FROM tsprh WHERE projid=? ORDER BY sprno FOR UPDATE',array($header['projid']));
        $available=array();foreach ($this->candidates($header['projid'],$header['kategori_id']) as $item) $available[$item['projid'].':'.$item['sprno']]=$item;
        $details=array();foreach ($keys as $key) {
            if (!isset($available[$key])) { $error='Saldo retensi peserta telah berubah. Klik Proses dan pilih kembali.';break; }
            $details[]=$available[$key];
        }
        if (!$error && !$details) $error='Centang minimal satu konsumen dengan saldo retensi.';
        if (!$error && $this->db->where('no_surat',$header['no_surat'])->where('id <>',$id)->count_all_results('tpengajuan_retensi')) $error='Nomor surat sudah digunakan.';
        if (!$error) {
            $header['updated_at']=date('Y-m-d H:i:s');
            if ($id) $this->db->where('id',$id)->update('tpengajuan_retensi',$header);
            else { $header['created_at']=$header['updated_at'];$header['created_by']=$this->session->userdata('userid');$this->db->insert('tpengajuan_retensi',$header);$id=$this->db->insert_id(); }
            $this->db->delete('tpengajuan_retensi_detail',array('pengajuan_id'=>$id));
            foreach ($details as &$detail) $detail['pengajuan_id']=$id;
            unset($detail);$this->db->insert_batch('tpengajuan_retensi_detail',$details);
        }
        if ($error || !$this->db->trans_status()) { $this->db->trans_rollback();$this->db->db_debug=$debug;return $error ?: 'Pengajuan gagal disimpan. Periksa nomor surat dan coba kembali.'; }
        $this->db->trans_commit();$this->db->db_debug=$debug;return '';
    }

    public function pay($id,$date,$keys)
    {
        $debug=$this->db->db_debug;$this->db->db_debug=FALSE;$this->db->trans_begin();$error='';
        $parent=$this->db->query('SELECT * FROM tpengajuan_retensi WHERE id=? FOR UPDATE',array($id))->row();
        if (!$parent) $error='Pengajuan tidak ditemukan.';
        $details=array();foreach ($this->details($id) as $item) $details[$item->projid.':'.$item->sprno]=$item;
        $chosen=array();foreach ($keys as $key) {
            if (!isset($details[$key])) { $error='Konsumen tidak termasuk dalam pengajuan ini.';break; }
            $chosen[$key]=$details[$key];
        }
        ksort($chosen,SORT_NATURAL);
        if (!$chosen && !$error) $error='Centang minimal satu konsumen yang sudah dibayar.';
        // Lock consumers in one order before taking the shared project lock.
        foreach ($chosen as $item) {
            if ($error || $item->pencairan_bayar_id!==NULL) continue;
            $this->db->query('SELECT sprno FROM tsprh WHERE projid=? AND sprno=? FOR UPDATE',array($item->projid,$item->sprno));
        }
        $master=array();foreach ($this->db->get('mretensi_kategori')->result() as $category) $master[$category->id]=TRUE;
        foreach ($chosen as $item) {
            if ($error) break;
            if ($item->pencairan_bayar_id!==NULL) continue;
            if ((int)$item->projid!==(int)$parent->projid) { $error='Proyek peserta tidak sesuai pengajuan.';break; }
            $spr=$this->db->query("SELECT sprno FROM tsprh WHERE projid=? AND sprno=? AND status<>'BTL' FOR UPDATE",array($item->projid,$item->sprno))->row();
            $project=$this->db->query('SELECT kkp FROM mproj WHERE projid=? FOR UPDATE',array($item->projid))->row();
            if (!$spr || !$project) { $error='Data konsumen atau proyek sudah tidak valid.';break; }
            $history=$this->db->order_by('id','DESC')->get_where('tpencairan',array('projid'=>$item->projid,'sprno'=>$item->sprno))->result();
            if (!$history) { $error='Histori pencairan konsumen tidak ditemukan.';break; }
            $latest=$history[0];$paid=0;foreach ($history as $payment) $paid+=(int)$payment->jumlah;
            $amount=(int)$item->jumlah;$available=(int)$latest->plafon-(int)$project->kkp-$paid;
            if ($amount<=0 || $amount>$available) { $error='Saldo pencairan sudah berubah. Periksa saldo konsumen sebelum menyimpan.';break; }
            $lines=$this->db->order_by('id')->get_where('tpencairan_retensi',array('pencairan_id'=>$latest->id))->result();
            $matching=array();$balance=0;
            foreach ($lines as $index=>$line) {
                if ((int)$line->kategori_id===(int)$parent->kategori_id || (!isset($master[$line->kategori_id]) && mb_strtoupper(trim($line->nama_retensi),'UTF-8')===mb_strtoupper(trim($parent->nama_retensi),'UTF-8'))) {
                    $matching[$index]=TRUE;$balance+=(int)$line->nilai;
                }
            }
            if ($amount>$balance) { $error='Saldo jenis retensi sudah berubah atau telah dibayar melalui pengajuan lain. Muat ulang dan periksa kembali.';break; }
            $remaining=$amount;$newLines=array();$total=0;
            foreach ($lines as $index=>$line) {
                $value=(int)$line->nilai;
                if (isset($matching[$index])) { $deduction=min($remaining,$value);$value-=$deduction;$remaining-=$deduction; }
                $total+=$value;$newLines[]=array('kategori_id'=>$line->kategori_id,'nama_retensi'=>$line->nama_retensi,'nilai'=>$value);
            }
            $this->db->insert('tpencairan',array('projid'=>$item->projid,'sprno'=>$item->sprno,'plafon'=>$latest->plafon,'potongan_kpp'=>0,
                'total_retensi'=>$total,'jumlah'=>$amount,'tanggal_pembayaran'=>$date,'request_key'=>bin2hex(random_bytes(16)),
                'created_by'=>$this->session->userdata('userid'),'created_at'=>date('Y-m-d H:i:s')));
            $paymentId=$this->db->insert_id();
            foreach ($newLines as &$line) $line['pencairan_id']=$paymentId;
            unset($line);if ($newLines) $this->db->insert_batch('tpencairan_retensi',$newLines);
            $this->db->where(array('pengajuan_id'=>$id,'projid'=>$item->projid,'sprno'=>$item->sprno))->update('tpengajuan_retensi_detail',array(
                'tanggal_cair'=>$date,'jumlah_cair'=>$amount,'pencairan_bayar_id'=>$paymentId,'dibayar_oleh'=>$this->session->userdata('userid')));
        }
        if (!$error) $this->db->where('id',$id)->update('tpengajuan_retensi',array('updated_at'=>date('Y-m-d H:i:s')));
        if ($error || !$this->db->trans_status()) { $this->db->trans_rollback();$this->db->db_debug=$debug;return $error ?: 'Pembayaran gagal disimpan. Silakan coba kembali.'; }
        $this->db->trans_commit();$this->db->db_debug=$debug;return '';
    }

    public function delete_request($id)
    {
        $this->db->trans_begin();
        $this->db->query('SELECT id FROM tpengajuan_retensi WHERE id=? FOR UPDATE',array($id));
        if ($this->db->where('pengajuan_id',$id)->where('pencairan_bayar_id IS NOT NULL',NULL,FALSE)->count_all_results('tpengajuan_retensi_detail')) {
            $this->db->trans_rollback();return 'Pengajuan sudah memiliki pembayaran dan tidak dapat dihapus.';
        }
        $this->db->delete('tpengajuan_retensi',array('id'=>$id));
        if (!$this->db->trans_status()) { $this->db->trans_rollback();return 'Pengajuan gagal dihapus.'; }
        $this->db->trans_commit();return '';
    }
}
