<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rab extends CI_Controller
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
	var $mid = 'M004';
	var $mid2 = 'M005';

	function __construct()
	{
		parent::__construct();
		$this->lib->check_login();
		$this->load->model('mod_rab', '', TRUE);
	}

	public function index()
	{
		$this->list_kat();
	}

	function print_rab()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid2, 'R');

		$data['rabd'] = $this->mod_rab->get_rabd($this->uri->segment(3))->result();

		foreach ($this->mod_rab->get_rabh($this->uri->segment(3))->result() as $row);
		$data['kerja'] = $row->kerja;
		$data['tgl'] = $this->lib->disp_date($row->tgl);

		foreach ($this->mod_proj->edit_proj($row->projid)->result() as $row_proj);
		$data['namproj'] = $row_proj->namproj;

		$filename = 'rab_'.$row_proj->projid.'_'.$this->uri->segment(3);
		//$this->load->view('print_rab', $data);
		$this->load->library('pdf');
		$this->pdf->load_view('print_rab',$data,$filename,'l');
	}

	function del_rabd()
	{

		$data = array(
			'del' => 1,
			'chby' => $this->session->userdata('userid'),
			'chdat' => date('Y-m-d H:i:s')
		);

		$this->mod_rab->update_rabd($this->uri->segment(4), $this->uri->segment(5), $data);

		$this->session->set_flashdata('ok', 'Data RAB telah dihapus');
		redirect('rab/edit_rab/' . $this->uri->segment(3) . '/' . $this->uri->segment(4));
	}

	function input_rabd()
	{
		$no = $this->mod_rab->get_rabd_no($this->input->post('txt_rabid'));
		$data = array(
			'no' => $no,
			'rabid' => $this->input->post('txt_rabid'),
			'konid' => $this->input->post('cb_kon'),
			'itmid' => $this->input->post('cb_itm'),
			'vol' => $this->lib->replace_commas_dot($this->input->post('txt_vol')),
			'hrg' => $this->lib->convert_idr_to_num($this->input->post('txt_hrg')),
			'total' => $this->lib->convert_idr_to_num($this->input->post('txt_tot')),
			'ket' => $this->input->post('txt_ket'),
			'crby' => $this->session->userdata('userid'),
			'crdat' => date('Y-m-d H:i:s')
		);

		$this->mod_rab->input_rabd2($data);

		$this->session->set_flashdata('ok', 'Data RAB telah ditambah');
		redirect('rab/edit_rab/' . $this->input->post('txt_projid') . '/' . $this->input->post('txt_rabid'));
	}

	function update_rabh()
	{

		$data = array(
			'kerja' => $this->input->post('txt_kerja'),
			'tgl' => $this->lib->conv_date_to_int($this->input->post('txt_tgl')),
			'chby' => $this->session->userdata('userid'),
			'chdat' => date('Y-m-d')
		);

		$this->mod_rab->update_rabh($this->input->post('txt_rabid'), $data);

		$this->session->set_flashdata('ok', 'Data RAB telah diubah');
		redirect('rab/list_rab/' . $this->input->post('txt_projid'));
	}

	function edit_rab()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid2, 'U');

		$data['rabd'] = $this->mod_rab->get_rabd($this->uri->segment(4))->result();

		foreach ($this->mod_proj->edit_proj($this->uri->segment(3))->result() as $row_proj);
		$data['title'] = 'RAB Project ' . $row_proj->namproj . ' ID : ' . $this->uri->segment(4);

		foreach ($this->mod_rab->get_rabh($this->uri->segment(4))->result() as $row);
		$data['kerja'] = $row->kerja;
		$data['rabid'] = $row->rabid;
		$data['projid'] = $row->projid;
		$data['tgl'] = $this->lib->disp_date($row->tgl);

		$data['cb_kat'] = $this->lib->generate_cb_kat($this->input->post('cb_kat'));

		$query = $this->db->query("SELECT * FROM `mkon`")->result();
		$data['cb_kon'] = "<option value=''>Silakan Pilih</option>";

		foreach ($query as $row) {
			$data['cb_kon'] .= '<option value="' . $row->konid . '">';
			$data['cb_kon'] .= $row->namkon . '</option>';
		}

		$data['form_action'] = site_url('rab/update_rabh');
		$data['form_action2'] = site_url('rab/input_rabd');

		$this->load->view('frm_rab_edit', $data);
	}

	function list_rab()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid2, 'R');

		$data['projid'] = '';

		if ($this->input->post('cb_proj') == '') {
			$data['projid'] = $this->uri->segment(3);
		} else {
			$data['projid'] = $this->input->post('cb_proj');
		}

		foreach ($this->mod_proj->edit_proj($data['projid'])->result() as $row_proj);
		$data['title'] = 'List RAB Project ' . $row_proj->namproj;

		$data['list_rab'] = $this->mod_rab->get_rab_projid($data['projid'])->result();

		$this->load->view('list_rab', $data);
	}

	function list_rab_init()
	{
		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action'] = site_url('rab/list_rab');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['main_title'] = 'List RAB';
		$data['title'] = 'Pilih Project';
		$this->load->view('frm_pick_proj', $data);
	}

	function do_input_rab()
	{
		$no = 0;

		$data['error'] = 0;
		$data['message'] = '';

		$sql_rabid = $this->db->query("SHOW TABLE STATUS LIKE 'trabh'");
		foreach ($sql_rabid->result()  as $row_rabid);

		$header = array(
			'tgl' => $this->lib->conv_date_to_int($this->input->post('txt_tgl')),
			'kerja' => $_POST['txt_kerja'],
			'projid' => $_POST['txt_projid'],
			'crby' => $this->session->userdata('userid'),
			'crdat' => date('Y-m-d H:i:s')
		);

		for ($i = 0; $i < count($_POST['konid']); $i++) {

			$detail[] = array(
				'no' => ++$no,
				'rabid' => $row_rabid->Auto_increment,
				'konid' => $_POST['konid'][$i],
				'itmid' => $_POST['itmid'][$i],
				'vol' => $_POST['vol'][$i],
				'hrg' => $_POST['hrg'][$i],
				'total' => $_POST['tot'][$i],
				'ket' => $_POST['ket'][$i],
				'crby' => $this->session->userdata('userid'),
				'crdat' => date('Y-m-d H:i:s')
			);
		}

		//print_r($header);
		//print_r($detail);

		$this->db->trans_start();
		$this->mod_rab->input_rabh($header);
		$this->mod_rab->input_rabd($detail);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$data['error'] = 1;
			$data['message'] = 'Tambah RAB gagal';
		} else {
			$data['message'] = 'Tambah RAB berhasil';
		}
		echo json_encode($data);
	}

	function input_rab()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid2, 'C');

		$data['projid'] = '';
		$data['form_action'] = site_url('rab/do_input_rab');

		if ($this->input->post('cb_proj') == '') {
			$data['projid'] = $this->uri->segment(3);
		} else {
			$data['projid'] = $this->input->post('cb_proj');
		}

		$query = $this->db->query("SELECT * FROM `mkon`")->result();
		$data['cb_kon'] = "<option value=''>Silakan Pilih</option>";

		foreach ($query as $row) {
			$data['cb_kon'] .= '<option value="' . $row->konid . '">';
			$data['cb_kon'] .= $row->namkon . '</option>';
		}

		foreach ($this->mod_proj->edit_proj($data['projid'])->result() as $row_proj);
		$data['title'] = 'Input RAB Untuk Project ' . $row_proj->namproj;

		$data['cb_kat'] = $this->lib->generate_cb_kat($this->input->post('cb_kat'));

		$this->load->view('frm_rab_input', $data);
	}

	function input_rab_init()
	{
		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action'] = site_url('rab/input_rab');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['main_title'] = 'Input RAB';
		$data['title'] = 'Pilih Project';
		$this->load->view('frm_pick_proj', $data);
	}

	function del_kat()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'D');
		$cek_itm = $this->mod_rab->count_kat($this->uri->segment(3));
		if ($cek_itm->num_rows() > 0) {
			$this->session->set_flashdata('error', 'Hapus kategori gagal, mohon hapus terlebih dahulu semua data item dengan kategori ini');
			redirect('rab/list_kat/' . $this->uri->segment(3));
		} else {
			$this->mod_rab->delete_kat($this->uri->segment(3));
			$this->session->set_flashdata('ok', 'Hapus kategori berhasil');
			redirect('rab/list_kat/' . $this->uri->segment(3));
		}
	}

	function del_itm()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'D');
		$cek_itm = $this->mod_rab->count_itm($this->uri->segment(4));
		if ($cek_itm->num_rows() > 0) {
			$this->session->set_flashdata('error', 'Hapus item gagal, mohon hapus terlebih dahulu semua data harga dengan item ini');
			redirect('rab/list_itm/' . $this->uri->segment(3));
		} else {
			$this->mod_rab->delete_itm($this->uri->segment(4));
			$this->session->set_flashdata('ok', 'Hapus item berhasil');
			redirect('rab/list_itm/' . $this->uri->segment(3));
		}
	}

	function do_disp_laprab()
	{

		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'R');

		$sql_get_rabkat = $this->mod_rab->get_kat_projid($this->input->post('cb_proj'))->result();
		$data['kat'] = $sql_get_rabkat;

		foreach ($this->mod_proj->edit_proj($this->input->post('cb_proj'))->result() as $row_proj);
		$data['title'] = 'Project ' . $row_proj->namproj;

		$this->load->view('lap_rab', $data);
	}

	function lap_rab()
	{
		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action'] = site_url('rab/do_disp_laprab');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['main_title'] = 'Laporan Master Rencana Anggaran Biaya';
		$data['title'] = 'Pilih Project';
		$this->load->view('frm_pick_proj', $data);
	}

	function do_edit_hrg()
	{

		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'U');

		$hrg = array(
			'projid' => $this->input->post('txt_projid'),
			'katid' => $this->input->post('txt_katid'),
			'itmid' => $this->input->post('txt_itmid'),
			'hrg' => $this->lib->convert_idr_to_num($this->input->post('txt_hrg')),
			'ket' => $this->input->post('txt_ket'),
			'chby' => $this->session->userdata('userid'),
			'chdat' => date('Y-m-d')
		);

		$this->mod_rab->update_hrg($this->input->post('txt_projid'), $this->input->post('txt_katid'), $this->input->post('txt_itmid'), $hrg);

		$this->session->set_flashdata('ok', 'Ubah harga berhasil');
		redirect('rab/list_hrg/' . $this->input->post('txt_projid'));
	}


	function edit_hrg()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'R');

		$data['form_action'] = site_url('rab/do_edit_hrg');

		$sql_get_hrg = $this->mod_rab->get_hrg($this->uri->segment(3), $this->uri->segment(4), $this->uri->segment(5));
		foreach ($sql_get_hrg->result()  as $row);

		$data['disabled'] = 'disabled';
		$data['cb_kat'] = $this->lib->generate_cb_kat($row->katid);
		$data['cb_itm'] = $this->lib->generate_cb_itm($row->itmid, $row->katid);
		$data['projid'] = $this->uri->segment(3);
		$data['default']['txt_hrg'] = $row->hrg;
		$data['default']['txt_ket'] = $row->ket;

		$data['katid'] = $row->katid;
		$data['itmid'] = $row->itmid;

		foreach ($this->mod_proj->edit_proj($this->uri->segment(3))->result() as $row_proj);
		$data['title'] = 'List Harga Untuk Project ' . $row_proj->namproj . ' > Ubah Harga';

		$this->load->view('frm_rab_hrg', $data);
	}

	function del_hrg()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'D');
		$this->mod_rab->delete_hrg($this->uri->segment(3), $this->uri->segment(4), $this->uri->segment(5));

		$this->session->set_flashdata('ok', 'Hapus harga berhasil');
		redirect('rab/list_hrg/' . $this->uri->segment(3));
	}

	function do_input_hrg()
	{

		$hrg = array(
			'projid' => $this->input->post('txt_projid'),
			'katid' => $this->input->post('cb_kat'),
			'itmid' => $this->input->post('cb_itm'),
			'hrg' => $this->lib->convert_idr_to_num($this->input->post('txt_hrg')),
			'ket' => $this->input->post('txt_ket'),
			'crby' => $this->session->userdata('userid'),
			'crdat' => date('Y-m-d')
		);

		$this->mod_rab->input_hrg($hrg);

		$this->session->set_flashdata('ok', 'Tambah harga berhasil');
		redirect('rab/list_hrg/' . $this->input->post('txt_projid'));
	}

	function get_itm_hrg()
	{
		$sql_get_hrg = $this->mod_rab->get_hrg_projid($this->input->post('projid'), $this->input->post('katid'), $this->input->post('itmid'));
		foreach ($sql_get_hrg->result()  as $row);
		echo $row->hrg;
	}

	function get_itm2()
	{
		$sql_get_itm = $this->mod_rab->get_itm_katid($this->input->post('katid'));
		echo "<option value=''>Silahkan Pilih</option>";
		foreach ($sql_get_itm->result()  as $row) {
			echo "<option value='" . $row->itmid . "'>" . $row->namitm . " [ " . $row->namsat . " ]</option>";
		}
	}

	function get_itm()
	{
		$sql_get_itm = $this->mod_rab->get_itm_projid($this->input->post('projid'), $this->input->post('katid'));
		echo "<option value=''>Silahkan Pilih</option>";
		foreach ($sql_get_itm->result()  as $row) {
			echo "<option value='" . $row->itmid . "'>" . $row->namitm . " [ " . $row->namsat . " ]</option>";
		}
	}


	function input_hrg()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'C');

		$data['form_action'] = site_url('rab/do_input_hrg');

		foreach ($this->mod_proj->edit_proj($this->uri->segment(3))->result() as $row_proj);
		$data['title'] = 'Input Harga Untuk Project ' . $row_proj->namproj;

		$data['cb_kat'] = $this->lib->generate_cb_kat($this->input->post('cb_kat'));
		$data['cb_itm'] = '';
		$data['projid'] = $this->uri->segment(3);

		$this->load->view('frm_rab_hrg', $data);
	}

	function list_hrg()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'R');

		$data['projid'] = '';

		if ($this->input->post('cb_proj') == '') {
			$data['projid'] = $this->uri->segment(3);
		} else {
			$data['projid'] = $this->input->post('cb_proj');
		}

		$sql_get_hrg = $this->mod_rab->list_hrg($data['projid'])->result();
		$data['list_hrg'] = $sql_get_hrg;

		$data['url_back'] = site_url('rab/pick_proj');

		foreach ($this->mod_proj->edit_proj($data['projid'])->result() as $row_proj);
		$data['title'] = 'List Harga Untuk Project ' . $row_proj->namproj;
		$this->load->view('list_rab_hrg', $data);
	}

	function pick_proj()
	{
		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action'] = site_url('rab/list_hrg');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['main_title'] = 'List Harga';
		$data['title'] = 'Pilih Project';
		$this->load->view('frm_pick_proj', $data);
	}

	function do_edit_itm()
	{

		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'U');

		$itm = array(
			'namitm' => $this->input->post('txt_namitm'),
			'sat' => $this->input->post('cb_sat'),
			'chby' => $this->session->userdata('userid'),
			'chdat' => date('Y-m-d')
		);

		$this->mod_rab->update_itm($this->input->post('txt_itmid'), $itm);

		$this->session->set_flashdata('ok', 'Ubah item RAB berhasil');
		redirect('rab/list_itm/' . $this->input->post('txt_katid'));
	}

	function edit_itm()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'R');

		$data['form_action'] = site_url('rab/do_edit_itm');

		$sql_get_itm = $this->mod_rab->get_itm($this->uri->segment(3));
		foreach ($sql_get_itm->result()  as $row);

		$sql_get_kat = $this->mod_rab->get_kat($row->katid);
		foreach ($sql_get_kat->result()  as $row_kat);

		$data['title'] = 'List Item Kategori ' . $row_kat->namkat . ' > Ubah Item';

		$data['default']['txt_itmid'] = $row->itmid;
		$data['default']['txt_namitm'] = $row->namitm;

		$data['katid'] = 	$row->katid;
		$data['sat'] = $this->lib->generate_cb_sat($row->sat);

		$this->load->view('frm_rab_itm', $data);
	}

	function do_input_itm()
	{
		$itm = array(
			'katid' => $this->input->post('txt_katid'),
			'namitm' => $this->input->post('txt_namitm'),
			'sat' => $this->input->post('cb_sat'),
			'crby' => $this->session->userdata('userid'),
			'crdat' => date('Y-m-d')
		);

		$this->mod_rab->input_itm($itm);

		$this->session->set_flashdata('ok', 'Tambah kategori RAB berhasil');
		redirect('rab/list_itm/' . $this->input->post('txt_katid'));
	}

	function input_itm()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'C');

		$data['form_action'] = site_url('rab/do_input_itm');

		$sql_get_kat = $this->mod_rab->get_kat($this->uri->segment(3));
		foreach ($sql_get_kat->result()  as $row);

		$data['title'] = 'Tambah Item Untuk Kategori ' . $row->namkat;

		$sql_itmid = $this->db->query("SHOW TABLE STATUS LIKE 'mrabitm'");
		foreach ($sql_itmid->result()  as $row_itmid);

		$data['default']['txt_itmid'] = 	$row_itmid->Auto_increment;
		$data['katid'] = 	$this->uri->segment(3);
		$data['sat'] = $this->lib->generate_cb_sat($this->input->post('cb_sat'));

		$this->load->view('frm_rab_itm', $data);
	}

	function list_itm()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'R');

		$data['url_back'] = site_url('rab/list_kat');

		$list_itm = $this->mod_rab->list_itm($this->uri->segment(3))->result();
		$data['list_itm'] = $list_itm;

		$sql_get_kat = $this->mod_rab->get_kat($this->uri->segment(3));
		foreach ($sql_get_kat->result()  as $row);

		$data['title'] = 'Kategori ' . $row->namkat . ' > Tambah Item';

		$this->load->view('list_rab_itm', $data);
	}

	function list_kat()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'R');

		$list_kat = $this->mod_rab->list_kat()->result();
		$data['list_kat'] = $list_kat;
		$data['title'] = 'List Kategori RAB';
		$this->load->view('list_rab_kat', $data);
	}

	function input_kat()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'C');

		$data['form_action'] = site_url('rab/do_input_kat');
		$data['title'] = 'Tambah Kategori';

		$sql_katid = $this->db->query("SHOW TABLE STATUS LIKE 'mrabkat'");
		foreach ($sql_katid->result()  as $row_katid);

		$data['default']['txt_katid'] = 	$row_katid->Auto_increment;

		$this->load->view('frm_rab_kat', $data);
	}

	function do_input_kat()
	{
		$kat = array(
			'namkat' => $this->input->post('txt_namkat'),
			'crby' => $this->session->userdata('userid'),
			'crdat' => date('Y-m-d')
		);

		$this->mod_rab->input_kat($kat);

		$this->session->set_flashdata('ok', 'Tambah kategori RAB berhasil');
		redirect('rab/input_kat');
	}

	function edit_kat()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'R');

		$data['form_action'] = site_url('rab/do_edit_kat');
		$data['title'] = 'List Kategori > Ubah Kategori';
		$sql_get_kat = $this->mod_rab->get_kat($this->uri->segment(3));
		foreach ($sql_get_kat->result()  as $row);

		$data['default']['txt_katid'] = $row->katid;
		$data['default']['txt_namkat'] = $row->namkat;


		$this->load->view('frm_rab_kat', $data);
	}

	function do_edit_kat()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'U');

		$kat = array(
			'namkat' => $this->input->post('txt_namkat'),
			'chby' => $this->session->userdata('userid'),
			'chdat' => date('Y-m-d')
		);

		$this->mod_rab->update_kat($this->input->post('txt_katid'), $kat);

		$this->session->set_flashdata('ok', 'Data kategori RAB telah diubah');
		redirect('rab');
	}
}
