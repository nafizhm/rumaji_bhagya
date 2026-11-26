<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends CI_Controller {

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
	 var $moid = 'PJ';
	 var $mid = 'M005';

	function __construct()
	{
		parent::__construct();
		$this->lib->check_login();
		$this->load->model('mod_bank', '', TRUE);
		$this->load->model('mod_proj', '', TRUE);
	}
	
	public function index()
	{
		$this->list_bankacc();
	}
	
	function _generate_cb_bank($bankid)
	{			$query_bank = $this->mod_bank->list_bank();
			$data_bank = $query_bank->result();

		
		$data['default']['cb_bank'] = $bankid;
		
			$data['bank']='';
			foreach($data_bank as $row_bank)
			{
			$data['bank'] .= $row_bank->bankid==$data['default']['cb_bank'] ? '<option value="'.$row_bank->bankid.'" selected="selected">': '<option value="'.$row_bank->bankid.'">';
			$data['bank'] .= $row_bank->nambank.'</option>';
			}	
		return $data['bank'];
	}
	
	function list_bankacc()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'R');
		
		$list_bankacc = $this->mod_bank->list_bankacc()->result();
		$data['list_bankacc'] = $list_bankacc;
		$data['title'] = 'List Bank Account';
		$this->load->view('list_bankacc',$data);
	}
	
	function input_bankacc()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'C');
		
		$data['form_action']= site_url('bank/do_input_bankacc');
		$data['title'] = 'Tambah Bank Account';
		$data['cb_proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		
		$data['readonly'] = "";	
		$data['disabled'] = "";
		$data['cb_bank'] = $this->_generate_cb_bank('');	
		$this->load->view('frm_bankacc',$data);
	}
	
	function do_input_bankacc()
	{
		$query = $this->db->query("SELECT * FROM mbankacc WHERE projid = '".$this->input->post('cb_proj')."'");
		if($query->num_rows() > 3)
		{
			$this->session->set_flashdata('error', 'Bank account maksimal 3');
			redirect('bank/input_bankacc');
		}
		else
		{
		  $bankacc = array( 
		  				'projid' => $this->input->post('cb_proj'),
						'bankid' => $this->input->post('cb_bank'),
						'kas' => $this->input->post('cb_kas'),
						'accno' => $this->input->post('txt_accno'),
						'namacc' => $this->input->post('txt_namacc'),
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d')
						);
			
			$this->mod_bank->input_bankacc($bankacc);
				
			$this->session->set_flashdata('ok', 'Tambah bank account berhasil');
			redirect('bank/input_bankacc');
		}
	}
	
	function edit_bankacc()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'R');
		
		$data['form_action']= site_url('bank/do_edit_bankacc');
		$data['title'] = 'List Bank Account > Ubah Bank Account';
		$sql_get_bankacc = $this->mod_bank->edit_bankacc($this->uri->segment(3),$this->uri->segment(4),$this->uri->segment(5));
		foreach( $sql_get_bankacc->result()  as $row);
		
		$data['cb_proj'] = $this->lib->generate_cb_proj($row->projid);
		$data['cb_bank'] = $this->_generate_cb_bank($row->bankid);	
		$data['readonly'] = 'readonly="readonly"';	
		$data['disabled'] = 'disabled="disabled"';	
		$data['default']['txt_accno'] = $row->accno;
		$data['default']['txt_namacc'] = $row->namacc;
		$data['default']['cb_kas'] = $row->kas;
		
		$this->load->view('frm_bankacc',$data);
	}
	
	function do_edit_bankacc()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'U');
		
		$bankacc = array( 
						'namacc' => $this->input->post('txt_namacc'),
						'kas' => $this->input->post('cb_kas'),
						'chby' => $this->session->userdata('userid'),
						'chdat' => date('Y-m-d')
						);
						
		$this->mod_bank->update_bankacc($this->input->post('cb_bank_hid'),$this->input->post('txt_accno'),$this->input->post('cb_proj_hid'),$bankacc);
				
		$this->session->set_flashdata('ok', 'Data bank account telah diubah');
		redirect('bank');
	}
	
	function delete_bankacc()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'D');
		
		$this->mod_bank->delete_bankacc($this->uri->segment(3),$this->uri->segment(4),$this->uri->segment(5));
		
		$this->session->set_flashdata('ok', 'Data bank account sudah di hapus');
		redirect('bank/list_bankacc/');
	}
	

}