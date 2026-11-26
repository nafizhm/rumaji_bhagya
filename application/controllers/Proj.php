<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Proj extends CI_Controller
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
	var $moid = 'PJ';
	var $mid = 'M001';

	function __construct()
	{
		parent::__construct();
		$this->lib->check_login();
		$this->load->model('mod_proj', '', TRUE);
		$this->load->model('mod_spr', '', TRUE);
		$this->load->model('mod_spk', '', TRUE);
	}
function input_pt()	{		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'C');		$data['form_action'] = site_url('proj/do_input_pt');		$data['title'] = 'Tambah Perusahaan';		$sql_pt = $this->db->query("SHOW TABLE STATUS LIKE 'mpt'");		foreach ($sql_pt->result()  as $row_ptid);		$data['default']['txt_ptid'] = 	$row_ptid->Auto_increment;				$this->load->view('frm_pt', $data);	}function do_input_pt()	{		$pt = array(			'nampt' => $this->input->post('txt_nampt')		);		$this->mod_proj->input_pt($pt);		$this->session->set_flashdata('ok', 'Tambah perusahaan berhasil');		redirect('proj/input_pt');	}function list_pt()	{		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'R');		$list_pt = $this->mod_proj->list_pt()->result();		$data['list_pt'] = $list_pt;		$data['title'] = 'List Perusahaan';		$this->load->view('list_pt', $data);	}function edit_pt()	{		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'R');		$data['form_action'] = site_url('proj/do_edit_pt');		$data['title'] = 'List Perusahaan > Ubah Perusahaan';		$sql_get_pt = $this->mod_proj->edit_pt($this->uri->segment(3));		foreach ($sql_get_pt->result()  as $row);		$data['default']['txt_ptid'] = $row->ptid;		$data['default']['txt_nampt'] = $row->nampt;				$this->load->view('frm_pt', $data);	}function do_edit_pt()	{		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'U');		$pt = array(			'nampt' => $this->input->post('txt_nampt')		);		$this->mod_proj->update_pt($this->input->post('txt_ptid'), $pt);		$this->session->set_flashdata('ok', 'Data perusahaan telah diubah');		redirect('proj/list_pt');	}	
	public function index()
	{
		$this->list_proj();
	}

	function list_proj()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'R');

		$list_proj = $this->mod_proj->list_proj()->result();
		$data['list_proj'] = $list_proj;
		$data['title'] = 'List Project';
		$this->load->view('list_proj', $data);
	}

	function input_proj()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'C');

		$data['form_action'] = site_url('proj/do_input_proj');
		$data['title'] = 'Tambah Project';

		$sql_projid = $this->db->query("SHOW TABLE STATUS LIKE 'mproj'");
		foreach ($sql_projid->result()  as $row_projid);

		$data['default']['txt_projid'] = 	$row_projid->Auto_increment;
		$data['stat'] = $this->lib->generate_cb_stat($this->input->post('cb_stat'), 'PRJ');
		$data['pt'] = $this->lib->generate_cb_pt($this->input->post('cb_pt'));

		$this->load->view('frm_proj', $data);
	}

	function do_input_proj()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'C');
		$kkp=$this->kkp_value();if ($kkp===NULL) return;
		$proj = array(
			'kkp' => $kkp,
			'namproj' => $this->input->post('txt_namproj'),
			'idext' => strtoupper($this->input->post('txt_idext')),
			'status' => $this->input->post('cb_status'),
			'ptid' => $this->input->post('cb_pt'),
			'crby' => $this->session->userdata('userid'),
			'crdat' => date('Y-m-d')
		);

		$no = array(
			'projid' => $this->input->post('txt_projid'),
			'crby' => $this->session->userdata('userid'),
			'crdat' => date('Y-m-d')
		);

		$this->mod_proj->input_proj($proj);
		$this->mod_proj->input_nomor($no);

		$this->session->set_flashdata('ok', 'Tambah project berhasil');
		redirect('proj/input_proj');
	}

	function edit_proj()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'R');

		$data['form_action'] = site_url('proj/do_edit_proj');
		$data['title'] = 'List Project > Ubah Project';
		$sql_get_proj = $this->mod_proj->edit_proj($this->uri->segment(3));
		foreach ($sql_get_proj->result()  as $row);

		$data['default']['txt_projid'] = $row->projid;
		$data['default']['txt_namproj'] = $row->namproj;
		$data['default']['txt_idext'] = $row->idext;
		$data['default']['txt_kkp'] = $row->kkp;

		$data['stat'] = $this->lib->generate_cb_stat($row->status, 'PRJ');
		$data['pt'] = $this->lib->generate_cb_pt($row->ptid);

		$this->load->view('frm_proj', $data);
	}

	function do_edit_proj()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'U');
		$kkp=$this->kkp_value();if ($kkp===NULL) return;

		$proj = array(
			'kkp' => $kkp,
			'namproj' => $this->input->post('txt_namproj'),
			'idext' => strtoupper($this->input->post('txt_idext')),
			'status' => $this->input->post('cb_status'),
			'ptid' => $this->input->post('cb_pt'),
			'chby' => $this->session->userdata('userid'),
			'chdat' => date('Y-m-d')
		);

		$this->mod_proj->update_proj($this->input->post('txt_projid'), $proj);

		$this->session->set_flashdata('ok', 'Data project telah diubah');
		redirect('proj');
	}

	private function kkp_value()
	{
		$value=$this->input->post('txt_kkp');
		if ($value===NULL || $value==='') return '0';
		if (!is_scalar($value) || !preg_match('/^(?:[0-9]{1,13}|[0-9]{1,3}(?:\.[0-9]{3}){1,4})$/D',(string)$value)) {
			show_error('Nilai KKP harus berupa rupiah bulat, maksimal 13 digit.',422);return NULL;
		}
		$value=str_replace('.','',(string)$value);
		if (strlen($value)>13) { show_error('Nilai KKP maksimal 13 digit.',422);return NULL; }
		return $value;
	}
}
