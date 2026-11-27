<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan_siteplan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();$this->lib->check_login();$this->load->model('mod_siteplan_status');
        if (!$this->session->userdata('siteplan_status_token')) $this->session->set_userdata('siteplan_status_token',bin2hex(random_bytes(32)));
    }

    private function authorize($action)
    {
        if (!$this->mod_siteplan_status->permission($action)) show_error('Anda tidak memiliki akses Pengaturan Siteplan.',403);
    }

    public function index()
    {
        $this->authorize('r');$this->render($this->mod_siteplan_status->listing());
    }

    private function render($rows,$error='')
    {
        $this->load->view('pengaturan_siteplan',array('title'=>'Pengaturan Siteplan','rows'=>$rows,'error'=>$error,'canUpdate'=>$this->mod_siteplan_status->permission('u')));
    }

    public function save()
    {
        $this->authorize('u');
        if ($this->input->method()!=='post') show_error('Metode tidak diizinkan.',405);
        $token=$this->input->post('token');
        if (!is_string($token) || !hash_equals($this->session->userdata('siteplan_status_token'),$token)) show_error('Sesi formulir tidak valid. Muat ulang halaman.',403);
        $input=$this->input->post('statuses');$rows=$this->mod_siteplan_status->listing();$updates=array();$error='';
        if (!is_array($input) || count($input)!==count($rows)) { $this->render($rows,'Data status tidak lengkap. Muat ulang halaman.');return; }
        foreach ($rows as $row) {
            $item=isset($input[$row->kode]) ? $input[$row->kode] : NULL;
            if (!is_array($item) || !isset($item['nama'],$item['warna']) || !is_string($item['nama']) || !is_string($item['warna'])) { $error='Data status tidak valid.';break; }
            $name=trim($item['nama']);$color=trim($item['warna']);
            if ($name==='' || mb_strlen($name)>80 || preg_match('/[\x00-\x1F\x7F]/u',$name)) $error='Nama status wajib diisi, maksimal 80 karakter.';
            if (!preg_match('/^#[0-9a-fA-F]{6}$/D',$color)) $error='Warna harus berupa kode HEX, misalnya #ffffff.';
            $row->nama=$name;$row->warna=preg_match('/^#[0-9a-fA-F]{6}$/D',$color) ? strtolower($color) : $row->warna;
            $updates[]=array('kode'=>$row->kode,'nama'=>$row->nama,'warna'=>$row->warna);
        }
        if ($error) { $this->render($rows,$error);return; }
        $ok=$this->mod_siteplan_status->save_all($updates);
        $this->session->set_flashdata($ok ? 'ok' : 'error',$ok ? 'Nama dan warna status siteplan berhasil disimpan.' : 'Pengaturan gagal disimpan.');redirect('pengaturan_siteplan');
    }
}
