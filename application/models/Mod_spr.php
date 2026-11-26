<?php
class Mod_spr extends CI_Model {
	/**
	 * Constructor
	 */
	
	function __construct()
	{
		parent::__construct();
	}
	

/* Insialiasi nama table */
	var $t_tsprh			= 'tsprh';
	var $t_tsprp			= 'tsprp';
	var $t_tsprf			= 'tsprf';
	var $t_tsprf_h			= 'tsprf_h';
	var $t_tsprd			= 'tsprd';
	var $t_tsprb			= 'tsprb';
	
	function update_sprb($sprno,$projid,$data)
	{
		$this->db->where('sprno', $sprno);
		$this->db->where('projid', $projid);
		$this->db->update($this->t_tsprb, $data);
	}
	
	function list_sprb($sprno,$projid)
	{
		$this->db->where('sprno', $sprno);
		$this->db->where('projid', $projid); 
		
		return $this->db->get($this->t_tsprb);
	}
	
	function input_sprb($data)
	{
		$this->db->insert($this->t_tsprb, $data);
	}
	
	function get_sprpb_t($sprno,$projid)
	{
		$this->db->where('sprno', $sprno);
		$this->db->where('projid', $projid);
		$this->db->where('ang', 0);
		$this->db->where('tipe', 'UBT');
		return $this->db->get($this->t_tsprp);
	}
	
	function get_sprb($sprno,$projid)
	{
		$this->db->where('sprno', $sprno);
		$this->db->where('projid', $projid);
		return $this->db->get($this->t_tsprb);
	}
	
	function list_spr_ptn($projid)
	{
		$this->db->select("tsprh.sprno,tsprh.projid,tsprh.blok,tsprh.no,tsprh.nama,mkav.lot,tsprh.telp,mkav.ltstd,mkav.tipe,mfit.namfit
		,tsprh.hrgtot,tsprh.umtot,tsprh.byttp,tsprf.hrgtotalf,tsprh.crdat");
		$this->db->from("tsprh");
		$this->db->join("mkav", "mkav.projid = tsprh.projid 
		AND mkav.blok = tsprh.blok
		AND mkav.no = tsprh.no");
				
		$this->db->join("tsprf", "tsprf.projid = tsprh.projid  AND tsprf.sprno = tsprh.sprno","left");			
		$this->db->join("mkavf", "mkavf.projid = tsprh.projid  AND mkavf.blok = tsprh.blok AND mkavf.no = tsprh.no","left");	
		$this->db->join("mfit", "mfit.ftid = mkavf.ftid","left");

		$this->db->where('tsprh.projid', $projid);
		$this->db->where('tsprh.status <>', 'BTL');

		return $this->db->get();
	}
	
	function update_sprh($sprno,$projid,$data)
	{
		$this->db->where('sprno', $sprno);
		$this->db->where('projid', $projid);
		$this->db->update($this->t_tsprh, $data);
	}
	
	function update_sprf($sprno,$projid,$data)
	{
		$this->db->where('sprno', $sprno);
		$this->db->where('projid', $projid);
		$this->db->update($this->t_tsprf, $data);
	}
	
	function delete_sprp($sprno,$projid)
	{
		$this->db->where('sprno', $sprno);
		$this->db->where('projid', $projid);
		$this->db->delete($this->t_tsprp);
	}
	
	function cek_sprp($sprno,$projid,$tipe,$ang)
	{
		$this->db->where('sprno', $sprno);
		$this->db->where('projid', $projid);
		$this->db->where('tipe', $tipe);
		$this->db->where('ang', $ang);
		return $this->db->get($this->t_tsprp);
	}
	
	function update_sprp($projid,$sprno,$tipe,$ang,$data)
	{
		$this->db->where('projid', $projid);
		$this->db->where('sprno', $sprno);
		$this->db->where('tipe', $tipe);
		$this->db->where('ang', $ang);
		$this->db->update($this->t_tsprp, $data);
	}
	
	function get_list_sprp($projid,$sprno)
	{
		$this->db->where('projid', $projid);
		$this->db->where('sprno', $sprno);
//$this->db->order_by('ang', 'ASC');
		$this->db->order_by('jtmp', 'ASC');
		return $this->db->get($this->t_tsprp);
	}
	
	function input_sprd($data)
	{
		$this->db->insert($this->t_tsprd, $data);
	}
	
	function list_sprd($sprno,$projid)
	{
		$this->db->where('sprno', $sprno);
		$this->db->where('projid', $projid); 
		
		return $this->db->get($this->t_tsprd);
	}
	
	function get_sprd($fid)
	{
		$this->db->where('fid', $fid);
		return $this->db->get($this->t_tsprd);
	}
	
	
	function delete_sprd($fid)
	{
		/*$this->db->where('projid', $projid); 
		$this->db->where('sprno', $sprno);*/
		$this->db->where('fid', $fid);
		$this->db->delete($this->t_tsprd);
	}
	
	function input_sprh($data)
	{
		$this->db->insert($this->t_tsprh, $data);
	}
	
	function input_sprp($data)
	{
		$this->db->insert_batch($this->t_tsprp, $data);
	}
	
	function input_sprf($data)
	{
		$this->db->insert($this->t_tsprf, $data);
	}
	
	function list_spr($projid)
	{
		$this->db->where('projid', $projid);
		return $this->db->get($this->t_tsprh);
	}
	
	function list_spr2($projid, $status = '', $limit = NULL, $offset = 0, $search = '')
	{
		$this->db->select("tsprh.sprno,tsprh.status,tsprh.projid,
		tsprh.blok,tsprh.no,tsprh.nama,
		tsprh.crby,tsprh.crby,tsprh.crdat,mkav.lot");
		$this->db->from("tsprh");
		$this->db->join("mkav", "mkav.projid = tsprh.projid 
		AND mkav.blok = tsprh.blok
		AND mkav.no = tsprh.no");
		$this->db->where('tsprh.projid', $projid);
		$this->filter_spr_list($status, $search);
		$this->db->order_by('tsprh.crdat', 'DESC');
		$this->db->order_by('tsprh.sprno', 'DESC');
		if ($limit !== NULL) {
			$this->db->limit($limit, $offset);
		}
		return $this->db->get();
		
	}

	private function filter_spr_list($status, $search)
	{
		if ($status === 'batal') {
			$this->db->where('tsprh.status', 'BTL');
		} elseif ($status === 'proses') {
			$this->db->where('tsprh.status', 'PRO');
		}
		if ($search !== '') {
			$this->db->group_start()->like('tsprh.sprno', $search)->or_like('tsprh.nama', $search)->or_like('tsprh.blok', $search)->or_like('mkav.lot', $search)->group_end();
		}
	}

	function summary_spr_list($projid, $search = '')
	{
		$this->db->select("COUNT(*) AS total, COALESCE(SUM(tsprh.status = 'BTL'), 0) AS batal, COALESCE(SUM(tsprh.status = 'PRO'), 0) AS proses", FALSE);
		$this->db->from('tsprh');
		$this->db->join('mkav', 'mkav.projid = tsprh.projid AND mkav.blok = tsprh.blok AND mkav.no = tsprh.no');
		$this->db->where('tsprh.projid', $projid);
		$this->filter_spr_list('', $search);
		return $this->db->get()->row();
	}
	
	function input_sprf_h($data)
	{
		$this->db->insert_batch($this->t_tsprf_h, $data);
	}
	
	function get_sprh($sprno,$projid)
	{
		$this->db->select("mproj.namproj,mproj.projid,mkav.lot,mkav.ltstd,mkav.tipe,
		tsprh.blok,tsprh.no,tsprh.sprno,tsprh.nama,tsprh.ktp,tsprh.npwp,tsprh.alm,tsprh.telp
		,tsprh.telp2,tsprh.email,tsprh.hrgstd,tsprh.tglhrg,tsprh.distp,tsprh.dis,tsprh.hrgtot
		,tsprh.utj,tsprh.umk,tsprh.umbyr,tsprh.umccl,tsprh.kpr,tsprh.kpr_ccl,tsprh.status
		,tsprh.crby,tsprh.crdat,tsprh.chby,tsprh.chdat,mstat.namstat,tsprh.byttp,tsprh.btldat,tsprh.bankid,tsprh.accno,tsprh.namacc,tsprh.totsetor,tsprh.totbyr,tsprh.byadm,tsprh.cclns,tsprh.umdis,tsprh.umdistp,tsprh.umtot,tsprh.btlstat,tsprh.ppn,tsprh.umt,tsprh.umtbyr,tsprh.umtccl
		,tsprh.umtcclns,mkav.tahun,mkav.jenis");
		$this->db->from("tsprh");
		$this->db->join("mproj", "mproj.projid = tsprh.projid");
		$this->db->join("mkav", "mkav.projid = tsprh.projid 
		AND mkav.blok = tsprh.blok
		AND mkav.no = tsprh.no");
		$this->db->join("mstat", "mstat.status = tsprh.status AND mstat.statgrp = 'SPR' ");
		$this->db->where('tsprh.sprno', $sprno); 
		$this->db->where('tsprh.projid', $projid); 
		return $this->db->get();
		
	}
	
	function get_sprf($sprno,$projid)
	{
		$this->db->where('sprno', $sprno);
		$this->db->where('projid', $projid);
		return $this->db->get($this->t_tsprf);
	}
	
	function get_sprf_h($sprno,$projid)
	{
		$this->db->select("tsprf_h.hrg,tsprf_h.tglhrg,tsprf_h.ltnh,mfit.namfit,tsprf_h.ftid,tsprf_h.ftid");
		$this->db->from("tsprf_h");
		$this->db->join("mfit", "mfit.ftid = tsprf_h.ftid");
		$this->db->where('tsprf_h.sprno', $sprno); 
		$this->db->where('tsprf_h.projid', $projid); 
		return $this->db->get();
		
	}
	
	function get_sprp($sprno,$projid,$tipe)
	{
		$this->db->where('sprno', $sprno);
		$this->db->where('projid', $projid);
		$this->db->where('tipe', $tipe);
		return $this->db->get($this->t_tsprp);
	}
	
	function get_sprp_t($sprno,$projid)
	{
		$this->db->where('sprno', $sprno);
		$this->db->where('projid', $projid);
		$this->db->where('ang', 0);
		$this->db->where('tipe <>', 'UFT');
		return $this->db->get($this->t_tsprp);
	}
	
	function get_sprpf_t($sprno,$projid)
	{
		$this->db->where('sprno', $sprno);
		$this->db->where('projid', $projid);
		$this->db->where('ang', 0);
		$this->db->where('tipe', 'UFT');
		return $this->db->get($this->t_tsprp);
	}
}
?>
