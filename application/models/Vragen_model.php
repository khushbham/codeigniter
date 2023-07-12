<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vragen_model extends CI_Model
{
	/////////
	// GET //
	/////////
	
	function getVragen($type = 'website')
	{
		$this->db->select('*');
		$this->db->from('vragen');
		$this->db->join('media', 'media.media_ID = vragen.media_ID', 'left');
		$this->db->where('vragen.vraag_type', $type);
		$this->db->order_by('vragen.vraag_positie', 'asc');
		$query = $this->db->get();
		return $query->result();
	}
	
	function getVragenGepubliceerd($type = 'website', $limit = 0)
	{
		$this->db->select('*');
		$this->db->from('vragen');
		$this->db->join('media', 'media.media_ID = vragen.media_ID', 'left');
		$this->db->where('vragen.vraag_type', $type);
		$this->db->where('vragen.vraag_gepubliceerd', 'ja');
		if($limit > 0) $this->db->limit($limit);
		$this->db->order_by('vragen.vraag_positie', 'asc');
		$query = $this->db->get();
		return $query->result();
	}
	
	function getVraagByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('vragen');
		$this->db->join('media', 'media.media_ID = vragen.media_ID', 'left');
		$this->db->where('vragen.vraag_ID', $item_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}
	
	function zoekVragen($zoekterm)
	{
		$this->db->select('*');
		$this->db->from('vragen');
		$this->db->like('vraag_titel', $zoekterm);
		$query = $this->db->get();
		return $query->result();
	}
	
	
	
	////////////
	// INSERT //
	////////////
	
	function insertVraag($data)
	{
		$this->db->insert('vragen', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	
	
	////////////
	// UPDATE //
	////////////
	
	function updateVraag($item_ID, $data)
	{
		$this->db->where('vraag_ID', $item_ID);
		$this->db->update('vragen', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	
	
	////////////
	// DELETE //
	////////////
	
	function deleteVraag($item_ID)
	{
		$this->db->where('vraag_ID', $item_ID);
		$this->db->delete('vragen');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
}