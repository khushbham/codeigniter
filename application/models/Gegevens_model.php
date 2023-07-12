<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gegevens_model extends CI_Model
{
	/////////
	// GET //
	/////////
	
	function getGegevens()
	{
		$this->db->select('*');
		$this->db->from('gegevens');
		$query = $this->db->get();
		return $query->result();
	}

    function getGegevensHuiswerk()
    {
        $this->db->select('gegeven_waarde');
        $this->db->from('gegevens');
        $this->db->where('gegeven_naam', 'Huiswerk insturen');
        $query = $this->db->get();
        return $query->result();
    }

    function getGegevensHuiswerkTekst()
    {
        $this->db->select('gegeven_waarde');
        $this->db->from('gegevens');
        $this->db->where('gegeven_naam', 'Huiswerk geblokkeerd bericht');
        $query = $this->db->get();
        return $query->result();
    }



    ////////////
	// UPDATE //
	////////////
	
	function updateGegeven($item_ID, $data)
	{
		$this->db->where('gegeven_ID', $item_ID);
		$this->db->update('gegevens', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
}