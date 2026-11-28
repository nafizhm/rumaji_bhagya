<?php
class Mod_kwt extends CI_Model
{
	/**
	 * Constructor
	 */

	function __construct()
	{
		parent::__construct();
	}


	/* Insialiasi nama table */
	var $t_tkwt = 'tkwt';

	//Rekap kwitansi per tanggal
	function sum_rkp_kwt($projid, $tgl_from, $tgl_to)
	{

		$query = $this->db->query("SELECT SUM(total) as total, SUM(deposit) as deposit, tgl 
		FROM `tkwt` 
		WHERE btl = 0 AND projid = '" . $projid . "' 
		AND tgl BETWEEN '" . $tgl_from . "'
		AND '" . $tgl_to . "' 
		GROUP BY `tgl` 
		ORDER BY `tgl`");
		return $query;
	}

	//Detail kwitansi per tanggal
	function list_rkp_kwt($projid, $tgl)
	{
		$this->db->select("tkwt.kwtno, tkwt.ang, tkwt.tipe, tkwt.sprno, tkwt.projid, 
		tkwt.tgl, tkwt.ket, tkwt.total, tkwt.deposit, tkwt.deposit_digunakan, COALESCE(tkwt.jumlah_bayar,tkwt.total) AS jumlah_bayar, tkwt.byr, tsprh.nama, tsprh.blok, 
		tsprh.no, tsprh.tglhrg, tsprh.hrgstd, tsprh.umk,tkwt.btl,tsprf.hrgtotf,tsprf.hrgtotalf,tkwt.ketbtl,tkwt.tgl_setor,tkwt.verby,tkwt.verdat,tkwt.crdat");
		$this->db->from("tkwt");
		$this->db->join("tsprh", "tsprh.projid = tkwt.projid AND tsprh.sprno = tkwt.sprno");
		$this->db->join("tsprf", "tsprf.projid = tkwt.projid AND tsprf.sprno = tkwt.sprno", "left");
		$this->db->where('tkwt.projid', $projid);
		$this->db->where('tkwt.tgl =', $tgl);
		return $this->db->get();
	}

	function list_jtmpo($projid, $date1 = '', $date2 = '')
	{
		$this->db->select("tsprp.tipe,tsprp.ang,tsprp.jtmp, tsprp.total, tsprh.sprno,tsprh.nama, tsprh.blok, 
		tsprh.no, tsprh.tglhrg, tsprh.hrgstd, tsprh.umk,tsprf.hrgtotf,tsprf.hrgtotalf,mkav.lot");
		$this->db->from("tsprp");
		$this->db->join("tsprh", "tsprh.projid = tsprp.projid AND tsprh.sprno = tsprp.sprno");
		$this->db->join("mkav", "mkav.projid = tsprh.projid AND mkav.blok = tsprh.blok AND mkav.no = tsprh.no ");
		$this->db->join("tsprf", "tsprf.projid = tsprp.projid AND tsprf.sprno = tsprp.sprno", "left");
		$this->db->where('tsprp.projid', $projid);
		$this->db->where('tsprh.status <>', 'BTL');
		$this->db->where('tsprp.status', 'BLN');
		if ($date1 == '' && $date2 == '') {
			$this->db->where('tsprp.jtmp <', date('Y-m-d'));
		} else {
			$this->db->where('tsprp.jtmp >', $date1);
			$this->db->where('tsprp.jtmp <', $date2);
		}

		return $this->db->get();
	}

	function list_byr($projid, $tgl_from, $tgl_to, $btl)
	{
		$this->db->select("tkwt.kwtno, tkwt.ang, tkwt.tipe, tkwt.sprno, tkwt.projid, 
		tkwt.tgl, tkwt.ket, tkwt.total, tkwt.byr, tsprh.nama, tsprh.blok, 
		tsprh.no, tsprh.tglhrg, tsprh.hrgstd, tsprh.umk,tkwt.btl,tsprf.hrgtotf,tsprf.hrgtotalf,tkwt.ketbtl");
		$this->db->from("tkwt");
		$this->db->join("tsprh", "tsprh.projid = tkwt.projid AND tsprh.sprno = tkwt.sprno");
		$this->db->join("tsprf", "tsprf.projid = tkwt.projid AND tsprf.sprno = tkwt.sprno", "left");
		$this->db->where('tkwt.projid', $projid);
		$this->db->where('tkwt.btl =', $btl);
		$this->db->where('tkwt.tgl >=', $tgl_from);
		$this->db->where('tkwt.tgl <=', $tgl_to);
		return $this->db->get();
	}

	function update_kwt($kwtno, $projid, $data)
	{
		$this->db->where('kwtno', $kwtno);
		$this->db->where('projid', $projid);
		$this->db->update($this->t_tkwt, $data);
	}

	function input_kwt($data)
	{
		$this->db->insert($this->t_tkwt, $data);
	}

	function get_kwt_spr($projid, $sprno)
	{
		$this->db->where('projid', $projid);
		$this->db->where('sprno', $sprno);
		$this->db->where('btl <>', '1');
		return $this->db->get($this->t_tkwt);
	}

	function deposit_references($receipt)
	{
		$remaining=(int)round($receipt->deposit_digunakan*100);
		if ($remaining<=0) return array();
		$previous=$this->db->query("SELECT COALESCE(SUM(deposit_digunakan),0) used FROM tkwt WHERE projid=? AND sprno=? AND kwtno<? AND btl<>'1'",array($receipt->projid,$receipt->sprno,$receipt->kwtno))->row()->used;
		$skip=(int)round($previous*100); $numbers=array();
		// Attribute the deposit balance to earlier receipts, oldest receipt first.
		$sources=$this->db->query("SELECT kwtno,deposit FROM tkwt WHERE projid=? AND sprno=? AND kwtno<? AND deposit>0 AND btl<>'1' ORDER BY kwtno",array($receipt->projid,$receipt->sprno,$receipt->kwtno))->result();
		foreach ($sources as $source) {
			$available=(int)round($source->deposit*100);
			$skipped=min($skip,$available); $skip-=$skipped; $available-=$skipped;
			if ($available<=0) continue;
			$numbers[]='DEP-'.(int)$receipt->projid.'-'.str_pad((string)(int)$source->kwtno,6,'0',STR_PAD_LEFT);
			$remaining-=min($available,$remaining);
			if ($remaining<=0) break;
		}
		return $numbers;
	}

	function get_kwt($projid, $kwtno)
	{
		$this->db->where('projid', $projid);
		$this->db->where('kwtno', $kwtno);
		return $this->db->get($this->t_tkwt);
	}

	function get_kwt_btl($projid, $sprno)
	{
		$this->db->where('projid', $projid);
		$this->db->where('sprno', $sprno);
		$this->db->where('tipe', 'BTL');
		$this->db->order_by('kwtno', 'DESC');
		$this->db->limit(1);
		return $this->db->get($this->t_tkwt);
	}
}
