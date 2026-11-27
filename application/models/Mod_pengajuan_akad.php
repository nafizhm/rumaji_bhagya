<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_pengajuan_akad extends CI_Model
{
    public function permission($action)
    {
        if ($this->session->userdata('roleid')==='ADM') return TRUE;
        $user=$this->db->get_where('mpengajuan_akad_akses_user',array('userid'=>$this->session->userdata('userid')))->row();
        if ($user) return !empty($user->$action);
        $role=$this->db->get_where('mauth',array('roleid'=>$this->session->userdata('roleid'),'moid'=>'PJ','mid'=>'T002'))->row();
        return $role && !empty($role->$action);
    }

    public function listing($projid=0)
    {
        $scope=$projid ? ' WHERE a.projid=? ' : ' ';
        $rows=$this->db->query('SELECT a.*,p.namproj,COALESCE(s.jumlah_akad,0) jumlah_akad,COALESCE(s.jumlah_hadir,0) jumlah_hadir,0 total_biaya
            FROM tpengajuan_akad a LEFT JOIN mproj p ON p.projid=a.projid LEFT JOIN (SELECT pengajuan_id,COUNT(*) jumlah_akad,SUM(hadir) jumlah_hadir,SUM(biaya_akad) total_biaya
            FROM tpengajuan_akad_peserta GROUP BY pengajuan_id) s ON s.pengajuan_id=a.id'.$scope.'ORDER BY a.tanggal_akad DESC,a.id DESC',$projid ? array($projid) : array())->result();
        if (!$rows) return $rows;
        $ids=array();$totals=array();$closed=array();foreach ($rows as $row) { $ids[]=$row->id;$totals[$row->id]=0;$closed[$row->id]=!empty($row->biaya_closed_at); }
        $members=$this->db->select('m.*,k.jenis')->from('tpengajuan_akad_peserta m')
            ->join('tsprh h','h.projid=m.projid AND h.sprno=m.sprno','left')
            ->join('mkav k','k.projid=h.projid AND k.blok=h.blok AND k.no=h.no','left')
            ->where_in('m.pengajuan_id',$ids)->get()->result();
        foreach ($members as $member) {
            $cost=(array)$member;$cost['pph']=self::calculate_pph($cost,$member->jenis);
            $parts=explode('.',$closed[$member->pengajuan_id] ? $member->biaya_akad : self::calculate_total_cost($cost));
            $totals[$member->pengajuan_id]+=(int)$parts[0]*100+(int)$parts[1];
        }
        foreach ($rows as $row) $row->total_biaya=sprintf('%d.%02d',intdiv($totals[$row->id],100),$totals[$row->id]%100);
        // Sum the same participant rows and balances used by the Pencairan detail page.
        $remaining=array();
        foreach ($this->disbursement_rows($ids) as $balance) {
            if (!isset($remaining[$balance->pengajuan_id])) $remaining[$balance->pengajuan_id]=0;
            $remaining[$balance->pengajuan_id]+=$balance->sisa;
        }
        foreach ($rows as $row) $row->sisa_pencairan=isset($remaining[$row->id]) ? $remaining[$row->id] : '0';
        return $rows;
    }

    public function find($id)
    {
        $row=$this->db->get_where('tpengajuan_akad',array('id'=>(int)$id))->row();
        if (!$row) show_404();
        return $row;
    }

    private function sp3k_sql()
    {
        return "h.status<>'BTL' AND k.status NOT IN ('AKD','AKS','BTL')
            AND (k.status IN ('SP3','SP3K') OR EXISTS (SELECT 1 FROM tsprd d WHERE d.projid=h.projid AND d.sprno=h.sprno AND d.status='D11'))
            AND NOT EXISTS (SELECT 1 FROM tsprh newer WHERE newer.projid=h.projid AND newer.blok=h.blok AND newer.no=h.no AND newer.status<>'BTL' AND newer.sprno>h.sprno)";
    }

    public function candidates($id)
    {
        $parent=$this->find($id);$projid=(int)$parent->projid;
        $eligible=$this->sp3k_sql();
        $scope=$projid ? "h.projid=? AND (($eligible) OR m.pengajuan_id IS NOT NULL)" : 'm.pengajuan_id IS NOT NULL';
        if (!empty($parent->peserta_closed_at)) $scope=$projid ? 'h.projid=? AND m.pengajuan_id IS NOT NULL' : 'm.pengajuan_id IS NOT NULL';
        $rows=$this->db->query("SELECT h.projid,h.sprno,h.nama,h.blok,h.no,h.status spr_status,p.namproj,k.status status_unit,k.pkav,
            m.pengajuan_id,m.hadir,m.biaya_akad,COALESCE(plan.total,0) total_rencana,COALESCE(paid.total,0) total_bayar
            FROM tsprh h JOIN mkav k ON k.projid=h.projid AND k.blok=h.blok AND k.no=h.no
            JOIN mproj p ON p.projid=h.projid
            LEFT JOIN tpengajuan_akad_peserta m ON m.pengajuan_id=? AND m.projid=h.projid AND m.sprno=h.sprno
            LEFT JOIN (SELECT projid,sprno,SUM(total) total FROM tsprp GROUP BY projid,sprno) plan ON plan.projid=h.projid AND plan.sprno=h.sprno
            LEFT JOIN (SELECT projid,sprno,SUM(total) total FROM tkwt WHERE btl=0 GROUP BY projid,sprno) paid ON paid.projid=h.projid AND paid.sprno=h.sprno
            WHERE $scope
            ORDER BY (m.pengajuan_id IS NOT NULL) DESC,p.projid,h.blok,h.no,h.sprno",$projid ? array($id,$projid) : array($id))->result();
        $this->load->model('mod_spr');
        foreach ($rows as $row) {
            // Match Lib::disp_prog_um, including rounding, but handle a missing payment plan safely.
            $row->persen_bayar=(float)$row->total_rencana>0 ? (float)number_format((float)$row->total_bayar/(float)$row->total_rencana*100,0,'.','') : 0;
            $row->persen_bangun=(float)$row->pkav;
            $row->persen_berkas=$this->mod_spr->document_completion($row->projid,$row->sprno)['percent'];
        }
        return $rows;
    }

    private function disbursement_rows($ids)
    {
        $this->db->select('m.pengajuan_id,m.projid,m.sprno,h.nama,h.blok,h.no,h.status spr_status,k.status status_unit,h.kpr,p.kkp,c.plafon,c.id pencairan_id,c.jumlah_pencairan,c.total_retensi')
            ->from('tpengajuan_akad_peserta m')
            ->join('tpengajuan_akad a','a.id=m.pengajuan_id')
            ->join('tsprh h','h.projid=m.projid AND h.sprno=m.sprno','left')
            ->join('mkav k','k.projid=h.projid AND k.blok=h.blok AND k.no=h.no','left')
            ->join('mproj p','p.projid=m.projid','left')
            ->join('(SELECT projid,sprno,MIN(id) id,MAX(plafon) plafon,SUM(jumlah) jumlah_pencairan,GREATEST(0,MAX(plafon)-SUM(potongan_kpp)-SUM(jumlah)) total_retensi FROM tpencairan GROUP BY projid,sprno) c','c.projid=m.projid AND c.sprno=m.sprno','left',FALSE)
            ->where_in('m.pengajuan_id',$ids)
            ->where('(a.projid IS NULL OR a.projid=0 OR m.projid=a.projid)',NULL,FALSE);
        $rows=$this->db->order_by('h.blok')->order_by('h.no')->order_by('h.nama')->get()->result();
        foreach ($rows as $row) {
            $row->plafon=$row->plafon!==NULL ? $row->plafon : $row->kpr;
            $row->kkp=(float)$row->kkp;
            $row->jumlah_pencairan=(float)$row->jumlah_pencairan;
            $row->total_retensi=(float)$row->total_retensi;
            $row->sisa=max(0,(float)$row->plafon-$row->kkp-$row->jumlah_pencairan);
        }
        return $rows;
    }

    public function disbursements($id,$status='')
    {
        $this->find($id);
        $rows=$this->disbursement_rows(array($id));
        if ($status!=='') $rows=array_values(array_filter($rows,function($row) use ($status) {
            $paid=$row->pencairan_id!==NULL && $row->sisa<=0;
            return $status==='lunas' ? $paid : !$paid;
        }));
        return $rows;
    }

    public function selected_participants($id)
    {
        $parent=$this->find($id);
        $this->db->select('m.*,h.nama,h.blok,h.no,p.namproj,k.jenis')->from('tpengajuan_akad_peserta m')
            ->join('tsprh h','h.projid=m.projid AND h.sprno=m.sprno','left')->join('mproj p','p.projid=m.projid','left')
            ->join('mkav k','k.projid=h.projid AND k.blok=h.blok AND k.no=h.no','left')
            ->where('m.pengajuan_id',$id);
        if ($parent->projid) $this->db->where('m.projid',$parent->projid);
        $rows=$this->db->order_by('m.projid')->order_by('h.blok')->order_by('h.no')->order_by('h.nama')->get()->result();
        foreach ($rows as $row) {
            if (!empty($parent->biaya_closed_at)) continue;
            $row->pph=self::calculate_pph((array)$row,$row->jenis);
            $row->biaya_akad=self::calculate_total_cost((array)$row);
            $row->total_pph=self::calculate_total_pph((array)$row);
        }
        return $rows;
    }

    public static function pph_rate($jenis)
    {
        if (in_array($jenis,array('Subsidi','Subsidi KPR'),TRUE)) return 10;
        if (in_array($jenis,array('Subsidi Cash','Komersial'),TRUE)) return 25;
        return NULL;
    }

    public static function calculate_pph($cost,$jenis)
    {
        $rate=self::pph_rate($jenis);if ($rate===NULL) return NULL;
        $parts=explode('.',isset($cost['harga_jual_rumah']) ? (string)$cost['harga_jual_rumah'] : '0');
        $base=(int)$parts[0]*100+(int)str_pad(isset($parts[1]) ? $parts[1] : '',2,'0');
        $cents=intdiv($base*$rate+500,1000);
        return sprintf('%d.%02d',intdiv($cents,100),$cents%100);
    }

    public static function calculate_total_cost($cost)
    {
        $cents=0;
        foreach (array('bn_roya_validasi','ppjb','ajb','bphtb','pph','ppn','biaya_akad_btn') as $field) {
            $parts=explode('.',isset($cost[$field]) ? (string)$cost[$field] : '0');
            $cents+=(int)$parts[0]*100+(int)str_pad(isset($parts[1]) ? $parts[1] : '',2,'0');
        }
        return sprintf('%d.%02d',intdiv($cents,100),$cents%100);
    }

    public static function calculate_total_pph($cost)
    {
        $base=0;
        foreach (array('bn_roya_validasi','ppjb','ajb') as $field) {
            $parts=explode('.',isset($cost[$field]) ? (string)$cost[$field] : '0');
            $base+=(int)$parts[0]*100+(int)str_pad(isset($parts[1]) ? $parts[1] : '',2,'0');
        }
        // (Notary costs * 100 / 97.5) * 2.5% = Notary costs / 39.
        $rupiah=intdiv($base+1950,3900);
        return $rupiah.'.00';
    }

    public function cost_fields()
    {
        return array('bn_roya_validasi'=>'BN + ROYA + VALIDASI','ppjb'=>'PPJB','ajb'=>'AJB','bphtb'=>'BPHTB',
            'pph'=>'PPh','ppn'=>'PPN','biaya_akad_btn'=>'Biaya Akad BTN (SP3K)','nilai_sp3k'=>'Nilai SP3K',
            'harga_kpr_spr'=>'Harga KPR SPR','harga_jual_rumah'=>'Harga Jual Rumah','biaya_akad'=>'Total Biaya','total_pph'=>'Total PPh');
    }

    public function save_costs($id,$costs,$finish=FALSE)
    {
        $debug=$this->db->db_debug;$this->db->db_debug=FALSE;$this->db->trans_begin();
        $parent=$this->db->query('SELECT id,projid,biaya_closed_at FROM tpengajuan_akad WHERE id=? FOR UPDATE',array($id))->row();
        $error=$parent ? '' : 'Pengajuan akad tidak ditemukan.';
        if ($parent && $parent->biaya_closed_at) $error='Biaya akad sudah Closed dan tidak dapat diubah.';
        if (!$error && $finish) {
            $count=$this->db->where('pengajuan_id',$id)->count_all_results('tpengajuan_akad_peserta');
            if (!$count || $count!==count($costs)) $error='Daftar peserta sudah berubah. Muat ulang halaman dan periksa seluruh biaya sebelum menyelesaikan.';
        }
        if (!$error) foreach ($costs as $cost) {
            $projid=$cost['projid'];$sprno=$cost['sprno'];unset($cost['projid'],$cost['sprno']);
            $member=$this->db->query('SELECT m.projid,k.jenis FROM tpengajuan_akad_peserta m
                LEFT JOIN tsprh h ON h.projid=m.projid AND h.sprno=m.sprno
                LEFT JOIN mkav k ON k.projid=h.projid AND k.blok=h.blok AND k.no=h.no
                WHERE m.pengajuan_id=? AND m.projid=? AND m.sprno=? FOR UPDATE',array($id,$projid,$sprno))->row();
            if (!$member || ($parent->projid && (int)$parent->projid!==$projid)) { $error='Daftar peserta sudah berubah atau konsumen tidak termasuk kloter ini. Muat ulang halaman biaya akad.';break; }
            $cost['pph']=self::calculate_pph($cost,$member->jenis);
            if ($cost['pph']===NULL) { $error='Jenis SPR konsumen belum valid. Tetapkan Subsidi KPR, Subsidi Cash, atau Komersial terlebih dahulu.';break; }
            $cost['biaya_akad']=self::calculate_total_cost($cost);
            $cost['total_pph']=self::calculate_total_pph($cost);
            if ((float)$cost['biaya_akad']>999999999999.99) { $error='Total biaya akad melebihi batas nominal yang dapat disimpan.';break; }
            $this->db->where(array('pengajuan_id'=>$id,'projid'=>$projid,'sprno'=>$sprno))->update('tpengajuan_akad_peserta',$cost);
        }
        if (!$error) {
            $header=array('updated_at'=>date('Y-m-d H:i:s'));
            if ($finish) $header['biaya_closed_at']=$header['updated_at'];
            $this->db->where('id',$id)->update('tpengajuan_akad',$header);
        }
        if ($error || !$this->db->trans_status()) {
            $this->db->trans_rollback();$this->db->db_debug=$debug;return $error ?: 'Rincian biaya akad gagal disimpan.';
        }
        $this->db->trans_commit();$this->db->db_debug=$debug;return '';
    }

    public function save($id,$data)
    {
        $debug=$this->db->db_debug;$this->db->db_debug=FALSE;
        $this->db->trans_begin();
        if ($id) {
            $current=$this->db->query('SELECT id FROM tpengajuan_akad WHERE id=? FOR UPDATE',array($id))->row();
            $other=$this->db->where('pengajuan_id',$id)->where('projid <>',$data['projid'])->count_all_results('tpengajuan_akad_peserta');
            if (!$current || $other) {
                $this->db->trans_rollback();$this->db->db_debug=$debug;
                return 'Proyek tidak dapat diganti karena masih ada peserta dari proyek lain. Keluarkan peserta tersebut melalui Detail terlebih dahulu.';
            }
        }
        $ok=$id ? $this->db->where('id',$id)->update('tpengajuan_akad',$data) : $this->db->insert('tpengajuan_akad',$data);
        if (!$ok || !$this->db->trans_status()) {
            $this->db->trans_rollback();$this->db->db_debug=$debug;return 'Pengajuan gagal disimpan. Periksa apakah kode akad sudah digunakan.';
        }
        $this->db->trans_commit();$this->db->db_debug=$debug;return '';
    }

    public function save_attendance($id,$projid,$sprno,$hadir)
    {
        $debug=$this->db->db_debug;$this->db->db_debug=FALSE;$this->db->trans_begin();
        $parent=$this->db->query('SELECT id,peserta_closed_at FROM tpengajuan_akad WHERE id=? FOR UPDATE',array($id))->row();
        $key=array('pengajuan_id'=>$id,'projid'=>$projid,'sprno'=>$sprno);
        $member=$this->db->get_where('tpengajuan_akad_peserta',$key)->row();
        $error=!$parent || !$parent->peserta_closed_at ? 'Kehadiran hanya dapat diubah setelah detail Closed.' : (!$member ? 'Peserta tidak terdaftar dalam pengajuan akad ini.' : '');
        if (!$error) {
            $this->db->where($key)->update('tpengajuan_akad_peserta',array('hadir'=>$hadir));
            $this->db->where('id',$id)->update('tpengajuan_akad',array('updated_at'=>date('Y-m-d H:i:s')));
        }
        if ($error || !$this->db->trans_status()) {
            $this->db->trans_rollback();$this->db->db_debug=$debug;return $error ?: 'Kehadiran gagal disimpan.';
        }
        $this->db->trans_commit();$this->db->db_debug=$debug;return '';
    }

    public function save_participants($id,$participants,$finish=FALSE)
    {
        $debug=$this->db->db_debug;$this->db->db_debug=FALSE;$this->db->trans_begin();
        $parent=$this->db->query('SELECT id,projid,biaya_closed_at,peserta_closed_at FROM tpengajuan_akad WHERE id=? FOR UPDATE',array($id))->row();
        $error=$parent ? '' : 'Pengajuan akad tidak ditemukan.';
        if ($parent && $parent->peserta_closed_at) $error='Detail pengajuan akad sudah Closed dan tidak dapat diubah.';
        if (!$error && $finish && !$participants) $error='Pilih minimal satu peserta sebelum menyelesaikan.';
        if ($parent && !$parent->projid && $participants) $error='Pilih proyek melalui Edit Pengajuan Akad terlebih dahulu.';
        $old=array();
        foreach ($this->db->get_where('tpengajuan_akad_peserta',array('pengajuan_id'=>$id))->result() as $row) $old[$row->projid.':'.$row->sprno]=$row;
        if ($parent && $parent->biaya_closed_at) {
            if (count($old)!==count($participants)) $error='Biaya akad sudah Closed. Daftar peserta tidak dapat ditambah atau dikurangi.';
            foreach ($participants as $row) if (!isset($old[$row['projid'].':'.$row['sprno']])) $error='Biaya akad sudah Closed. Daftar peserta tidak dapat diubah.';
        }
        if (!$error) foreach ($participants as $row) {
            if ((int)$row['projid']!==(int)$parent->projid) { $error='Peserta harus berasal dari proyek yang dipilih pada pengajuan akad.';break; }
            if (isset($old[$row['projid'].':'.$row['sprno']])) continue;
            $candidate=$this->db->query('SELECT h.sprno FROM tsprh h JOIN mkav k ON k.projid=h.projid AND k.blok=h.blok AND k.no=h.no
                WHERE h.projid=? AND h.sprno=? AND '.$this->sp3k_sql().' FOR UPDATE',array($row['projid'],$row['sprno']))->row();
            if (!$candidate) { $error='Ada konsumen yang belum SP3K atau sudah tidak memenuhi status SP3K. Muat ulang halaman dan pilih kembali.';break; }
        }
        if (!$error) {
            $chosen=array();foreach ($participants as $row) $chosen[$row['projid'].':'.$row['sprno']]=TRUE;
            foreach ($old as $key=>$row) if (!isset($chosen[$key])) $this->db->delete('tpengajuan_akad_peserta',array('pengajuan_id'=>$id,'projid'=>$row->projid,'sprno'=>$row->sprno));
            foreach ($participants as $row) {
                $key=array('pengajuan_id'=>$id,'projid'=>$row['projid'],'sprno'=>$row['sprno']);
                // Updating selection/attendance must preserve every saved cost field.
                if (isset($old[$row['projid'].':'.$row['sprno']])) $this->db->where($key)->update('tpengajuan_akad_peserta',array('hadir'=>$old[$row['projid'].':'.$row['sprno']]->hadir));
                else $this->db->insert('tpengajuan_akad_peserta',array_merge($key,array('hadir'=>$old[$row['projid'].':'.$row['sprno']]->hadir)));
            }
            $header=array('updated_at'=>date('Y-m-d H:i:s'));
            if ($finish) $header['peserta_closed_at']=$header['updated_at'];
            $this->db->where('id',$id)->update('tpengajuan_akad',$header);
        }
        if ($error || !$this->db->trans_status()) {
            $this->db->trans_rollback();$this->db->db_debug=$debug;return $error ?: 'Peserta gagal disimpan. Silakan coba kembali.';
        }
        $this->db->trans_commit();$this->db->db_debug=$debug;return '';
    }
}
