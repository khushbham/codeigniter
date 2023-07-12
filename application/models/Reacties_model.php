<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reacties_model extends CI_Model
{
	/////////
	// GET //
	/////////
	
	function getReacties($aantal = 0, $pagina = 1)
	{
		$this->db->select('*');
		$this->db->from('reacties');
		$this->db->join('media', 'media.media_ID = reacties.media_ID', 'left');
		$this->db->order_by('reactie_datum', 'desc');
		if($aantal > 0) $this->db->limit($aantal, (($pagina - 1) * $aantal));
		$query = $this->db->get();
		return $query->result();
	}
	
	function getReactiesGepubliceerd($aantal = 0, $pagina = 1)
	{
		$this->db->select('*');
		$this->db->from('reacties');
		$this->db->join('media', 'media.media_ID = reacties.media_ID', 'left');
		$this->db->order_by('reactie_datum', 'desc');
		$this->db->where('reactie_gepubliceerd', 'ja');
		if($aantal > 0) $this->db->limit($aantal, (($pagina - 1) * $aantal));
		$query = $this->db->get();
		return $query->result();
	}
	
	function getReactiesAantal()
	{
		$this->db->select('*');
		$this->db->from('reacties');
		return $this->db->count_all_results();
	}
	
	function getReactiesGepubliceerdAantal()
	{
		$this->db->select('*');
		$this->db->from('reacties');
		$this->db->where('reactie_gepubliceerd', 'ja');
		return $this->db->count_all_results();
	}
	
	function getReactieByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('reacties');
		$this->db->join('media', 'media.media_ID = reacties.media_ID', 'left');
		$this->db->where('reacties.reactie_ID', $item_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}
	
	function getReactieByURL($item_url)
	{
		$this->db->select('*');
		$this->db->from('reacties');
		$this->db->join('media', 'media.media_ID = reacties.media_ID', 'left');
		$this->db->where('reactie_url', $item_url);
		$this->db->where('reactie_gepubliceerd', 'ja');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}
	
	function zoekReacties($zoekterm)
	{
		$this->db->select('*');
		$this->db->from('reacties');
		$this->db->like('reactie_titel', $zoekterm);
		$this->db->or_like('reactie_deelnemer', $zoekterm);
		$query = $this->db->get();
		return $query->result();
	}
	
	
	
	////////////
	// INSERT //
	////////////
	
	function insertReactie($data)
	{
		$this->db->insert('reacties', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	
	
	////////////
	// UPDATE //
	////////////
	
	function updateReactie($item_ID, $data)
	{
		$this->db->where('reactie_ID', $item_ID);
		$this->db->update('reacties', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	
	
	////////////
	// DELETE //
	////////////
	
	function deleteReactie($item_ID)
	{
		$this->db->where('reactie_ID', $item_ID);
		$this->db->delete('reacties');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
}