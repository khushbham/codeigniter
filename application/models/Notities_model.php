<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notities_model extends CI_Model
{
    /////////
    // GET //
    /////////

    function getGebruikersMetNotities()
    {
        $this->db->select('*');
        $this->db->from('gebruikers');
        $this->db->where('gebruiker_notitie_verbergen =', 0);
        $this->db->where('gebruiker_notities !=', '');
        $query = $this->db->get();
        return $query->result();
    }

    function getGebruikersZonderNotities() {
        $this->db->select('*');
        $this->db->from('gebruikers');
        $this->db->where('gebruiker_notitie_verbergen =', 1);
        $this->db->or_where('gebruiker_notities =', '');
        $query = $this->db->get();
        return $query->result();
    }

    function getGebruikerByID($item_ID) {
        $this->db->select('*');
        $this->db->from('gebruikers');
        $this->db->where('gebruiker_ID', $item_ID);
        $query = $this->db->get();
        return $query->result();
    }

    ////////////
    // UPDATE //
    ////////////

    function updateNotitie($item_ID, $data)
    {
        $this->db->where('gebruiker_ID', $item_ID);
        $this->db->update('gebruikers', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }
}
