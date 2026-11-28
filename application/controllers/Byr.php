<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Byr extends CI_Controller
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
	var $moid = 'CI';
	var $mid = 'T001';
	var $mid2 = 'R001';
	var $mid3 = 'R002';
	var $mid4 = 'R003';

	function __construct()
	{
		parent::__construct();
		$this->lib->check_login();
		$this->load->model('mod_spr', '', TRUE);
		$this->load->model('mod_proj', '', TRUE);
		$this->load->model('mod_kav', '', TRUE);
		$this->load->model('mod_kwt', '', TRUE);
		$this->load->model('mod_bank', '', TRUE);
	}

	public function index()
	{

		$this->list_spr();
	}

	function do_verify_kwt()
	{

		$kwt = array(
			'tgl_setor' => $this->lib->conv_date_to_int($this->input->post('txt_tgl')),
			'verby' => $this->session->userdata('userid'),
			'verdat' => date('Y-m-d')
		);

		$this->mod_kwt->update_kwt($this->input->post('txt_kwtno'), $this->input->post('txt_projid'), $kwt);

		$this->session->set_flashdata('ok', 'Verifikasi Kwitansi No ' . $this->lib->no_kwt($this->input->post('txt_kwtno')) . ' Sukses');
		redirect('byr/do_lap_rkpkwt_disp/' . $this->input->post('txt_projid') . '/' . $this->input->post('txt_tgl_from') . '/' . $this->input->post('txt_tgl_to'));
	}

	function lap_rkp_kwt()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid3, 'R');

		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action'] = site_url('byr/do_lap_rkpkwt_disp');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['main_title'] = 'Laporan Rekap Kwitansi';
		$data['title'] = 'Pilih Parameter';
		$this->load->view('frm_lap_rkpkwt', $data);
	}

	function do_lap_rkpkwt_disp()
	{

		if ($this->input->post('cb_proj') == '') {
			$projid = $this->uri->segment(3);
		} else {
			$projid = $this->input->post('cb_proj');
		}

		if ($this->input->post('txt_tgl_from') == '' || $this->input->post('txt_tgl_to') == '') {
			$tgl_from = $this->lib->disp_date($this->uri->segment(4));
			$tgl_to = $this->lib->disp_date($this->uri->segment(5));
		} else {
			$tgl_from = $this->input->post('txt_tgl_from');
			$tgl_to = $this->input->post('txt_tgl_to');
		}

		foreach ($this->mod_proj->edit_proj($projid)->result() as $row_proj);
		$data['projid'] = $projid;
		$data['title'] = 'Project ' . $row_proj->namproj;
		$data['subtitle'] = 'Tanggal ' . $tgl_from . ' Sampai Dengan ' . $tgl_to;

		$data['tgl_from'] = $this->lib->conv_date_to_int($tgl_from);
		$data['tgl_to'] = $this->lib->conv_date_to_int($tgl_to);

		$sum_kwt = $this->mod_kwt->sum_rkp_kwt(
			$projid,
			$this->lib->conv_date_to_int($tgl_from),
			$this->lib->conv_date_to_int($tgl_to)
		)->result();
		$data['sum_kwt'] = $sum_kwt;

		$query = $this->db->query("SELECT u FROM mauth WHERE roleid = '" . $this->session->userdata('roleid') . "' AND moid = '" . $this->moid . "'  
		AND mid = '" . $this->mid . "'");
		foreach ($query->result()  as $row);
		if ($this->session->userdata('roleid') != 'ADM') {
			$data['verify'] = $row->u;
		} else {
			$data['verify'] = 1;
		}


		$this->load->view('lap_rkpkwt', $data);
	}

	function lap_jtmpo()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid3, 'R');

		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action'] = site_url('byr/do_lap_jtmpo_disp');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['main_title'] = 'Laporan Jatuh Tempo';
		$data['title'] = 'Pilih Parameter';
		$this->load->view('frm_lap_jtmpo', $data);
	}

	function do_lap_jtmpo_disp()
	{
		if ($this->input->post('txt_tgl_from') != '' && $this->input->post('txt_tgl_from') != '') {
			$date_from = $this->lib->conv_date_to_int($this->input->post('txt_tgl_from'));
			$date_to = $this->lib->conv_date_to_int($this->input->post('txt_tgl_to'));
		} else {
			$date_from = '';
			$date_to = '';
		}

		$list_jtmpo = $this->mod_kwt->list_jtmpo($this->input->post('cb_proj'), $date_from, $date_to)->result();
		$data['list_jtmpo'] = $list_jtmpo;

		foreach ($this->mod_proj->edit_proj($this->input->post('cb_proj'))->result() as $row_proj);
		$data['title'] = 'Project ' . $row_proj->namproj;

		$data['subtitle'] = 'Tanggal ' . $this->input->post('txt_tgl_from') . ' Sampai Dengan ' . $this->input->post('txt_tgl_to');

		$this->load->view('lap_jtmpo', $data);
	}

	function lap_byr()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid2, 'R');

		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action'] = site_url('byr/do_lap_byr_disp');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['main_title'] = 'Laporan Cash In';
		$data['title'] = 'Pilih Parameter';
		$this->load->view('frm_lap_byr', $data);
	}


	// start bambang 2021-10-27
	function do_lap_byr_disp()
	{

		if ($this->input->post('cb_proj') == '') {
			$cb_proj = $this->uri->segment(3);
			$txt_tgl_from = $this->uri->segment(4);
			$txt_tgl_to = $this->uri->segment(5);
			$mbtl = $this->uri->segment(6);
			if ($mbtl=="0") {$btl = '';} else {$btl = '1';};
			$txt_tgl_from = str_replace("-","/",$txt_tgl_from);
			$txt_tgl_to = str_replace("-","/",$txt_tgl_to);
	
		} else {
			if ($this->input->post('chk_btl') == '') {
				$btl = '';
				$mbtl="0";
			} else {
				$btl = '1';
				$mbtl="1";
			}
			$cb_proj = $this->input->post('cb_proj');
			$txt_tgl_from = $this->input->post('txt_tgl_from');
			$txt_tgl_to = $this->input->post('txt_tgl_to');
		}

		$data['projid'] = $cb_proj;
		$data['mtgl1'] = str_replace("/","-",$txt_tgl_from);
		$data['mtgl2'] = str_replace("/","-",$txt_tgl_to);
		$data['mbtl'] = $mbtl;	

		// echo $btl;

		$list_byr = $this->mod_kwt->list_byr($cb_proj, $this->lib->conv_date_to_int($txt_tgl_from), $this->lib->conv_date_to_int($txt_tgl_to), $btl)->result();
		$data['list_byr'] = $list_byr;

		foreach ($this->mod_proj->edit_proj($cb_proj)->result() as $row_proj);
		$data['title'] = 'Project ' . $row_proj->namproj;

		$data['subtitle'] = 'Tanggal ' . $txt_tgl_from . ' Sampai Dengan ' . $txt_tgl_to;

		$this->load->view('lap_byr', $data);
	}

	// function do_lap_byr_disp()
	// {

	// 	if ($this->input->post('chk_btl') == '') {
	// 		$btl = '';
	// 	} else {
	// 		$btl = '1';
	// 	}

	// 	$list_byr = $this->mod_kwt->list_byr($this->input->post('cb_proj'), $this->lib->conv_date_to_int($this->input->post('txt_tgl_from')), $this->lib->conv_date_to_int($this->input->post('txt_tgl_to')), $btl)->result();
	// 	$data['list_byr'] = $list_byr;

	// 	foreach ($this->mod_proj->edit_proj($this->input->post('cb_proj'))->result() as $row_proj);
	// 	$data['title'] = 'Project ' . $row_proj->namproj;

	// 	$data['subtitle'] = 'Tanggal ' . $this->input->post('txt_tgl_from') . ' Sampai Dengan ' . $this->input->post('txt_tgl_to');

	// 	$this->load->view('lap_byr', $data);
	// }
	// end bambang 2021-10-27

	function cetak_kwt_btl()
	{
		$sprh = $this->mod_spr->get_sprh($this->uri->segment(3), $this->uri->segment(4))->result();
		$data['sprh'] = $sprh;

		$kwt = $this->mod_kwt->get_kwt_btl($this->uri->segment(4), $this->uri->segment(3))->result();
		$data['kwt'] = $kwt;

		$filename = 'KWT_BTL_' . $this->uri->segment(4) . '-' . $this->uri->segment(3);
		$this->load->library('pdf');

		$this->pdf->load_view('print_kwt_btl', $data, $filename);
	}

	function cetak_kwt()
	{
		//Cek verifikasi kwitansi
		$query = $this->db->query("SELECT COUNT(*) as total FROM `tkwt`
		 WHERE `sprno` = '" . $this->uri->segment(5) . "' AND `projid` = '" . $this->uri->segment(3) . "' AND `total` > 0 AND `btl` = 0 AND verby = '' AND `crdat` >= '" . $_SERVER['TGL_VER'] . "'");
		foreach ($query->result()  as $row);

		if ($row->total > 0) {
			echo '<h1>Tidak bisa cetak kwitansi, mohon verifikasi dulu kwitansi sebelumnya</h1>';
			return;
		}

		$sprh = $this->mod_spr->get_sprh($this->uri->segment(5), $this->uri->segment(3))->result();
		$data['sprh'] = $sprh;

		$kwt = $this->mod_kwt->get_kwt($this->uri->segment(3), $this->uri->segment(4))->result();
		$data['kwt'] = $kwt;

		//Cek tipe kwitansi
		foreach ($kwt as $row);
		if ($row->total==0 && $row->deposit>0) { redirect('byr/cetak_deposit/'.$row->projid.'/'.$row->kwtno.'/'.$row->sprno); return; }
		$data['deposit_references']=$this->mod_kwt->deposit_references($row);
		if ($row->tipe == 'UBT') {
			$bank = $this->mod_bank->get_bankacc2($this->uri->segment(3), 'KK')->result();
		} else {
			$bank = $this->mod_bank->get_bankacc2($this->uri->segment(3), 'KB')->result();
		}
		//$bank = $this->mod_bank->get_bankacc($this->uri->segment(3))->result();
		$data['bank'] = $bank;

		$filename = 'KWT_' . $this->lib->no_kwt($this->uri->segment(4)) . '-' . $this->uri->segment(3);
		$this->load->library('pdf');

		$this->pdf->load_view('print_kwt', $data, $filename);
	}

	function cetak_deposit($projid=0,$kwtno=0,$sprno=0)
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'R');
		$receipt=$this->db->get_where('tkwt',array('projid'=>(int)$projid,'kwtno'=>(int)$kwtno,'sprno'=>(int)$sprno))->row();
		if (!$receipt || $receipt->deposit<=0 || $receipt->btl==='1') { show_404(); return; }
		$pending=$this->db->query("SELECT COUNT(*) total FROM tkwt WHERE projid=? AND sprno=? AND (total>0 OR deposit>0) AND btl<>'1' AND verby='' AND crdat>=?",array((int)$projid,(int)$sprno,$_SERVER['TGL_VER']))->row()->total;
		if ($pending) { show_error('Tidak bisa cetak kwitansi deposit, mohon verifikasi dulu kwitansi sebelumnya.',403); return; }
		$number='DEP-'.(int)$projid.'-'.str_pad((string)(int)$kwtno,6,'0',STR_PAD_LEFT);
		$dataDepositOnly=$receipt->total==0;
		$receipt->total=$receipt->deposit;
		$data=array('sprh'=>$this->mod_spr->get_sprh((int)$sprno,(int)$projid)->result(),'kwt'=>array($receipt),'bank'=>$this->mod_bank->get_bankacc2((int)$projid,$receipt->tipe==='UBT'?'KK':'KB')->result(),'deposit_number'=>$number,'deposit_only'=>$dataDepositOnly);
		$this->load->library('pdf');
		$this->pdf->load_view('print_kwt',$data,$number);
	}

	function list_byr()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'R');

		foreach ($this->mod_proj->edit_proj($this->uri->segment(3))->result() as $row_proj);
		$data['title'] = $row_proj->namproj;

		foreach ($this->mod_spr->get_sprh($this->uri->segment(4), $this->uri->segment(3))->result() as $row_sprh);


		$data['subtitle'] = ' No. SPR : ' . $this->lib->no_spr($this->uri->segment(4)) . ' Blok/No: ' . $row_sprh->blok . $row_sprh->no . ' Konsumen: ' . $row_sprh->nama;

		$data['status'] = $row_sprh->status;
		if (!$this->session->userdata('byr_token')) $this->session->set_userdata('byr_token',bin2hex(random_bytes(32)));
		$data['deposit']=$this->db->query("SELECT COALESCE(SUM(deposit-deposit_digunakan),0) saldo FROM tkwt WHERE projid=? AND sprno=? AND btl<>'1'",array($this->uri->segment(3),$this->uri->segment(4)))->row()->saldo;
		$data['deposit_receipts']=array();
		$data['deposit_only_receipts']=$this->db->query("SELECT * FROM tkwt WHERE projid=? AND sprno=? AND total=0 AND deposit>0 AND btl<>'1' ORDER BY kwtno DESC",array($this->uri->segment(3),$this->uri->segment(4)))->result();
		foreach ($this->db->query("SELECT kwtno,deposit FROM tkwt WHERE projid=? AND sprno=? AND btl<>'1' AND deposit>0",array($this->uri->segment(3),$this->uri->segment(4)))->result() as $receipt) $data['deposit_receipts'][$receipt->kwtno]=$receipt->deposit;
		$data['list_sprp']  = $this->mod_spr->get_list_sprp($this->uri->segment(3), $this->uri->segment(4))->result();
		$data['form_action']  = site_url('byr/do_input_byr');
		$data['form_action2']  = site_url('byr/do_input_btl');
		$this->load->view('list_byr', $data);
	}

	function do_input_byr()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'C');
		if ($this->input->method()!=='post') show_error('Metode tidak diizinkan.',405);
		if (!$this->session->userdata('byr_token') || !hash_equals((string)$this->session->userdata('byr_token'),(string)$this->input->post('byr_token'))) show_error('Sesi formulir tidak valid. Muat ulang halaman.',403);
		$received=$this->input->post('txt_jumlah_bayar');
		if (!is_string($received) || !preg_match('/^(?:\d{1,12}|\d{1,3}(?:\.\d{3}){1,3})(?:,\d{1,2})?$/D',$received)) show_error('Jumlah bayar tidak valid.',422);
		$receivedCents=(int)round((float)str_replace(array('.',','),array('','.'),$received)*100);
		$this->db->trans_start();
		$this->db->query('SELECT projid FROM mproj WHERE projid=? FOR UPDATE',array($this->input->post('txt_projid')));
		$plan=$this->db->query('SELECT p.*,h.status spr_status FROM tsprp p JOIN tsprh h ON h.projid=p.projid AND h.sprno=p.sprno WHERE p.projid=? AND p.sprno=? AND p.tipe=? AND p.ang=? FOR UPDATE',array($this->input->post('txt_projid'),$this->input->post('txt_sprno'),$this->input->post('txt_tipe'),$this->input->post('txt_ang')))->row();
		if (!$plan || $plan->status!=='BLN' || $plan->spr_status==='BTL') { $this->db->trans_rollback(); show_error('Tagihan sudah dibayar atau tidak dapat dibayar. Muat ulang halaman.',409); return; }
		$dueCents=(int)round($plan->total*100);
		$balance=$this->db->query("SELECT COALESCE(SUM(deposit-deposit_digunakan),0) saldo FROM tkwt WHERE projid=? AND sprno=? AND btl<>'1'",array($plan->projid,$plan->sprno))->row()->saldo;
		$balanceCents=(int)round($balance*100);
		if ($dueCents<=0 || ($receivedCents===0 && $balanceCents<$dueCents)) { $this->db->trans_rollback(); show_error('Isi jumlah bayar lebih dari nol atau gunakan saldo deposit yang cukup.',422); return; }
		$depositOnly=$receivedCents+$balanceCents<$dueCents;
		$usedCents=$depositOnly?0:max(0,$dueCents-$receivedCents);

		foreach ($this->mod_spr->cek_sprp($this->input->post('txt_sprno'), $this->input->post('txt_projid'), $this->input->post('txt_tipe'), $this->input->post('txt_ang'))->result() as $row_sprp);

		if ($row_sprp->status == 'LNS') {
			$this->session->set_flashdata('error', 'Tidak bisa input pembayaran, kwitansi sudah ada!');
			redirect('byr/list_byr/' . $this->input->post('txt_projid') . '/' . $this->input->post('txt_sprno'));
		}

		$kwtno = $this->lib->get_no($this->input->post('txt_projid'), 'KWT');

		$kwt = array(
			'kwtno' => $kwtno,
			'projid' => $this->input->post('txt_projid'),
			'sprno' => $this->input->post('txt_sprno'),
			'tipe' => $this->input->post('txt_tipe'),
			'ang' => $this->input->post('txt_ang'),
			'tgl' => $this->lib->conv_date_to_int($this->input->post('txt_tgl')),
			'ket' => $this->input->post('txt_ket'),
			'byr' => $this->input->post('cb_byr'),
			'total' => $depositOnly?0:$dueCents/100,
			'jumlah_bayar' => number_format($receivedCents/100,2,'.',''),
			'deposit' => number_format(($depositOnly?$receivedCents:max(0,$receivedCents-$dueCents))/100,2,'.',''),
			'deposit_digunakan' => number_format($usedCents/100,2,'.',''),
			'crby' => $this->session->userdata('userid'),
			'crdat' => date('Y-m-d')
		);
		$sprp = array(
			'kwtno' => $kwtno,
			'status' => 'LNS',
		);

		$this->mod_kwt->input_kwt($kwt);
		if (!$depositOnly) $this->mod_spr->update_sprp($this->input->post('txt_projid'), $this->input->post('txt_sprno'), $this->input->post('txt_tipe'), $this->input->post('txt_ang'), $sprp);
		$this->lib->commit_no($this->input->post('txt_projid'), $kwtno, 'KWT');

		/*Disable jika penurunan KPR sudah lunas status langsung AKAD => request by anton 28/06/2019
			if($this->input->post('txt_tipe')=='UMT')
			{
				if($this->lib->cek_umt_lunas($this->input->post('txt_projid'),$this->input->post('txt_sprno'))== 1)
				{
					$kav = array( 
						'status' => 'AKD',
						'chby' => $this->session->userdata('userid'),
						'chdat' => date('Y-m-d')
						);
						
					$sprh = $this->mod_spr->get_sprh($this->input->post('txt_sprno'),$this->input->post('txt_projid'))->result();
					foreach($sprh as $row_sprh);
					
					$kavlog = array( 
						'projid' => $this->input->post('txt_projid'),
						'blok' => $row_sprh->blok,
						'no' => $row_sprh->no,
						'sprno' => $this->input->post('txt_sprno'),
						'status' => 'AKD',
						'ket' => 'PELUNASAN KEKURANGAN UANG MUKA',
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d'),
						'crtim' => $this->lib->get_time()
						);
						
						$this->mod_kav->update_kav($this->input->post('txt_projid'),$row_sprh->blok,$row_sprh->no,$kav);
						$this->mod_kav->input_kavlog($kavlog);
				}
			}
			*/
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			$this->session->set_flashdata('error', 'Input pembayaran gagal!');
			redirect('byr/list_byr/' . $this->input->post('txt_projid') . '/' . $this->input->post('txt_sprno'));
		} else {
			if ($depositOnly) {
				$this->session->set_flashdata('ok','Dana berhasil disimpan seluruhnya sebagai deposit. Cicilan masih belum lunas. Silakan verifikasi kwitansi deposit sebelum mencetak.');
				redirect('byr/list_byr/'.$plan->projid.'/'.$plan->sprno); return;
			}
			$this->session->set_flashdata('ok', 'Input pembayaran sukses, no kwitansi : ' . $this->lib->no_kwt($kwtno) . '<a target="_blank" href="' . site_url('byr/cetak_kwt/' . $this->input->post('txt_projid') . '/' . $kwtno . '/' . $this->input->post('txt_sprno')) . '"> Klik disini untuk cetak kwitansi</a>');
			redirect('byr/list_byr/' . $this->input->post('txt_projid') . '/' . $this->input->post('txt_sprno'));
		}
	}

	function do_input_btl()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'U');
		if ($this->input->method()!=='post') show_error('Metode tidak diizinkan.',405);
		if (!$this->session->userdata('byr_token') || !hash_equals((string)$this->session->userdata('byr_token'),(string)$this->input->post('byr_token'))) show_error('Sesi formulir tidak valid. Muat ulang halaman.',403);
		$this->db->trans_start();
		$this->db->query('SELECT projid FROM mproj WHERE projid=? FOR UPDATE',array($this->input->post('txt_projid2')));
		$receipt=$this->db->query('SELECT * FROM tkwt WHERE projid=? AND kwtno=? AND sprno=? FOR UPDATE',array($this->input->post('txt_projid2'),$this->input->post('txt_kwtno'),$this->input->post('txt_sprno2')))->row();
		if (!$receipt || $receipt->btl==='1') { $this->db->trans_rollback(); show_error('Kwitansi tidak tersedia atau sudah dibatalkan.',409); return; }
		$balance=$this->db->query("SELECT COALESCE(SUM(deposit-deposit_digunakan),0) saldo FROM tkwt WHERE projid=? AND sprno=? AND btl<>'1'",array($receipt->projid,$receipt->sprno))->row()->saldo;
		if (round(($balance-$receipt->deposit+$receipt->deposit_digunakan)*100)<0) { $this->db->trans_rollback(); show_error('Deposit dari kwitansi ini sudah digunakan. Batalkan pembayaran yang memakai deposit terlebih dahulu.',409); return; }

		/*$kwtno = $this->lib->get_no($this->input->post('txt_projid2'),'KWT');
		
		$kwt = array( 
						'kwtno' => $kwtno,
						'projid' => $this->input->post('txt_projid2'),
						'sprno' => $this->input->post('txt_sprno2'),
						'tipe' => 'BTL',
						'ang' => $this->input->post('txt_ang2'),
						'tgl' => date('Y-m-d'),
						'kwtbtl' => $this->input->post('txt_kwtno'),
						'ket' => $this->input->post('txt_ket2'),
						'total' => $this->lib->convert_idr_to_num($this->input->post('txt_total2')) * -1,
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d')
						);*/

		$kwt = array(
			'btl' => '1',
			'ketbtl' => $this->input->post('txt_ket2'),
			'btlby' => $this->session->userdata('userid'),
			'btldat' => date('Y-m-d')
		);

		$sprp = array(
			'kwtno' => 0,
			'status' => 'BLN',
		);

		//$this->mod_kwt->input_kwt($kwt);

		$this->mod_kwt->update_kwt($this->input->post('txt_kwtno'), $this->input->post('txt_projid2'), $kwt);

		if ($receipt->total>0) $this->db->where('projid',$receipt->projid)->where('sprno',$receipt->sprno)->where('tipe',$receipt->tipe)->where('ang',$receipt->ang)->where('kwtno',$receipt->kwtno)->update('tsprp',$sprp);
		//$this->lib->commit_no($this->input->post('txt_projid2'),$kwtno,'KWT');

		if ($receipt->total>0 && $receipt->tipe == 'UMT') {
			$sprh = $this->mod_spr->get_sprh($this->input->post('txt_sprno2'), $this->input->post('txt_projid2'))->result();
			foreach ($sprh as $row_sprh);

			if ($this->lib->get_kav_status($this->input->post('txt_projid2'), $row_sprh->blok, $row_sprh->no) == 'AKD') {
				$kav = array(
					'status' => 'SP3K',
					'chby' => $this->session->userdata('userid'),
					'chdat' => date('Y-m-d')
				);



				$kavlog = array(
					'projid' => $this->input->post('txt_projid2'),
					'blok' => $row_sprh->blok,
					'no' => $row_sprh->no,
					'sprno' => $this->input->post('txt_sprno2'),
					'status' => 'SP3K',
					'ket' => 'BATAL KWITANSI PENURUNAN KPR',
					'crby' => $this->session->userdata('userid'),
					'crdat' => date('Y-m-d'),
					'crtim' => $this->lib->get_time()
				);

				$this->mod_kav->update_kav($this->input->post('txt_projid2'), $row_sprh->blok, $row_sprh->no, $kav);
				$this->mod_kav->input_kavlog($kavlog);
			}
		}


		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			$this->session->set_flashdata('error', 'Batal pembayaran gagal!');
			redirect('byr/list_byr/' . $this->input->post('txt_projid2') . '/' . $this->input->post('txt_sprno2'));
		} else {
			$this->session->set_flashdata('ok', 'Batal pembayaran sukses');
			redirect('byr/list_byr/' . $this->input->post('txt_projid2') . '/' . $this->input->post('txt_sprno2'));
		}
	}

	function get_spr()
	{
		$sql_get_spr = $this->mod_spr->list_spr($this->input->post('projid'))->result();
		$data["data"] = array();
		foreach ($sql_get_spr as $row) {

			$query_status = $this->mod_kav->get_kav_spr($row->projid, $row->blok, $row->no);
			foreach ($query_status->result()  as $row_status);

			$data["data"][] = array(
				'sprno' => $this->lib->no_spr($row->sprno),
				'blokno' => $row->blok . $row->no,
				'nama' => $row->nama,
				'namstat' => $row_status->namstat,
				'crby' => $row->crby,
				'crdat' => $this->lib->disp_date($row->crdat),
				'pilihan' => '<a href="' . site_url('byr/list_byr/' . $row->projid . '/' . $row->sprno) . '" class="btn btn-primary"><i class="fa fa-search-plus"></i> List Bayar</a>'

			);
		}
		echo json_encode($data);
	}

	function list_spr()
	{
		$this->lib->check_auth($this->session->userdata('roleid'), $this->moid, $this->mid, 'R');

		$data['title'] = 'Tambah Pembayaran > Pilih SPR';
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));

		$this->load->view('frm_spr_byr', $data);
	}

	// start bambang 2021-10-27
	function do_print_byr()
	{
		$sprno = $this->input->post('txt_sprno');
		$kwtno = explode(",", $this->input->post('txt_kwtno'));
		$projid = $this->input->post('txt_projid');

		foreach ($this->mod_proj->edit_proj($projid)->result() as $row_proj);
		$data['nama_project'] = 'Project ' . $row_proj->namproj;

		$data['query'] = $this->db->query("select sprno,kwtno,ket,total,nama,blok,tgl,no from cetak_byr_v where total<>0 and projid = '" . $projid . "' AND sprno = '" . $sprno . "' AND kwtno IN (" . implode(',', $kwtno) . ") order by tgl ");

        // echo "select sprno,kwtno,ket,total from cetak_byr_v where projid = '" . $projid . "' AND sprno = '" . $sprno . "' AND kwtno IN (" . implode(',', $kwtno) . ") ";

		$this->load->library('pdf');
		$this->pdf->load_view('print_byr', $data, 'print_byr');
	}

	function print_byr()
	{
		foreach ($this->mod_proj->edit_proj($this->uri->segment(3))->result() as $row_proj);

		$mtgl1 = $this->uri->segment(4);
		$mtgl2 = $this->uri->segment(5);
		$mbtl = $this->uri->segment(6);

		$data['form_action'] = site_url('byr/do_print_byr');
		$data['main_title'] = 'Cetak Bukti Bank Masuk';
		$data['title'] = 'Project ' . $row_proj->namproj;
		$data['projid'] = $row_proj->projid;
		$data['url_back'] = site_url('byr/do_lap_byr_disp/' . $row_proj->projid . '/' . $mtgl1
		. '/' . $mtgl2 . '/' . $mbtl  );

		$this->load->view('frm_print_byr', $data);
	}
    // end bambang 2021-10-27

}
