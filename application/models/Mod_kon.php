<?php
class Mod_kon extends CI_Model {
	/**
	 * Constructor
	 */
	
	function __construct()
	{
		parent::__construct();
	}
	

/* Insialiasi nama table */
	var $t_mkon			= 'mkon';
	
	function input_mkon($data)
	{
		$this->db->insert($this->t_mkon, $data);
	}
	
	function edit_mkon($konid)
	{
			$this->db->where('konid', $konid); 
			return $this->db->get($this->t_mkon);
	}

	
	function delete_mkon($konid)
	{
			$this->db->where('konid', $konid); 
			$this->db->delete($this->t_mkon);
			
	}
	
	function update_mkon($konid,$data)
	{
		$this->db->where('konid', $konid);
		$this->db->update($this->t_mkon, $data);
	}

	function list_mkon()
	{
		return $this->db->get($this->t_mkon);
	}
	
}
?>