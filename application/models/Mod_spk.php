<?php
class Mod_spk extends CI_Model {
	/**
	 * Constructor
	 */
	
	function __construct()
	{
		parent::__construct();
	}
	

/* Insialiasi nama table */
	var $t_tspkn	    = 'tspkn';
	var $t_tspk			= 'tspk';
	var $t_tspklog = 'tspklog';
	
	function edit_spk_app($projid,$spkno)
	{
		$this->db->select("mpa.paid, mpa.nampa, mby.namby, mproj.namproj, mproj.projid, tspk.spkno, 
		tspk.total, tspk.ket, tspk.crby, tspk.crdat,tspk.chby,tspk.status,tspk.tipe,tspk.tgl,tspk.wo_vol,tspk.wo_sat,tspk.wo_hrg,tspk.wo_spek,tspk.konid,tspk.spk_jp,tspk.spk_isi");
		$this->db->from("tspk");
		$this->db->join("mproj", "mproj.projid = tspk.projid");
		$this->db->join("mpa", "mpa.paid = tspk.paid");
		$this->db->join("mby", "mby.paid = mpa.paid AND mby.byid = tspk.byid");
		$this->db->where('tspk.projid', $projid); 
		$this->db->where('tspk.spkno', $spkno); 
		return $this->db->get();
		
	}
	
	function list_spk_app($projid,$roleid)
	{
		
		$this->db->select("mpa.nampa, mby.namby, mproj.namproj, mproj.projid, tspk.spkno, 
		tspk.total, tspk.ket, tspk.crby, tspk.crdat,tspk.status,tspk.tipe,tspk.tgl");
		$this->db->from("tspk");
		$this->db->join("mproj", "mproj.projid = tspk.projid");
		$this->db->join("mpa", "mpa.paid = tspk.paid");
		$this->db->join("mby", "mby.paid = mpa.paid AND mby.byid = tspk.byid");
		$this->db->where('tspk.projid', $projid); 
		if($roleid == 'DIR')
		{
			$stat = array('P01','P02','A03');
			//$this->db->where('tspk.status', 'P01'); 
			$this->db->where_in('tspk.status', $stat); 
		}
		/*
		elseif($roleid == 'AUD' || $roleid == 'KOM')
		{
			$this->db->where('tspk.status', 'P02');  //Pending cost control
		}
		*/
		else
		{
			$this->db->where('tspk.status', ''); 
		}
		return $this->db->get();
		
	}
	
	function input_spklog($data)
	{
		$this->db->insert($this->t_tspklog, $data);
	}
	
	function list_spklog($projid,$spkno)
	{
		$this->db->where('projid', $projid); 
		$this->db->where('spkno', $spkno); 
		return $this->db->get($this->t_tspklog);
	}
	
	function input_spkn($data)
	{
		$this->db->insert($this->t_tspkn, $data);
	}
	
	function input_spk($data)
	{
		$this->db->insert($this->t_tspk, $data);
	}
	
	function edit_spk($spkno,$projid)
	{
			$this->db->where('spkno', $spkno); 
			$this->db->where('projid',$projid); 
			/*$this->db->where('paid', $paid); 
		$this->db->where('byid', $byid); */
			return $this->db->get($this->t_tspk);
	}

	
	function delete_spk($spkno,$projid)
	{
			$this->db->where('spkno', $spkno); 
			$this->db->where('projid',$projid); 
			/*$this->db->where('paid', $paid); 
		$this->db->where('byid', $byid); */
			$this->db->delete($this->t_tspk);		
	}
	
	function update_spk($spkno,$projid,$data)
	{
		$this->db->where('spkno', $spkno); 
		$this->db->where('projid',$projid); 
		/*$this->db->where('paid', $paid); 
		$this->db->where('byid', $byid); */
		$this->db->update($this->t_tspk, $data);
	}

	function list_spk($projid)
	{
		$this->db->where('projid',$projid); 
		return $this->db->get($this->t_tspk);
	}
	
	function get_spk_byid($projid,$paid,$byid)
	{
		$this->db->where('projid',$projid); 
		$this->db->where('paid',$paid); 
		$this->db->where('byid',$byid); 
		$this->db->where('status','A02'); 
		return $this->db->get($this->t_tspk);
	}
	
	
}
?>