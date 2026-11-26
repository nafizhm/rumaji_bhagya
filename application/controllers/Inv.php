<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inv extends CI_Controller {

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
		var $moid = 'IN';
		var $mid = 'R001';
	function __construct()
	{
		parent::__construct();
		$this->lib->check_login();
		$this->load->model('mod_inv', '', TRUE);
		$this->load->model('mod_proj', '', TRUE);
	}
	
	public function index()
	{
		$this->lap_per_prfktr();
	}
	
	function lap_per_prfktr()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'R');
		
		$data['form_action']= site_url('inv/do_lap_per_prfktr_disp');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['main_title'] = 'Laporan';
		$data['title'] = 'Laporan > Laporan Perkiraan Profit Kotor';
		
		$this->load->view('frm_pick_proj',$data);
	}
	
	function do_lap_per_prfktr_disp()
	{
		$projid = '';
		
		if($this->input->post('cb_proj') == '')
		{
			$projid = $this->uri->segment(3);
		}
		else
		{
			$projid = $this->input->post('cb_proj');
		}
		
		$data['projid'] = $projid;
		
		foreach($this->mod_proj->edit_proj($projid)->result() as $row_proj );
		$data['title'] = $row_proj->namproj;
		
		$this->load->view('lap_per_prfktr',$data);
	}

}