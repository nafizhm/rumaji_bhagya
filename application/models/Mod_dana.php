<?php
class Mod_dana extends CI_Model {
	/**
	 * Constructor
	 */
	
	function __construct()
	{
		parent::__construct();
	}
	

/* Insialiasi nama table */
	var $t_tdana = 'tdana';
	var $t_tdnlog = 'tdnlog';
	
	function list_dana_app2($projid,$roleid)
	{
		
		$this->db->select("mpa.nampa, mby.namby, mproj.namproj, mproj.projid, tdana.reqno, 
		tdana.spkno, tdana.total, tdana.ket, tdana.crby, tdana.crdat,tdana.week,tdana.mon,tdana.year");
		$this->db->from("tdana");
		$this->db->join("mproj", "mproj.projid = tdana.projid");
		$this->db->join("mpa", "mpa.paid = tdana.paid");
		$this->db->join("mby", "mby.paid = mpa.paid AND mby.byid = tdana.byid");
		$this->db->where('tdana.projid', $projid); 
		if($roleid == 'FIN')
		{
			$stat = array('P01','P02','A03');
			$this->db->where_in('tdana.status', $stat); 
		}
		/*
		elseif($roleid == 'AUD' || $roleid == 'KOM')
		{
			$this->db->where('tdana.status', 'P02'); 
		}*/
		else
		{
			$this->db->where('tdana.status', 'A01'); 
		}
		return $this->db->get();
		
	}
	
	function edit_dana_app($projid,$reqno)
	{
		$this->db->select("mpa.nampa, mby.namby, mproj.namproj, mproj.projid, tdana.reqno, 
		tdana.spkno, tdana.total, tdana.ket, tdana.crby, tdana.chby");
		$this->db->from("tdana");
		$this->db->join("mproj", "mproj.projid = tdana.projid");
		$this->db->join("mpa", "mpa.paid = tdana.paid");
	//	$this->db->join("mby", "mby.byid = tdana.byid");
	$this->db->join("mby", "mby.paid = mpa.paid AND mby.byid = tdana.byid");
		$this->db->where('tdana.projid', $projid); 
	    $this->db->where('tdana.reqno', $reqno); 
		return $this->db->get();
		
	}
	
	function list_dana_app($projid,$roleid)
	{
		if($roleid == 'FIN')
		{
			$this->db->where('status', 'P01'); 
		}
		else
		{
			$this->db->where('status', 'A01'); 
		}
		$this->db->where('projid', $projid); 
		return $this->db->get($this->t_tdana);
	}
	
	function input_dnlog($data)
	{
		$this->db->insert($this->t_tdnlog, $data);
	}
	
	function list_dnlog($projid,$reqno)
	{
		$this->db->where('projid', $projid); 
		$this->db->where('reqno', $reqno); 
		return $this->db->get($this->t_tdnlog);
	}
	
	function input_dana($data)
	{
		$this->db->insert($this->t_tdana, $data);
	}
	
	function edit_dana($projid,$reqno)
	{
			$this->db->where('projid', $projid); 
			$this->db->where('reqno', $reqno); 
			return $this->db->get($this->t_tdana);
	}

	
	function delete_dana($projid,$reqno)
	{
			$this->db->where('projid', $projid); 
			$this->db->where('reqno', $reqno); 
			$this->db->delete($this->t_tdana);
			
	}
	
	function update_dana($projid,$reqno,$data)
	{
		$this->db->where('projid', $projid); 
		$this->db->where('reqno', $reqno); 
		$this->db->update($this->t_tdana, $data);
	}

	function list_dana($projid)
	{
		$this->db->where('projid', $projid); 
		return $this->db->get($this->t_tdana);
	}
	
	function update_dana_byspk($projid,$spkno,$data)
	{
		$this->db->where('projid', $projid); 
		$this->db->where('spkno', $spkno); 
		$this->db->update($this->t_tdana, $data);
	}
	
}
?>