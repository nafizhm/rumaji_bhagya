<?php
class Mod_proj extends CI_Model {
	/**
	 * Constructor
	 */
	
	function __construct()
	{
		parent::__construct();
	}
	

/* Insialiasi nama table */
	var $t_mproj			= 'mproj';
	var $t_tnomor			= 'tnomor';		var $t_mpt			= 'mpt';
	function update_pt($ptid,$data)	{		$this->db->where('ptid', $ptid);		$this->db->update($this->t_mpt, $data);	}	function input_pt($data)	{		$this->db->insert($this->t_mpt, $data);	}	function list_pt()	{		return $this->db->get($this->t_mpt);	}	function edit_pt($ptid)	{			$this->db->where('ptid', $ptid); 			return $this->db->get($this->t_mpt);	}	
	function input_nomor($data)
	{
		$this->db->insert($this->t_tnomor, $data);
	}
	
	function update_proj($projid,$data)
	{
		$this->db->where('projid', $projid);
		$this->db->update($this->t_mproj, $data);
	}

	function input_proj($data)
	{
		$this->db->insert($this->t_mproj, $data);
	}

	function list_proj()
	{
		$this->db->select("mproj.projid,mproj.namproj,mproj.idext,mproj.status,mproj.crby,mproj.crdat,mproj.chby,
		mproj.chdat,mstat.namstat");
		$this->db->from("mproj");
		$this->db->join("mstat", "mstat.statgrp = 'PRJ' AND mstat.status = mproj.status");
		return $this->db->get();
		//return $this->db->get($this->t_mproj);
	}
	
	function list_proj_akt() //List project aktif
	{
		$this->db->where('status', 'AKT'); 
		return $this->db->get($this->t_mproj);
	}
	
	function edit_proj($projid)
	{
			$this->db->where('projid', $projid); 
			return $this->db->get($this->t_mproj);
	}

	
	function delete_proj($projid)
	{
			$this->db->where('projid', $projid); 
			$this->db->delete($this->t_mproj);
	}
	
}
?>