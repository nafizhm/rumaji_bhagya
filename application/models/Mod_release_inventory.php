<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_release_inventory extends CI_Model
{
    // All inventory queries use the same k (stock), p (project), and l (release) aliases.
    public static function ready_sql()
    {
        return "k.status IN ('BLA','BTL') AND p.status='AKT' AND l.siteplan_active=1
            AND l.release_saved_at IS NOT NULL AND l.projid=k.projid
            AND NOT EXISTS (SELECT 1 FROM tbooking booked WHERE booked.projid=k.projid AND booked.blok=k.blok AND booked.no=k.no AND booked.deleted_at IS NULL AND booked.status IN ('Baru','Pending','Disetujui'))
            AND NOT EXISTS (SELECT 1 FROM tsprh sold WHERE sold.projid=k.projid AND sold.blok=k.blok AND sold.no=k.no AND sold.status<>'BTL')";
    }

    private function joins()
    {
        return ' FROM mkav k JOIN mproj p ON p.projid=k.projid
            JOIN mlot_unit lu ON lu.projid=k.projid AND lu.blok=k.blok AND lu.no=k.no
            JOIN mlot l ON l.id=lu.lot_id ';
    }

    public function lots($projid)
    {
        return $this->db->query('SELECT l.id,l.kode_lot,COUNT(*) jumlah_unit'.$this->joins().
            'WHERE k.projid=? AND '.self::ready_sql().' GROUP BY l.id,l.kode_lot ORDER BY l.kode_lot,l.id',array($projid))->result();
    }

    public function units($projid,$lotid)
    {
        if (!$lotid) return array();
        return $this->db->query('SELECT k.*,p.namproj,l.id release_lot_id,l.kode_lot release_kode_lot,
            l.harga_jual release_harga_jual,l.biaya_lain release_biaya_lain'.$this->joins().
            'WHERE k.projid=? AND l.id=? AND '.self::ready_sql().' ORDER BY k.blok,k.no',array($projid,$lotid))->result();
    }

    public function unit($projid,$blok,$no,$lotid)
    {
        if (!$lotid) return NULL;
        return $this->db->query('SELECT k.*,p.namproj,l.id release_lot_id,l.kode_lot release_kode_lot,
            l.harga_jual release_harga_jual,l.biaya_lain release_biaya_lain'.$this->joins().
            'WHERE k.projid=? AND k.blok=? AND k.no=? AND l.id=? AND '.self::ready_sql(),array($projid,$blok,$no,$lotid))->row();
    }
}
