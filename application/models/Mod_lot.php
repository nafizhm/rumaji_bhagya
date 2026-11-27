<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_lot extends CI_Model
{
    public function permission($action)
    {
        if ($this->session->userdata('roleid') === 'ADM') return TRUE;
        if ($this->db->table_exists('mlot_akses_user')) {
            $user = $this->db->get_where('mlot_akses_user', array('userid'=>$this->session->userdata('userid')))->row();
            if ($user) return !empty($user->$action);
        }
        $row = $this->db->get_where('mauth', array('roleid'=>$this->session->userdata('roleid'), 'moid'=>'PJ', 'mid'=>'M008'))->row();
        return $row && !empty($row->$action);
    }

    public function listing()
    {
        return $this->db->query('SELECT l.*, p.namproj, (SELECT COUNT(*) FROM mlot_unit u WHERE u.lot_id=l.id) AS jumlah_unit FROM mlot l LEFT JOIN mproj p ON p.projid=l.projid ORDER BY l.kode_lot')->result();
    }

    public function find($id)
    {
        $row = $this->db->get_where('mlot', array('id'=>(int)$id))->row();
        if (!$row) show_404();
        return $row;
    }

    public function unit_count($id)
    {
        return $this->db->where('lot_id', (int)$id)->count_all_results('mlot_unit');
    }

    public function available_units($row)
    {
        if (!$row->projid) return array();
        $membershipFilter=$row->release_saved_at ? 'u.lot_id=?' : '(u.lot_id IS NULL OR u.lot_id=?)';
        return $this->db->query('SELECT k.blok,k.no,k.tipe,k.status,u.lot_id,
            s.namstat FROM mkav k
            LEFT JOIN mlot_unit u ON u.projid=k.projid AND u.blok=k.blok AND u.no=k.no
            LEFT JOIN mstat s ON s.statgrp=\'KAV\' AND s.status=k.status
            WHERE k.projid=? AND '.$membershipFilter.' ORDER BY k.blok,k.no', array($row->projid,$row->id))->result();
    }

    public function save_master($id, $data)
    {
        $debug=$this->db->db_debug;
        $this->db->db_debug=FALSE;
        $this->db->trans_begin();
        if ($id) {
            $current=$this->db->query('SELECT * FROM mlot WHERE id=? FOR UPDATE',array($id))->row();
            if (!$current || ((int)$current->projid !== (int)$data['projid'] && $this->unit_count($id))) {
                $this->db->trans_rollback(); $this->db->db_debug=$debug;
                return 'Lokasi proyek tidak dapat diganti selama masih ada unit terpilih. Kosongkan pilihan unit terlebih dahulu.';
            }
        }
        $ok=$id ? $this->db->where('id',$id)->update('mlot',$data) : $this->db->insert('mlot',$data);
        if (!$ok || !$this->db->trans_status()) {
            $this->db->trans_rollback(); $this->db->db_debug=$debug;
            return 'Lot gagal disimpan. Kode Lot mungkin sudah digunakan pada proyek ini.';
        }
        $this->db->trans_commit(); $this->db->db_debug=$debug;
        return '';
    }

    public function save_units($id, $units)
    {
        $debug=$this->db->db_debug; $this->db->db_debug=FALSE;
        $this->db->trans_begin();
        $lot=$this->db->query('SELECT * FROM mlot WHERE id=? FOR UPDATE',array($id))->row();
        $error='';
        if (!$lot || !$lot->projid) $error='Pilih lokasi proyek melalui Edit Lot terlebih dahulu.';
        if ($lot && $lot->release_saved_at) $error='Unit sudah dikunci karena Release telah disimpan. Unit tidak dapat ditambah atau dikurangi.';
        if (!$error) {
            // Unique project/block/number keys also protect against simultaneous selections in other lots.
            $this->db->delete('mlot_unit',array('lot_id'=>$id));
            foreach ($units as $unit) {
                $stock=$this->db->get_where('mkav',array('projid'=>$lot->projid,'blok'=>$unit[0],'no'=>$unit[1]))->row();
                if (!$stock) { $error='Ada unit yang tidak terdaftar di lokasi proyek ini.'; break; }
                if (!$this->db->insert('mlot_unit',array('lot_id'=>$id,'projid'=>$lot->projid,'blok'=>$unit[0],'no'=>$unit[1]))) {
                    $error='Ada unit yang sudah dipilih pada Lot Release lain. Muat ulang halaman lalu pilih kembali.'; break;
                }
            }
        }
        if ($error || !$this->db->trans_status()) {
            $this->db->trans_rollback(); $this->db->db_debug=$debug;
            return $error ?: 'Pilihan unit gagal disimpan. Silakan coba kembali.';
        }
        $this->db->trans_commit(); $this->db->db_debug=$debug;
        return '';
    }
}
