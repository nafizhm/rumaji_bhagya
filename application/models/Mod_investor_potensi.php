<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_investor_potensi extends CI_Model
{
    public function permission($action)
    {
        if ($this->session->userdata('roleid')==='ADM') return TRUE;
        if (!$this->db->table_exists('minvestor_potensi_akses_user')) return FALSE;
        $p=$this->db->get_where('minvestor_potensi_akses_user',array('userid'=>$this->session->userdata('userid')))->row();
        if ($p) return !empty($p->$action);
        $p=$this->db->get_where('mauth',array('roleid'=>$this->session->userdata('roleid'),'moid'=>'IN','mid'=>'R002'))->row();
        return $p && !empty($p->$action);
    }

    public function current($projid)
    {
        // Pick one effective price, one active SPR and one release per unit, avoiding duplicated totals.
        $sql="SELECT k.blok,k.no,k.lot,k.tipe,h.sprno,h.hrgtot,h.byttp,
            (SELECT f.hrgtotalf FROM tsprf f WHERE f.projid=h.projid AND f.sprno=h.sprno LIMIT 1) fitur_spr,
            (SELECT b.hrgtotalb FROM tsprb b WHERE b.projid=h.projid AND b.sprno=h.sprno LIMIT 1) bangunan_spr,
            l.id release_id,l.harga_jual,l.biaya_lain,p.hrgstd,p.byttp biaya_master,
            (SELECT SUM(CASE WHEN f.ftid='TLE' THEN f.ltnh ELSE 1 END *
                (SELECT hf.hrg FROM mhrgf hf WHERE hf.projid=f.projid AND hf.ftid=f.ftid AND hf.tgl<=? ORDER BY hf.tgl DESC LIMIT 1))
                FROM mkavf f WHERE f.projid=k.projid AND f.blok=k.blok AND f.no=k.no) fitur_master,
            (SELECT COUNT(*) FROM mkavf f WHERE f.projid=k.projid AND f.blok=k.blok AND f.no=k.no AND NOT EXISTS
                (SELECT 1 FROM mhrgf hf WHERE hf.projid=f.projid AND hf.ftid=f.ftid AND hf.tgl<=?)) fitur_missing
            FROM mkav k
            LEFT JOIN tsprh h ON h.projid=k.projid AND h.blok=k.blok AND h.no=k.no AND h.status<>'BTL'
                AND h.sprno=(SELECT MAX(s.sprno) FROM tsprh s WHERE s.projid=k.projid AND s.blok=k.blok AND s.no=k.no AND s.status<>'BTL')
            LEFT JOIN mlot_unit lu ON lu.projid=k.projid AND lu.blok=k.blok AND lu.no=k.no
            LEFT JOIN mlot l ON l.id=lu.lot_id AND l.release_saved_at IS NOT NULL
            LEFT JOIN mhrg p ON p.projid=k.projid AND p.lot=k.lot AND p.tipe=k.tipe
                AND p.tgl=(SELECT MAX(hp.tgl) FROM mhrg hp WHERE hp.projid=k.projid AND hp.lot=k.lot AND hp.tipe=k.tipe AND hp.tgl<=?)
            WHERE k.projid=? ORDER BY k.blok,k.no";
        $today=(new DateTimeImmutable('now',new DateTimeZone('Asia/Jakarta')))->format('Y-m-d');$items=array();
        foreach ($this->db->query($sql,array($today,$today,$today,$projid))->result() as $u) {
            if ($u->sprno!==NULL) { $amount=(float)$u->hrgtot+(float)$u->byttp+(float)$u->fitur_spr+(float)$u->bangunan_spr;$source='SPR #'.$u->sprno; }
            elseif ($u->release_id!==NULL) { $amount=$u->harga_jual>0?(float)$u->harga_jual+(float)$u->biaya_lain:NULL;$source='Lot Release'; }
            else { $amount=$u->hrgstd>0 && !$u->fitur_missing?(float)$u->hrgstd+(float)$u->biaya_master+(float)$u->fitur_master:NULL;$source='Harga Master'; }
            $code=$u->blok.'/'.$u->no;
            $items['penerimaan:'.$code]=array('jenis'=>'penerimaan','kode'=>$code,'label'=>$code.' (Lot '.$u->lot.', tipe '.$u->tipe.')','sumber'=>$source,'nilai'=>$amount);
        }
        $sql='SELECT a.paid,a.byid,a.total,p.nampa,b.namby FROM mang a LEFT JOIN mpa p ON p.paid=a.paid LEFT JOIN mby b ON b.paid=a.paid AND b.byid=a.byid WHERE a.projid=? ORDER BY a.paid,a.byid';
        foreach ($this->db->query($sql,array($projid))->result() as $a) {
            $code=$a->paid.'/'.$a->byid;
            $items['pengeluaran:'.$code]=array('jenis'=>'pengeluaran','kode'=>$code,'label'=>($a->nampa?:$a->paid).' / '.($a->namby?:$a->byid),'sumber'=>'Rencana Anggaran','nilai'=>(float)$a->total);
        }
        return $items;
    }

    public function baseline($projid,$userid)
    {
        $this->db->trans_begin();
        // Serialize automatic creation and rebasing per project.
        $this->db->query('SELECT projid FROM mproj WHERE projid=? FOR UPDATE',array($projid));
        $row=$this->db->order_by('id','DESC')->get_where('tinvestor_cutoff',array('projid'=>$projid,'deleted_at'=>NULL))->row();
        if (!$row) {
            $captured=(new DateTimeImmutable('now',new DateTimeZone('Asia/Jakarta')))->format('Y-m-d H:i:s');
            $this->db->insert('tinvestor_cutoff',array('projid'=>$projid,'captured_at'=>$captured,'captured_by'=>$userid,'catatan'=>''));
            $id=$this->db->insert_id();
            foreach ($this->current($projid) as $item) { $item['cutoff_id']=$id;$this->db->insert('tinvestor_cutoff_item',$item); }
            $row=$this->db->get_where('tinvestor_cutoff',array('id'=>$id))->row();
        }
        if (!$this->db->trans_status()) { $this->db->trans_rollback();throw new RuntimeException('Cut off gagal disimpan.'); }
        $this->db->trans_commit();return $row;
    }

    public function comparison($baseline,$current)
    {
        $rows=array();
        foreach ($this->db->get_where('tinvestor_cutoff_item',array('cutoff_id'=>$baseline->id))->result_array() as $item) {
            $key=$item['jenis'].':'.$item['kode'];
            $rows[$key]=array('jenis'=>$item['jenis'],'kode'=>$item['kode'],'label'=>$item['label'],'cutoff'=>$item['nilai']===NULL?NULL:(float)$item['nilai'],'real'=>0,'source'=>'Unit / anggaran sudah dihapus','old_exists'=>TRUE,'new_exists'=>FALSE);
        }
        foreach ($current as $key=>$item) {
            if (!isset($rows[$key])) $rows[$key]=array('jenis'=>$item['jenis'],'kode'=>$item['kode'],'label'=>$item['label'],'cutoff'=>0,'old_exists'=>FALSE);
            $rows[$key]['real']=$item['nilai'];$rows[$key]['source']=$item['sumber'];$rows[$key]['new_exists']=TRUE;
        }
        ksort($rows);return $rows;
    }
}
