<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Saldo extends CI_Controller {

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
	 var $moid = 'FI';
	  var $mid = 'R001'; //Laporan saldo bank
	 
	function __construct()
	{
		parent::__construct();
		$this->load->model('mod_bank', '', TRUE);
		$this->lib->check_login();
	}
	
	public function index()
	{
		$this->lap_saldo_bank();
	}
	
	function lap_trans_bank()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'R');
		
		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action']= site_url('saldo/do_trans_bank_disp');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		
		$data['main_title'] = 'Laporan Transaksi Bank';
		$data['title'] = 'Pilih Project';
		$this->load->view('frm_lap_trans_bank',$data);
	}
	
	function do_trans_bank_disp()
	{
		foreach($this->mod_proj->edit_proj($this->input->post('cb_proj'))->result() as $row_proj );
		$data['title'] = 'Project '.$row_proj->namproj;
    	
		$bank = explode(";", $this->input->post('cb_bank'));
		$data['bank_nama'] = $this->lib->get_nambank($bank[0]);
		$data['bank_accno'] = $bank[1];
		$data['bank_kas'] = $bank[2];
		
		$data['subtitle'] = $this->lib->get_nambank($bank[0]).' Tanggal '.$this->input->post('txt_tgl_from').' Sampai Dengan '.$this->input->post('txt_tgl_to');
		
		/*
		if($bank[2]=='KB')
		{
			$in = "'KB','KP','CO'";
		}
		elseif($bank[2]=='KK')
		{
			$in = "'KK'";
		}
		elseif($bank[2]=='KP')
		{
			$in = "'KP'";
		}
		*/
		
		$sql_trans = $this->db->query("SELECT * FROM vmutasi WHERE tgl BETWEEN '".$this->lib->conv_date_to_int($this->input->post('txt_tgl_from'))."' AND '".$this->lib->conv_date_to_int($this->input->post('txt_tgl_to'))."' AND projid = '".$this->input->post('cb_proj')."' AND kas ='".$bank[2]."'")->result();
		
		$data['trans'] = $sql_trans;
		$this->load->view('lap_trans_bank',$data);
	}
	
	function get_bank()
	{
		echo $this->lib->generate_cb_bank_acc($this->input->post('projid'));
	}
	
	function lap_saldo_bank()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'R');
		
		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action']= site_url('saldo/do_saldo_bank_disp');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		
		$data['main_title'] = 'Laporan Saldo Bank';
		$data['title'] = 'Pilih Project';
		$this->load->view('frm_lap_saldo_bank',$data);
	}
	
	function do_saldo_bank_disp()
	{
		$mg = explode("-", $this->input->post('txt_mg'));
		$week = $mg[0].$mg[1];
		
		foreach($this->mod_proj->edit_proj($this->input->post('cb_proj'))->result() as $row_proj );
		$data['title'] = 'Project '.$row_proj->namproj;
    	$data['subtitle'] = 'Minggu Ke '.$mg[1].' Tahun '.$mg[0];
		
		$bank = explode(";", $this->input->post('cb_bank'));
		$data['bank_nama'] = $this->lib->get_nambank($bank[0]);
		$data['bank_accno'] = $bank[1];
		$data['bank_kas'] = $bank[2];
		
		
		if($bank[2]=='KB')
		{
			$in = "'KB','KP'";
		}
		elseif($bank[2]=='KK')
		{
			$in = "'KK'";
		}
		elseif($bank[2]=='KP')
		{
			$in = "'KP'";
		}
		
		
		//Saldo Awal Bank Masuk	
		
		$sql_sld_awl_bm = $this->db->query("SELECT SUM(total) as total FROM vmutasi WHERE YEARWEEK(tgl, 1) < '".$week."' AND projid = '".$this->input->post('cb_proj')."' AND kas = '".$bank[2]."' AND total > 0");
		if($sql_sld_awl_bm->num_rows() > 0)
		{
foreach($sql_sld_awl_bm->result() as $row_sld_awl_bm);		
		$data['sld_awl_bm'] = $row_sld_awl_bm->total;
		}
		else
		{
			$data['sld_awl_bm'] = 0;
			
		}
		
		//Saldo Awal Bank Keluar		
		$sql_sld_awl_bk = $this->db->query("SELECT SUM(total) as total FROM vmutasi WHERE YEARWEEK(tgl, 1) < '".$week."' AND projid = '".$this->input->post('cb_proj')."' AND kas IN ($in) AND total < 0");
		
		if($sql_sld_awl_bk->num_rows() > 0)
		{
		foreach($sql_sld_awl_bk->result() as $row_sld_awl_bk);
		$data['sld_awl_bk'] = $row_sld_awl_bk->total * -1;
		}
		else
		{
			$data['sld_awl_bk'] = 0;
		}
		
		//Saldo Akhir Bank Masuk			
		$sql_sld_akh_bm = $this->db->query("SELECT SUM(total) as total FROM vmutasi WHERE YEARWEEK(tgl, 1) = '".$week."' AND projid = '".$this->input->post('cb_proj')."' AND kas = '".$bank[2]."'  AND total > 0");
		if($sql_sld_akh_bm->num_rows() > 0)
		{
			foreach($sql_sld_akh_bm->result() as $row_sld_akh_bm);
			$data['sld_akh_bm'] = $row_sld_akh_bm->total;
		}
		else
		{
			$data['sld_akh_bm'] = 0;
		}
		
		//Saldo Akhir Bank Keluar			
		$sql_sld_akh_bk = $this->db->query("SELECT SUM(total) as total FROM vmutasi WHERE YEARWEEK(tgl, 1) = '".$week."' AND projid = '".$this->input->post('cb_proj')."' AND kas IN ($in) AND total < 0");
		if($sql_sld_akh_bk->num_rows() > 0)
		{
			foreach($sql_sld_akh_bk->result() as $row_sld_akh_bk);
			$data['sld_akh_bk'] = $row_sld_akh_bk->total  * -1;
		}
		else
		{
			$data['sld_akh_bk'] = 0;
		}
		
/*
//Saldo Awal Bank Masuk	
		foreach($sql_sld_awl_bm as $row_sld_awl_bm);		$sql_sld_awl_bm = $this->db->query("SELECT SUM(total) as total FROM tkwt WHERE YEARWEEK(tgl, 1) < '".$week."' AND projid = '".$this->input->post('cb_proj')."'")->result();

		$data['sld_awl_bm'] = $row_sld_awl_bm->total;

//Saldo Awal Bank Keluar		
		$sql_sld_awl_bk = $this->db->query("SELECT SUM(total) as total FROM tdana WHERE YEARWEEK(crdat, 1) < '".$week."' AND projid = '".$this->input->post('cb_proj')."' AND status = 'A02'")->result();
		foreach($sql_sld_awl_bk as $row_sld_awl_bk);
		$data['sld_awl_bk'] = $row_sld_awl_bk->total;

//Saldo Akhir Bank Masuk			
		$sql_sld_akh_bm = $this->db->query("SELECT SUM(total) as total FROM tkwt WHERE YEARWEEK(tgl, 1) = '".$week."' AND projid = '".$this->input->post('cb_proj')."'")->result();
		foreach($sql_sld_akh_bm as $row_sld_akh_bm);
		$data['sld_akh_bm'] = $row_sld_akh_bm->total;

//Saldo Akhir Bank Keluar			
		$sql_sld_akh_bk = $this->db->query("SELECT SUM(total) as total FROM tdana WHERE YEARWEEK(crdat, 1) = '".$week."' AND projid = '".$this->input->post('cb_proj')."' AND status = 'A02'")->result();
		foreach($sql_sld_akh_bk as $row_sld_akh_bk);
		$data['sld_akh_bk'] = $row_sld_akh_bk->total;

*/		
		$this->load->view('lap_saldo_bank',$data);
		
	}
	
	

}