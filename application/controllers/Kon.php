<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kon extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	
	 /* Insialiasi otorisasi */
	 var $moid = 'CO';
	 var $mid = 'M003';
	
	function __construct()
	{
		parent::__construct();
		$this->lib->check_login();
		$this->load->model('mod_kon', '', TRUE);

	}
	
	public function index()
	{
		$this->list_kon();
	}
	
	
	function input_kon()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'C');
		
		$data['form_action']= site_url('kon/do_input_kon');
		$data['url_back']= site_url('kon/list_kon');
		$data['title'] = 'Tambah Vendor';
		
		$data['cb_bank'] = $this->lib->generate_cb_bank('');	

		$this->load->view('frm_kon',$data);
	}
	
	function do_input_kon()
	{
		
		$kon = array( 
						'namkon' => $this->input->post('txt_namkon'),
						'alm' => $this->input->post('txt_alm'),
						'telp' => $this->input->post('txt_telp'),
						'bankid' => $this->input->post('cb_bank'),
						'accno' => $this->input->post('txt_accno'),
						'namacc' => $this->input->post('txt_namacc'),
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d')
						);
			$this->mod_kon->input_mkon($kon);

			$this->session->set_flashdata('ok', 'Tambah vendor berhasil');
			redirect('kon/input_kon');
		
	}
	
	function edit_kon()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'U');
		
		$data['form_action']= site_url('kon/do_edit_kon');
		
		$data['title'] = 'List Vendor > Ubah Vendor';
		$data['url_back']= site_url('kon/list_kon');
		$sql_get_kon = $this->mod_kon->edit_mkon($this->uri->segment(3));
		foreach( $sql_get_kon->result()  as $row);
		
		$data['default']['txt_konid'] = $row->konid;
		$data['default']['txt_namkon'] = $row->namkon;
		$data['default']['txt_alm'] = $row->alm;
		$data['default']['txt_telp'] = $row->telp;

		$data['cb_bank'] = $this->lib->generate_cb_bank($row->bankid);	

		$data['default']['txt_accno'] = $row->accno;
		$data['default']['txt_namacc'] = $row->namacc;
		
		$this->load->view('frm_kon',$data);
	}
	
	function do_edit_kon()
	{
		$kon = array( 
						'namkon' => $this->input->post('txt_namkon'),
						'alm' => $this->input->post('txt_alm'),
						'telp' => $this->input->post('txt_telp'),
						'bankid' => $this->input->post('cb_bank'),
						'accno' => $this->input->post('txt_accno'),
						'namacc' => $this->input->post('txt_namacc'),
						'chby' => $this->session->userdata('userid'),
						'chdat' => date('Y-m-d')
						);
			$this->mod_kon->update_mkon($this->input->post('txt_konid'),$kon);
				
			$this->session->set_flashdata('ok', 'Data vendor telah diubah');
			redirect('kon/edit_kon/'.$this->input->post('txt_konid'));	
		
	}
	
	function list_kon()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'R');
		
		$list_kon = $this->mod_kon->list_mkon()->result();
		$data['list_kon'] = $list_kon;
		$data['title'] = 'List Vendor';
		$this->load->view('list_kon',$data);
	}
	
	function delete_kon()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'D');
		
		$this->mod_kon->delete_mkon($this->uri->segment(3));
		
		$this->session->set_flashdata('ok', 'Data vendor sudah di hapus');
		redirect('kon/list_kon/');
	}
	
	function get_kon()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'C');
		
		$array = array();
		$query = $this->db->query('SELECT * FROM mkon WHERE namkon LIKE "%'.$this->input->post('query').'%"');
		foreach( $query->result()  as $row)
		{
			 $array[] = array (
            'name' => $row->namkon,
            'id' => $row->konid,
        );
		
		
		}
		
		echo json_encode ($array);
	}
	
	

}