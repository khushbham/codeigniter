<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Producten_model extends CI_Model
{
	/////////
	// GET //
	/////////
	
	function getProducten()
	{
		$this->db->select('*');
		$this->db->from('producten');
		$this->db->join('media', 'media.media_ID = producten.media_ID', 'left');
		$this->db->order_by('producten.product_positie', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function getProductByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('producten');
		$this->db->join('media', 'media.media_ID = producten.media_ID', 'left');
		$this->db->where('producten.product_ID', $item_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}
	
	function getWorkshopsByProductID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('workshops_producten');
		$this->db->where('product_ID', $item_ID);
		$query = $this->db->get();
		return $query->result();
	}
	
	function getBestellingenByProductID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('bestellingen_producten');
		$this->db->where('product_ID', $item_ID);
		$query = $this->db->get();
		return $query->result();
	}
	
	function zoekProducten($zoekterm)
	{
		$this->db->select('*');
		$this->db->from('producten');
		$this->db->like('product_naam', $zoekterm);
		$query = $this->db->get();
		return $query->result();
	}
	
	
	
	////////////
	// INSERT //
	////////////
	
	function insertProduct($data)
	{
		$this->db->insert('producten', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	
	
	////////////
	// UPDATE //
	////////////
	
	function updateProduct($item_ID, $data)
	{
		$this->db->where('product_ID', $item_ID);
		$this->db->update('producten', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	
	
	////////////
	// DELETE //
	////////////
	
	function deleteProductByID($item_ID)
	{
		$this->db->where('product_ID', $item_ID);
		$this->db->delete('workshops_producten');
		
		$this->db->where('product_ID', $item_ID);
		$this->db->delete('producten');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
}