<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Berichten_model extends CI_Model
{
	/////////
	// GET //
	/////////
	
	function getBerichtenByGebruikerID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('berichten');
		$this->db->where('bericht_ontvanger_ID', $item_ID);
        $this->db->where('bericht_verwijderd_ontvanger =', 0);
		$this->db->order_by('bericht_datum', 'DESC');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = berichten.bericht_afzender_ID');
		$query = $this->db->get();
		return $query->result();
	}

    function getVerzondenBerichtenByGebruikerID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('berichten');
        $this->db->where('bericht_afzender_ID', $item_ID);
        $this->db->where('bericht_verwijderd_afzender =', 0);
        $this->db->order_by('bericht_datum', 'DESC');
        $this->db->join('gebruikers', 'gebruikers.gebruiker_ID = berichten.bericht_ontvanger_ID');
        $query = $this->db->get();
        return $query->result();
    }

    function getTemplates() {
        $this->db->select('*');
        $this->db->from('templates');
        $query = $this->db->get();
        return $query->result();
    }

    function getTemplatebyID($item_ID) {
        $this->db->select('*');
        $this->db->from('templates');
        $this->db->where('template_ID', $item_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function updateTemplate($item_ID, $data) {
        $this->db->where('template_ID', $item_ID);
        $this->db->update('templates', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function insertTemplate($data) {
        $this->db->insert('templates', $data);
        if($this->db->affected_rows() == 1) return $this->db->insert_id();
        else return 0;
    }

    function deleteTemplate($item_ID) {
        $this->db->where('template_ID', $item_ID);
        $this->db->delete('templates');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }
	
	function getAantalNieuweBerichtenByGebruikerID($item_ID)
	{
		$this->db->select('*');
		$this->db->where('bericht_ontvanger_ID', $item_ID);
		$this->db->where('bericht_verwijderd_ontvanger', 0);
		$this->db->where('bericht_nieuw', 'ja');
		$this->db->from('berichten');
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	function getBerichtByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('berichten');
		$this->db->where('bericht_ID', $item_ID);
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = berichten.bericht_afzender_ID');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

    function getBerichtByandZenderID($item_ID, $gebruiker_ID)
    {
        $this->db->select('*');
        $this->db->from('berichten');
        $this->db->where('bericht_ID', $item_ID);
        $this->db->where('bericht_afzender_ID', $gebruiker_ID);
        $this->db->join('gebruikers', 'gebruikers.gebruiker_ID = berichten.bericht_afzender_ID');
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

    function getBerichtByandOntvangerID($item_ID, $gebruiker_ID)
    {
        $this->db->select('*');
        $this->db->from('berichten');
        $this->db->where('bericht_ID', $item_ID);
        $this->db->where('bericht_ontvanger_ID', $gebruiker_ID);
        $this->db->join('gebruikers', 'gebruikers.gebruiker_ID = berichten.bericht_ontvanger_ID');
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

    function getBerichtVerwijderen($item_ID) {
        $this->db->select('*');
        $this->db->from('berichten');
        $this->db->where('bericht_ID', $item_ID);
        $this->db->where('bericht_verwijderd_ontvanger', 1);
        $this->db->where('bericht_verwijderd_afzender', 1);
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

    function getBerichtVerwijderenZichzelf($item_ID, $gebruiker_ID) {
        $this->db->select('*');
        $this->db->from('berichten');
        $this->db->where('bericht_ID', $item_ID);
        $this->db->where('bericht_ontvanger_ID', $gebruiker_ID);
        $this->db->where('bericht_afzender_ID', $gebruiker_ID);
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }
	
	function getBerichtWhereIDIn(array $item_IDs)
	{
		$this->db->select('*');
		$this->db->from('berichten');
		$this->db->where_in('bericht_ID', $item_IDs);
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = berichten.bericht_afzender_ID');
		$query = $this->db->get();
		return $query->result();
	}	
	
	
	////////////
	// INSERT //
	////////////
	
	function verzendBericht($data)
	{
		$this->db->insert('berichten', $data);
		if($this->db->affected_rows() == 1) return $this->db->insert_id();
		else return 0;
	}
	
	
	
	////////////
	// UPDATE //
	////////////
	
	function updateBerichtByID($item_ID, $data)
	{
		$this->db->where('bericht_ID', $item_ID);
		$this->db->update('berichten', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	
	
	////////////
	// DELETE //
	////////////
	
	function verwijderBerichtByID($item_ID)
	{
		$this->db->where('bericht_ID', $item_ID);
		$this->db->delete('berichten');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

	function verwijderBerichtWhereIDIn(array $item_IDs)
	{
		$this->db->where_in('bericht_ID', $item_IDs);
		$this->db->delete('berichten');
		if($this->db->affected_rows() >= 1) return true;
		else return false;
	}
}