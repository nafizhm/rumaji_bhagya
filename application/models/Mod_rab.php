<?php
class Mod_rab extends CI_Model
{
	/**
	 * Constructor
	 */

	function __construct()
	{
		parent::__construct();
	}


	/* Insialiasi nama table */
	var $t_mrabkat			= 'mrabkat';
	var $t_mrabitm		    = 'mrabitm';
	var $t_mrabhrg		    = 'mrabhrg';
	var $t_trabh		    = 'trabh';
	var $t_trabd		    = 'trabd';


	function get_rabd_no($rabid)
	{
		$query = $this->db->query("SELECT MAX(no) + 1 as no FROM `trabd` WHERE rabid = '" . $rabid . "'")->result();
		foreach($query as $row);
		return $row->no;
	}

	function update_rabd($rabid,$no, $data)
	{
		$this->db->where('rabid', $rabid);
		$this->db->where('no', $no);
		$this->db->update($this->t_trabd, $data);
	}

	function update_rabh($rabid, $data)
	{
		$this->db->where('rabid', $rabid);
		$this->db->update($this->t_trabh, $data);
	}

	function get_rabd($rabid)
	{
		$query = $this->db->query("SELECT trabd.*,mkon.namkon,mrabitm.namitm,msat.namsat 
		FROM `trabd`
		INNER JOIN mkon ON trabd.konid = mkon.konid
		INNER JOIN mrabitm ON trabd.itmid = mrabitm.itmid
		INNER JOIN msat ON msat.sat = mrabitm.sat
		WHERE trabd.rabid = '" . $rabid . "' AND trabd.del = 0
		ORDER BY trabd.no");

		return $query;
	}

	function get_rabh($rabid)
	{
		$this->db->where('rabid', $rabid);
		return $this->db->get($this->t_trabh);
	}

	function get_rab_projid($projid)
	{
		$query = $this->db->query("SELECT trabh.*, 
		(SELECT COALESCE(SUM(total),0) FROM trabd 
		WHERE rabid = trabh.rabid AND del = 0) as total 
		FROM `trabh`
		WHERE projid = '" . $projid . "'");

		return $query;
	}

	function input_rabd2($data)
	{
		$this->db->insert($this->t_trabd, $data);
	}

	function input_rabh($data)
	{
		$this->db->insert($this->t_trabh, $data);
	}

	function input_rabd($data)
	{
		$this->db->insert_batch($this->t_trabd, $data);
	}

	function count_itm($itmid)
	{
		$this->db->where('itmid', $itmid);
		return $this->db->get($this->t_mrabhrg);
	}

	function count_kat($katid)
	{
		$this->db->where('katid', $katid);
		return $this->db->get($this->t_mrabitm);
	}

	function get_itmhrg_projid($projid, $katid)
	{

		$this->db->select("mrabhrg.*,mrabkat.namkat,mrabitm.namitm,mrabitm.sat,msat.namsat");
		$this->db->from($this->t_mrabhrg);
		$this->db->join("mrabkat", "mrabhrg.katid = mrabkat.katid");
		$this->db->join("mrabitm", "mrabhrg.itmid = mrabitm.itmid");
		$this->db->join("mproj", "mrabhrg.projid = mproj.projid");
		$this->db->join("msat", "mrabitm.sat = msat.sat");
		$this->db->where("mrabhrg.projid", $projid);
		$this->db->where("mrabhrg.katid", $katid);

		return $this->db->get();
	}

	function get_kat_projid($projid)
	{
		$query = $this->db->query("SELECT DISTINCT(mrabhrg.katid) as katid, mrabkat.namkat
		FROM `mrabhrg` 
		INNER JOIN mrabkat ON mrabkat.katid = mrabhrg.katid
		WHERE mrabhrg.projid = '" . $projid . "'");

		return $query;
	}

	function get_hrg_projid($projid, $katid, $itmid)
	{
		$query = $this->db->query("SELECT hrg
		FROM `mrabhrg`
		WHERE projid = '" . $projid . "'
		AND katid = '" . $katid . "'
		AND itmid = '" . $itmid . "'");

		return $query;
	}

	function get_itm_katid($katid)
	{
		$query = $this->db->query("SELECT mrabitm.itmid,mrabitm.namitm,msat.namsat
		FROM `mrabitm` 
		INNER JOIN msat ON mrabitm.sat = msat.sat
		WHERE mrabitm.katid = '" . $katid . "'");

		return $query;
	}

	function get_itm_projid($projid, $katid)
	{
		$query = $this->db->query("SELECT mrabitm.itmid,mrabitm.namitm,msat.namsat
		FROM `mrabitm` 
		INNER JOIN msat ON mrabitm.sat = msat.sat
		WHERE mrabitm.katid = '" . $katid . "' 
		AND mrabitm.itmid NOT IN ( SELECT itmid FROM mrabhrg WHERE projid = '" . $projid . "' )");

		return $query;
	}

	function get_hrg($projid, $katid, $itmid)
	{
		$this->db->where('projid', $projid);
		$this->db->where('katid', $katid);
		$this->db->where('itmid', $itmid);
		return $this->db->get($this->t_mrabhrg);
	}

	function update_hrg($projid, $katid, $itmid, $data)
	{
		$this->db->where('projid', $projid);
		$this->db->where('katid', $katid);
		$this->db->where('itmid', $itmid);
		$this->db->update($this->t_mrabhrg, $data);
	}

	function delete_hrg($projid, $katid, $itmid)
	{
		$this->db->where('projid', $projid);
		$this->db->where('katid', $katid);
		$this->db->where('itmid', $itmid);
		$this->db->delete($this->t_mrabhrg);
	}

	function input_hrg($data)
	{
		$this->db->insert($this->t_mrabhrg, $data);
	}

	function list_hrg($projid)
	{
		$this->db->select("mrabhrg.*,mrabkat.namkat,mrabitm.namitm,mrabitm.sat,msat.namsat");
		$this->db->from($this->t_mrabhrg);
		$this->db->join("mrabkat", "mrabhrg.katid = mrabkat.katid");
		$this->db->join("mrabitm", "mrabhrg.itmid = mrabitm.itmid");
		$this->db->join("mproj", "mrabhrg.projid = mproj.projid");
		$this->db->join("msat", "mrabitm.sat = msat.sat");
		$this->db->where("mrabhrg.projid", $projid);

		return $this->db->get();
	}

	function delete_kat($katid)
	{
		$this->db->where('katid', $katid);
		$this->db->delete($this->t_mrabkat);
	}


	function input_kat($data)
	{
		$this->db->insert($this->t_mrabkat, $data);
	}

	function get_kat($katid)
	{
		$this->db->where('katid', $katid);
		return $this->db->get($this->t_mrabkat);
	}

	function list_kat()
	{
		return $this->db->get($this->t_mrabkat);
	}

	function update_kat($katid, $data)
	{
		$this->db->where('katid', $katid);
		$this->db->update($this->t_mrabkat, $data);
	}

	function input_itm($data)
	{
		$this->db->insert($this->t_mrabitm, $data);
	}

	function get_itm($itmid)
	{
		$this->db->select("mrabitm.*,mrabkat.namkat");
		$this->db->from($this->t_mrabitm);
		$this->db->join("mrabkat", " mrabitm.katid = mrabkat.katid");
		$this->db->where("itmid", $itmid);

		return $this->db->get();
	}

	function list_itm($katid)
	{
		$this->db->select("mrabitm.*,mrabkat.namkat");
		$this->db->from($this->t_mrabitm);
		$this->db->join("mrabkat", "mrabitm.katid = mrabkat.katid");
		$this->db->where("mrabitm.katid", $katid);

		return $this->db->get();
	}

	function update_itm($itemid, $data)
	{
		$this->db->where('itmid', $itemid);
		$this->db->update($this->t_mrabitm, $data);
	}

	function delete_itm($itemid)
	{
		$this->db->where('itmid', $itemid);
		$this->db->delete($this->t_mrabitm);
	}
}
