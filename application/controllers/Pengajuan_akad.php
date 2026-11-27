<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuan_akad extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();$this->lib->check_login();$this->load->model('mod_pengajuan_akad');
        if (!$this->session->userdata('pengajuan_akad_token')) $this->session->set_userdata('pengajuan_akad_token',bin2hex(random_bytes(32)));
    }

    private function authorize($action)
    {
        if (!$this->mod_pengajuan_akad->permission($action)) show_error('Anda tidak memiliki hak akses Pengajuan Akad.',403);
    }

    private function mutation()
    {
        if ($this->input->method()!=='post') show_error('Metode tidak diizinkan.',405);
        $token=$this->input->post('token');
        if (!is_string($token) || !hash_equals($this->session->userdata('pengajuan_akad_token'),$token)) show_error('Sesi formulir tidak valid. Muat ulang halaman.',403);
    }

    public function index()
    {
        $this->authorize('r');$projid=max(0,(int)$this->input->get('projid'));
        $this->load->view('list_pengajuan_akad',array('title'=>'Pengajuan Akad','rows'=>$this->mod_pengajuan_akad->listing($projid),'projects'=>$this->db->order_by('projid','ASC')->get('mproj')->result(),'projid'=>$projid));
    }

    private function respond($message,$status=200)
    {
        return $this->output->set_status_header($status)->set_content_type('application/json')->set_output(json_encode(array('message'=>$message)));
    }

    public function create() { $this->authorize('c');redirect('pengajuan_akad?form=add'); }
    public function edit($id=0) { $this->authorize('u');$row=$this->mod_pengajuan_akad->find($id);redirect('pengajuan_akad?edit='.$row->id); }

    public function save()
    {
        $id=(int)$this->input->post('id');$this->authorize($id ? 'u' : 'c');$this->mutation();
        $row=$id ? $this->mod_pengajuan_akad->find($id) : NULL;
        $this->form_validation->set_rules('projid','Nama Proyek','required|integer');
        $this->form_validation->set_rules('kode_akad','Kode Akad','trim|required|max_length[50]');
        $this->form_validation->set_rules('tanggal_akad','Tanggal Akad','required');
        $this->form_validation->set_rules('keterangan','Keterangan','trim|max_length[5000]');
        if (!$this->form_validation->run()) { $this->respond(trim(strip_tags(validation_errors())),422);return; }
        $projid=(int)$this->input->post('projid');
        if (!$this->db->get_where('mproj',array('projid'=>$projid))->row()) { $this->respond('Nama proyek tidak valid.',422);return; }
        $date=(string)$this->input->post('tanggal_akad');$parsed=DateTime::createFromFormat('!Y-m-d',$date);
        if (!$parsed || $parsed->format('Y-m-d')!==$date || $parsed->format('Y')<'1000') { $this->respond('Tanggal akad tidak valid.',422);return; }
        $code=trim((string)$this->input->post('kode_akad'));
        if ($this->db->where('kode_akad',$code)->where('id <>',$id)->count_all_results('tpengajuan_akad')) { $this->respond('Kode akad sudah digunakan.',409);return; }
        $data=array('projid'=>$projid,'kode_akad'=>$code,'tanggal_akad'=>$date,'keterangan'=>trim((string)$this->input->post('keterangan')),'updated_at'=>date('Y-m-d H:i:s'));
        if (!$id) { $data['created_at']=$data['updated_at'];$data['created_by']=$this->session->userdata('userid'); }
        $error=$this->mod_pengajuan_akad->save($id,$data);
        if ($error) { $this->respond($error,409);return; }
        $this->respond('Pengajuan akad berhasil disimpan.');
    }

    public function detail($id=0)
    {
        $this->authorize('r');$row=$this->mod_pengajuan_akad->find($id);
        $project=$row->projid ? $this->db->get_where('mproj',array('projid'=>$row->projid))->row() : NULL;
        $this->load->view('detail_pengajuan_akad',array('title'=>'Detail Pengajuan Akad','row'=>$row,'project'=>$project,'rows'=>$this->mod_pengajuan_akad->candidates($row->id)));
    }

    public function pencairan($id=0)
    {
        $this->authorize('r');$row=$this->mod_pengajuan_akad->find($id);
        $project=$row->projid ? $this->db->get_where('mproj',array('projid'=>$row->projid))->row() : NULL;
        $this->load->view('pencairan_pengajuan_akad',array('title'=>'Pencairan Pengajuan Akad','row'=>$row,'project'=>$project,
            'rows'=>$this->mod_pengajuan_akad->disbursements($row->id)));
    }

    public function biaya($id=0)
    {
        $this->authorize('r');$row=$this->mod_pengajuan_akad->find($id);
        $project=$row->projid ? $this->db->get_where('mproj',array('projid'=>$row->projid))->row() : NULL;
        $this->load->view('biaya_pengajuan_akad',array('title'=>'Biaya Akad','row'=>$row,'project'=>$project,'rows'=>$this->mod_pengajuan_akad->selected_participants($row->id),'cost_fields'=>$this->mod_pengajuan_akad->cost_fields()));
    }

    public function save_costs()
    {
        $this->authorize('u');$this->mutation();$id=(int)$this->input->post('id');$this->mod_pengajuan_akad->find($id);
        $finish=$this->input->post('finish');
        if (!in_array($finish,array(NULL,'0','1'),TRUE)) { $this->respond('Tindakan biaya akad tidak valid.',422);return; }
        $payload=$this->input->post('costs_json');$items=is_string($payload) ? json_decode($payload,TRUE) : NULL;
        if (!is_array($items)) { $this->respond('Data biaya tidak valid. Muat ulang halaman.',422);return; }
        $fields=$this->mod_pengajuan_akad->cost_fields();$rows=array();
        foreach ($items as $item) {
            if (!is_array($item) || !isset($item['projid'],$item['sprno']) || !is_int($item['projid']) || !is_int($item['sprno']) || $item['projid']<1 || $item['sprno']<1) { $this->respond('Data konsumen tidak valid.',422);return; }
            $key=$item['projid'].':'.$item['sprno'];
            if (isset($rows[$key])) { $this->respond('Konsumen tidak boleh muncul lebih dari sekali.',422);return; }
            $data=array('projid'=>$item['projid'],'sprno'=>$item['sprno']);
            foreach ($fields as $field=>$label) {
                if (in_array($field,array('pph','biaya_akad','total_pph'),TRUE)) continue;
                if (!array_key_exists($field,$item) || ($item[$field]!==NULL && !is_string($item[$field]))) { $this->respond($label.' tidak valid.',422);return; }
                $value=$item[$field]===NULL ? '' : trim($item[$field]);
                if ($value!=='' && !preg_match('/^\d{1,12}(?:\.\d{1,2})?$/D',$value)) { $this->respond($label.' harus berupa nominal positif atau nol, maksimal 12 digit dan 2 angka desimal.',422);return; }
                $data[$field]=$value==='' ? ($field==='biaya_akad' ? '0' : NULL) : $value;
            }
            foreach (array('bank_cabang'=>'Bank Cabang','nama_notaris'=>'Nama Notaris') as $field=>$label) {
                if (!isset($item[$field]) || !is_string($item[$field]) || mb_strlen(trim($item[$field]))>150) { $this->respond($label.' maksimal 150 karakter.',422);return; }
                $data[$field]=trim($item[$field]);
            }
            $rows[$key]=$data;
        }
        $error=$this->mod_pengajuan_akad->save_costs($id,array_values($rows),$finish==='1');
        if ($error) { $this->respond($error,409);return; }
        $message=$finish==='1' ? 'Biaya akad selesai. Status Closed dan seluruh isian dikunci.' : 'Rincian biaya akad berhasil disimpan.';
        $this->session->set_flashdata('ok',$message);$this->respond($message);
    }

    public function save_participants()
    {
        $this->authorize('u');$this->mutation();$id=(int)$this->input->post('id');$this->mod_pengajuan_akad->find($id);
        $finish=$this->input->post('finish');
        if (!in_array($finish,array(NULL,'0','1'),TRUE)) show_error('Tindakan peserta tidak valid.',422);
        $payload=$this->input->post('participants_json');$participants=is_string($payload) ? json_decode($payload,TRUE) : NULL;
        if (!is_array($participants)) show_error('Pilihan peserta tidak valid. Muat ulang halaman.',422);
        $data=array();
        foreach ($participants as $item) {
            if (!is_array($item) || !isset($item['projid'],$item['sprno'],$item['hadir'])
                || !is_int($item['projid']) || !is_int($item['sprno']) || $item['projid']<1 || $item['sprno']<1
                || !is_bool($item['hadir'])) show_error('Data peserta atau kehadiran tidak valid.',422);
            $key=$item['projid'].':'.$item['sprno'];
            if (isset($data[$key])) show_error('Peserta tidak boleh dipilih lebih dari sekali.',422);
            $data[$key]=array('projid'=>$item['projid'],'sprno'=>$item['sprno'],'hadir'=>$item['hadir'] ? 1 : 0);
        }
        $error=$this->mod_pengajuan_akad->save_participants($id,array_values($data),$finish==='1');
        $this->session->set_flashdata($error ? 'error' : 'ok',$error ?: ($finish==='1' ? 'Detail pengajuan akad selesai. Status Closed.' : 'Peserta dan kehadiran berhasil disimpan.'));
        if ($error) {
            redirect('pengajuan_akad/detail/'.$id);
            return;
        }
        if ($finish==='1') { redirect('pengajuan_akad/detail/'.$id);return; }
        $pengajuan=$this->mod_pengajuan_akad->find($id);
        redirect('pengajuan_akad'.($pengajuan->projid ? '?projid='.(int)$pengajuan->projid : ''));
    }

    public function save_attendance()
    {
        $this->authorize('u');$this->mutation();
        $id=$this->input->post('id');$projid=$this->input->post('projid');$sprno=$this->input->post('sprno');$hadir=$this->input->post('hadir');
        foreach (array($id,$projid,$sprno) as $value) if (!is_string($value) || !ctype_digit($value) || (int)$value<1) { $this->respond('Data peserta tidak valid.',422);return; }
        if (!in_array($hadir,array('0','1'),TRUE)) { $this->respond('Kehadiran tidak valid.',422);return; }
        $error=$this->mod_pengajuan_akad->save_attendance((int)$id,(int)$projid,(int)$sprno,(int)$hadir);
        $this->respond($error ?: 'Kehadiran tersimpan otomatis.',$error ? 409 : 200);
    }

    public function delete()
    {
        $this->authorize('d');$this->mutation();$row=$this->mod_pengajuan_akad->find($this->input->post('id'));
        $ok=$this->db->delete('tpengajuan_akad',array('id'=>$row->id));
        $this->session->set_flashdata($ok ? 'ok' : 'error',$ok ? 'Pengajuan akad berhasil dihapus.' : 'Pengajuan akad gagal dihapus.');redirect('pengajuan_akad');
    }
}
