<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_akad extends CI_Model
{
    public function get($projid, $sprno)
    {
        return $this->db->select('h.*, k.lot, k.jenis, k.status AS status_akad, p.namproj, b.nambank')
            ->from('tsprh h')->join('mkav k','k.projid=h.projid AND k.blok=h.blok AND k.no=h.no')
            ->join('mproj p','p.projid=h.projid')->join('mbank b','b.bankid=h.bankid','left')
            ->where('h.projid',$projid)->where('h.sprno',$sprno)->where('h.status <>','BTL')
            ->where_in('k.status',array('AKD','AKS'))->get()->row();
    }

    public function retensi_report($projid)
    {
        // Each disbursement stores a snapshot of the remaining category balances.
        $rows=$this->db->query("SELECT h.sprno,h.nama,k.lot,k.blok,k.no,p.id AS pencairan_id
            FROM mkav k
            LEFT JOIN (SELECT projid,blok,no,MAX(sprno) sprno FROM tsprh
                WHERE projid=? AND status <> 'BTL' GROUP BY projid,blok,no) current_spr
                ON current_spr.projid=k.projid AND current_spr.blok=k.blok AND current_spr.no=k.no
            LEFT JOIN tsprh h ON h.projid=current_spr.projid AND h.sprno=current_spr.sprno
            LEFT JOIN (SELECT projid,sprno,MAX(id) id FROM tpencairan WHERE projid=? GROUP BY projid,sprno) latest
                ON latest.projid=h.projid AND latest.sprno=h.sprno
            LEFT JOIN tpencairan p ON p.id=latest.id
            WHERE k.projid=? AND k.status IN ('AKD','AKS') ORDER BY k.blok,k.no,h.sprno",array($projid,$projid,$projid))->result();
        $categories=array();$balances=array();
        foreach ($this->db->order_by('nama_retensi')->get('mretensi_kategori')->result() as $category) $categories[$category->id]=$category->nama_retensi;
        $ids=array();foreach ($rows as $row) if ($row->pencairan_id!==NULL) $ids[]=$row->pencairan_id;
        if ($ids) {
            $lines=$this->db->where_in('pencairan_id',$ids)->get('tpencairan_retensi')->result();
            foreach ($lines as $line) {
                if (!isset($categories[$line->kategori_id])) $categories[$line->kategori_id]=$line->nama_retensi;
                $balances[$line->pencairan_id][$line->kategori_id]=(float)$line->nilai;
            }
        }
        foreach ($rows as $row) {
            $row->balances=isset($balances[$row->pencairan_id])?$balances[$row->pencairan_id]:array();
            $row->jumlah=array_sum($row->balances);
            $row->retensi_lunas=$row->pencairan_id!==NULL && $row->jumlah<=0;
        }
        return array('categories'=>$categories,'rows'=>$rows);
    }

    public function payments($projid, $sprno)
    {
        return $this->db->order_by('tgl','DESC')->order_by('kwtno','DESC')
            ->get_where('tkwt',array('projid'=>$projid,'sprno'=>$sprno,'btl <>'=>'1'))->result();
    }

    public function schedule($projid, $sprno)
    {
        return $this->db->query("SELECT s.*, COALESCE(p.dibayar,0) AS dibayar FROM tsprp s
            LEFT JOIN (SELECT projid,sprno,tipe,ang,SUM(total) dibayar FROM tkwt
                WHERE projid=? AND sprno=? AND btl <> '1' GROUP BY projid,sprno,tipe,ang) p
            ON p.projid=s.projid AND p.sprno=s.sprno AND p.tipe=s.tipe AND p.ang=s.ang
            WHERE s.projid=? AND s.sprno=? ORDER BY s.jtmp,s.tipe,s.ang",array($projid,$sprno,$projid,$sprno))->result();
    }

    public function disbursements($projid, $sprno)
    {
        $rows=$this->db->order_by('created_at')->order_by('id')->get_where('tpencairan',array('projid'=>$projid,'sprno'=>$sprno))->result();
        foreach ($rows as $row) $row->retensi=$this->db->get_where('tpencairan_retensi',array('pencairan_id'=>$row->id))->result();
        return $rows;
    }
}
