<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nieuws_model extends CI_Model
{
	/////////
	// GET //
	/////////
	
	function getNieuws($aantal = 0, $pagina = 1)
	{
		$this->db->select('*');
		$this->db->from('nieuws');
		$this->db->order_by('nieuws_datum', 'desc');
		if($aantal > 0) $this->db->limit($aantal, (($pagina - 1) * $aantal));
		$query = $this->db->get();
		return $query->result();
	}
	
	function getNieuwsGepubliceerd($aantal = 0, $pagina = 1)
	{
		$this->db->select('*');
		$this->db->from('nieuws');
		$this->db->join('media', 'media.media_ID = nieuws.media_ID', 'left');
		$this->db->order_by('nieuws_datum', 'desc');
		$this->db->where('nieuws_gepubliceerd', 'ja');
		if($aantal > 0) $this->db->limit($aantal, (($pagina - 1) * $aantal));
		$query = $this->db->get();
		return $query->result();
	}
	
	function getNieuwsAantal()
	{
		$this->db->select('*');
		$this->db->from('nieuws');
		return $this->db->count_all_results();
	}
	
	function getNieuwsGepubliceerdAantal()
	{
		$this->db->select('*');
		$this->db->from('nieuws');
		$this->db->where('nieuws_gepubliceerd', 'ja');
		return $this->db->count_all_results();
	}
	
	function getNieuwsByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('nieuws');
		$this->db->join('media', 'media.media_ID = nieuws.media_ID', 'left');
		$this->db->where('nieuws_ID', $item_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}
	
	function getVorigeBericht($item_datum)
	{
		$this->db->select('*');
		$this->db->from('nieuws');
		$this->db->join('media', 'media.media_ID = nieuws.media_ID', 'left');
		$this->db->where('nieuws_datum <', $item_datum);
		$this->db->where('nieuws_gepubliceerd', 'ja');
		$this->db->order_by('nieuws_datum', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}
	
	function getVolgendeBericht($item_datum)
	{
		$this->db->select('*');
		$this->db->from('nieuws');
		$this->db->join('media', 'media.media_ID = nieuws.media_ID', 'left');
		$this->db->where('nieuws_datum >', $item_datum);
		$this->db->where('nieuws_gepubliceerd', 'ja');
		$this->db->order_by('nieuws_datum', 'asc');
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}
	
	function getNieuwsByURL($item_url)
	{
		$this->db->select('*');
		$this->db->from('nieuws');
		$this->db->join('media', 'media.media_ID = nieuws.media_ID', 'left');
		$this->db->where('nieuws_url', $item_url);
		$this->db->where('nieuws_gepubliceerd', 'ja');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}
	
	function zoekNieuws($zoekterm)
	{
		$this->db->select('*');
		$this->db->from('nieuws');
		$this->db->like('nieuws_titel', $zoekterm);
		$query = $this->db->get();
		return $query->result();
	}
	
	
	
	////////////
	// INSERT //
	////////////
	
	function insertNieuws($data)
	{
		$this->db->insert('nieuws', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	
	
	////////////
	// UPDATE //
	////////////
	
	function updateNieuws($item_ID, $data, $noChange = false)
	{
		// Added to return correct status even without affected changes
        if($noChange) return $this->db->where('nieuws_ID', $item_ID)->update('nieuws', $data);

		$this->db->where('nieuws_ID', $item_ID);
		$this->db->update('nieuws', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	
	
	////////////
	// DELETE //
	////////////
	
	function deleteNieuws($item_ID)
	{
		$this->db->where('nieuws_ID', $item_ID);
		$this->db->delete('nieuws');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
}