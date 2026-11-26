<?php
class Mod_bank extends CI_Model {
	/**
	 * Constructor
	 */
	
	function __construct()
	{
		parent::__construct();
	}
	

/* Insialiasi nama table */
	var $t_mbankacc			= 'mbankacc';
	var $t_mbank			= 'mbank';
	
	function list_bank()
	{
		return $this->db->get($this->t_mbank);
	}
	
	function input_bankacc($data)
	{
		$this->db->insert($this->t_mbankacc, $data);
	}
	
	function get_bankacc_kas($projid,$kas)
	{
		$this->db->select("mbankacc.projid,mbankacc.bankid, mbankacc.accno, mbankacc.namacc, mbankacc.crby, mbankacc.crdat,mbankacc.crby,mbankacc.chby,mbankacc.chdat,mbank.nambank");
		$this->db->from("mbankacc");
		$this->db->join("mbank", "mbank.bankid = mbankacc.bankid");
		$this->db->where("mbankacc.projid",$projid);
		$this->db->where("mbankacc.kas",$kas);
		return $this->db->get();
	}
	
	function get_bankacc($projid)
	{
		$this->db->select("mbankacc.projid,mbankacc.bankid, mbankacc.accno, mbankacc.namacc, mbankacc.crby, mbankacc.crdat,mbankacc.crby,mbankacc.chby,mbankacc.chdat,mbank.nambank,mbankacc.kas");
		$this->db->from("mbankacc");
		$this->db->join("mbank", "mbank.bankid = mbankacc.bankid");
		$this->db->where("projid",$projid);
		return $this->db->get();
	}

function get_bankacc2($projid,$kas)
	{
		$this->db->select("mbankacc.projid,mbankacc.bankid, mbankacc.accno, mbankacc.namacc, mbankacc.crby, mbankacc.crdat,mbankacc.crby,mbankacc.chby,mbankacc.chdat,mbank.nambank,mbankacc.kas");
		$this->db->from("mbankacc");
		$this->db->join("mbank", "mbank.bankid = mbankacc.bankid");
		$this->db->where("projid",$projid);
		$this->db->where("mbankacc.kas",$kas);
		return $this->db->get();
	}

	function list_bankacc()
	{
		$this->db->select("mbankacc.projid,mbankacc.bankid, mbankacc.accno, mbankacc.namacc, mbankacc.crby, mbankacc.crdat,mbankacc.crby,mbankacc.chby,mbankacc.chdat,mbank.nambank,mproj.namproj,mbankacc.kas");
		$this->db->from("mbankacc");
		$this->db->join("mbank", "mbank.bankid = mbankacc.bankid");
		$this->db->join("mproj", "mproj.projid = mbankacc.projid");
		return $this->db->get();
	}

	function edit_bankacc($bankid,$accno,$projid)
	{
			$this->db->where('bankid', $bankid); 
			$this->db->where('accno', $accno); 
			$this->db->where('projid', $projid); 
			return $this->db->get($this->t_mbankacc);
	}

	
	function delete_bankacc($bankid,$accno,$projid)
	{
			$this->db->where('bankid', $bankid); 
			$this->db->where('accno', $accno); 
			$this->db->where('projid', $projid); 
			$this->db->delete($this->t_mbankacc);
	}
	
	function update_bankacc($bankid,$accno,$projid,$data)
	{
			$this->db->where('bankid', $bankid); 
			$this->db->where('accno', $accno); 
			$this->db->where('projid', $projid); 
			$this->db->update($this->t_mbankacc,$data);
	}
	
}
?>