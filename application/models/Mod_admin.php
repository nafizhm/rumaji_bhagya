<?php
class Mod_admin extends CI_Model {
	/**
	 * Constructor
	 */
	
	function __construct()
	{
		parent::__construct();
	}
	

/* Insialiasi nama table */
	var $t_mstat			= 'mstat';
	var $t_mrole			= 'mrole';
	var $t_mtipe			= 'mtipe';
	var $t_mfit				= 'mfit';
	var $t_mauth		    = 'mauth';
	var $t_mmenu		    = 'mmenu';
	
	function get_menu()
	{
		return $this->db->get($this->t_mmenu);
	}
	
	function list_mauth($roleid)
	{
		$this->db->select("mauth.roleid,mauth.moid,mauth.mid,mauth.c,mauth.r,mauth.u,mauth.d,mmod.namod,mmenu.mdesc");
		$this->db->from("mauth");
		$this->db->join("mmod", "mmod.moid = mauth.moid");
		$this->db->join("mmenu", "mmenu.mid = mauth.mid AND mmenu.moid = mauth.moid");
		$this->db->join("mrole", "mrole.roleid = mauth.roleid");
		$this->db->where('mauth.roleid =', $roleid);
		$this->db->order_by('mauth.moid', 'ASC');
		return $this->db->get();
		
	}
	
	function input_mauth($data)
	{
		$this->db->insert_batch($this->t_mauth, $data);
	}
	
	function update_mauth($roleid,$moid,$mid,$data)
	{
		$this->db->where('roleid', $roleid);
		$this->db->where('moid', $moid);
		$this->db->where('mid', $mid);
		$this->db->update($this->t_mauth, $data);
	}
	
	function input_role($data)
	{
		$this->db->insert($this->t_mrole, $data);
	}
	
	function edit_role($roleid)
	{
		$this->db->where('roleid', $roleid);
		return $this->db->get($this->t_mrole);
	}
	
	function update_role($roleid,$data)
	{
		$this->db->where('roleid', $roleid);
		$this->db->update($this->t_mrole, $data);
	}
	
	function get_role()
	{
		$this->db->where('roleid <>', 'ADM');
		return $this->db->get($this->t_mrole);
	}
	
	function get_stat($statgrp)
	{
		$this->db->where('statgrp', $statgrp);
		return $this->db->get($this->t_mstat);
	}
	
	function get_tipe()
	{
		return $this->db->get($this->t_mtipe);
	}
	
	function get_fit()
	{
		return $this->db->get($this->t_mfit);
	}
	

}
?>