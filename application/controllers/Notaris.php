<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notaris extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_notaris');
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
        if (!$this->mod_notaris->permission($action)) {
            $this->respond(array('message' => 'Anda tidak memiliki hak akses untuk tindakan ini.'), 403);
            return FALSE;
        }
        return TRUE;
    }

    public function index()
    {
        $this->lib->check_login();
        if (!$this->mod_notaris->permission('r')) { show_error('Anda tidak memiliki akses Notaris.', 403); return; }
        if (!$this->session->userdata('notaris_token')) $this->session->set_userdata('notaris_token', bin2hex(random_bytes(32)));
        $data = array('title' => 'Notaris', 'can_create' => $this->mod_notaris->permission('c'), 'can_update' => $this->mod_notaris->permission('u'), 'can_delete' => $this->mod_notaris->permission('d'));
        $this->load->view('kategori_notaris', $data);
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
        $columns = array('id', 'nama_notaris', 'keterangan');
        $column = isset($order[0]['column'], $columns[(int) $order[0]['column']]) ? $columns[(int) $order[0]['column']] : 'id';
        $direction = isset($order[0]['dir']) && $order[0]['dir'] === 'asc' ? 'ASC' : 'DESC';
        $data = $this->mod_notaris->listing($search, $length, $start, $column, $direction);
        $data['draw'] = (int) $this->input->get('draw');
        $this->respond($data);
    }

    private function mutation($action)
    {
        if (!$this->allowed($action)) return FALSE;
        if ($this->input->method() !== 'post') { $this->respond(array('message' => 'Metode tidak diizinkan.'), 405); return FALSE; }
        $token = $this->session->userdata('notaris_token');
        if (!$token || !hash_equals($token, (string) $this->input->post('token'))) { $this->respond(array('message' => 'Sesi formulir tidak valid. Muat ulang halaman.'), 403); return FALSE; }
        return TRUE;
    }

    public function save()
    {
        $id = (int) $this->input->post('id');
        if (!$this->mutation($id ? 'u' : 'c')) return;
        $this->form_validation->set_rules('nama_notaris', 'Nama notaris', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|max_length[5000]');
        if (!$this->form_validation->run()) { $this->respond(array('message' => trim(strip_tags(validation_errors()))), 422); return; }
        if ($id && !$this->db->get_where('mnotaris', array('id' => $id))->row()) { $this->respond(array('message' => 'Notaris tidak ditemukan.'), 404); return; }
        $name = trim((string) $this->input->post('nama_notaris'));
        $duplicate = $this->db->where('nama_notaris', $name)->where('id <>', $id)->get('mnotaris')->row();
        if ($duplicate) { $this->respond(array('message' => 'Nama notaris sudah digunakan. Gunakan nama lain.'), 409); return; }
        $data = array('nama_notaris' => $name, 'keterangan' => trim((string) $this->input->post('keterangan')), 'updated_at' => date('Y-m-d H:i:s'));
        if (!$id) $data['created_at'] = $data['updated_at'];
        $debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;
        try {
            $ok = $id ? $this->db->where('id', $id)->update('mnotaris', $data) : $this->db->insert('mnotaris', $data);
        } catch (Exception $e) { $ok = FALSE; }
        $this->db->db_debug = $debug;
        $this->respond(array('message' => $ok ? ($id ? 'Notaris berhasil diperbarui.' : 'Notaris berhasil ditambahkan.') : 'Data belum tersimpan. Periksa apakah nama sudah digunakan, lalu coba kembali.'), $ok ? 200 : 409);
    }

    public function delete()
    {
        if (!$this->mutation('d')) return;
        $id = (int) $this->input->post('id');
        $this->db->trans_begin();
        if (!$id || !$this->db->query('SELECT id FROM mnotaris WHERE id=? FOR UPDATE',array($id))->row()) { $this->db->trans_rollback();$this->respond(array('message' => 'Notaris tidak ditemukan.'), 404); return; }
        if ($this->db->where('notaris_id',$id)->count_all_results('tsprh') || $this->db->where('notaris_id',$id)->count_all_results('tkavlog')) {
            $this->db->trans_rollback();$this->respond(array('message'=>'Notaris sudah digunakan dalam akad dan tidak dapat dihapus.'),409);return;
        }
        $ok = $this->db->delete('mnotaris', array('id' => $id));
        if ($ok && $this->db->trans_status()) $this->db->trans_commit();
        else { $this->db->trans_rollback();$ok=FALSE; }
        $this->respond(array('message' => $ok ? 'Notaris berhasil dihapus.' : 'Notaris tidak dapat dihapus.'), $ok ? 200 : 500);
    }
}
