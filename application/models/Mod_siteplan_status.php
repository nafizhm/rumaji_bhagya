<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_siteplan_status extends CI_Model
{
    public function permission($action)
    {
        if ($this->session->userdata('roleid')==='ADM') return TRUE;
        $user=$this->db->get_where('msiteplan_status_akses_user',array('userid'=>$this->session->userdata('userid')))->row();
        if ($user) return !empty($user->$action);
        $role=$this->db->get_where('mauth',array('roleid'=>$this->session->userdata('roleid'),'moid'=>'PJ','mid'=>'M009'))->row();
        return $role && !empty($role->$action);
    }

    public function listing()
    {
        return $this->db->order_by('urutan')->get('msiteplan_status')->result();
    }

    public function legend()
    {
        $legend=array();foreach ($this->listing() as $row) $legend[$row->kode]=array($row->nama,$row->warna);
        return $legend;
    }

    public function save_all($rows)
    {
        $this->db->trans_begin();
        foreach ($rows as $row) {
            $row['updated_by']=$this->session->userdata('userid');$row['updated_at']=date('Y-m-d H:i:s');
            $code=$row['kode'];unset($row['kode']);$this->db->where('kode',$code)->update('msiteplan_status',$row);
        }
        if (!$this->db->trans_status()) { $this->db->trans_rollback();return FALSE; }
        $this->db->trans_commit();return TRUE;
    }
}
