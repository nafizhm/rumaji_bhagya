<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Spk extends CI_Controller {

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
	 var $mid = 'T001';
	 var $mid2 = 'T003'; //Approval
	 
	 
	function __construct()
	{
		parent::__construct();
		$this->lib->check_login();
		$this->load->model('mod_spk', '', TRUE);
		$this->load->model('mod_dana', '', TRUE);
		$this->load->model('mod_proj', '', TRUE);
		$this->load->model('mod_ang', '', TRUE);

	}
	
	public function index()
	{
		$this->list_spk();
	}
	
	function print_spk()
	{
		$sql_print_spk = $this->mod_spk->edit_spk_app($this->uri->segment(4),$this->uri->segment(3));
		foreach( $sql_print_spk->result()  as $row );
		
		$data['txt_nodok'] = $this->lib->format_spkno($row->projid,$row->spkno);
		
		$data['txt_projid'] = $row->projid;
		$data['txt_spkno'] = $row->spkno;
		$data['txt_namproj'] = $row->namproj;
		$data['txt_nampa'] = $row->nampa;
		$data['txt_namby'] = $row->namby;
		$data['txt_tipe'] = $row->tipe;
		$data['txt_total'] = $this->lib->format_idr($row->total);
		$data['txt_ket'] = $row->ket;
		$data['txt_tgl'] = $this->lib->disp_date( $row->tgl );
		$data['txt_konid'] = $row->konid;
		
		$filename = $row->tipe.'_'.$row->projid.'_'.$row->spkno;
		$this->load->library('pdf');
		
		if($row->tipe == 'WO' || $row->tipe == 'PAI' || $row->tipe == 'PO')
		{
			$data['txt_wo_mandor'] = $this->lib->get_namkon($row->konid);
			$data['txt_wo_vol'] = number_format($row->wo_vol,2,",",".");
			$data['txt_wo_sat'] = $row->wo_sat;
			$data['txt_wo_spek'] = $row->wo_spek;
			$data['txt_wo_hrg'] = $this->lib->format_idr($row->wo_hrg);
			
			switch($row->tipe)
			{
				case 'WO':
				$this->pdf->load_view('print_wo',$data,$filename);
				break;
				case 'PO':
				$this->pdf->load_view('print_po',$data,$filename);
				break;
				case 'PAI':
				$this->pdf->load_view('print_pai',$data,$filename);
				break;
			}
		}
		elseif ($row->tipe == 'SPK')
		{
			$data['txt_spk_vendor'] = $this->lib->get_namkon($row->konid);
			$data['txt_spk_jp'] = $row->spk_jp;
			$data['txt_spk_isi'] = $row->spk_isi;
			$this->pdf->load_view('print_spk',$data,$filename);
		}
	
	}
	
	function do_app_spk()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid2,'U');
		
		$spk_app = array( 
						'status' => $this->input->post('cb_status'),
						'chby' => $this->session->userdata('userid'),
						'chdat' => date('Y-m-d')
					);
	
		$spklog = array( 
						'projid' => $this->input->post('txt_projid'),
						'spkno' => $this->input->post('txt_spkno'),
						'status' => $this->input->post('cb_status'),
						'ket' => $this->input->post('txt_ket'),
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d'),
						'crtim' => $this->lib->get_time()
					);
				
			$this->mod_spk->update_spk($this->input->post('txt_spkno'),$this->input->post('txt_projid'),$spk_app);
			$this->mod_spk->input_spklog($spklog);
			
			$this->_send_email_usr($this->input->post('txt_projid'),$this->input->post('txt_spkno'),$this->input->post('cb_status'),$this->session->userdata('roleid'),$this->input->post('txt_ket'));
	
	$txt_status = $this->input->post('cb_status');
	if($txt_status[0] == 'A')
	{
		$txt_app = 'approve';
	}
	else
	{
		$txt_app = 'reject';
	}
	$this->session->set_flashdata('ok', 'No dokumen '.$this->lib->format_spkno($this->input->post('txt_projid'),$this->input->post('txt_spkno')).' sudah di '.$txt_app);	
		
			redirect('spk/do_search_app/'.$this->input->post('txt_projid'));	
	}
	
	function edit_spk_app()
	{
		$data['form_action']= site_url('spk/do_app_spk');
		
		$data['url_back']= site_url('spk/do_search_app/'.$this->uri->segment(3));
		$sql_get_spk_app = $this->mod_spk->edit_spk_app($this->uri->segment(3),$this->uri->segment(4));
		foreach( $sql_get_spk_app->result()  as $row );
		
		$data['title'] = 'List Approval Pemakaian Anggaran > Dok. No : '.$this->lib->format_spkno($row->projid,$row->spkno);
		
		$data['txt_projid'] = $row->projid;
		$data['txt_spkno'] = $row->spkno;
		$data['txt_namproj'] = $row->namproj;
		$data['txt_nampa'] = $row->nampa;
		$data['txt_paid'] = $row->paid;
		$data['txt_namby'] = $row->namby;
		$data['txt_tipe'] = $row->tipe;
		$data['txt_total'] = $this->lib->format_idr($row->total);
		$data['txt_ket'] = $row->ket;
		$data['txt_tgl'] = $this->lib->disp_date( $row->tgl );
		$data['txt_konid'] = $row->konid;
		
		$data['txt_wo_mandor'] = $this->lib->get_namkon($row->konid);
		$data['txt_wo_vol'] = number_format($row->wo_vol,2,",",".");
		$data['txt_wo_sat'] = $row->wo_sat;
		$data['txt_wo_spek'] = $row->wo_spek;
		$data['txt_wo_hrg'] = $this->lib->format_idr($row->wo_hrg);
		
		$data['txt_spk_vendor'] = $this->lib->get_namkon($row->konid);
		$data['txt_spk_jp'] = $row->spk_jp;
		$data['txt_spk_isi'] = $row->spk_isi;
		
		
		$data['list_spklog'] = $this->mod_spk->list_spklog($this->uri->segment(3),$this->uri->segment(4))->result();
		
		$this->load->view('frm_spk_app',$data);
	}
	
	function do_search_app()
	{
		$data['projid'] = '';
		if($this->input->post('cb_proj') == '')
		{
			$data['projid'] = $this->uri->segment(3);
		}
		else
		{
			$data['projid'] = $this->input->post('cb_proj');
		}
		
		$list_spk_app =  $this->mod_spk->list_spk_app($data['projid'],$this->session->userdata('roleid'))->result();
		$data['list_spk_app'] = $list_spk_app;
		
		foreach($this->mod_proj->edit_proj($data['projid'])->result() as $row_proj );
		$data['title'] = 'Approval Pemakaian Anggaran Untuk Project '.$row_proj->namproj;
		$this->load->view('list_spk_app',$data);
	}
	
	function pick_proj2()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid2,'R');
		
		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action']= site_url('spk/do_search_app');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['main_title'] = 'List Approval Pemakaian Anggaran';
		$data['title'] = 'Pilih Project';
		$this->load->view('frm_pick_proj',$data);
	}
	
	
	function list_spk_app()
	{
		//$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'D');
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid2,'C');
		
		$this->pick_proj2();
	}
	
	function detail_spk()
	{
		$sql_get_spk = $this->mod_spk->edit_spk($this->input->post('txt_spkno'),$this->input->post('txt_projid'));
		foreach( $sql_get_spk->result()  as $row );
		
		$data['txt_total'] = $this->lib->format_idr($row->total);
		$data['txt_total_dana'] = $this->lib->format_idr($this->lib->get_tot_dana($row->projid,$row->spkno));
		$data['txt_total_sisa'] = $this->lib->format_idr($row->total - $this->lib->get_tot_dana($row->projid,$row->spkno) );
		$data['txt_tipe'] = $row->tipe;
		$data['txt_ket'] = $row->ket;
		$data['txt_spkno'] = $this->lib->format_spkno($row->projid,$row->spkno);
		
		$this->load->view('frm_spk_det',$data);
	}
	
	function input_spk()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'C');
		
		$data['form_action']= site_url('spk/do_input_spk');
		$data['url_back']= site_url('spk/list_spk');
		$data['title'] = 'Tambah Pemakaian Anggaran';
		
		$data['disabled'] = '';
		
		//$list_proj = $this->mod_proj->list_proj()->result();
		$data['cb_proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		
		//$list_pa = $this->mod_ang->list_mpa()->result();
		$data['cb_pa'] = $this->lib->generate_cb_pa($this->input->post('cb_pa'));
		$data['cb_by'] = '';
		
		$this->load->view('frm_spk',$data);
	}
	
	function do_input_spk()
	{
		$spkno = $this->lib->get_no($this->input->post('cb_proj'),'SPK');
		
		//Cek merupakan pos anggaran teknik?
		/*	
		$teknik = false;
		if($this->lib->cek_tipe_ang($this->input->post('cb_pa'),'T') == TRUE)
		{
			$stat = 'P02';
			$teknik = true;
		}
		else
		{
		*/
		
			$stat = 'P01';
		/*	
		}
		*/
		
		$spk = array( 
						'projid' => $this->input->post('cb_proj'),
						'paid' => $this->input->post('cb_pa'),
						'byid' => $this->input->post('cb_by'),
						'spkno' => $spkno,
						'thn' => date('Y'),
						'tipe' => $this->input->post('cb_tipe'),
						'konid' => $this->input->post('txt_konid'),
						'status' => $stat,
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d')
					);
		
		if($this->input->post('cb_tipe') == 'WO' || $this->input->post('cb_tipe') == 'PAI' || $this->input->post('cb_tipe') == 'PO')
		{
			$wo = array( 
						'total' => 	$this->lib->convert_idr_to_num($this->input->post('txt_wo_tot')),
						'ket' => $this->input->post('txt_wo_krj'),
						'tgl' => $this->lib->conv_date_to_int($this->input->post('txt_wo_tgl')),
						//'wo_mandor' => $this->input->post('txt_wo_mandor'),
						'wo_vol' => $this->lib->replace_commas_dot($this->input->post('txt_wo_vol')),
						'wo_sat' => $this->input->post('cb_wo_sat'),
						'wo_spek' => $this->input->post('txt_wo_spek'),
						'wo_hrg' => $this->lib->convert_idr_to_num($this->input->post('txt_wo_hrg'))
						);
						
			$insert_spk = array_merge($spk, $wo);
		}
		elseif($this->input->post('cb_tipe') == 'SPK')
		{
			
			$spkk = array( 
						'spk_jp' => $this->input->post('cb_spk_jp'),
						'spk_isi' => $this->input->post('txt_spk_isi'),
						'total' => 	$this->lib->convert_idr_to_num($this->input->post('txt_spk_tot')),
						'ket' => $this->input->post('txt_spk_ket'),
						'tgl' => $this->lib->conv_date_to_int($this->input->post('txt_spk_tgl'))
						);
						
			$insert_spk = array_merge($spk, $spkk);
			
		}
		
		
		$spklog = array( 
						'projid' => $this->input->post('cb_proj'),
						'spkno' => $spkno,
						'status' => $stat,
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d'),
						'crtim' => $this->lib->get_time()
					);
		
		$this->mod_spk->input_spk($insert_spk);
		$this->mod_spk->input_spklog($spklog);

		$this->lib->commit_no($this->input->post('cb_proj'),$spkno,'SPK');
	
	//Cek merupakan pos anggaran teknik?	
		/*if($teknik == TRUE)
		{
			$this->_send_email_app($this->input->post('cb_proj'),$spkno,'AUD');
			$this->_send_email_app($this->input->post('cb_proj'),$spkno,'KOM');
		}
		else
		{
		*/
		
			$this->_send_email_app($this->input->post('cb_proj'),$spkno,'DIR');
	   /*
		}
		*/
		$this->session->set_flashdata('ok', 'Tambah dokumen berhasil, no '.$this->lib->format_spkno($this->input->post('cb_proj'),$spkno));
		redirect('spk/input_spk');
	}

private function _send_email_usr($projid,$spkno,$status,$roleid,$txt_ket)
{
	$sql_get_spk_app = $this->mod_spk->edit_spk_app($projid,$spkno);
		foreach( $sql_get_spk_app->result()  as $row );
		
		$data['txt_spkno'] = $this->lib->format_spkno($projid,$spkno);
		$data['txt_namproj'] = $row->namproj;
		$data['txt_nampa'] = $row->nampa;
		$data['txt_namby'] = $row->namby;
		$data['txt_tipe'] = $row->tipe;
		$data['txt_total'] = $this->lib->format_idr($row->total);
		$data['txt_ket'] = $row->ket;

if($status[0] == 'A')
{
	$data['txt_status'] = 'Approve';
}
elseif($status[0] == 'R')
{
	$data['txt_status'] = 'Reject';
}
$data['txt_ket_reject'] = $txt_ket;

$query_app = $this->db->query("SELECT * FROM `muser` WHERE userid = '".$row->chby."'");
foreach( $query_app->result()  as $row_app)
$data['txt_app'] = $row_app->namusr;


$query = $this->db->query("SELECT * FROM `muser` WHERE userid = '".$row->crby."'");
foreach( $query->result()  as $row_user)
$data['txt_nama'] = $row_user->namusr;	

$this->lib->send_email($row_user->email, 'SPK No : '.$this->lib->format_spkno($projid,$spkno).' Telah Di '.$data['txt_status'], 'frm_email_spk_app_usr',$data);

}
	
	private function _send_email_app($projid,$spkno,$roleid)
{
	$sql_get_spk_app = $this->mod_spk->edit_spk_app($projid,$spkno);
		foreach( $sql_get_spk_app->result()  as $row );
		
		$data['txt_spkno'] = $this->lib->format_spkno($projid,$spkno);
		$data['txt_namproj'] = $row->namproj;
		$data['txt_nampa'] = $row->nampa;
		$data['txt_namby'] = $row->namby;
		$data['txt_tipe'] = $row->tipe;
		$data['txt_total'] = $this->lib->format_idr($row->total);
		$data['txt_ket'] = $row->ket;
		$data['txt_url'] = $this->config->item('base_url').'/spk/edit_spk_app/'.$projid.'/'.$spkno;

$query = $this->db->query("SELECT * FROM `muser` WHERE roleid = '".$roleid."' AND status='AKT' ");
	foreach( $query->result()  as $row_user)
	{		
$data['txt_nama'] = $row_user->namusr;
$this->lib->send_email($row_user->email, 'Approve '.$row->tipe.' No : '.$this->lib->format_spkno($projid,$spkno), 'frm_email_spk_app',$data);
	}
}

	function pick_proj()
	{
		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action']= site_url('spk/do_search_spk');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['main_title'] = 'List Pemakaian Anggaran';
		$data['title'] = 'Pilih Project';
		$this->load->view('frm_pick_proj',$data);
	}
	
	function list_spk()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'R');
		
		$this->pick_proj();
		
	}
	
	function do_search_spk()
	{
		$data['projid'] = '';
		if($this->input->post('cb_proj') == '')
		{
			$data['projid'] = $this->uri->segment(3);
		}
		else
		{
			$data['projid'] = $this->input->post('cb_proj');
		}
		
		$list_spk = $this->mod_spk->list_spk($data['projid'])->result();
		$data['list_spk'] = $list_spk;
		
		foreach($this->mod_proj->edit_proj($data['projid'])->result() as $row_proj );
		$data['title'] = 'Project '.$row_proj->namproj;
		$this->load->view('list_spk',$data);
	}
	
	function get_by()
	{
		echo $this->lib->generate_cb_by_edit($this->input->post('projid'),$this->input->post('paid'),'');
		/*$sql_get_by = $this->mod_ang->get_mby_projid($this->input->post('paid'),$this->input->post('projid'));
		echo "<option value=''>Silahkan pilih</option>";
		foreach( $sql_get_by->result()  as $row)
		{
			$tot_ang = $this->lib->get_tot_ang($this->input->post('projid'),$this->input->post('paid'),$row->byid);
			$sisa = $row->total - $tot_ang;
			echo "<option value='".$row->byid."'>".$row->byid.'-'.$row->namby.' ( Anggaran: '.$this->lib->format_idr($row->total).' Terpakai: '.$this->lib->format_idr($tot_ang).' Sisa : '.$this->lib->format_idr($sisa).' )'."</option>";
		}*/
	}

	
	function delete_spk()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'D');
		
		$this->mod_spk->delete_spk($this->uri->segment(3),$this->uri->segment(4),$this->uri->segment(5),$this->uri->segment(6));
		
		$this->session->set_flashdata('ok', 'Data SPK sudah di hapus');
		redirect('spk/do_search_spk/'.$this->uri->segment(4));
	}
	
	
	function edit_spk()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'R');
		
		$data['form_action']= site_url('Spk/do_edit_spk');
		
		$data['url_back']= site_url('spk/do_search_spk/'.$this->uri->segment(4));
		$sql_get_spk = $this->mod_spk->edit_spk($this->uri->segment(3),$this->uri->segment(4));
		foreach( $sql_get_spk->result()  as $row );
		
		$data['title'] = 'List Pemakaian Anggaran > Ubah SPK No : '.$this->lib->format_spkno($row->projid,$row->spkno);
		//$list_proj = $this->mod_proj->list_proj()->result();
		$data['cb_proj'] = $this->lib->generate_cb_proj($row->projid);
		$data['default']['txt_projid'] = $row->projid;
		
		
		//$list_pa = $this->mod_ang->list_mpa()->result();
		$data['cb_pa'] = $this->lib->generate_cb_pa($row->paid);
		$data['cb_by'] = $this->lib->generate_cb_by_edit($row->projid,$row->paid,$row->byid);
		
		
		$data['default']['txt_tipe'] = $row->tipe;
		
		$data['default']['txt_byid'] = $row->byid;
		$data['default']['txt_spkno'] = $row->spkno;
		$data['default']['txt_konid'] = $row->konid;
		
		$data['disabled'] = 'disabled="disabled"';
		
		if($row->tipe == 'WO' || $row->tipe == 'PAI' || $row->tipe == 'PO')
		{
			$data['default']['txt_wo_tgl'] = $this->lib->disp_date($row->tgl);
			$data['default']['txt_wo_krj'] = $row->ket;
			$data['default']['txt_wo_tot'] = $row->total;
			$data['default']['txt_wo_mandor'] = $this->lib->get_namkon($row->konid);
			$data['default']['txt_wo_vol'] = number_format($row->wo_vol,2,",",".");
			$data['default']['cb_wo_sat'] = $row->wo_sat;
			$data['default']['txt_wo_spek'] = $row->wo_spek;
			$data['default']['txt_wo_hrg'] = $row->wo_hrg;
		}
		elseif($row->tipe == 'SPK')
		{
			$data['default']['txt_spk_tgl'] = $this->lib->disp_date($row->tgl);
			$data['default']['txt_spk_ket'] = $row->ket;
			$data['default']['txt_spk_tot'] = $row->total;
			$data['default']['txt_spk_vendor'] = $this->lib->get_namkon($row->konid);
			$data['default']['cb_spk_jp'] = $row->spk_jp;
			$data['default']['txt_spk_isi'] = $row->spk_isi;
		}
				//if( $row->status=='A02' || $row->status=='A03')//-- 20/12/2022
		if( ($row->status=='A02' || $row->status=='A03') && $this->session->userdata('roleid') != 'DIR' ) //++ 20/12/2022
		{
			$this->session->set_flashdata('error', 'Tidak bisa mengubah dokumen karena status <> reject atau pending!');
			redirect('spk/do_search_spk/'.$row->projid);	
		}
	
		$this->load->view('frm_spk',$data);
		
	}
	
	function do_edit_spk()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'U');
		
		//Cek merupakan pos anggaran teknik?
		/*
		$teknik = false;	
		if($this->lib->cek_tipe_ang($this->input->post('cb_pa'),'T') == TRUE)
		{
			$status = 'P02';
			$teknik = true;	
		}
		else
		{
		*/						
		//++ 20/12/2022			
		if( $this->session->userdata('roleid') == 'DIR' )			
		{				
			$status = 'A02';			
		}			
		else			
		{		    
			//++ 20/12/2022				
			$status = 'P01';			
			//++ 20/12/2022			
		}			
		//++ 20/12/2022
			
		/*
		}
		*/
		
		$spk = array( 
						'projid' => $this->input->post('cb_proj'),
						'paid' => $this->input->post('cb_pa'),
						'byid' => $this->input->post('cb_by'),
						/*'tipe' => $this->input->post('cb_tipe'),
						'total' => 	$this->lib->convert_idr_to_num($this->input->post('txt_total')),
						'ket' => $this->input->post('txt_ket'),*/
						'konid' => $this->input->post('txt_konid'),
						'status' => $status,
						'chby' => $this->session->userdata('userid'),
						'chdat' => date('Y-m-d')
					);
			
			if($this->input->post('txt_tipe') == 'WO' || $this->input->post('txt_tipe') == 'PAI' || $this->input->post('txt_tipe') == 'PO')
			{
			$wo = array( 
						'total' => 	$this->lib->convert_idr_to_num($this->input->post('txt_wo_tot')),
						'ket' => $this->input->post('txt_wo_krj'),
						'tgl' => $this->lib->conv_date_to_int($this->input->post('txt_wo_tgl')),
						//'wo_mandor' => $this->input->post('txt_wo_mandor'),
						'wo_vol' => $this->lib->replace_commas_dot($this->input->post('txt_wo_vol')),
						'wo_sat' => $this->input->post('cb_wo_sat'),
						'wo_spek' => $this->input->post('txt_wo_spek'),
						'wo_hrg' => $this->lib->convert_idr_to_num($this->input->post('txt_wo_hrg'))
						);
						
			$update_spk = array_merge($spk, $wo);
			}
			elseif( $this->input->post('txt_tipe') == 'SPK' )
			{
				$spkk = array( 
						'spk_jp' => $this->input->post('cb_spk_jp'),
						'spk_isi' => $this->input->post('txt_spk_isi'),
						'total' => 	$this->lib->convert_idr_to_num($this->input->post('txt_spk_tot')),
						'ket' => $this->input->post('txt_spk_ket'),
						'tgl' => $this->lib->conv_date_to_int($this->input->post('txt_spk_tgl'))
						);
			$update_spk = array_merge($spk, $spkk);
			}
				
			$this->mod_spk->update_spk($this->input->post('txt_spkno'),$this->input->post('txt_projid'),$update_spk);
			
			//Check apakah sudah dibuat permintaan dana ?
			$query_dana = $this->db->query("SELECT * FROM `tdana` 
			WHERE projid = '".$this->input->post('cb_proj')."' AND spkno = '".$this->input->post('txt_spkno')."'");
			if($query_dana->num_rows() > 0)
			{
				foreach( $query_dana->result()  as $row);
				$dana = array( 
						'paid' => $this->input->post('cb_pa'),
						'byid' => $this->input->post('cb_by')
						);
				$this->mod_dana->update_dana_byspk($this->input->post('cb_proj'),$this->input->post('txt_spkno'),$dana);
			}
			
			/*
			if($teknik == true)
			{
				$this->_send_email_app($this->input->post('cb_proj'),$this->input->post('txt_spkno'),'AUD');
				$this->_send_email_app($this->input->post('cb_proj'),$this->input->post('txt_spkno'),'KOM');
			}
			else
			{
			*/	
				$this->_send_email_app($this->input->post('cb_proj'),$this->input->post('txt_spkno'),'DIR');	
			/*	
			}
			*/
			
			$this->session->set_flashdata('ok', 'Dokumen telah diubah');
			redirect('spk/do_search_spk/'.$this->input->post('txt_projid'));	
	}

function get_spk()
	{
		$array = array();
		$sql_get_spk = $this->mod_spk->get_spk_byid($this->input->post('projid'),$this->input->post('paid'),$this->input->post('byid'));
		
		foreach( $sql_get_spk->result()  as $row)
		{
			$tot_dana = $this->lib->get_tot_dana($row->projid,$row->spkno);
			$sisa = $row->total - $tot_dana;
			
			 $array[] = array (
            //'name' => $this->lib->format_spkno($row->projid,$row->spkno).'-'.$row->ket.'( Anggaran: '.$this->lib->format_idr($row->total).' Terpakai: '.$this->lib->format_idr($tot_dana).' Sisa : '.$this->lib->format_idr($sisa).' )',
			'name' => $this->lib->format_spkno($row->projid,$row->spkno).'-'.$row->ket.' ( '.$this->lib->format_idr($row->total).' )',
            'id' => $row->spkno,
        );
			//echo "<option value='".$row->spkno."'>".$this->lib->format_spkno($row->projid,$row->spkno).'-'.$row->ket."</option>";
		}
		
		echo json_encode ($array);
	}
	
}