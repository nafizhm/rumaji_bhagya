<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuan_retensi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();$this->lib->check_login();$this->load->model('mod_pengajuan_retensi');
        if (!$this->session->userdata('pengajuan_retensi_token')) $this->session->set_userdata('pengajuan_retensi_token',bin2hex(random_bytes(32)));
    }

    private function authorize($action)
    {
        if (!$this->mod_pengajuan_retensi->permission($action)) show_error('Anda tidak memiliki akses Pengajuan Pencairan Retensi.',403);
    }

    private function mutation()
    {
        if ($this->input->method()!=='post') show_error('Metode tidak diizinkan.',405);
        $token=$this->input->post('token');
        if (!is_string($token) || !hash_equals($this->session->userdata('pengajuan_retensi_token'),$token)) show_error('Sesi formulir tidak valid. Muat ulang halaman.',403);
    }

    public function index()
    {
        $this->authorize('r');$this->load->view('list_pengajuan_retensi',array('title'=>'Pengajuan Pencairan Retensi','rows'=>$this->mod_pengajuan_retensi->listing()));
    }

    public function create() { $this->form(0); }
    public function edit($id=0) { $this->form((int)$id); }

    private function form($id,$data=NULL,$error='',$processed=NULL,$selected=NULL)
    {
        $this->authorize($id ? 'u' : 'c');
        $existing=$id ? $this->mod_pengajuan_retensi->find($id) : NULL;
        if ($data===NULL) $data=$existing ? (array)$existing : array('projid'=>'','tanggal_surat'=>(new DateTimeImmutable('now',new DateTimeZone('Asia/Jakarta')))->format('Y-m-d'),'no_surat'=>'','tujuan_surat'=>'','no_rekening'=>'','kategori_id'=>'');
        $data=array_merge(array('kota_surat'=>'Cirendeu','nama_perusahaan'=>'PT RAJA CAHAYA PROPERTINDO','alamat_proyek'=>'','bank_tujuan'=>'PT. Bank Tabungan Negara (Persero) Tbk.','alamat_tujuan'=>'','atas_nama'=>'','bank_rekening'=>''),$data);
        if ($selected===NULL) { $selected=array();if ($id) foreach ($this->mod_pengajuan_retensi->details($id) as $item) $selected[]=$item->projid.':'.$item->sprno; }
        if ($processed===NULL) $processed=(bool)$id;
        $rows=$processed ? $this->mod_pengajuan_retensi->candidates((int)$data['projid'],(int)$data['kategori_id']) : array();
        $accounts=$this->db->select('a.projid,a.accno,a.namacc,b.nambank')->from('mbankacc a')->join('mbank b','b.bankid=a.bankid','left')->order_by('a.projid')->order_by('a.accno')->get()->result();
        $destinations=$this->db->select('bank_cabang')->distinct()->where('bank_cabang <>','')->order_by('bank_cabang')->get('tpengajuan_akad_peserta')->result();
        $this->load->view('frm_pengajuan_retensi',array('title'=>$id ? 'Edit Pengajuan Pencairan Retensi' : 'Pengajuan Pencairan Retensi Baru','id'=>$id,'data'=>$data,'error'=>$error,'processed'=>$processed,'selected'=>$selected,'rows'=>$rows,
            'projects'=>$this->db->order_by('projid','ASC')->get('mproj')->result(),'categories'=>$this->mod_pengajuan_retensi->categories(),'accounts'=>$accounts,'destinations'=>$destinations));
    }

    public function save()
    {
        $this->mutation();$id=(int)$this->input->post('id');$this->authorize($id ? 'u' : 'c');
        $data=array();$error='';
        foreach (array('projid','tanggal_surat','no_surat','tujuan_surat','no_rekening','kategori_id') as $field) {
            $value=$this->input->post($field);$data[$field]=is_string($value) ? trim($value) : '';
            if ($data[$field]==='') $error='Lengkapi seluruh isian surat.';
        }
        foreach (array('kota_surat'=>100,'nama_perusahaan'=>150,'alamat_proyek'=>2000,'bank_tujuan'=>150,'alamat_tujuan'=>2000,'atas_nama'=>150,'bank_rekening'=>150) as $field=>$limit) {
            $value=$this->input->post($field);$data[$field]=is_string($value) ? trim($value) : '';
            if (($value!==NULL && !is_string($value)) || mb_strlen($data[$field])>$limit) $error='Isian data cetak tidak valid atau terlalu panjang.';
        }
        $categories=$this->mod_pengajuan_retensi->categories();
        if (!ctype_digit($data['projid']) || !$this->db->get_where('mproj',array('projid'=>(int)$data['projid']))->row()) $error='Nama proyek tidak valid.';
        if (!ctype_digit($data['kategori_id']) || !isset($categories[$data['kategori_id']])) $error='Jenis retensi tidak valid.';
        $date=DateTime::createFromFormat('!Y-m-d',$data['tanggal_surat']);
        if (!$date || $date->format('Y-m-d')!==$data['tanggal_surat'] || $date->format('Y')<'1000') $error='Tanggal surat tidak valid.';
        foreach (array('no_surat'=>100,'tujuan_surat'=>150,'no_rekening'=>50) as $field=>$limit) if (mb_strlen($data[$field])>$limit) $error='Isian surat melebihi panjang maksimal.';
        $keys=$this->input->post('participants');$keys=is_array($keys) ? $keys : array();$selected=array();
        foreach ($keys as $key) {
            if (!is_string($key) || !preg_match('/^[1-9][0-9]*:[1-9][0-9]*$/D',$key)) { $error='Pilihan konsumen tidak valid.';break; }
            $selected[$key]=$key;
        }
        $process=$this->input->post('action')==='process';
        if ($error) { $this->form($id,$data,$error,FALSE,array_values($selected));return; }
        if ($process) { $this->form($id,$data,'',TRUE,array_values($selected));return; }
        $data['projid']=(int)$data['projid'];$data['kategori_id']=(int)$data['kategori_id'];$data['nama_retensi']=$categories[$data['kategori_id']];
        $error=$this->mod_pengajuan_retensi->save_request($id,$data,array_values($selected));
        if ($error) { $this->form($id,$data,$error,TRUE,array_values($selected));return; }
        $this->session->set_flashdata('ok','Pengajuan pencairan retensi berhasil disimpan.');redirect('pengajuan_retensi');
    }

    public function detail($id=0)
    {
        $this->authorize('r');$row=$this->mod_pengajuan_retensi->find($id);
        $this->load->view('detail_pengajuan_retensi',array('title'=>'Detail Pengajuan Pencairan Retensi','row'=>$row,'project'=>$this->db->get_where('mproj',array('projid'=>$row->projid))->row(),'rows'=>$this->mod_pengajuan_retensi->details($row->id)));
    }

    public function cetak($id=0)
    {
        $this->authorize('r');$row=$this->mod_pengajuan_retensi->find($id);
        $project=$this->db->get_where('mproj',array('projid'=>$row->projid))->row();
        $rows=$this->mod_pengajuan_retensi->print_details($row->id);$total=0;
        foreach ($rows as $item) $total+=(int)$item->jumlah;
        $account=$this->db->select('a.namacc,b.nambank')->from('mbankacc a')->join('mbank b','b.bankid=a.bankid','left')->where('a.projid',$row->projid)->where('a.accno',$row->no_rekening)->get()->row();
        $owner=$row->atas_nama!=='' ? $row->atas_nama : ($account ? $account->namacc : $row->nama_perusahaan);
        $bank=$row->bank_rekening!=='' ? $row->bank_rekening : ($account ? $account->nambank : '');
        $this->load->library('retensi_pdf');
        $content=$this->retensi_pdf->generate($row,$project,$rows,$total,$owner,$bank,ucwords($this->mod_pengajuan_retensi->terbilang($total)).' Rupiah');
        $filename='Pengajuan-Retensi-'.preg_replace('/[^A-Za-z0-9_-]/','-',$row->no_surat).'.pdf';
        $this->output->set_content_type('application/pdf')->set_header('Content-Disposition: inline; filename="'.$filename.'"')->set_output($content);
    }

    public function pencairan($id=0)
    {
        $this->authorize('r');$this->payment_page($this->mod_pengajuan_retensi->find($id));
    }

    private function payment_page($row,$error='',$date=NULL,$selected=array())
    {
        if ($date===NULL) $date=(new DateTimeImmutable('today',new DateTimeZone('Asia/Jakarta')))->format('Y-m-d');
        $this->load->view('pencairan_pengajuan_retensi',array('title'=>'Pencairan Pengajuan Retensi','row'=>$row,'rows'=>$this->mod_pengajuan_retensi->print_details($row->id),
            'project'=>$this->db->get_where('mproj',array('projid'=>$row->projid))->row(),'error'=>$error,'payment_date'=>$date,'selected'=>$selected));
    }

    public function save_payment()
    {
        $this->authorize('u');$this->mutation();$row=$this->mod_pengajuan_retensi->find($this->input->post('id'));
        $date=$this->input->post('tanggal_pembayaran');$error='';
        $parsed=is_string($date) ? DateTime::createFromFormat('!Y-m-d',$date) : FALSE;
        if (!$parsed || $parsed->format('Y-m-d')!==$date || $parsed->format('Y')<'1000') $error='Tanggal pembayaran tidak valid.';
        $keys=$this->input->post('participants');$keys=is_array($keys) ? $keys : array();$selected=array();
        foreach ($keys as $key) {
            if (!is_string($key) || !preg_match('/^[1-9][0-9]*:[1-9][0-9]*$/D',$key)) { $error='Pilihan konsumen tidak valid.';break; }
            $selected[$key]=$key;
        }
        if (!$error) $error=$this->mod_pengajuan_retensi->pay($row->id,$date,array_values($selected));
        if ($error) { $this->payment_page($row,$error,is_string($date) ? $date : '',array_values($selected));return; }
        $this->session->set_flashdata('ok','Pembayaran tersimpan. Saldo retensi konsumen terpilih sudah diperbarui.');
        redirect('pengajuan_retensi/pencairan/'.$row->id);
    }

    public function delete()
    {
        $this->authorize('d');$this->mutation();$row=$this->mod_pengajuan_retensi->find($this->input->post('id'));
        $error=$this->mod_pengajuan_retensi->delete_request($row->id);
        $this->session->set_flashdata($error ? 'error' : 'ok',$error ?: 'Pengajuan berhasil dihapus.');redirect('pengajuan_retensi');
    }
}
