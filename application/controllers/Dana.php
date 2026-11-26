<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dana extends CI_Controller
{

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
	var $mid = 'T002';
	var $mid2 = 'T003'; //Approval


	function __construct()
	{
		parent::__construct();
		$this->lib->check_login();
		$this->load->model('mod_dana', '', TRUE);
		$this->load->model('mod_ang', '', TRUE);
		$this->load->model('mod_spk', '', TRUE);
	}

	public function index()
	{
		$this->list_dana();
	}


	function do_app_multi()
	{
		$projid = '';
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid2, 'U');

		$arrlength = count($this->input->post('chk'));
		if ($arrlength > 0) {

			for ($x = 0; $x < $arrlength; $x++) {
				$data = explode(";", $_POST['chk'][$x]);

				if ($projid == '') {
					$projid = $data[0];
				}

				if ($this->session->userdata('roleid') == 'FIN') {
					$status = 'A01';
				} elseif ($this->session->userdata('roleid') == 'AUD') {
					$status = 'A03';
				} else {
					$status = 'A02';
				}


				$dana_app = array(
					'status' => $status,
					'chby' => $this->session->userdata('userid'),
					'chdat' => date('Y-m-d')
				);

				$dnlog = array(
					'projid' => $data[0],
					'reqno' => $data[1],
					'status' => $status,
					'ket' => '',
					'crby' => $this->session->userdata('userid'),
					'crdat' => date('Y-m-d'),
					'crtim' => $this->lib->get_time()
				);

				$this->mod_dana->update_dana($data[0], $data[1], $dana_app);
				$this->mod_dana->input_dnlog($dnlog);
			}

			$this->session->set_flashdata('ok', 'Data permintaan dana telah di approve');
		} else {
			$this->session->set_flashdata('error', 'Tidak ada data untuk di approve');
		}
		redirect('dana/do_search_app/' . $projid);
	}

	function do_print_dana()
	{
		$reqno = explode(",", $this->input->post('txt_reqno'));
		$projid = $this->input->post('txt_projid');

		foreach ($this->mod_proj->edit_proj($projid)->result() as $row_proj);
		$data['nama_project'] = 'Project ' . $row_proj->namproj;

		$data['query'] = $this->db->query("SELECT * FROM `tdana` WHERE projid = '" . $projid . "' AND reqno IN (" . implode(',', $reqno) . ") ");

		$this->load->library('pdf');
		$this->pdf->load_view('print_dana', $data, 'print_dana');
	}

	function print_dana()
	{
		foreach ($this->mod_proj->edit_proj($this->uri->segment(3))->result() as $row_proj);
		$data['form_action'] = site_url('dana/do_print_dana');
		$data['main_title'] = 'Cetak Bukti Bank Keluar';
		$data['title'] = 'Project ' . $row_proj->namproj;
		$data['projid'] = $row_proj->projid;
		$data['url_back'] = site_url('dana/do_search_dana/' . $row_proj->projid);
		$this->load->view('frm_print_dana', $data);
	}

	function delete_dana()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid2, 'D');
		$this->mod_dana->delete_dana($this->uri->segment(3), $this->uri->segment(4));
		$this->session->set_flashdata('ok', 'Data permintaan dana telah di hapus');
		redirect('dana/do_search_dana/' . $this->uri->segment(3));
	}

	function pick_proj2()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid2, 'R');

		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action'] = site_url('dana/do_search_app');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['main_title'] = 'List Approval Permintaan Dana';
		$data['title'] = 'Pilih Project';
		$this->load->view('frm_pick_proj', $data);
	}

	function list_dana_app()
	{
		$this->pick_proj2();
	}

	function do_search_app()
	{
		$data['projid'] = '';
		if ($this->input->post('cb_proj') == '') {
			$data['projid'] = $this->uri->segment(3);
		} else {
			$data['projid'] = $this->input->post('cb_proj');
		}


		//$list_dana_app = $this->mod_dana->list_dana_app($data['projid'],$this->session->userdata('roleid'))->result();
		$list_dana_app =  $this->mod_dana->list_dana_app2($data['projid'], $this->session->userdata('roleid'))->result();
		$data['list_dana_app'] = $list_dana_app;

		foreach ($this->mod_proj->edit_proj($data['projid'])->result() as $row_proj);
		$data['title'] = 'Approval Permintaan Dana Untuk Project ' . $row_proj->namproj;
		$this->load->view('list_dana_app', $data);
	}

	function edit_dana_app()
	{
		$data['form_action'] = site_url('Dana/do_app_dana');

		$data['url_back'] = site_url('dana/do_search_app/' . $this->uri->segment(3));
		$sql_get_dana_app = $this->mod_dana->edit_dana_app($this->uri->segment(3), $this->uri->segment(4));
		foreach ($sql_get_dana_app->result()  as $row);

		$data['title'] = 'List Approval Permintaan Dana > Req. No : ' . $this->lib->no_req($row->reqno);

		$data['txt_projid'] = $row->projid;
		$data['txt_namproj'] = $row->namproj;
		$data['txt_nampa'] = $row->nampa;
		$data['txt_namby'] = $row->namby;
		$data['txt_spkno'] = $row->spkno;
		$data['txt_reqno'] = $row->reqno;
		$data['txt_total'] = $this->lib->format_idr($row->total);
		$data['txt_ket'] = $row->ket;
		//$data['txt_namkon'] = $this->lib->get_namkon($row->konid);

		$data['list_dnlog'] = $this->mod_dana->list_dnlog($this->uri->segment(3), $this->uri->segment(4))->result();

		$this->load->view('frm_dana_app', $data);
	}

	function do_app_dana()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid2, 'U');

		$dana_app = array(
			'status' => $this->input->post('cb_status'),
			'chby' => $this->session->userdata('userid'),
			'chdat' => date('Y-m-d')
		);

		$dnlog = array(
			'projid' => $this->input->post('txt_projid'),
			'reqno' => $this->input->post('txt_reqno'),
			'status' => $this->input->post('cb_status'),
			'ket' => $this->input->post('txt_ket'),
			'crby' => $this->session->userdata('userid'),
			'crdat' => date('Y-m-d'),
			'crtim' => $this->lib->get_time()
		);

		$this->mod_dana->update_dana($this->input->post('txt_projid'), $this->input->post('txt_reqno'), $dana_app);
		$this->mod_dana->input_dnlog($dnlog);

		if ($this->input->post('cb_status') == 'A01' || $this->input->post('cb_status') == 'A02') {
			//Send email
			if ($this->input->post('cb_status') == 'A01') {
				$this->_send_email_app($this->input->post('txt_projid'), $this->input->post('txt_reqno'), 'DIR');
			}
			/*
				elseif($this->input->post('cb_status') == 'A03')
				{
					$this->_send_email_app($this->input->post('txt_projid'),$this->input->post('txt_reqno'),'FIN');
				}
				*/

			$this->session->set_flashdata('ok', 'Data permintaan dana telah di approve');
		} else {
			$this->session->set_flashdata('error', 'Data permintaan dana telah di reject');
		}

		//Send email
		$this->_send_email_usr($this->input->post('txt_projid'), $this->input->post('txt_reqno'), $this->input->post('cb_status'), $this->session->userdata('roleid'), $this->input->post('txt_ket'));
		redirect('dana/do_search_app/' . $this->input->post('txt_projid'));
	}

	function get_by()
	{
		$sql_get_by = $this->mod_ang->get_mby_projid($this->input->post('paid'), $this->input->post('projid'));
		echo "<option value=''>Silahkan pilih</option>";
		foreach ($sql_get_by->result()  as $row) {
			$tot_ang = $this->lib->get_tot_ang($this->input->post('projid'), $this->input->post('paid'), $row->byid);
			$sisa = $row->total - $tot_ang;
			echo "<option value='" . $row->byid . "'>" . $row->byid . '-' . $row->namby . "</option>";
			//echo "<option value='".$row->byid."'>".$row->byid.'-'.$row->namby.' ( Anggaran: '.$this->lib->format_idr($row->total).' Terpakai: '.$this->lib->format_idr($tot_ang).' Sisa : '.$this->lib->format_idr($sisa).' )'."</option>";
		}
	}


	function input_dana()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'C');

		$data['form_action'] = site_url('dana/do_input_dana');
		$data['url_back'] = site_url('dana/list_dana');
		$data['title'] = 'Tambah Permintaan Dana';

		$data['cb_proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['cb_pa'] = $this->lib->generate_cb_pa($this->input->post('cb_pa'));
		$data['cb_by'] = '';
		$data['cb_spk'] = '';

		$this->load->view('frm_dana', $data);
	}

	function do_input_dana()
	{
		$query_ang = $this->db->query("SELECT mang.total FROM mang 
		INNER JOIN mby ON mby.byid = mang.byid AND mby.paid = mang.paid	
		WHERE mang.projid = '".$this->input->post('cb_proj')."' AND mang.paid = '".$this->input->post('cb_pa')."' 
		AND mang.byid = '".$this->input->post('cb_by')."'");		
		
		foreach ($query_ang->result()  as $row_ang);
		
		$klr_ang = $this->lib->get_tot_ang($this->input->post('cb_proj'), $this->input->post('cb_pa'), $this->input->post('cb_by'));		
		$sisa_ang = $row_ang->total - $klr_ang;		$total =  $this->lib->convert_idr_to_num($this->input->post('txt_total'));		
		if ( $sisa_ang < $total && $this->session->userdata('roleid') != 'DIR' )		
		{
			$this->session->set_flashdata('error', 'Permintaan dana gagal karena melebihi sisa anggaran ( Anggaran dikurang permintaan dana yang sudah diapprove )');			
			redirect('dana/input_dana');
		}		
		else{			
		$reqno = $this->lib->get_no($this->input->post('cb_proj'), 'REQ');					
		/* Cek merupakan pos anggaran teknik?		
		$teknik = false;		
		if($this->lib->cek_tipe_ang($this->input->post('cb_pa'),'T') == TRUE)		
		{			
			$stat = 'P02';			
			$teknik = true;		
		}		
		else		
		{ */		
		$stat = 'P01';		
		/* } */					
		$dana = array(			
		'projid' => $this->input->post('cb_proj'),			
		'paid' => $this->input->post('cb_pa'),			
		'byid' => $this->input->post('cb_by'),			
		'reqno' => $reqno,			
		/* 'konid' => $this->input->post('txt_konid'), */			
		'spkno' => $this->input->post('cb_spk'),			
		'status' => $stat,			
		'week' => $this->lib->weekOfMonth(strtotime($this->lib->conv_date_to_int($this->input->post('txt_tgl_trans')))),			
		'mon' => date('m', strtotime($this->lib->conv_date_to_int($this->input->post('txt_tgl_trans')))),			
		'year' => date('Y', strtotime($this->lib->conv_date_to_int($this->input->post('txt_tgl_trans')))),			
		'total' => 	$this->lib->convert_idr_to_num($this->input->post('txt_total')),			
		'totalbyr' => 	$this->lib->convert_idr_to_num($this->input->post('txt_total')),			
		'ket' => $this->input->post('txt_ket'),			
		'crby' => $this->session->userdata('userid'),			
		'crdat' => $this->lib->conv_date_to_int($this->input->post('txt_tgl_trans')));		

		$dnlog = array(			
		'projid' => $this->input->post('cb_proj'),			
		'reqno' => $reqno,			
		'status' => $stat,			
		'crby' => $this->session->userdata('userid'),			
		'crdat' => date('Y-m-d'),			
		'crtim' => $this->lib->get_time());				

		$this->mod_dana->input_dana($dana);		
		$this->mod_dana->input_dnlog($dnlog);		
		$this->lib->commit_no($this->input->post('cb_proj'), $reqno, 'REQ');				

		/* Send email
		if($teknik == true)
		{

		$this->_send_email_app($this->input->post('cb_proj'),$reqno,'AUD');
		$this->_send_email_app($this->input->post('cb_proj'),$reqno,'KOM');

		}

		else
		{ */

		$this->_send_email_app($this->input->post('cb_proj'), $reqno, 'FIN');
		/* } */		
		
		$this->session->set_flashdata('ok', 'Tambah permintaan dana berhasil');		
		redirect('dana/input_dana');				
}					
	}

	private function _send_email_usr($projid, $reqno, $status, $roleid, $txt_ket)
	{
		$sql_get_dana_app = $this->mod_dana->edit_dana_app($projid, $reqno);
		foreach ($sql_get_dana_app->result()  as $row);


		$data['txt_namproj'] = $row->namproj;
		$data['txt_nampa'] = $row->nampa;
		$data['txt_namby'] = $row->namby;
		$data['txt_spkno'] = $this->lib->format_spkno($projid, $row->spkno);
		$data['txt_reqno'] = $row->reqno;
		$data['txt_total'] = $this->lib->format_idr($row->total);
		$data['txt_ket'] = $row->ket;
		//$data['txt_namkon'] = $this->lib->get_namkon($row->konid);

		if ($status[0] == 'A') {
			$data['txt_status'] = 'Approve';
		} elseif ($status[0] == 'R') {
			$data['txt_status'] = 'Reject';
		}
		$data['txt_ket_reject'] = $txt_ket;

		$query_app = $this->db->query("SELECT * FROM `muser` WHERE userid = '" . $row->chby . "'");
		foreach ($query_app->result()  as $row_app)
			$data['txt_app'] = $row_app->namusr;


		$query = $this->db->query("SELECT * FROM `muser` WHERE userid = '" . $row->crby . "'");
		foreach ($query->result()  as $row_user)
			$data['txt_nama'] = $row_user->namusr;

		$this->lib->send_email($row_user->email, 'Permintaan Dana No : ' . $this->lib->no_req($reqno) . ' Telah Di ' . $data['txt_status'], 'frm_email_app_usr', $data);
	}

	private function _send_email_app($projid, $reqno, $roleid)
	{
		$sql_get_dana_app = $this->mod_dana->edit_dana_app($projid, $reqno);
		foreach ($sql_get_dana_app->result()  as $row);


		$data['txt_namproj'] = $row->namproj;
		$data['txt_nampa'] = $row->nampa;
		$data['txt_namby'] = $row->namby;
		$data['txt_spkno'] = $this->lib->format_spkno($projid, $row->spkno);
		$data['txt_reqno'] = $row->reqno;
		$data['txt_total'] = $this->lib->format_idr($row->total);
		$data['txt_ket'] = $row->ket;
		//$data['txt_namkon'] = $this->lib->get_namkon($row->konid);
		$data['txt_url'] = $this->config->item('base_url') . '/dana/edit_dana_app/' . $projid . '/' . $reqno;

		$query = $this->db->query("SELECT * FROM `muser` WHERE roleid = '" . $roleid . "' AND status='AKT' ");
		foreach ($query->result()  as $row_user) {
			$data['txt_nama'] = $row_user->namusr;
			$this->lib->send_email($row_user->email, 'Approve Permintaan Dana No : ' . $this->lib->no_req($reqno), 'frm_email_app', $data);
		}
	}
	function pick_proj()
	{
		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action'] = site_url('dana/do_search_dana');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['main_title'] = 'List Permintaan Dana';
		$data['title'] = 'Pilih Project';
		$this->load->view('frm_pick_proj', $data);
	}

	function list_dana()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'R');

		$this->pick_proj();
	}

	function do_search_dana()
	{
		$data['projid'] = '';
		if ($this->input->post('cb_proj') == '') {
			$data['projid'] = $this->uri->segment(3);
		} else {
			$data['projid'] = $this->input->post('cb_proj');
		}

		$list_dana = $this->mod_dana->list_dana($data['projid'])->result();
		$data['list_dana'] = $list_dana;

		foreach ($this->mod_proj->edit_proj($data['projid'])->result() as $row_proj);
		$data['title'] = 'Permintaan Dana Untuk Project ' . $row_proj->namproj;
		$this->load->view('list_dana', $data);
	}

	function edit_dana()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'R');

		$data['form_action'] = site_url('Dana/do_edit_dana');

		$data['url_back'] = site_url('dana/do_search_dana/' . $this->uri->segment(3));
		$sql_get_dana = $this->mod_dana->edit_dana($this->uri->segment(3), $this->uri->segment(4));
		foreach ($sql_get_dana->result()  as $row);

		$data['title'] = 'List Permintaan Dana > Ubah Req. No : ' . $this->lib->no_req($row->reqno);

		$data['cb_proj'] = $this->lib->generate_cb_proj($row->projid);
		$data['cb_pa'] = $this->lib->generate_cb_pa($row->paid);
		$data['cb_by'] = $this->lib->generate_cb_by_edit($row->projid, $row->paid, $row->byid);
		$data['cb_spk'] = $this->lib->generate_cb_spk($row->projid, $row->paid, $row->byid, $row->spkno);

		$data['default']['txt_proj'] = $row->projid;
		$data['default']['txt_reqno'] = $row->reqno;
		$data['default']['txt_total'] = $row->total;
		$data['default']['txt_ket'] = $row->ket;
		//$data['default']['txt_konid'] = $row->konid;
		//$data['default']['txt_namkon'] = $this->lib->get_namkon($row->konid);
		$data['default']['txt_status'] = $row->status;
		$data['default']['txt_tgl_trans'] = $this->lib->disp_date($row->crdat);

		$data['list_dnlog'] = $this->mod_dana->list_dnlog($this->uri->segment(3), $this->uri->segment(4))->result();

		$this->load->view('frm_dana', $data);
	}

	function do_edit_dana()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'D');


		if ($this->input->post('txt_status') == 'R01') {
			$status = 'P01';
			//Send email
			$this->_send_email_app($this->input->post('cb_proj'), $this->input->post('txt_reqno'), 'FIN');
		}
		/*
		elseif($this->input->post('txt_status') == 'R03') //Cost control
		{
			$status = 'P02';
			//Send email
			$this->_send_email_app($this->input->post('cb_proj'),$this->input->post('txt_reqno'),'AUD');
			$this->_send_email_app($this->input->post('cb_proj'),$this->input->post('txt_reqno'),'KOM');
		}
		*/ elseif ($this->input->post('txt_status') == 'R02') {
			$status = 'A01';
			//Send email
			$this->_send_email_app($this->input->post('cb_proj'), $this->input->post('txt_reqno'), 'DIR');
		} else {
			if ($this->input->post('txt_status') == 'A02') {
				$status = 'P01'; //Imam 25/10/23 jika sudah diapprove maka masih bisa direvisi
				//Imam 25/10/23
				/*$this->session->set_flashdata('error', 'Tidak bisa mengubah permintaan dana karena dalam proses approval sudah selesai');
				redirect('dana/do_search_dana/' . $this->input->post('cb_proj'));*/
				//Imam 25/10/23
			}
			/*
			elseif($this->input->post('txt_status') == 'A03') //Cost control
			{
				$this->session->set_flashdata('error', 'Tidak bisa mengubah permintaan dana karena sudah di approve oleh cost control');
				redirect('dana/do_search_dana/'.$this->input->post('cb_proj'));	
			}
			*/ elseif ($this->input->post('txt_status') == 'A01') {
				$this->session->set_flashdata('error', 'Tidak bisa mengubah permintaan dana karena sudah di approve oleh finance');
				redirect('dana/do_search_dana/' . $this->input->post('cb_proj'));
			} else {
				/*if($this->lib->cek_tipe_ang($this->input->post('cb_pa'),'T') == TRUE)
				{
				$status = 'P02';
				}
				else
				{
				*/
				$status = 'P01';
				/*
				}
				*/
			}
		}

		$dana = array(
			'projid' => $this->input->post('cb_proj'),
			'paid' => $this->input->post('cb_pa'),
			'byid' => $this->input->post('cb_by'),
			//'konid' => $this->input->post('txt_konid'),
			'spkno' => $this->input->post('cb_spk'),
			'total' => 	$this->lib->convert_idr_to_num($this->input->post('txt_total')),
			'totalbyr' => 	$this->lib->convert_idr_to_num($this->input->post('txt_total')),
			'ket' => $this->input->post('txt_ket'),
			'status' => $status,
			'week' => $this->lib->weekOfMonth(strtotime($this->lib->conv_date_to_int($this->input->post('txt_tgl_trans')))),
			'mon' => date('m', strtotime($this->lib->conv_date_to_int($this->input->post('txt_tgl_trans')))),
			'year' => date('Y', strtotime($this->lib->conv_date_to_int($this->input->post('txt_tgl_trans')))),
			'crdat' => $this->lib->conv_date_to_int($this->input->post('txt_tgl_trans')),
			'chby' => $this->session->userdata('userid'),
			'chdat' => date('Y-m-d')
		);

		$this->mod_dana->update_dana($this->input->post('txt_proj'), $this->input->post('txt_reqno'), $dana);

		$this->session->set_flashdata('ok', 'Data permintaan dana telah diubah');
		redirect('dana/do_search_dana/' . $this->input->post('cb_proj'));
	}
}
