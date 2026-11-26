<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_siteplan extends CI_Model
{
    public function projects()
    {
        return $this->db->select('p.projid,p.namproj,s.projid AS has_siteplan')->from('mproj p')
            ->join('msiteplan s','s.projid=p.projid','left')->order_by('p.projid')->get()->result();
    }

    public function template($projid)
    {
        return $this->db->get_where('msiteplan',array('projid'=>$projid))->row();
    }

    public function units($projid)
    {
        $rows=$this->db->query("SELECT u.*,k.lot,k.ltstd,k.tipe,k.jenis,k.status,s.namstat,
            b.status AS booking_status,h.sprno
            FROM msiteplan_unit u
            INNER JOIN mkav k ON k.projid=u.projid AND k.blok=u.blok AND k.no=u.no
            LEFT JOIN mstat s ON s.statgrp='KAV' AND s.status=k.status
            LEFT JOIN tbooking b ON b.projid=k.projid AND b.blok=k.blok AND b.no=k.no
            LEFT JOIN (SELECT projid,blok,no,MAX(sprno) sprno FROM tsprh WHERE projid=? AND status <> 'BTL'
                GROUP BY projid,blok,no) h ON h.projid=k.projid AND h.blok=k.blok AND h.no=k.no
            WHERE u.projid=? ORDER BY u.blok,u.no",array($projid,$projid))->result();
        foreach ($rows as $row) {
            if (in_array($row->status,array('AKD','AKS'),TRUE)) $row->state='akad';
            elseif ($row->status==='BLA' && in_array($row->booking_status,array('Baru','Pending','Disetujui'),TRUE)) $row->state='booking';
            elseif ($row->status==='BLA') $row->state='tersedia';
            elseif ($row->status==='BTL') $row->state='batal';
            else $row->state='spr';
        }
        return $rows;
    }
}
