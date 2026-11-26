<?php
class Mod_ang extends CI_Model {
	/**
	 * Constructor
	 */
	
	function __construct()
	{
		parent::__construct();
	}
	

/* Insialiasi nama table */
	var $t_mpa			= 'mpa';
	var $t_mby			= 'mby';
	var $t_mang			= 'mang';
	var $t_tanglog		= 'tanglog';

/*--------------------- Anggaran ----------------------*/

function ang_mg($projid,$mon,$mg,$thn)
	{
		$this->db->select("mpa.nampa, mby.namby, mproj.namproj, mproj.projid, tdana.reqno, 
		tdana.spkno, tdana.total, tdana.ket, tdana.crby, tdana.crdat, tspk.konid, tdana.totalbyr,tdana.mon,tdana.week,tdana.year,tdana.bankid,tdana.accno,tdana.nocek");
		$this->db->from("tdana");
		$this->db->join("mproj", "mproj.projid = tdana.projid");
		$this->db->join("mpa", "mpa.paid = tdana.paid");
		$this->db->join("mby", "mby.paid = mpa.paid AND mby.byid = tdana.byid");
		$this->db->join("tspk", "tspk.projid = tdana.projid AND tspk.spkno = tdana.spkno");
		$this->db->where('tdana.projid', $projid); 
	    $this->db->where('tdana.mon', $mon); 
		if($mg <> 0)
		{
		$this->db->where('tdana.week', $mg);
		}
		$this->db->where('tdana.year', $thn); 
		$this->db->where('tdana.status', 'A02'); 
		return $this->db->get();
		
	}
	
function input_mang($data)
	{
		$this->db->insert($this->t_mang, $data);
	}
	
function edit_ang($projid,$paid,$byid)
	{
			$this->db->where('projid', $projid); 
			$this->db->where('paid', $paid); 
			$this->db->where('byid', $byid); 
			return $this->db->get($this->t_mang);
	}

	function update_mang($projid,$paid,$byid,$data)
	{
		$this->db->where('projid', $projid); 
		$this->db->where('paid', $paid); 
		$this->db->where('byid', $byid); 
		$this->db->update($this->t_mang, $data);
	}
	
	function input_tanglog($data)
	{
		$this->db->insert($this->t_tanglog, $data);
	}
	
	function list_tanglog($projid,$paid,$byid)
	{
		$this->db->where('projid', $projid); 
		$this->db->where('paid', $paid); 
		$this->db->where('byid', $byid); 
		$this->db->order_by('crdat', 'DESC');
		$this->db->order_by('crtim', 'DESC');
		return $this->db->get($this->t_tanglog);
	}
/*--------------------- Pos anggaran ----------------------*/

	function input_mpa($data)
	{
		$this->db->insert($this->t_mpa, $data);
	}
	
	function edit_mpa($paid)
	{
			$this->db->where('paid', $paid); 
			return $this->db->get($this->t_mpa);
	}

	
	function delete_mpa($paid)
	{
			$this->db->where('paid', $paid); 
			$this->db->delete($this->t_mpa);
			
	}
	
	function update_mpa($paid,$data)
	{
		$this->db->where('paid', $paid);
		$this->db->update($this->t_mpa, $data);
	}

	function list_mpa()
	{
		$this->db->order_by("paid", "ASC");
		return $this->db->get($this->t_mpa);
	}
	
/*--------------------- Biaya ----------------------*/
	
	function input_mby($data)
	{
		$this->db->insert($this->t_mby, $data);
	}
	
	function edit_mby($paid,$byid)
	{
			$this->db->where('paid', $paid); 
			$this->db->where('byid', $byid); 
			return $this->db->get($this->t_mby);
	}

	
	function delete_mby($paid,$byid)
	{
			$this->db->where('paid', $paid); 
			$this->db->where('byid', $byid); 
			$this->db->delete($this->t_mby);
			
	}
	
	function update_mby($paid,$byid,$data)
	{
		$this->db->where('paid', $paid);
		$this->db->where('byid', $byid); 
		$this->db->update($this->t_mby, $data);
	}

	function list_mby($paid)
	{
		$this->db->where('paid', $paid); 
		return $this->db->get($this->t_mby);
	}
	
	function get_mby_projid($paid,$projid)
	{
		$this->db->select("mang.total, mby.namby, mby.byid");
		$this->db->from("mang");
		$this->db->join("mby", "mby.paid = mang.paid AND mby.byid = mang.byid");
		$this->db->where('mby.paid', $paid);
		$this->db->where('mang.projid', $projid);
		return $this->db->get();
	}
	
/*--------------------- Pos anggaran ----------------------*/

}
?>