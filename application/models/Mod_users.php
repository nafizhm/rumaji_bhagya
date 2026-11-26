<?php
class Mod_users extends CI_Model {
	/**
	 * Constructor
	 */
	
	function __construct()
	{
		parent::__construct();
	}
	

/* Insialiasi nama table */
	var $t_muser			= 'muser';
	var $t_mprjusr		    = 'mprjusr';
	
	function delete_prjusr($usrid)
	{
		$this->db->where('userid', $usrid);
		$this->db->delete($this->t_mprjusr);
	}
	
	function get_prjusr($usrid,$projid)
	{
		$this->db->where('userid', $usrid);
		$this->db->where('projid', $projid);
		return $this->db->get($this->t_mprjusr);
	}
	
	function input_prjusr($data)
	{
		$this->db->insert_batch($this->t_mprjusr, $data);
	}
	
/* Cek login */
	function check_login($usrid)
	{
		$this->db->where('userid', $usrid);
		return $this->db->get($this->t_muser);
	}



	function update_users($userid,$data)
	{
		$this->db->where('userid', $userid);
		$this->db->update($this->t_muser, $data);
	}

	function input_users($data)
	{
		$this->db->insert($this->t_muser, $data);
	}

	function list_users()
	{
		$this->db->select("muser.userid,muser.namusr,muser.status,mrole.namrol,muser.crby,muser.crdat,muser.chby,muser.chdat");
		$this->db->from("muser");
		$this->db->join("mrole", "mrole.roleid = muser.roleid");
		$this->db->where('muser.roleid !=', 'ADM');
		return $this->db->get();

			/*
			return $this->db->get($this->t_muser);*/
	}

	function edit_users($userid)
	{
			$this->db->where('userid', $userid); 
			return $this->db->get($this->t_muser);
	}

	
	function delete_users($userid)
	{
			$this->db->where('userid', $userid); 
			$this->db->delete($this->t_muser);
	}
	
}
?>