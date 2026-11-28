<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lot extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->lib->check_login();
        $this->load->model('mod_lot');
        if (!$this->session->userdata('lot_token')) $this->session->set_userdata('lot_token', bin2hex(random_bytes(32)));
    }

    private function authorize($action)
    {
        if (!$this->mod_lot->permission($action)) show_error('Anda tidak memiliki hak akses Pengaturan Lot.', 403);
    }

    private function mutation()
    {
        if ($this->input->method() !== 'post') show_error('Metode tidak diizinkan.', 405);
        if (!hash_equals((string)$this->session->userdata('lot_token'), (string)$this->input->post('lot_token'))) show_error('Sesi formulir tidak valid. Muat ulang halaman.', 403);
    }

    public function index()
    {
        $this->authorize('r');
        $this->load->view('list_lot', array('title'=>'Pengaturan Lot', 'rows'=>$this->mod_lot->listing()));
    }

    public function create()
    {
        $this->authorize('c');
        $this->form(NULL);
    }

    public function edit($id = 0)
    {
        $this->authorize('u');
        $this->form($this->mod_lot->find($id));
    }

    private function form($row, $error = '')
    {
        $this->load->view('frm_lot', array('title'=>$row ? 'Edit Lot' : 'Tambah Lot', 'row'=>$row, 'error'=>$error, 'projects'=>$this->db->order_by('projid', 'ASC')->get('mproj')->result()));
    }

    public function detail($id = 0)
    {
        $this->authorize('r');
        $row = $this->mod_lot->find($id);
        $project=$row->projid ? $this->db->get_where('mproj',array('projid'=>$row->projid))->row() : NULL;
        $this->load->view('detail_lot', array('title'=>'Detail Lot Release', 'row'=>$row, 'project'=>$project, 'units'=>$this->mod_lot->available_units($row), 'jumlah_unit'=>$this->mod_lot->unit_count($row->id)));
    }

    public function save()
    {
        $id = (int)$this->input->post('id');
        $this->authorize($id ? 'u' : 'c');
        $this->mutation();
        $row = $id ? $this->mod_lot->find($id) : NULL;
        $this->form_validation->set_rules('projid','Lokasi proyek','required|integer');
        $this->form_validation->set_rules('kode_lot','Kode Lot','trim|required|max_length[50]');
        $this->form_validation->set_rules('keterangan','Keterangan','trim|max_length[5000]');
        if (!$this->form_validation->run()) { $this->form($row); return; }
        $code = strtoupper(trim((string)$this->input->post('kode_lot')));
        $projid=(int)$this->input->post('projid');
        if (!$this->db->get_where('mproj',array('projid'=>$projid))->row()) { $this->form($row,'Lokasi proyek tidak valid.'); return; }
        if ($this->db->where('projid',$projid)->where('kode_lot',$code)->where('id <>',$id)->count_all_results('mlot')) { $this->form($row, 'Kode Lot sudah digunakan pada proyek ini.'); return; }
        $data = array('projid'=>$projid,'kode_lot'=>$code,'keterangan'=>trim((string)$this->input->post('keterangan')));
        $error=$this->mod_lot->save_master($id,$data);
        if ($error) { $this->form($row,$error); return; }
        $this->session->set_flashdata('ok', 'Data Lot berhasil disimpan.');
        redirect('lot');
    }

    public function delete()
    {
        $this->authorize('d');
        $this->mutation();
        $row = $this->mod_lot->find($this->input->post('id'));
        $ok = $this->db->delete('mlot', array('id'=>$row->id));
        $this->session->set_flashdata($ok ? 'ok' : 'error', $ok ? 'Lot berhasil dihapus. Unit dapat dipilih kembali pada Lot Release lain.' : 'Lot gagal dihapus.');
        redirect('lot');
    }

    private function release_response($message, $status = 200)
    {
        return $this->output->set_status_header($status)->set_content_type('application/json')->set_output(json_encode(array('message'=>$message)));
    }

    public function release()
    {
        $this->authorize('u');
        $this->mutation();
        $id=(int)$this->input->post('id');
        $this->mod_lot->find($id);
        $date=$this->input->post('tanggal_release');
        $type=$this->input->post('release_type');
        $nomorSurat=$this->input->post('nomor_surat');
        $siteplan=$this->input->post('siteplan_active');
        if (!in_array($siteplan,array(NULL,'0','1'),TRUE)) { $this->release_response('Pilihan Siteplan tidak valid.',422); return; }
        if ($nomorSurat === NULL) $nomorSurat='';
        if (!is_string($nomorSurat) || mb_strlen(trim($nomorSurat))>150) { $this->release_response('Nomor surat maksimal 150 karakter.',422); return; }
        if (!is_string($date) || !is_string($type)) { $this->release_response('Data release tidak valid.',422); return; }
        $date=trim($date); $type=trim($type);
        $parsed=DateTime::createFromFormat('!Y-m-d',$date);
        if ($date !== '' && (!$parsed || $parsed->format('Y-m-d')!==$date || $parsed->format('Y')<'1000')) { $this->release_response('Tanggal release tidak valid.',422); return; }
        if (mb_strlen($type)>100) { $this->release_response('Type maksimal 100 karakter.',422); return; }
        $data=array('tanggal_release'=>$date === '' ? NULL : $date,'release_type'=>$type,'nomor_surat'=>trim($nomorSurat),'siteplan_active'=>$siteplan==='1' ? 1 : 0);
        $data['utj']='500000.00';
        $data['utj_cicilan']=1;
        foreach (array('um_cicilan','bll_cicilan') as $field) {
            $value=$this->input->post($field);
            if (!is_string($value) || !preg_match('/^[1-9]\d{0,2}$/D',$value)) { $this->release_response('Jumlah cicilan UM dan BLL harus 1 sampai 999 kali.',422); return; }
            $data[$field]=(int)$value;
        }
        $amounts=array('harga_jual','uang_muka','diskon_uang_muka','biaya_lain','kpr','perkiraan_angsuran','biaya_hook','biaya_kelebihan_tanah');
        $cents=array();
        foreach ($amounts as $field) {
            $value=$this->input->post($field);
            if (!is_string($value) || !preg_match('/^\d{1,12}(?:\.\d{1,2})?$/D',trim($value))) { $this->release_response('Isi nominal dengan angka positif atau nol, maksimal 2 angka desimal.',422); return; }
            $cents[$field]=(int)round((float)trim($value)*100);
            $data[$field]=number_format($cents[$field]/100,2,'.','');
        }
        if ($cents['diskon_uang_muka']>$cents['uang_muka']) { $this->release_response('Diskon uang muka tidak boleh melebihi uang muka.',422); return; }
        $data['uang_muka_setelah_diskon']=number_format(($cents['uang_muka']-$cents['diskon_uang_muka'])/100,2,'.','');
        $data['release_saved_at']=date('Y-m-d H:i:s');
        $ok=$this->db->where('id',$id)->update('mlot',$data);
        $this->release_response($ok ? 'Data release berhasil disimpan.' : 'Data release gagal disimpan.',$ok ? 200 : 500);
    }

    public function cetak($id = 0)
    {
        $this->authorize('r');
        $row=$this->mod_lot->find($id);
        if (!$row->release_saved_at) {
            $this->session->set_flashdata('error','Simpan data Release terlebih dahulu sebelum mencetak.');
            redirect('lot');
            return;
        }
        $project=$row->projid ? $this->db->get_where('mproj',array('projid'=>$row->projid))->row() : NULL;
        $units=$this->db->where('lot_id',$row->id)->order_by('blok')->order_by('no')->get('mlot_unit')->result();
        $this->load->view('cetak_lot_release',array('row'=>$row,'project'=>$project,'units'=>$units));
    }

    public function save_units()
    {
        $this->authorize('u');
        $this->mutation();
        $id=(int)$this->input->post('id');
        $this->mod_lot->find($id);
        $payload=$this->input->post('units_json');
        $values=is_string($payload) ? json_decode($payload,TRUE) : NULL;
        if (!is_array($values)) show_error('Pilihan unit tidak valid. Muat ulang halaman lalu simpan kembali.',422);
        $units=array();
        foreach ($values ?: array() as $value) {
            $unit=is_string($value) ? json_decode(base64_decode($value,TRUE),TRUE) : NULL;
            if (!is_array($unit) || count($unit)!==2 || !isset($unit[0],$unit[1]) || !is_string($unit[0]) || !is_string($unit[1]) || strlen($unit[0])>3 || strlen($unit[1])>4) show_error('Pilihan unit tidak valid.',422);
            $units[json_encode($unit)]=$unit;
        }
        $error=$this->mod_lot->save_units($id,array_values($units));
        $this->session->set_flashdata($error ? 'error' : 'ok',$error ?: 'Pilihan unit Lot Release berhasil disimpan.');
        redirect('lot/detail/'.$id);
    }
}
