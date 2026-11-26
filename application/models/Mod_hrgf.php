<?php
class Mod_hrgf extends CI_Model {
	/**
	 * Constructor
	 */
	
	function __construct()
	{
		parent::__construct();
	}
	

/* Insialiasi nama table */
	var $t_mhrgf		= 'mhrgf';
	var $t_thrgflog		= 'thrgflog';
	var $t_slsman		= 'msalesman';
	
	
	function update_hrgf($projid,$ftid,$tgl,$data)
	{
		$this->db->where('projid', $projid);
		$this->db->where('ftid', $ftid);
		$this->db->where('tgl', $tgl);
		$this->db->update($this->t_mhrgf, $data);
	}

	function input_hrgf($data)
	{
		$this->db->insert($this->t_mhrgf, $data);
	}

	function input_slsman($data)
	{
		$this->db->insert($this->t_slsman, $data);
	}

	function list_hrgf($projid)
	{
		$this->db->select("mhrgf.projid,mhrgf.ftid,mhrgf.hrg,mhrgf.tgl,mhrgf.crby,mhrgf.crdat,mhrgf.chby,mhrgf.chdat,mfit.namfit");
		$this->db->from("mhrgf");
		$this->db->join("mfit", "mfit.ftid = mhrgf.ftid");
		$this->db->where('mhrgf.projid', $projid);
		return $this->db->get();
		
	}

	function edit_hrgf($projid,$ftid,$tgl)
	{
		$this->db->where('projid', $projid);
		$this->db->where('ftid', $ftid);
		$this->db->where('tgl', $tgl);
		return $this->db->get($this->t_mhrgf);
	}

	
	function input_hrgflog($data)
	{
		$this->db->insert($this->t_thrgflog, $data);
	}
	
	function list_hrgflog($projid,$ftid,$tgl)
	{
		$this->db->where('projid', $projid);
		$this->db->where('ftid', $ftid);
		$this->db->where('tgl', $tgl);
		$this->db->order_by('crdat', 'DESC');
		$this->db->order_by('crtim', 'DESC');
		return $this->db->get($this->t_thrgflog);
	}
	
	function get_hrgf($projid,$ftid)
	{
		$this->db->where('projid', $projid);
		$this->db->where('ftid', $ftid);
		$this->db->where('tgl <=', date('Y-m-d'));
		$this->db->order_by('tgl', 'DESC');
		return $this->db->get($this->t_mhrgf,1,0);
		
	}
	
	

}
?>