<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends CI_Controller {

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
	function __construct()
	{
		parent::__construct();
		$this->lib->check_login();
		$this->load->model('mod_admin', '', TRUE);
	}
	
	public function index()
	{
		$this->list_role();
	}
	
	function edit_auth()
	{
		$data['form_action']= site_url('role/do_edit_auth/'.$this->uri->segment(3));
		$data['title'] = 'List Role > Ubah Otorisasi Role '.$this->uri->segment(3);
		
		$sql_get_auth = $this->mod_admin->list_mauth($this->uri->segment(3))->result();
		$data['list_auth'] = $sql_get_auth;
		$this->load->view('list_auth',$data);
	}
	
	function do_edit_auth()
	{
		$arrlength = count($this->input->post('chk_mid'));
		$i = 0;
		for($x = 0; $x < $arrlength; $x++) {
			//echo $_POST['chk_c'.$x];
			$i++;
			
			$roleid = $_POST['chk_roleid'][$x];
			
			$_POST['chk_c'.$i] = isset($_POST['chk_c'.$i]) ? $_POST['chk_c'.$i] : 0;
			$_POST['chk_r'.$i] = isset($_POST['chk_r'.$i]) ? $_POST['chk_r'.$i] : 0;
			$_POST['chk_u'.$i] = isset($_POST['chk_u'.$i]) ? $_POST['chk_u'.$i] : 0;
			$_POST['chk_d'.$i] = isset($_POST['chk_d'.$i]) ? $_POST['chk_d'.$i] : 0;
			
			$auth = array( 
						'c' =>  $_POST['chk_c'.$i],
						'r' =>  $_POST['chk_r'.$i],
						'u' =>  $_POST['chk_u'.$i],
						'd' =>  $_POST['chk_d'.$i],
						'chby' => $this->session->userdata('userid'),
						'chdat' => date('Y-m-d')
						);
						
			$this->mod_admin->update_mauth($_POST['chk_roleid'][$x],$_POST['chk_moid'][$x],$_POST['chk_mid'][$x],$auth);
			
		}
			$this->session->set_flashdata('ok', 'Data otorisasi sudah di update');
			redirect('role/edit_auth/'.$roleid);
	}
	
	function input_role()
	{
		$data['form_action']= site_url('role/do_input_role');
		$data['title'] = 'Tambah Role';
		
		$data['readonly'] = "";	
		$this->load->view('frm_role',$data);
	}
	
	function do_input_role()
	{
		 $role = array( 
		  				'roleid' => $this->input->post('txt_roleid'),
						'namrol' => $this->input->post('txt_namrol'),
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d')
						);
			
		$sql_get_menu = $this->mod_admin->get_menu();
		foreach( $sql_get_menu->result()  as $row)
		{
			 $auth[] = array( 
		  				'roleid' => $this->input->post('txt_roleid'),
						'moid' => $row->moid,
						'mid' => $row->mid,
						'c' => 0,
						'r' => 0,
						'u' => 0,
						'd' => 0,
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d')
						);
		}
		
			$this->db->trans_start();	
			$this->mod_admin->input_role($role);
			$this->mod_admin->input_mauth($auth);
			$this->db->trans_complete();
	
			if ($this->db->trans_status() === FALSE)
			{
				$this->session->set_flashdata('error', 'Buat role gagal!');
				redirect('role/input_role');
			}
			else
			{
				$this->session->set_flashdata('ok', 'Tambah role berhasil');
				redirect('role/input_role');
			}
				
			
	}
	
	function edit_role()
	{
		$data['form_action']= site_url('role/do_edit_role');
		$data['title'] = 'List Role > Ubah Role '.$this->uri->segment(3);
		$sql_get_role = $this->mod_admin->edit_role($this->uri->segment(3));
		foreach( $sql_get_role->result()  as $row);
		
		$data['readonly'] = 'readonly="readonly"';	

		$data['default']['txt_roleid'] = $row->roleid;
		$data['default']['txt_namrol'] = $row->namrol;
		
		$this->load->view('frm_role',$data);
	}
	
	function do_edit_role()
	{
		$role = array( 
						'namrol' => $this->input->post('txt_namrol'),
						'chby' => $this->session->userdata('userid'),
						'chdat' => date('Y-m-d')
						);
						
		$this->mod_admin->update_role($this->input->post('txt_roleid'),$role);
				
		$this->session->set_flashdata('ok', 'Data role telah diubah');
		redirect('role');
	}
	
	function list_role()
	{
		$list_role = $this->mod_admin->get_role()->result();
		$data['list_role'] = $list_role;
		$data['title'] = 'List Role';
		$this->load->view('list_role',$data);
	}
	
	

}