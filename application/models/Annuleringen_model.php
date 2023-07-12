<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Annuleringen_model extends CI_Model
{
    /////////
    // GET //
    /////////

    function getOverzicht()
    {
        $this->db->select('*, workshops.workshop_ID');
        $this->db->from('workshops');
		$this->db->join('annuleringen', 'workshops.workshop_ID = annuleringen.workshop_ID', 'left');
        $query = $this->db->get();
        return $query->result();
    }

    function getAnnuleringByWorkshopID($workshop_ID)
    {
        $this->db->select('*');
        $this->db->from('annuleringen');
        $this->db->join('workshops', 'annuleringen.workshop_ID = workshops.workshop_ID');
        $this->db->where('annuleringen.workshop_ID', $workshop_ID);
        $query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
        return $query->result();
    }

    function getAnnuleringByWorkshopIDActief($workshop_ID)
    {
        $this->db->select('*');
        $this->db->from('annuleringen');
        $this->db->join('workshops', 'annuleringen.workshop_ID = workshops.workshop_ID');
        $this->db->where('annuleringen.workshop_ID', $workshop_ID);
        $this->db->where('annuleringen.annulering_actief', 'Ja');
        $query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
        return $query->result();
    }

    public function insertAnnulering($data) {
        $this->db->insert('annuleringen', $data);
        if($this->db->affected_rows() == 1) return $this->db->insert_id();
        else return 0;
    }

    public function updateAnnulering($item_ID, $data) {
        $this->db->where('workshop_ID', $item_ID);
        $this->db->update('annuleringen', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    public function deleteAnnulering($item_ID) {
        $this->db->where('workshop_ID', $item_ID);
        $this->db->delete('annuleringen');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }
}
