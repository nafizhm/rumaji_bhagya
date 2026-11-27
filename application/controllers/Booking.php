<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Booking extends CI_Controller
{
    public function __construct() { parent::__construct();$this->load->model('mod_booking'); }
    private function json($data,$status=200) { return $this->output->set_status_header($status)->set_content_type('application/json')->set_output(json_encode($data)); }
    private function access() { $this->lib->check_login();$this->lib->check_auth($this->session->userdata('roleid'),'PJ','T001','R'); }
    public function index()
    {
        if (!$this->session->userdata('booking_token')) $this->session->set_userdata('booking_token',bin2hex(random_bytes(32)));
        $this->load->view('booking_public',array('fields'=>$this->mod_booking->fields(),'attachments'=>$this->mod_booking->attachments(),
            'projects'=>$this->db->order_by('projid','ASC')->get_where('mproj',array('status'=>'AKT'))->result(),
            'marketing'=>$this->db->order_by('namaslsman')->get('msalesman')->result(),'request_key'=>bin2hex(random_bytes(16))));
    }
    public function lots($projid=NULL) { $this->json(array('data'=>$this->mod_booking->lots((int)$projid))); }
    public function siteplan()
    {
        $this->load->model('mod_siteplan');
        $projects=$this->mod_siteplan->projects(TRUE,TRUE);
        $requested=$this->input->get('projid');$projid=(int)$requested;$project=NULL;
        if ($requested===NULL) foreach ($projects as $item) if ($item->has_siteplan!==NULL) { $projid=(int)$item->projid;break; }
        foreach ($projects as $item) if ((int)$item->projid===$projid) $project=$item;
        if ($projid && !$project) { show_404();return; }
        $lotid=0;
        $template=$project ? $this->mod_siteplan->template($projid) : NULL;
        $units=$template ? $this->mod_siteplan->units($projid,$lotid) : array();
        $this->load->model('mod_siteplan_status');
        $legend=$this->mod_siteplan_status->legend();
        $counts=array_fill_keys(array_keys($legend),0);
        foreach ($units as $unit) { $counts[$unit->state]++;$unit->stock_url=NULL;$unit->spr_url=NULL; }
        $this->load->view('siteplan_public',array('title'=>'Siteplan Penjualan','projects'=>$projects,'project'=>$project,'projid'=>$projid,'template'=>$template,'units'=>$units,'legend'=>$legend,'counts'=>$counts));
    }
    public function units($projid=NULL) {
        $units=$this->mod_booking->units((int)$projid,(int)$this->input->get('lot_release_id'));
        $data=array();foreach ($units as $unit) $data[]=array('blok'=>$unit->blok,'no'=>$unit->no,'lot'=>$unit->release_kode_lot,'jenis'=>$unit->jenis);
        $this->json(array('data'=>$data));
    }
    public function price()
    {
        $unit=$this->mod_booking->unit((int)$this->input->get('projid'),$this->input->get('blok'),$this->input->get('no'),(int)$this->input->get('lot_release_id'));
        if (!$unit) { $this->json(array('message'=>'Unit tidak tersedia.'),404);return; }
        $price=$this->mod_booking->price($unit);
        $this->json(array('harga'=>$price?(float)$price->hrgstd:0,'biaya_titipan'=>$price?(float)$price->byttp:0,'total'=>$price?(float)$price->hrgstd+(float)$price->byttp:0));
    }
    public function store()
    {
        if ($this->input->method()!=='post') { $this->json(array('message'=>'Metode tidak diizinkan.'),405);return; }
        $token=$this->session->userdata('booking_token');
        if (!$token || !hash_equals($token,(string)$this->input->post('token'))) { $this->json(array('message'=>'Sesi formulir berakhir atau unggahan terlalu besar. Muat ulang halaman dan periksa ukuran berkas.'),403);return; }
        $key=(string)$this->input->post('request_key');
        if (!preg_match('/^[a-f0-9]{32}$/D',$key)) { $this->json(array('message'=>'Referensi formulir tidak valid.'),422);return; }
        $old=$this->db->get_where('tbooking',array('request_key'=>$key))->row();
        if ($old) { $this->json(array('message'=>'Booking sudah diterima.','no_registrasi'=>$old->no_registrasi));return; }
        foreach ($this->mod_booking->fields() as $name=>$field) {
            $rules='trim|'.($field[2]?'required|':'').'max_length['.($field[1]==='textarea'?'2000':'150').']';
            if ($name==='nik') $rules.='|regex_match[/^[0-9]{16}$/]';
            if ($name==='nik_p') $rules.='|regex_match[/^[0-9]{16}$/]';
            if ($name==='email') $rules.='|valid_email';
            if (is_array($field[1])) $rules.='|in_list['.implode(',',$field[1]).']';
            $this->form_validation->set_rules($name,$field[0],$rules);
        }
        $this->form_validation->set_rules('no_telp','No. Telp / WA','trim|required|max_length[30]|regex_match[/^[+0-9 ()-]{6,30}$/]');
        $this->form_validation->set_rules('jenis_perumahan','Jenis Perumahan','required|in_list[Subsidi,Komersil]');
        $this->form_validation->set_rules('jenis_pembelian','Jenis Pembelian','required|in_list[Pembelian Cash,Cash Bertahap,KPR]');
        if (!$this->form_validation->run()) { $this->json(array('message'=>trim(strip_tags(validation_errors()))),422);return; }
        foreach (array('tanggal','tgl_lahir') as $field) { $value=$this->input->post($field);$date=DateTime::createFromFormat('!Y-m-d',$value);if (!$date || $date->format('Y-m-d')!==$value || ($field==='tgl_lahir'&&$value>date('Y-m-d'))) { $this->json(array('message'=>'Tanggal tidak valid.'),422);return; } }
        $fee=str_replace('.','',(string)$this->input->post('booking_fee'));
        if (!preg_match('/^[0-9]{1,13}$/D',$fee) || (int)$fee<=0) { $this->json(array('message'=>'Booking fee harus berupa rupiah lebih dari nol.'),422);return; }
        $unit=$this->mod_booking->unit((int)$this->input->post('projid'),(string)$this->input->post('blok'),(string)$this->input->post('no'),(int)$this->input->post('lot_release_id'));
        if (!$unit) { $this->json(array('message'=>'Unit tidak tersedia. Pilih ulang lokasi dan kavling.'),422);return; }
        $price=$this->mod_booking->price($unit);$total=$price?(float)$price->hrgstd+(float)$price->byttp:0;
        if ($total<=0) { $this->json(array('message'=>'Harga unit belum ditentukan. Hubungi marketing.'),422);return; }
        if ((int)$fee>$total) { $this->json(array('message'=>'Booking fee tidak boleh melebihi harga unit.'),422);return; }
        $marketing=(string)$this->input->post('id_marketing');
        $sales=$marketing==='0'?NULL:$this->db->get_where('msalesman',array('kodeslsman'=>$marketing))->row();
        if ($marketing!=='0'&&!$sales) { $this->json(array('message'=>'Pilih marketing yang valid.'),422);return; }
        $payload=array();foreach ($this->mod_booking->fields() as $name=>$field) $payload[$name]=trim((string)$this->input->post($name));
        for ($i=1;$i<=5;$i++) foreach (array('kontak_darurat','nama_pemilik_kontak_darurat','keterangan_kontak_darurat') as $prefix) { $name=$prefix.'_'.$i;$value=$this->input->post($name);if (!is_scalar($value)&&$value!==NULL || mb_strlen((string)$value)>255) { $this->json(array('message'=>'Kontak darurat tidak valid.'),422);return; }$payload[$name]=trim((string)$value); }
        $files=array();$paths=array();$folder=APPPATH.'uploads/booking/';
        if (!is_dir($folder)) mkdir($folder,0750,TRUE);
        $this->load->library('upload');
        foreach ($this->mod_booking->attachments() as $field=>$label) {
            if (empty($_FILES[$field]) || $_FILES[$field]['error']===UPLOAD_ERR_NO_FILE) { if ($field==='foto_ktp') { foreach ($paths as $path) unlink($path);$this->json(array('message'=>'Foto KTP wajib diunggah.'),422);return; }continue; }
            $filename=bin2hex(random_bytes(20));
            $this->upload->initialize(array('upload_path'=>$folder,'allowed_types'=>'jpg|jpeg|png|webp|pdf','max_size'=>10240,'file_name'=>$filename,'file_ext_tolower'=>TRUE));
            if (!$this->upload->do_upload($field)) { foreach ($paths as $path) unlink($path);$this->json(array('message'=>$label.': '.strip_tags($this->upload->display_errors('',''))),422);return; }
            $file=$this->upload->data();$paths[]=$file['full_path'];$files[$field]=array('nama'=>$label,'file'=>$file['file_name'],'mime'=>$file['file_type']);
        }
        $this->db->trans_begin();
        $release=$this->db->query('SELECT id FROM mlot WHERE id=? AND siteplan_active=1 AND release_saved_at IS NOT NULL FOR UPDATE',array($unit->release_lot_id))->row();
        $locked=$this->db->query('SELECT status FROM mkav WHERE projid=? AND blok=? AND no=? FOR UPDATE',array($unit->projid,$unit->blok,$unit->no))->row();
        $ready=$release ? $this->mod_booking->unit($unit->projid,$unit->blok,$unit->no,$unit->release_lot_id) : NULL;
        $taken=$this->db->where_in('status',array('Baru','Pending','Disetujui'))->get_where('tbooking',array('projid'=>$unit->projid,'blok'=>$unit->blok,'no'=>$unit->no))->row();
        if (!$ready||!$locked||!in_array($locked->status,array('BLA','BTL'),TRUE)||$taken) { $this->db->trans_rollback();foreach ($paths as $path) unlink($path);$this->json(array('message'=>'Unit tidak lagi ready pada Lot Release ini. Silakan pilih unit lain.'),409);return; }
        $latestPrice=$this->mod_booking->price($ready);
        if ((float)$latestPrice->hrgstd+(float)$latestPrice->byttp!==$total) { $this->db->trans_rollback();foreach ($paths as $path) unlink($path);$this->json(array('message'=>'Harga Lot Release berubah. Pilih ulang unit untuk memperbarui harga.'),409);return; }
        $registration='BG-'.date('Ymd').'-'.strtoupper(bin2hex(random_bytes(5)));
        $ok=$this->db->insert('tbooking',array('no_registrasi'=>$registration,'request_key'=>$key,'lot_release_id'=>$unit->release_lot_id,'tanggal'=>$payload['tanggal'],'nama_lengkap'=>$payload['nama_lengkap'],'nik'=>$payload['nik'],'no_telp'=>$payload['no_telp'],'projid'=>$unit->projid,'blok'=>$unit->blok,'no'=>$unit->no,'nama_proyek'=>$unit->namproj,'marketing'=>$sales?$sales->namaslsman:'Non Marketing','jenis_perumahan'=>$this->input->post('jenis_perumahan'),'jenis_pembelian'=>$this->input->post('jenis_pembelian'),'total_harga'=>$total,'booking_fee'=>$fee,'status'=>'Pending','data_pemohon'=>json_encode($payload),'berkas'=>json_encode($files),'created_at'=>date('Y-m-d H:i:s')));
        if (!$ok||!$this->db->trans_status()) { $this->db->trans_rollback();foreach ($paths as $path) unlink($path);$this->json(array('message'=>'Booking gagal disimpan. Silakan coba kembali.'),500);return; }
        $this->db->trans_commit();$this->json(array('message'=>'Booking berhasil dikirim. Pengajuan konsumen siap ditindaklanjuti.','no_registrasi'=>$registration));
    }
    public function admin() { $this->access();$this->load->view('list_booking',array('title'=>'Booking')); }
    public function data()
    {
        $this->access();$search=$this->input->get('search');$q=is_array($search)&&isset($search['value'])?trim((string)$search['value']):'';
        $total=$this->db->count_all('tbooking');
        if ($q!=='') $this->db->group_start()->like('nama_lengkap',$q)->or_like('no_registrasi',$q)->or_like('nama_proyek',$q)->or_like('blok',$q)->group_end();
        $filtered=$this->db->count_all_results('tbooking');
        if ($q!=='') $this->db->group_start()->like('nama_lengkap',$q)->or_like('no_registrasi',$q)->or_like('nama_proyek',$q)->or_like('blok',$q)->group_end();
        $length=(int)$this->input->get('length');$length=in_array($length,array(10,25,50,100),TRUE)?$length:10;
        $rows=$this->db->select('id,no_registrasi,tanggal,nama_lengkap,no_telp,nama_proyek,blok,no,marketing,booking_fee,status')->order_by('id','DESC')->get('tbooking',$length,max(0,(int)$this->input->get('start')))->result();
        foreach ($rows as $row) if ($row->status==='Baru') $row->status='Pending';
        $this->json(array('draw'=>(int)$this->input->get('draw'),'recordsTotal'=>$total,'recordsFiltered'=>$filtered,'data'=>$rows));
    }
    public function detail($id=NULL)
    {
        $this->access();$row=$this->db->get_where('tbooking',array('id'=>(int)$id))->row();if (!$row) { show_404();return; }
        if ($row->status==='Baru') $row->status='Pending';
        if (!$this->session->userdata('booking_verify_token')) $this->session->set_userdata('booking_verify_token',bin2hex(random_bytes(32)));
        $this->load->view('detail_booking',array('title'=>'Verifikasi Booking','booking'=>$row,'fields'=>$this->mod_booking->fields(),'payload'=>json_decode($row->data_pemohon,TRUE),'files'=>json_decode($row->berkas,TRUE)));
    }
    public function verify($id=NULL)
    {
        $this->access();
        $this->lib->check_auth($this->session->userdata('roleid'),'PJ','T001','U');
        if ($this->input->method()!=='post') { show_error('Metode tidak diizinkan.',405);return; }
        $token=$this->session->userdata('booking_verify_token');
        if (!$token || !hash_equals($token,(string)$this->input->post('token'))) { show_error('Sesi verifikasi berakhir. Muat ulang halaman.',403);return; }
        $status=$this->input->post('status');
        if (!in_array($status,array('Pending','Disetujui','Ditolak'),TRUE)) { show_error('Status tidak valid.',422);return; }
        $booking=$this->db->get_where('tbooking',array('id'=>(int)$id))->row();
        if (!$booking) { show_404();return; }
        if ($booking->status==='SPR') { show_error('Booking sudah dibuat SPR dan tidak dapat diverifikasi ulang.',409);return; }
        $this->db->trans_begin();
        $stock=$this->db->query('SELECT status FROM mkav WHERE projid=? AND blok=? AND no=? FOR UPDATE',array($booking->projid,$booking->blok,$booking->no))->row();
        $current=$this->db->query('SELECT status FROM tbooking WHERE id=? FOR UPDATE',array($booking->id))->row();
        if (!$current || $current->status==='SPR') { $this->db->trans_rollback();show_error('Booking sudah dibuat SPR atau tidak ditemukan.',409);return; }
        if ($status!=='Ditolak') {
            $other=$this->db->where('id <>',$booking->id)->where_in('status',array('Baru','Pending','Disetujui'))->get_where('tbooking',array('projid'=>$booking->projid,'blok'=>$booking->blok,'no'=>$booking->no))->row();
            $sold=$this->db->get_where('tsprh',array('projid'=>$booking->projid,'blok'=>$booking->blok,'no'=>$booking->no,'status <>'=>'BTL'))->row();
            if (!$stock || !in_array($stock->status,array('BLA','BTL'),TRUE) || $other || $sold) { $this->db->trans_rollback();show_error('Unit sudah dipakai booking atau SPR lain.',409);return; }
        }
        $this->db->where('id',(int)$id)->update('tbooking',array('status'=>$status));
        if (!$this->db->trans_status()) { $this->db->trans_rollback();show_error('Status gagal disimpan.',500);return; }
        $this->db->trans_commit();
        $this->session->set_flashdata('booking_verified',TRUE);
        redirect('booking/admin');
    }
    public function file($id=NULL,$key='')
    {
        $this->access();$row=$this->db->get_where('tbooking',array('id'=>(int)$id))->row();$files=$row?json_decode($row->berkas,TRUE):array();
        if (!isset($files[$key])) { show_404();return; }$file=$files[$key];$path=APPPATH.'uploads/booking/'.basename($file['file']);if (!is_file($path)) { show_404();return; }
        $inline=$this->input->get('view')==='1' && in_array($file['mime'],array('image/jpeg','image/png','image/webp','application/pdf'),TRUE);
        $this->output->set_content_type($file['mime'])->set_header('X-Content-Type-Options: nosniff')->set_header('Cache-Control: private, no-store')->set_header('Content-Disposition: '.($inline?'inline':'attachment').'; filename="'.basename($file['file']).'"')->set_output(file_get_contents($path));
    }
}
