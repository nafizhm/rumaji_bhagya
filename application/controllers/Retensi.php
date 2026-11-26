<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retensi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_retensi');
    }

    private function respond($data, $status = 200)
    {
        return $this->output->set_status_header($status)->set_content_type('application/json')->set_output(json_encode($data));
    }

    private function allowed($action)
    {
        if (!$this->session->userdata('logged_in')) {
            $this->respond(array('message' => 'Sesi berakhir. Silakan login kembali.'), 401);
            return FALSE;
        }
        if (!$this->mod_retensi->permission($action)) {
            $this->respond(array('message' => 'Anda tidak memiliki hak akses untuk tindakan ini.'), 403);
            return FALSE;
        }
        return TRUE;
    }

    public function index()
    {
        $this->lib->check_login();
        if (!$this->mod_retensi->permission('r')) { show_error('Anda tidak memiliki akses Kategori Retensi.', 403); return; }
        if (!$this->session->userdata('retensi_token')) $this->session->set_userdata('retensi_token', bin2hex(random_bytes(32)));
        $data = array('title' => 'Kategori Retensi', 'can_create' => $this->mod_retensi->permission('c'), 'can_update' => $this->mod_retensi->permission('u'), 'can_delete' => $this->mod_retensi->permission('d'));
        $this->load->view('kategori_retensi', $data);
    }

    public function data()
    {
        if (!$this->allowed('r')) return;
        $length = (int) $this->input->get('length');
        $length = in_array($length, array(10,25,50,100), TRUE) ? $length : 25;
        $start = max(0, (int) $this->input->get('start'));
        $search = $this->input->get('search');
        $search = is_array($search) && isset($search['value']) ? trim((string) $search['value']) : '';
        $order = $this->input->get('order');
        $columns = array('id', 'nama_retensi', 'keterangan');
        $column = isset($order[0]['column'], $columns[(int) $order[0]['column']]) ? $columns[(int) $order[0]['column']] : 'id';
        $direction = isset($order[0]['dir']) && $order[0]['dir'] === 'asc' ? 'ASC' : 'DESC';
        $data = $this->mod_retensi->listing($search, $length, $start, $column, $direction);
        $data['draw'] = (int) $this->input->get('draw');
        $this->respond($data);
    }

    private function mutation($action)
    {
        if (!$this->allowed($action)) return FALSE;
        if ($this->input->method() !== 'post') { $this->respond(array('message' => 'Metode tidak diizinkan.'), 405); return FALSE; }
        $token = $this->session->userdata('retensi_token');
        if (!$token || !hash_equals($token, (string) $this->input->post('token'))) { $this->respond(array('message' => 'Sesi formulir tidak valid. Muat ulang halaman.'), 403); return FALSE; }
        return TRUE;
    }

    public function save()
    {
        $id = (int) $this->input->post('id');
        if (!$this->mutation($id ? 'u' : 'c')) return;
        $this->form_validation->set_rules('nama_retensi', 'Nama retensi', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|max_length[5000]');
        if (!$this->form_validation->run()) { $this->respond(array('message' => trim(strip_tags(validation_errors()))), 422); return; }
        if ($id && !$this->db->get_where('mretensi_kategori', array('id' => $id))->row()) { $this->respond(array('message' => 'Kategori tidak ditemukan.'), 404); return; }
        $name = trim((string) $this->input->post('nama_retensi'));
        $duplicate = $this->db->where('nama_retensi', $name)->where('id <>', $id)->get('mretensi_kategori')->row();
        if ($duplicate) { $this->respond(array('message' => 'Nama retensi sudah digunakan. Gunakan nama lain.'), 409); return; }
        $data = array('nama_retensi' => $name, 'keterangan' => trim((string) $this->input->post('keterangan')), 'updated_at' => date('Y-m-d H:i:s'));
        if (!$id) $data['created_at'] = $data['updated_at'];
        $debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;
        try {
            $ok = $id ? $this->db->where('id', $id)->update('mretensi_kategori', $data) : $this->db->insert('mretensi_kategori', $data);
        } catch (Exception $e) { $ok = FALSE; }
        $this->db->db_debug = $debug;
        $this->respond(array('message' => $ok ? ($id ? 'Kategori retensi berhasil diperbarui.' : 'Kategori retensi berhasil ditambahkan.') : 'Data belum tersimpan. Periksa apakah nama sudah digunakan, lalu coba kembali.'), $ok ? 200 : 409);
    }

    public function delete()
    {
        if (!$this->mutation('d')) return;
        $id = (int) $this->input->post('id');
        if (!$id || !$this->db->get_where('mretensi_kategori', array('id' => $id))->row()) { $this->respond(array('message' => 'Kategori tidak ditemukan.'), 404); return; }
        $ok = $this->db->delete('mretensi_kategori', array('id' => $id));
        $this->respond(array('message' => $ok ? 'Kategori retensi berhasil dihapus.' : 'Kategori tidak dapat dihapus.'), $ok ? 200 : 500);
    }
}
