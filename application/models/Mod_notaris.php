<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_notaris extends CI_Model
{
    public function permission($action)
    {
        if ($this->session->userdata('roleid') === 'ADM') return TRUE;
        if ($this->db->table_exists('mnotaris_akses_user')) {
            $user=$this->db->get_where('mnotaris_akses_user',array('userid'=>$this->session->userdata('userid')))->row();
            if ($user) return !empty($user->$action);
        }
        $row = $this->db->get_where('mauth', array('roleid' => $this->session->userdata('roleid'), 'moid' => 'PJ', 'mid' => 'M007'))->row();
        return $row && !empty($row->$action);
    }

    private function search($search)
    {
        if ($search !== '') $this->db->group_start()->like('nama_notaris', $search)->or_like('keterangan', $search)->group_end();
    }

    public function listing($search, $length, $start, $column, $direction)
    {
        $total = $this->db->count_all('mnotaris');
        $this->search($search);
        $filtered = $this->db->count_all_results('mnotaris');
        $this->search($search);
        $rows = $this->db->order_by($column, $direction)->order_by('id', 'DESC')->get('mnotaris', $length, $start)->result();
        return array('recordsTotal' => $total, 'recordsFiltered' => $filtered, 'data' => $rows);
    }
}
