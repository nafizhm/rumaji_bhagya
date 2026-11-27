<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__.'/Mod_release_inventory.php';

class Mod_siteplan extends CI_Model
{
    public function projects($activeOnly=FALSE,$releaseOnly=FALSE)
    {
        $this->db->select('p.projid,p.namproj,s.projid AS has_siteplan')->from('mproj p')
            ->join('msiteplan s','s.projid=p.projid','left');
        if ($activeOnly) $this->db->where('p.status','AKT');
        if ($releaseOnly) $this->db->where('EXISTS (SELECT 1 FROM mlot release_lot WHERE release_lot.projid=p.projid)',NULL,FALSE);
        return $this->db->order_by('p.projid')->get()->result();
    }

    public function template($projid)
    {
        return $this->db->get_where('msiteplan',array('projid'=>$projid))->row();
    }

    public function units($projid,$lotid=0)
    {
        $ready=Mod_release_inventory::ready_sql();
        $rows=$this->db->query("SELECT u.*,k.lot,k.ltstd,k.tipe,k.jenis,k.status,s.namstat,
            b.status AS booking_status,b.id booking_id,h.sprno,l.id release_lot_id,l.kode_lot release_kode_lot,
            l.release_type,l.harga_jual release_harga_jual,l.biaya_lain release_biaya_lain,
            (($ready) AND (?=0 OR l.id=?)) AS ready_for_sale
            FROM msiteplan_unit u
            INNER JOIN mkav k ON k.projid=u.projid AND k.blok=u.blok AND k.no=u.no
            INNER JOIN mproj p ON p.projid=k.projid
            LEFT JOIN mlot_unit lu ON lu.projid=k.projid AND lu.blok=k.blok AND lu.no=k.no
            LEFT JOIN mlot l ON l.id=lu.lot_id
            LEFT JOIN mstat s ON s.statgrp='KAV' AND s.status=k.status
            LEFT JOIN tbooking b ON b.projid=k.projid AND b.blok=k.blok AND b.no=k.no AND b.status IN ('Baru','Pending','Disetujui')
            LEFT JOIN (SELECT projid,blok,no,MAX(sprno) sprno FROM tsprh WHERE projid=? AND status <> 'BTL'
                GROUP BY projid,blok,no) h ON h.projid=k.projid AND h.blok=k.blok AND h.no=k.no
            WHERE u.projid=? ORDER BY u.blok,u.no",array($lotid,$lotid,$projid,$projid))->result();
        foreach ($rows as $row) {
            if (in_array($row->status,array('AKD','AKS'),TRUE)) $row->state='akad';
            elseif (in_array($row->status,array('BLA','BTL'),TRUE) && in_array($row->booking_status,array('Baru','Pending','Disetujui'),TRUE)) $row->state='booking';
            elseif (in_array($row->status,array('BLA','BTL'),TRUE) && $row->sprno!==NULL) $row->state='spr';
            elseif (in_array($row->status,array('BLA','BTL'),TRUE) && $row->ready_for_sale) $row->state='tersedia';
            elseif (in_array($row->status,array('BLA','BTL'),TRUE) && $row->booking_id!==NULL) $row->state='tidak_tersedia';
            elseif (in_array($row->status,array('BLA','BTL'),TRUE)) $row->state='belum_rilis';
            else $row->state='spr';
        }
        return $rows;
    }
}
