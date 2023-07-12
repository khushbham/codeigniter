<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Docenten_model extends CI_Model
{
	/////////
	// GET //
	/////////
	
	function getDocenten($aantal = 0, $pagina = 1)
	{
		$this->db->select('*');
		$this->db->from('docenten');
		$this->db->join('media', 'media.media_ID = docenten.media_ID', 'left');
		$this->db->join('gebruikers', 'docenten.gebruiker_ID = gebruikers.gebruiker_ID', 'left');
		$this->db->order_by('docenten.docent_positie', 'asc');
		if($aantal > 0) $this->db->limit($aantal, (($pagina - 1) * $aantal));
		$query = $this->db->get();
		return $query->result();
	}

	function getAllDocenten()
	{
		$this->db->select('*');
		$this->db->from('docenten');
		$this->db->join('media', 'media.media_ID = docenten.media_ID', 'left');
		$this->db->join('gebruikers', 'docenten.gebruiker_ID = gebruikers.gebruiker_ID', 'left');
		$this->db->order_by('docenten.docent_positie', 'asc');
		$query = $this->db->get();
		return $query->result();
	}
	
	function getDocentenAantal()
	{
		$this->db->select('*');
		$this->db->from('docenten');
		return $this->db->count_all_results();
	}
	
	function getDocentByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('docenten');
		$this->db->join('media', 'media.media_ID = docenten.media_ID', 'left');
		$this->db->where('docenten.docent_ID', $item_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getDocentByGroepLesID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('groepen_lessen');
		$this->db->join('docenten', 'groepen_lessen.docent_ID = docenten.docent_ID', 'left');
		$this->db->where('groepen_lessen.groep_les_ID', $item_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

    function getDocentByGebruikerID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('docenten');
        $this->db->join('media', 'media.media_ID = docenten.media_ID', 'left');
        $this->db->join('gebruikers', 'docenten.gebruiker_ID = gebruikers.gebruiker_ID', 'left');
        $this->db->where('docenten.gebruiker_ID', $item_ID);
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }
	
	function zoekDocenten($zoekterm)
	{
		$this->db->select('*');
		$this->db->from('docenten');
		$this->db->like('docent_naam', $zoekterm);
		$query = $this->db->get();
		return $query->result();
	}
	
	
	
	////////////
	// INSERT //
	////////////
	
	function insertDocent($data)
	{
		$this->db->insert('docenten', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	
	
	////////////
	// UPDATE //
	////////////
	
	function updateDocent($item_ID, $data)
	{
		$this->db->where('docent_ID', $item_ID);
		$this->db->update('docenten', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

    function updateDocentByGebruikerID($item_ID, $data)
    {
        $this->db->where('gebruiker_ID', $item_ID);
        $this->db->update('docenten', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }
	
	
	
	////////////
	// DELETE //
	////////////
	
	function deleteDocent($item_ID)
	{
		$this->db->where('docent_ID', $item_ID);
		$this->db->delete('docenten');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

    function deleteDocentByGebruikerID($item_ID)
    {
        $this->db->where('gebruiker_ID', $item_ID);
        $this->db->delete('docenten');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }
}