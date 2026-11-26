<?php
class Mod_hrg extends CI_Model {
	/**
	 * Constructor
	 */
	
	function __construct()
	{
		parent::__construct();
	}
	

/* Insialiasi nama table */
	var $t_mhrg			= 'mhrg';
	var $t_thrglog		= 'thrglog';
	
	function delete_hrg($projid,$lot,$tipe,$tgl)
	{
		$this->db->where('projid', $projid);
		$this->db->where('lot', $lot);
		$this->db->where('tipe', $tipe);
		$this->db->where('tgl', $tgl);
		$this->db->delete($this->t_mhrg);
	}
	
	function delete_hrglog($projid,$lot,$tipe,$tgl)
	{
		$this->db->where('projid', $projid);
		$this->db->where('lot', $lot);
		$this->db->where('tipe', $tipe);
		$this->db->where('tgl', $tgl);
		$this->db->delete($this->t_thrglog);
	}
	
	function update_hrg($projid,$lot,$tipe,$tgl,$data)
	{
		$this->db->where('projid', $projid);
		$this->db->where('lot', $lot);
		$this->db->where('tipe', $tipe);
		$this->db->where('tgl', $tgl);
		$this->db->update($this->t_mhrg, $data);
	}

	function input_hrg($data)
	{
		$this->db->insert($this->t_mhrg, $data);
	}

	function list_hrg($projid)
	{
		$this->db->where('projid', $projid);
		return $this->db->get($this->t_mhrg);
	}

	function edit_hrg($projid,$lot,$tipe,$tgl)
	{
		$this->db->where('projid', $projid);
		$this->db->where('lot', $lot);
		$this->db->where('tipe', $tipe);
		$this->db->where('tgl', $tgl);
		return $this->db->get($this->t_mhrg);
	}

	
	function input_hrglog($data)
	{
		$this->db->insert($this->t_thrglog, $data);
	}
	
	function list_hrglog($projid,$lot,$tipe,$tgl)
	{
		$this->db->where('projid', $projid);
		$this->db->where('lot', $lot);
		$this->db->where('tipe', $tipe);
		$this->db->where('tgl', $tgl);
		$this->db->order_by('crdat', 'DESC');
		$this->db->order_by('crtim', 'DESC');
		return $this->db->get($this->t_thrglog);
	}
	
	function get_hrg($projid,$lot,$tipe)
	{
		$this->db->where('projid', $projid);
		$this->db->where('lot', $lot);
		$this->db->where('tipe', $tipe);
		$this->db->where('tgl <=', date('Y-m-d'));
		$this->db->order_by('tgl', 'DESC');
		return $this->db->get($this->t_mhrg,1,0);
		
	}
	

}
?>