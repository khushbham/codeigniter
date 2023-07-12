<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uitnodigingen_model extends CI_Model
{
	/////////
	// GET //
	/////////

	function getUitnodigingen()
	{
		$this->db->select('uitnodigingen.*, workshops.workshop_ID, workshops.workshop_titel, lessen.les_ID, lessen.les_titel');
		$this->db->from('uitnodigingen');
		$this->db->join('workshops', 'uitnodigingen.workshop_ID = workshops.workshop_ID');
		$this->db->join('lessen', 'uitnodigingen.les_ID = lessen.les_ID', 'left');
		$query = $this->db->get();
		return $query->result();
	}

	function getUitnodigingByID($uitnodiging_ID)
	{
		$this->db->select('uitnodigingen.*, workshops.workshop_ID, workshops.workshop_titel, lessen.les_ID, lessen.les_titel');
		$this->db->from('uitnodigingen');
		$this->db->join('workshops', 'uitnodigingen.workshop_ID = workshops.workshop_ID');
		$this->db->join('lessen', 'uitnodigingen.les_ID = lessen.les_ID', 'left');
		$this->db->where('uitnodiging_ID', $uitnodiging_ID);

		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getLinks() {
        $this->db->select('uitnodigingen_links.*, workshops.workshop_ID, workshops.workshop_titel, workshops.workshop_url, groepen.groep_naam');
        $this->db->from('uitnodigingen_links');
        $this->db->join('workshops', 'uitnodigingen_links.workshop_ID = workshops.workshop_ID');
        $this->db->join('groepen', 'uitnodigingen_links.groep_ID = groepen.groep_ID', 'left');
        $query = $this->db->get();
        return $query->result();
    }

    function getLinkByID($item_ID) {
        $this->db->select('uitnodigingen_links.*, workshops.workshop_ID, workshops.workshop_url, groepen.groep_naam');
        $this->db->from('uitnodigingen_links');
        $this->db->join('workshops', 'uitnodigingen_links.workshop_ID = workshops.workshop_ID');
        $this->db->join('groepen', 'uitnodigingen_links.groep_ID = groepen.groep_ID', 'left');
        $this->db->where('link_ID', $item_ID);

        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

    function getLinkByCode($code) {
        $this->db->select('uitnodigingen_links.*, workshops.workshop_ID, workshops.workshop_url, groepen.groep_naam');
        $this->db->from('uitnodigingen_links');
        $this->db->join('workshops', 'uitnodigingen_links.workshop_ID = workshops.workshop_ID');
        $this->db->join('groepen', 'uitnodigingen_links.groep_ID = groepen.groep_ID', 'left');
        $this->db->where('link_code', $code);

        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

	////////////
	// INSERT //
	////////////

	function insertUitnodiging($data)
	{
		$this->db->insert('uitnodigingen', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

    function insertUitnodigingsLink($data)
    {
        $this->db->insert('uitnodigingen_links', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

	////////////
	// UPDATE //
	////////////

	function updateUitnodiging($item_ID, $data)
	{
		$this->db->where('uitnodiging_ID', $item_ID);
		$this->db->update('uitnodigingen', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

    function updateUitnodigingsLink($item_ID, $data)
    {
        $this->db->where('link_ID', $item_ID);
        $this->db->update('uitnodigingen_links', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

	////////////
	// DELETE //
	////////////

	function deleteUitnodiging($item_ID)
	{
		$this->db->where('uitnodiging_ID', $item_ID);
		$this->db->delete('uitnodigingen');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

    function deleteLink($item_ID)
    {
        $this->db->where('link_ID', $item_ID);
        $this->db->delete('uitnodigingen_links');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }
}