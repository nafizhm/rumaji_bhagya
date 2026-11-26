<?php
class Mod_kav extends CI_Model {
	/**
	 * Constructor
	 */
	
	function __construct()
	{
		parent::__construct();
	}
	

/* Insialiasi nama table */
	var $t_mkav			= 'mkav';
	var $t_mkavf	    = 'mkavf';
	var $t_tkavlog		= 'tkavlog';
	
	function list_kav_bylot($projid,$lot)
	{
		$this->db->where('projid', $projid); 
		$this->db->where('lot', $lot);
		return $this->db->get($this->t_mkav);
	}
	
	function update_kav($projid,$blok,$no,$data)
	{
		$this->db->where('projid', $projid);
		$this->db->where('blok', $blok);
		$this->db->where('no', $no);
		$this->db->update($this->t_mkav, $data);
	}

	function input_kav($data)
	{
		$this->db->insert($this->t_mkav, $data);
	}

	function list_kav($projid)
	{
		$this->db->select("mkav.projid,mkav.blok,mkav.no,mkav.lot,mkav.ltstd,mkav.tipe,mkav.crby,
		mkav.crdat,mkav.chby, mkav.chdat,mstat.namstat,mkav.pkav,mkav.tahun,mkav.jenis");
		$this->db->from("mkav");
	//	$this->db->join("mstatgrp", "mstatgrp.statgrp = 'KAV'");
	//	$this->db->join("mstat", "mstat.status = mkav.status");
		$this->db->join("mstat", "mstat.statgrp = 'KAV' AND mstat.status = mkav.status");
		$this->db->where('mkav.projid', $projid);
		return $this->db->get();
	}

	function edit_kav($projid,$blok,$no)
	{
		$this->db->where('projid', $projid);
		$this->db->where('blok', $blok);
		$this->db->where('no', $no);
		return $this->db->get($this->t_mkav);
	}

	
	function delete_kav($projid,$blok,$no)
	{
		$this->db->where('projid', $projid); 
		$this->db->where('blok', $blok);
		$this->db->where('no', $no);
		$this->db->delete($this->t_mkav);
	}
	
	function input_kavlog($data)
	{
		$this->db->insert($this->t_tkavlog, $data);
	}
	
	function list_kavlog($projid,$blok,$no)
	{
		$this->db->where('projid', $projid); 
		$this->db->where('blok', $blok);
		$this->db->where('no', $no);
		$this->db->order_by('crdat', 'DESC');
		$this->db->order_by('crtim', 'DESC');
		return $this->db->get($this->t_tkavlog);
	}
	
	function delete_kavlog($projid,$blok,$no)
	{
		$this->db->where('projid', $projid); 
		$this->db->where('blok', $blok);
		$this->db->where('no', $no);
		$this->db->delete($this->t_tkavlog);
	}
	
	function input_kavf($data)
	{
		$this->db->insert_batch($this->t_mkavf, $data);
	}
	
	function list_kavf($projid,$blok,$no)
	{
		$this->db->select("mfit.namfit,mfit.ftid,mkavf.ltnh");
		$this->db->from("mkavf");
		$this->db->join("mfit", "mfit.ftid = mkavf.ftid");
		$this->db->where('mkavf.projid', $projid); 
		$this->db->where('mkavf.blok', $blok);
		$this->db->where('mkavf.no', $no);
		return $this->db->get();
	}
	
	function delete_kavf($projid,$blok,$no)
	{
		$this->db->where('projid', $projid); 
		$this->db->where('blok', $blok);
		$this->db->where('no', $no);
		$this->db->delete($this->t_mkavf);
	}
	
	function get_lot_proj($projid)
	{
		$this->db->select("lot");
		$this->db->from("mkav");
		$this->db->where('projid', $projid); 
		$this->db->group_by('lot');
		return $this->db->get();
	}
	
	function get_kav_proj($projid,$lot)
	{
		$stat = array('BLA', 'BTL');
		
		$this->db->select("mkav.projid,mkav.blok,mkav.no,mkav.lot,mkav.ltstd,mkav.tipe,mkav.crby,
		mkav.crdat,mkav.chby, mkav.chdat,mstat.namstat");
		$this->db->from("mkav");
		$this->db->join("mstat", "mstat.statgrp = 'KAV' AND mstat.status = mkav.status");
		$this->db->where('mkav.projid', $projid);
		$this->db->where('mkav.lot', $lot); 
		$this->db->where_in('mkav.status', $stat); 
		//$this->db->or_where('mkav.status', 'BTL');
		return $this->db->get();
	}
	
	function get_kav_spr($projid,$blok,$no)
	{
		$this->db->select("mproj.namproj,mproj.projid,mkav.blok,mkav.no,mkav.lot,mkav.ltstd,mkav.tipe,mstat.namstat,mkav.jenis");
		$this->db->from("mkav");
		$this->db->join("mproj", "mproj.projid = mkav.projid");
		$this->db->join("mstatgrp", "mstatgrp.statgrp = 'KAV'");
		$this->db->join("mstat", "mstat.status = mkav.status");
		$this->db->where('mkav.projid', $projid); 
		$this->db->where('mkav.blok', $blok);
		$this->db->where('mkav.no', $no);
		return $this->db->get();
	}
}
?>