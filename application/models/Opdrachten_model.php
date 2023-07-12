<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Opdrachten_model extends CI_Model
{
	/////////
	// GET //
	/////////

	function getOpdrachten()
	{
		$this->db->select('*');
		$this->db->from('opdrachten');
		$query = $this->db->get();
		return $query->result();
	}

	function getOpdrachtByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('opdrachten');
		$this->db->where('opdracht_ID', $item_ID);
		$this->db->join('media', 'media.media_ID = opdrachten.opdracht_media_ID', 'left');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getOpdracht($item_url)
	{
		$this->db->select('*');
		$this->db->from('opdrachten');
		$this->db->join('media', 'media.media_ID = opdrachten.opdracht_media_ID', 'left');
		$this->db->where('opdracht_url', $item_url);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getOpdrachtByURL($item_url)
	{
		$this->db->select('*');
		$this->db->from('opdrachten');
		$this->db->join('media', 'media.media_ID = opdrachten.opdracht_media_ID', 'left');
		$this->db->where('opdracht_url', $item_url);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getOpdrachtAndGebruikerID($gebruiker_ID, $opdracht_ID)
	{
		$this->db->select('*');
		$this->db->from('opdrachten_beoordelingen');
		$this->db->where('opdrachten_beoordelingen.gebruiker_ID', $gebruiker_ID);
		$this->db->where('opdrachten_beoordelingen.opdracht_ID', $opdracht_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getVoltooideOpdrachten()
	{
		$this->db->select('*');
		$this->db->from('opdrachten_beoordelingen');
		$this->db->join('opdrachten', 'opdrachten.opdracht_ID = opdrachten_beoordelingen.opdracht_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = opdrachten_beoordelingen.gebruiker_ID', 'left');
		$query = $this->db->get();
		return $query->result();
	}

	function getVoltooideOpdrachtenZonderBeoordeling()
	{
		$this->db->select('*');
		$this->db->from('opdrachten_beoordelingen');
		$this->db->where('opdrachten_beoordelingen.opdracht_beoordeling =', '');
		$this->db->join('opdrachten', 'opdrachten.opdracht_ID = opdrachten_beoordelingen.opdracht_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = opdrachten_beoordelingen.gebruiker_ID', 'left');
		$query = $this->db->get();
		return $query->result();
	}

	function getVoltooideOpdrachtenByGebruikerID($gebruiker_ID)
	{
		$this->db->select('*');
		$this->db->from('opdrachten_beoordelingen');
		$this->db->where('opdrachten_beoordelingen.gebruiker_ID', $gebruiker_ID);
		$this->db->join('opdrachten', 'opdrachten.opdracht_ID = opdrachten_beoordelingen.opdracht_ID', 'left');
		$query = $this->db->get();
		return $query->result();
	}

	function getVoltooideOpdrachtByID($opdracht_beoordeling_ID)
	{
		$this->db->select('*');
		$this->db->from('opdrachten_beoordelingen');
		$this->db->where('opdrachten_beoordelingen.opdracht_beoordeling_ID', $opdracht_beoordeling_ID);
		$this->db->join('opdrachten', 'opdrachten.opdracht_ID = opdrachten_beoordelingen.opdracht_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = opdrachten_beoordelingen.gebruiker_ID', 'left');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getBeoordelingByOpdrachtAndGebruikerID($gebruiker_ID, $opdracht_ID)
	{
		$this->db->select('*');
		$this->db->from('opdrachten_beoordelingen');
		$this->db->where('opdrachten_beoordelingen.gebruiker_ID', $gebruiker_ID);
		$this->db->where('opdrachten_beoordelingen.opdracht_ID', $opdracht_ID);
		$this->db->where('opdrachten_beoordelingen.opdracht_beoordeling !=', null);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}



	////////////
	// INSERT //
	////////////

	function insertOpdracht($data)
	{
		$this->db->insert('opdrachten', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

    function insertOpdrachtReturnID($data)
    {
        $this->db->insert('opdrachten', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }



	////////////
	// UPDATE //
	////////////

	function updateOpdracht($item_ID, $data)
	{
		$this->db->where('opdracht_ID', $item_ID);
		$this->db->update('opdrachten', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

	function updateInzending($gebruiker_ID, $opdracht_ID, $data)
	{
		$this->db->where('gebruiker_ID', $gebruiker_ID);
		$this->db->where('opdracht_ID', $opdracht_ID);
		$this->db->update('opdrachten_beoordelingen', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}



	////////////
	// DELETE //
	////////////

	function deleteOpdrachtByID($item_ID)
	{
		// Opdracht verwijderen

		$this->db->where('opdracht_ID', $item_ID);
		$this->db->delete('opdrachten');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
}
