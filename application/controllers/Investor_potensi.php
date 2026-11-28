<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Investor_potensi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();date_default_timezone_set('Asia/Jakarta');$this->lib->check_login();$this->load->model('mod_investor_potensi');
    }
    private function access($action)
    {
        if (!$this->mod_investor_potensi->permission($action)) { show_error('Anda tidak mempunyai akses laporan kontrol potensi.',403);exit; }
    }
    public function index()
    {
        $this->access('r');
        $projects=$this->db->order_by('projid')->get('mproj')->result();
        $projid=$this->input->get('projid')!==NULL?(int)$this->input->get('projid'):($projects?(int)$projects[0]->projid:0);
        $project=NULL;foreach ($projects as $p) if ((int)$p->projid===$projid) $project=$p;
        if (!$project) { show_404();return; }
        // Capture every existing project when this report is first initialized.
        foreach ($projects as $p) $this->mod_investor_potensi->baseline($p->projid,$this->session->userdata('userid'));
        $baseline=$this->mod_investor_potensi->baseline($projid,$this->session->userdata('userid'));
        $current=$this->mod_investor_potensi->current($projid);
        if (!$this->session->userdata('investor_token')) $this->session->set_userdata('investor_token',bin2hex(random_bytes(32)));
        $this->load->view('investor_potensi',array('title'=>'Laporan Kontrol Potensi','projects'=>$projects,'project'=>$project,'baseline'=>$baseline,
            'rows'=>$this->mod_investor_potensi->comparison($baseline,$current),'can_update'=>$this->mod_investor_potensi->permission('u'),'can_delete'=>$this->mod_investor_potensi->permission('d')));
    }
    public function manage($id=NULL)
    {
        if ($this->input->method()!=='post') { show_error('Metode tidak diizinkan.',405);return; }
        $reset=$this->input->post('action')==='reset';$this->access($reset?'d':'u');
        if ($reset) $this->access('c');
        $token=$this->session->userdata('investor_token');
        if (!$token || !hash_equals($token,(string)$this->input->post('token'))) { show_error('Sesi berakhir. Muat ulang laporan.',403);return; }
        $row=$this->db->get_where('tinvestor_cutoff',array('id'=>(int)$id,'deleted_at'=>NULL))->row();
        if (!$row) { show_404();return; }
        $note=$this->input->post('catatan');
        if (!$reset && (!is_string($note) || mb_strlen($note)>2000)) { show_error('Catatan maksimal 2000 karakter.',422);return; }
        $this->db->trans_begin();
        $this->db->query('SELECT projid FROM mproj WHERE projid=? FOR UPDATE',array($row->projid));
        $locked=$this->db->query('SELECT id FROM tinvestor_cutoff WHERE id=? AND deleted_at IS NULL FOR UPDATE',array($row->id))->row();
        if (!$locked) { $this->db->trans_rollback();show_error('Acuan sudah berubah. Muat ulang laporan.',409);return; }
        $data=$reset?array('deleted_at'=>date('Y-m-d H:i:s'),'deleted_by'=>$this->session->userdata('userid')):array('catatan'=>trim($note));
        $this->db->where('id',$row->id)->update('tinvestor_cutoff',$data);
        if (!$this->db->trans_status()) { $this->db->trans_rollback();show_error('Perubahan gagal disimpan.',500);return; }
        $this->db->trans_commit();
        if ($reset) $this->mod_investor_potensi->baseline($row->projid,$this->session->userdata('userid'));
        $this->session->set_flashdata('investor_message',$reset?'Acuan lama diarsipkan. Cut off baru diambil dari kondisi terbaru.':'Catatan disimpan. Nilai cut off tetap.');
        redirect('investor_potensi?projid='.$row->projid);
    }
}
