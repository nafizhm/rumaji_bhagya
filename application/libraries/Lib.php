<?php
class Lib
{
	function __construct()
	{
		/* Load library CI */
		$this->CI = &get_instance();
		$this->CI->load->library(array('session', 'form_validation', 'encryption'));
		$this->CI->load->model('mod_proj', '', TRUE);
		$this->CI->load->model('mod_hrg', '', TRUE);
		$this->CI->load->model('mod_hrgf', '', TRUE);
		$this->CI->load->model('mod_admin', '', TRUE);
		$this->CI->load->model('mod_bank', '', TRUE);
		$this->CI->load->model('mod_rab', '', TRUE);
		//$this->CI->load->model('mod_kas', '', TRUE);
		$this->CI->load->helper(array('form', 'url'));
	}

	//CB PT
	function generate_cb_pt($ptid)
	{

		$query = $this->CI->db->query("SELECT * FROM `mpt`");

		$data_pt = $query->result();

		$data['default']['cb_pt'] = $ptid;

		$data['pt'] = '';

		foreach ($data_pt as $row) {
			$data['pt'] .= $row->ptid == $data['default']['cb_pt'] ? '<option value="' . $row->ptid . '" selected="selected">' : '<option value="' . $row->ptid . '">';
			$data['pt'] .= $row->nampt . '</option>';
		}
		return $data['pt'];
	}

	//Get Nama PT
	function get_pt($projid)
	{
		$query = $this->CI->db->query("SELECT mpt.nampt FROM `mpt` INNER JOIN mproj ON mproj.ptid = mpt.ptid WHERE mproj.projid = '" . $projid . "'");
		foreach ($query->result() as $row);
		return $row->nampt;
	}
	//Format qty
	function format_qty($qty)
	{
		return number_format($qty, 2, ",", ".");
	}

	//CB Item
	function generate_cb_itm($itm, $katid)
	{

		$query_itm = $this->CI->db->query("SELECT * FROM `mrabitm` WHERE katid = '" . $katid . "'");

		$data_itm = $query_itm->result();

		$data['default']['cb_itm'] = $itm;

		$data['itm'] = '';

		foreach ($data_itm as $row) {
			$data['itm'] .= $row->itmid == $data['default']['cb_itm'] ? '<option value="' . $row->itmid . '" selected="selected">' : '<option value="' . $row->itmid . '">';
			$data['itm'] .= $row->namitm . '</option>';
		}
		return $data['itm'];
	}

	//CB Kategori
	function generate_cb_kat($kat)
	{

		$query_kat = $this->CI->db->query("SELECT * FROM `mrabkat`");

		$data_kat = $query_kat->result();

		$data['default']['cb_kat'] = $kat;

		$data['kat'] = '';

		foreach ($data_kat as $row) {
			$data['kat'] .= $row->katid == $data['default']['cb_kat'] ? '<option value="' . $row->katid . '" selected="selected">' : '<option value="' . $row->katid . '">';
			$data['kat'] .= $row->namkat . '</option>';
		}
		return $data['kat'];
	}

	//CB Satuan
	function generate_cb_sat($sat)
	{

		$query_sat = $this->CI->db->query("SELECT * FROM `msat`");

		$data_sat = $query_sat->result();

		$data['default']['cb_sat'] = $sat;

		$data['sat'] = '';

		foreach ($data_sat as $row) {
			$data['sat'] .= $row->sat == $data['default']['cb_sat'] ? '<option value="' . $row->sat . '" selected="selected">' : '<option value="' . $row->sat . '">';
			$data['sat'] .= $row->namsat . '</option>';
		}
		return $data['sat'];
	}

	function cek_prog_done($projid, $sprno, $blok, $no)
	{
		$um = $this->disp_prog_um($projid, $sprno);
		$dok = $this->disp_prog_dok($projid, $sprno);
		$kav = $this->disp_prog_kav($projid, $blok, $no);

		if ($um != 100 || $dok != 100 || $kav != 100) {
			return false;
		} else {
			return true;
		}
	}

	function get_kav_status($projid, $blok, $no)
	{
		$query = $this->CI->db->query("SELECT status FROM `mkav` 
	WHERE projid = '" . $projid . "'
	AND blok = '" . $blok . "'
	AND no = '" . $no . "'");

		foreach ($query->result() as $row);
		return $row->status;
	}

	function cek_umt_lunas($projid, $sprno)
	{
		$query = $this->CI->db->query("SELECT * FROM `tsprp` 
	WHERE projid = '" . $projid . "'
	AND sprno = '" . $sprno . "'
	AND tipe = 'UMT'
	AND status='BLN'");

		return $query->num_rows();
	}
	/*
function get_bank_kas($projid,$bankid,$accno)
{
	$query = $this->CI->db->query("SELECT * FROM `mbankacc` 
	WHERE projid = '".$projid."'
	AND bankid = '".$bankid."'
	AND accno = '".$accno."'");
	if($query->num_rows() > 0)
	{
		foreach($query->result() as $row);
		return $row->kas;
	}
}
*/
	function kas_text($kas)
	{
		$txt = '';
		switch ($kas) {
			case 'KB':
				$txt = 'Kas Besar';
				break;
			case 'KP':
				$txt = 'Kas Proyek';
				break;
			case 'KK':
				$txt = 'Kas Konsumen';
				break;
		}

		return $txt;
	}
	/*
function generate_cb_tkas($kas,$dk,$tipe)
	{
		
		$data['default']['cb_tkas'] = $tipe;
		
			$query_tkas = $this->CI->mod_kas->list_kas_typ($kas,$dk);
			$data_tkas = $query_tkas->result();
			$data['tkas']='';
			foreach($data_tkas as $row_tkas)
			{
			$data['tkas'] .= $row_tkas->tipe==$data['default']['cb_tkas'] ? '<option value="'.$row_tkas->tipe.'" selected="selected">': '<option value="'.$row_tkas->tipe.'">';
			$data['tkas'] .= $row_tkas->namtipe.'</option>';
			}	
		return $data['tkas'];
	}
*/
	//Cek tipe pos anggaran
	function cek_tipe_ang($paid, $kat)
	{
		$query = $this->CI->db->query("SELECT kat FROM `mpa` 
	WHERE paid = '" . $paid . "'
	AND kat = '" . $kat . "'");
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	//Display user foto
	function disp_foto()
	{
		$row_foto = $this->CI->db->select('foto')->where('userid', $this->CI->session->userdata('userid'))->get('muser')->row();
		$filename = $row_foto ? basename((string) $row_foto->foto) : '';
		if ($filename !== '' && is_file(FCPATH . 'assets/uploads_foto/' . $filename)) {
			return $this->CI->config->item('assets') . '/uploads_foto/' . rawurlencode($filename);
		}
		return $this->CI->config->item('assets') . '/img/avatar-default.svg';
	}

	//Progress Uang Muka
	function disp_prog_um($projid, $sprno)
	{
		$query_um = $this->CI->db->query("SELECT SUM(total) as tot_um FROM `tsprp` 
	WHERE projid = '" . $projid . "' AND sprno = '" . $sprno . "'");
		foreach ($query_um->result()  as $row_um);

		$query_byr = $this->CI->db->query("SELECT SUM(total) as tot_byr FROM `tkwt` 
	WHERE projid = '" . $projid . "' AND sprno = '" . $sprno . "' AND btl = 0");
		foreach ($query_byr->result()  as $row_byr);

		$query_spr = $this->CI->db->query("SELECT byttp FROM `tsprh` 
	WHERE projid = '" . $projid . "' AND sprno = '" . $sprno . "'");
		foreach ($query_spr->result()  as $row_spr);

		//$progress = number_format(($row_byr->tot_byr / ($row_um->tot_um - $row_spr->byttp))*100);
		$progress = number_format(($row_byr->tot_byr / $row_um->tot_um) * 100);

		/*
	if($projid == 2)
	{
	$progress = number_format(($row_byr->tot_byr / $row_um->tot_um)*100); //Griya samaji
	}
	else
	{
	$progress = number_format(($row_byr->tot_byr / ($row_um->tot_um - 4000000))*100); //Samaji asri sepang 2,3
	}
	*/
		return $progress;
	}
	//Progress Dokumen
	function disp_prog_dok($projid, $sprno)
	{
		$query = $this->CI->db->query("SELECT COUNT(*) as tot FROM `tsprd` WHERE projid = '" . $projid . "' AND sprno = '" . $sprno . "'");
		foreach ($query->result()  as $row);

		//$progress = number_format(($row->tot / 10) * 100);
		$progress = number_format(($row->tot / 11) * 100);
		return $progress;
	}

	//Progress Bangun
	function disp_prog_kav($projid, $blok, $no)
	{
		$query = $this->CI->db->query("SELECT pkav FROM `mkav` WHERE projid = '" . $projid . "' AND blok = '" . $blok . "' 
	AND no = '" . $no . "'");
		foreach ($query->result()  as $row);

		$progress = $row->pkav;
		return $progress;
	}

	function tanggal_indo($tanggal, $cetak_hari = false)
	{
		$hari = array(
			1 =>    'Senin',
			'Selasa',
			'Rabu',
			'Kamis',
			'Jumat',
			'Sabtu',
			'Minggu'
		);

		$bulan = array(
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$split 	  = explode('-', $tanggal);
		$tgl_indo = $split[2] . ' ' . $bulan[(int) $split[1]] . ' ' . $split[0];

		if ($cetak_hari) {
			$num = date('N', strtotime($tanggal));
			return $hari[$num] . ', ' . $tgl_indo;
		}
		return $tgl_indo;
	}

	function get_nambank($bankid)
	{
		$query = $this->CI->db->query("SELECT * FROM mbank WHERE bankid = '" . $bankid . "'");
		foreach ($query->result()  as $row);
		if (empty($row)) {
			return '';
		} else {
			return $row->nambank;
		}
	}

	function generate_cb_bank_acc($projid)
	{

		$query_bank = $this->CI->mod_bank->get_bankacc($projid);
		$data_bank = $query_bank->result();

		$data['bank'] = '';
		foreach ($data_bank as $row_bank) {
			$data['bank'] .= '<option value="' . $row_bank->bankid . ';' . $row_bank->accno . ';' . $row_bank->kas . '">';
			$data['bank'] .= $row_bank->nambank . ' ACC. NO : ' . $row_bank->accno . ' A/N ' . $row_bank->namacc . '</option>';
		}
		return $data['bank'];
	}

	function generate_cb_bank($bankid)
	{
		$query_bank = $this->CI->mod_bank->list_bank();
		$data_bank = $query_bank->result();


		$data['default']['cb_bank'] = $bankid;

		$data['bank'] = '';
		foreach ($data_bank as $row_bank) {
			$data['bank'] .= $row_bank->bankid == $data['default']['cb_bank'] ? '<option value="' . $row_bank->bankid . '" selected="selected">' : '<option value="' . $row_bank->bankid . '">';
			$data['bank'] .= $row_bank->nambank . '</option>';
		}
		return $data['bank'];
	}

	function get_time()
	{
		date_default_timezone_set("Asia/Bangkok");
		return date('H:i:s');
	}

	function disp_namusr($usrid)
	{
		$query = $this->CI->db->query("SELECT namusr FROM muser WHERE userid = '" . $usrid . "'");
		foreach ($query->result()  as $row);
		if (empty($row)) {
			return '';
		} else {
			return $row->namusr;
		}
	}

	//Replace commas with .	
	function replace_commas_dot($number)
	{
		$numbers = str_replace(',', '.', $number);
		return $numbers;
	}

	function disp_msg()
	{
		echo $this->CI->session->flashdata('ok') == '' ? '' : '<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>' . $this->CI->session->flashdata('ok') . '</div>';

		echo $this->CI->session->flashdata('error') == '' ? '' : '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>' . $this->CI->session->flashdata('error') . '</div>';

		echo $this->CI->session->flashdata('warning') == '' ? '' : '<div class="alert alert-warning alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>' . $this->CI->session->flashdata('warning') . '</div>';

		echo validation_errors() == '' ? '' : ' <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>' . validation_errors() . '</div>';
	}

	function check_login()
	{
		if ($this->CI->session->userdata('logged_in') == FALSE) {
			$this->CI->session->set_flashdata('error', 'Anda belum login!');
			redirect('auth/login');
		}
	}

	function no_kas($no)
	{
		return str_pad($no, 4, '0', STR_PAD_LEFT);
	}


	function no_kwt($no)
	{
		return str_pad($no, 4, '0', STR_PAD_LEFT);
	}

	function no_spr($no)
	{
		return str_pad($no, 4, '0', STR_PAD_LEFT);
	}

	function no_req($no)
	{
		return str_pad($no, 4, '0', STR_PAD_LEFT);
	}

	function disp_date($date)
	{
		$dispdate = '';
		if ($date == '0000-00-00') {
			$dispdate = '-';
		} else {
			$dispdate = date("d/m/Y", strtotime($date));
		}
		return $dispdate;
	}

	function conv_date_to_int($date)
	{

		$date = date("Y-m-d", strtotime(str_replace('/', '-', $date)));
		return $date;
	}

	function get_status_text($grp, $stat)
	{
		$query = $this->CI->db->query("SELECT * FROM mstat WHERE statgrp = '" . $grp . "' AND status = '" . $stat . "'");
		foreach ($query->result()  as $row);
		return $row->namstat;
	}

	function get_namkon($konid)
	{
		$query = $this->CI->db->query("SELECT * FROM mkon WHERE konid = '" . $konid . "'");
		foreach ($query->result()  as $row);
		if (empty($row)) {
			return '';
		} else {
			return $row->namkon;
		}
	}

	function disp_stat_user($code)
	{
		$desc = '';

		switch ($code) {
			case 0:
				$desc = '<font color="#FF0000">Non Aktif</font>';
				break;
			case 1:
				$desc = '<font color="#009900">Aktif</font>';
				break;
		}
		return $desc;
	}
	function disp_bayar($bayar)
	{
		return (number_format($bayar));
	}

	function conv_bayar($bayar)
	{
		return str_replace(",", "", $bayar);
	}

	function disp_usnam($userid)
	{
		$query = $this->CI->db->query("SELECT * FROM users WHERE userid = '" . $userid . "'");
		foreach ($query->result()  as $row);

		return $row->name;
	}

	function terbilang($x)
	{
		$abil = array("", "Satu ", "Dua ", "Tiga ", "Empat ", "Lima ", "Enam ", "Tujuh ", "Delapan ", "Sembilan ", "Sepuluh ", "Sebelas ");
		if ($x < 12)
			return " " . $abil[$x];
		elseif ($x < 20)
			return $this->terbilang($x - 10) . "Belas";
		elseif ($x < 100)
			return $this->terbilang($x / 10) . " Puluh" . $this->terbilang($x % 10);
		elseif ($x < 200)
			return " Seratus" . $this->terbilang($x - 100);
		elseif ($x < 1000)
			return $this->terbilang($x / 100) . " Ratus" . $this->terbilang($x % 100);
		elseif ($x < 2000)
			return " Seribu" . $this->terbilang($x - 1000);
		elseif ($x < 1000000)
			return $this->terbilang($x / 1000) . " Ribu" . $this->terbilang($x % 1000);
		elseif ($x < 1000000000)
			return $this->terbilang($x / 1000000) . " Juta" . $this->terbilang($x % 1000000);
		elseif ($x < 1000000000000)
			return $this->terbilang($x / 1000000000) . " Milyard" . $this->terbilang($x % 1000000000);
	}

	function enkripsi($string)
	{
		return $this->CI->encryption->encrypt($string);
	}

	function dekripsi($string)
	{
		return $this->CI->encryption->decrypt($string);
	}


	function generate_cb_proj($proj)
	{

		$data['default']['cb_proj'] = $proj;

		/* Generate combo box project */
		//$query_proj = $this->CI->mod_proj->list_proj_akt();
		/*if($this->CI->session->userdata('roleid') == 'ADM')
			{
				$query_proj = $this->CI->mod_proj->list_proj();
			}
			else
			{
			$query_proj =  $this->CI->db->query("SELECT mproj.projid, mproj.namproj 
			FROM mproj
			INNER JOIN mprjusr ON mprjusr.projid = mproj.projid
			WHERE mprjusr.userid = '".$this->CI->session->userdata('userid')."'");
			}*/
		$query_proj =  $this->CI->db->query("SELECT mproj.projid, mproj.namproj 
			FROM mproj
			INNER JOIN mprjusr ON mprjusr.projid = mproj.projid
			WHERE mprjusr.userid = '" . $this->CI->session->userdata('userid') . "'
			AND mproj.status = 'AKT'");
		if ($query_proj->num_rows() == 0) {
			$query_proj = $this->CI->mod_proj->list_proj_akt();
		}

		$data_proj = $query_proj->result();
		$data['proj'] = '';
		foreach ($data_proj as $row_proj) {
			$data['proj'] .= $row_proj->projid == $data['default']['cb_proj'] ? '<option value="' . $row_proj->projid . '" selected="selected">' : '<option value="' . $row_proj->projid . '">';
			$data['proj'] .= $row_proj->namproj . '</option>';
		}
		return $data['proj'];
	}


	function generate_cb_tipe($tipe)
	{

		$data['default']['cb_tipe'] = $tipe;

		/* Generate combo box tipe rumah*/
		$query_tipe = $this->CI->mod_admin->get_tipe();
		$data_tipe = $query_tipe->result();
		$data['tipe'] = '';
		foreach ($data_tipe as $row_tipe) {
			$data['tipe'] .= $row_tipe->tipe == $data['default']['cb_tipe'] ? '<option value="' . $row_tipe->tipe . '" selected="selected">' : '<option value="' . $row_tipe->tipe . '">';
			$data['tipe'] .= $row_tipe->tipe . '</option>';
		}
		return $data['tipe'];
	}

	function generate_cb_fit($fit)
	{

		$data['default']['cb_fit'] = $fit;

		/* Generate combo box fitur*/
		$query_fit = $this->CI->mod_admin->get_fit();
		$data_fit = $query_fit->result();
		$data['fit'] = '';
		foreach ($data_fit as $row_fit) {
			$data['fit'] .= $row_fit->ftid == $data['default']['cb_fit'] ? '<option value="' . $row_fit->ftid . '" selected="selected">' : '<option value="' . $row_fit->ftid . '">';
			$data['fit'] .= $row_fit->namfit . '</option>';
		}
		return $data['fit'];
	}

	function generate_cb_stat($stat, $statgrp)
	{

		$data['default']['cb_stat'] = $stat;

		/* Generate combo box status */
		$query_stat = $this->CI->mod_admin->get_stat($statgrp);
		$data_stat = $query_stat->result();
		$data['stat'] = '';
		foreach ($data_stat as $row_stat) {
			$data['stat'] .= $row_stat->status == $data['default']['cb_stat'] ? '<option value="' . $row_stat->status . '" selected="selected">' : '<option value="' . $row_stat->status . '">';
			$data['stat'] .= $row_stat->namstat . '</option>';
		}
		return $data['stat'];
	}

	function generate_cb_role($role)
	{

		$data['default']['cb_role'] = $role;

		$query_role = $this->CI->mod_admin->get_role();
		$data_role = $query_role->result();
		$data['role'] = '';

		foreach ($data_role as $row_role) {
			$data['role'] .= $row_role->roleid == $data['default']['cb_role'] ? '<option value="' . $row_role->roleid . '" selected="selected">' : '<option value="' . $row_role->roleid . '">';
			$data['role'] .= $row_role->namrol . '</option>';
		}

		return $data['role'];
	}

	function generate_cb_pa($paid)
	{

		$data['default']['cb_pa'] = $paid;

		/* Generate combo box pos anggaran */
		$query_pa = $this->CI->mod_ang->list_mpa();
		$data_pa = $query_pa->result();
		$data['pa'] = '';
		foreach ($data_pa as $row_pa) {
			$data['pa'] .= $row_pa->paid == $data['default']['cb_pa'] ? '<option value="' . $row_pa->paid . '" selected="selected">' : '<option value="' . $row_pa->paid . '">';
			$data['pa'] .= $row_pa->paid . '-' . $row_pa->nampa . '</option>';
		}
		return $data['pa'];
	}

	function generate_cb_by($paid, $byid)
	{

		$data['default']['cb_by'] = $byid;

		/* Generate combo box pos anggaran */
		$query_by = $this->CI->mod_ang->list_mby($paid);
		$data_by = $query_by->result();
		$data['by'] = '';
		foreach ($data_by as $row_by) {

			$data['by'] .= $row_by->byid == $data['default']['cb_by'] ? '<option value="' . $row_by->byid . '" selected="selected">' : '<option value="' . $row_by->byid . '">';
			$data['by'] .= $row_by->byid . '-' . $row_by->namby . '</option>';
		}
		return $data['by'];
	}

	function generate_cb_by_edit($projid, $paid, $byid)
	{

		$data['default']['cb_by'] = $byid;

		/* Generate combo box pos anggaran */

		$sql_get_by = $this->CI->mod_ang->get_mby_projid($paid, $projid);
		$data['by'] = "<option value=''>Silahkan pilih</option>";

		foreach ($sql_get_by->result()  as $row) {
			$tot_ang = $this->get_tot_ang($projid, $paid, $row->byid);
			$sisa = $row->total - $tot_ang;

			$data['by'] .= $row->byid == $data['default']['cb_by'] ? '<option value="' . $row->byid . '" selected="selected">' : '<option value="' . $row->byid . '">';
			$data['by'] .= $row->byid . '-' . $row->namby . '</option>';
			//$data['by'] .= $row->byid.'-'.$row->namby.' ( Anggaran: '.$this->format_idr($row->total).' Terpakai: '.$this->format_idr($tot_ang).' Sisa : '.$this->format_idr($sisa).' )</option>';


		}

		return $data['by'];
	}

	function generate_cb_spk($projid, $paid, $byid, $spkno)
	{

		$data['default']['cb_spk'] = $spkno;

		/* Generate combo box spk */
		$query_spk = $this->CI->mod_spk->get_spk_byid($projid, $paid, $byid);
		$data_spk = $query_spk->result();
		$data['spk'] = '';
		foreach ($data_spk as $row_spk) {
			$tot_dana = $this->get_tot_dana($row_spk->projid, $row_spk->spkno);
			$sisa = $row_spk->total - $tot_dana;
			$data['spk'] .= $row_spk->spkno == $data['default']['cb_spk'] ? '<option value="' . $row_spk->spkno . '" selected="selected">' : '<option value="' . $row_spk->spkno . '">';
			$data['spk'] .= $this->format_spkno($row_spk->projid, $row_spk->spkno) . '-' . $row_spk->ket . '  ( Anggaran: ' . $this->format_idr($row_spk->total) . ' Terpakai: ' . $this->format_idr($tot_dana) . ' Sisa : ' . $this->format_idr($sisa) . ' )</option>';
		}
		return $data['spk'];
	}

	function disp_mon($mon)
	{
		switch ($mon) {
			case '01':
				$mon = 'Januari';
				break;
			case '02':
				$mon = 'Februari';
				break;
			case '03':
				$mon = 'Maret';
				break;
			case '04':
				$mon = 'April';
				break;
			case '05':
				$mon = 'Mei';
				break;
			case '06':
				$mon = 'Juni';
				break;
			case '07':
				$mon = 'Juli';
				break;
			case '08':
				$mon = 'Agustus';
				break;
			case '09':
				$mon = 'September';
				break;
			case '10':
				$mon = 'Oktober';
				break;
			case '11':
				$mon = 'November';
				break;
			case '12':
				$mon = 'Desember';
				break;
		}

		return $mon;
	}
	function generate_cb_mon($mon)
	{

		$data['default']['cb_mon'] = $mon;

		/* Generate combo box bulan */
		$data['mon'] = '';
		for ($i = 1; $i <= 12; $i++) {
			$monv = str_pad($i, 2, '0', STR_PAD_LEFT);

			$data['mon'] .= date('m') == $monv ? '<option value="' . $monv . '" selected="selected">' : '<option value="' . $monv . '">';
			$data['mon'] .= $this->disp_mon($monv) . '</option>';
		}
		return $data['mon'];
	}


	//Format mata uang rupiah
	function format_idr($amount)
	{
		$amount = number_format($amount, 0, "", ".");

		return $amount;
	}

	//Convert format IDR ke format angka
	function convert_idr_to_num($curr)
	{
		$lv_found = 0;
		$lv_result = '';
		for ($i = 0; $i <= strlen($curr); $i++) {
			if (($lv_found == 0) and ($curr[$i] != ",")) {
				$lv_result = $lv_result . $curr[$i];
			} else {
				$lv_found = 1;
				//$lv_result = $lv_result.$string[$i];
			}
		}
		return str_replace(".", "", $lv_result);
	}

	function get_harga($projid, $lot, $tipe, $get)
	{

		$query_hrg = $this->CI->mod_hrg->get_hrg($projid, $lot, $tipe)->result();
		foreach ($query_hrg as $rows);

		if ($get == 'H') {
			return $rows->hrgstd;
		}
		if ($get == 'B') {
			return $rows->byttp;
		} elseif ($get == 'T') {
			return $rows->tgl;
		}
	}

	function get_hargaf($projid, $ftid, $get)
	{

		$query_hrg = $this->CI->mod_hrgf->get_hrgf($projid, $ftid)->result();
		foreach ($query_hrg as $rows);
		if ($get == 'H') {
			return $rows->hrg;
		} elseif ($get == 'T') {
			return $rows->tgl;
		}
	}

	function get_no($projid, $tipe)
	{
		switch ($tipe) {
			case 'SPR':
				$query = $this->CI->db->query("SELECT nospr FROM tnomor WHERE projid = '" . $projid . "'");
				foreach ($query->result()  as $row);
				$no = $row->nospr + 1;
				break;
			case 'SPK':
				$query = $this->CI->db->query("SELECT nospk FROM tnomor WHERE projid = '" . $projid . "'");
				foreach ($query->result()  as $row);
				$no = $row->nospk + 1;
				break;
			case 'REQ':
				$query = $this->CI->db->query("SELECT noreq FROM tnomor WHERE projid = '" . $projid . "'");
				foreach ($query->result()  as $row);
				$no = $row->noreq + 1;
				break;
			case 'KWT':
				$query = $this->CI->db->query("SELECT nokwt FROM tnomor WHERE projid = '" . $projid . "'");
				foreach ($query->result()  as $row);
				$no = $row->nokwt + 1;
				break;
			case 'KAS':
				$query = $this->CI->db->query("SELECT nokas FROM tnomor WHERE projid = '" . $projid . "'");
				foreach ($query->result()  as $row);
				$no = $row->nokas + 1;
				break;
		}

		return $no;
	}

	function commit_no($projid, $no, $tipe)
	{
		switch ($tipe) {
			case 'SPR':
				$data = array(
					'nospr' => $no
				);
				break;
			case 'SPK':
				$data = array(
					'nospk' => $no
				);
				break;
			case 'REQ':
				$data = array(
					'noreq' => $no
				);
				break;
			case 'KWT':
				$data = array(
					'nokwt' => $no
				);
				break;
			case 'KAS':
				$data = array(
					'nokas' => $no
				);
				break;
		}

		$this->CI->db->where('projid', $projid);
		$this->CI->db->update('tnomor', $data);
	}

	function format_spkno($projid, $spkno)
	{
		$query = $this->CI->db->query("SELECT * FROM tspk WHERE projid = '" . $projid . "' AND spkno = '" . $spkno . "'");
		foreach ($query->result()  as $row);

		$query_prj = $this->CI->db->query("SELECT idext FROM mproj WHERE projid = '" . $projid . "'");
		foreach ($query_prj->result()  as $row_prj);

		$spkno = str_pad($row->spkno, 3, '0', STR_PAD_LEFT) . '/' . $row->paid . '/' . $row->byid . '/' . $row->tipe . '/' . $row->thn . '/' . $row_prj->idext;

		return $spkno;
	}

	function generate_cb_stat_kav($projid, $blok, $no)
	{
		$data['cb_stat'] = '';
		$count = 0;
		$query = $this->CI->db->query("SELECT status, namstat 
	FROM mstat WHERE statgrp = 'KAV' 
	AND status NOT IN 
	( SELECT status 
	 FROM tkavlog 
	 WHERE projid = '" . $projid . "' 
	 AND blok = '" . $blok . "'  AND no = '" . $no . "'  ) 
	 AND seq > 0 ORDER BY seq");
		foreach ($query->result()  as $row) {
			/*
		$count++;
		if($count > 1 )
		{
			$data['cb_stat'].= '<option disabled="disabled" value="'.$row->status.'">'.$row->namstat.'</option>';
		}
		else
		{
			$data['cb_stat'].= '<option value="'.$row->status.'">'.$row->namstat.'</option>';
		}
		*/
			$data['cb_stat'] .= '<option value="' . $row->status . '">' . $row->namstat . '</option>';
		}

		return $data['cb_stat'];
	}

	function generate_cb_stat_dok($projid, $sprno)
	{
		$data['cb_stat_dok'] = '';
		$count = 0;
		$query = $this->CI->db->query("SELECT status, namstat, seq
	FROM mstat WHERE statgrp = 'DOK' 
	AND status NOT IN 
	( SELECT status 
	 FROM tsprd 
	 WHERE projid = '" . $projid . "' 
	 AND sprno = '" . $sprno . "' ) 
	 AND seq > 0 ORDER BY seq");
		foreach ($query->result()  as $row) {
			$count++;
			if ($count > 1 && $row->seq < 4) {
				$data['cb_stat_dok'] .= '<option disabled="disabled" value="' . $row->status . '">' . $row->namstat . '</option>';
			} else {
				$data['cb_stat_dok'] .= '<option value="' . $row->status . '">' . $row->namstat . '</option>';
			}
		}

		return $data['cb_stat_dok'];
	}

	function get_tot_ang($projid, $paid, $byid)
	{
		$query = $this->CI->db->query("SELECT SUM(totalbyr) as total 
	FROM `tdana` WHERE projid = '" . $projid . "' AND paid = '" . $paid . "' and byid = '" . $byid . "'
	AND status = 'A02'");
		foreach ($query->result()  as $row);
		return $row->total;
	}

	/*function weekOfMonth($date) {
    //Get the first day of the month.
    $firstOfMonth = strtotime(date("Y-m-01",$date));
    //Apply above formula.
    return intval(date("W", $date)) - intval(date("W", $firstOfMonth)) + 1;
}*/

	function weekOfMonth($date)
	{
		$cut = substr($date, 0, 8);
		$daylen = 86400;

		$timestamp = strtotime($date);
		$first = strtotime($cut . "00");
		$elapsed = ($timestamp - $first) / $daylen;

		$weeks = 1;

		for ($i = 1; $i <= $elapsed; $i++) {
			$dayfind = $cut . (strlen($i) < 2 ? '0' . $i : $i);
			$daytimestamp = strtotime($dayfind);

			$day = strtolower(date("l", $daytimestamp));

			if ($day == strtolower("sunday"))  $weeks++;
		}

		return $weeks;
	}

	function get_tot_dana($projid, $spkno)
	{
		$query = $this->CI->db->query("SELECT sum(totalbyr) as tot_dana FROM `tdana` WHERE projid = '" . $projid . "' AND spkno = '" . $spkno . "' AND status = 'A02'");
		foreach ($query->result()  as $row);
		return $row->tot_dana;
	}

	function send_email($to, $subject, $template, $data)
	{
		/*	$config = Array(
    'protocol' => 'smtp',
    'smtp_host' => $this->CI->config->item('smtp_host'),
    'smtp_port' => $this->CI->config->item('smtp_port'),
    'smtp_user' => $this->CI->config->item('smtp_user'),
    'smtp_pass' => $this->CI->config->item('smtp_pass'),
    'mailtype'  => 'html', 
    'charset'   => 'iso-8859-1',
	'wordwrap' => TRUE);
*/

		/*temporary disabled 
	$config = Array(
    'protocol' => 'mail',
    'mailtype'  => 'html', 
    'charset'   => 'iso-8859-1',
	'wordwrap' => TRUE);
	
$this->CI->load->library('email', $config);

$this->CI->email->set_newline("\r\n");
$this->CI->email->from('notifikasi@samaji.co.id', 'Notifikasi Sistem Internal Samaji');
$this->CI->email->to($to); 
$this->CI->email->subject($subject.' (Tidak perlu dibalas)');

$body = $this->CI->load->view($template,$data,TRUE);	
$this->CI->email->message($body);	
if($this->CI->email->send())
     {
      //echo 'Email sent.';
     }
     else
    {
     show_error($this->CI->email->print_debugger());
    }
	*/
	}

	function tipe_byr_txt($tipe)
	{
		switch ($tipe) {
			case 'UTJ':
				$txt = 'Uang Tanda Jadi';
				break;
			case 'UMK':
				$txt = 'Uang Muka';
				break;
			case 'UFT':
				$txt = 'Uang Fitur';
				break;
			case 'BTL':
				$txt = 'Batal';
				break;
			case 'UBT':
				$txt = 'Biaya Biaya Titipan';
				break;
			case 'UMT':
				$txt = 'Kekurangan Uang Muka';
				break;
		}

		return $txt;
	}

	function stat_byr_txt($status)
	{
		switch ($status) {
			case 'BLN':
				$txt = '<font color="#FF0000">Belum lunas</font>';
				break;
			case 'LNS':
				$txt = '<font color="#009900">Lunas</font>';
				break;
		}

		return $txt;
	}

	function check_auth($roleid, $moid, $mid, $auth)
	{
		if ($roleid != 'ADM') {
			$query = $this->CI->db->query("SELECT * FROM mauth WHERE roleid = '" . $roleid . "' AND moid = '" . $moid . "'  
	AND mid = '" . $mid . "'");
			foreach ($query->result()  as $row);

			$status = "";

			switch ($auth) {
				case "C":
					$auth_txt = 'Create';
					$status = $row->c == 0 ? $status : 'ok';
					break;
				case "R":
					$auth_txt = 'Read';
					$status = $row->r == 0 ? $status : 'ok';
					break;
				case "U":
					$auth_txt = 'Update';
					$status = $row->u == 0 ? $status : 'ok';
					break;
				case "D":
					$auth_txt = 'Delete';
					$status = $row->d == 0 ? $status : 'ok';
					break;
			}

			if ($status == '') {
				$msg = 'Anda tidak mempunyai otorisasi ' . $auth_txt . ' pada menu ' . $mid . ', modul id ' . $moid . ', role id ' . $roleid . ' !';
				$this->CI->session->set_userdata("auth_error", $msg);
				redirect('errors');
			}
		}
	}
}
