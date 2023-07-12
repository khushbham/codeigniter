<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aanwezigheid_model extends CI_Model
{
    /////////
    // GET //
    /////////
    public function getAanwezigheidByGebruikerID($gebruiker_ID) {
        $this->db->select('*');
        $this->db->from('aanwezigheid');
        $this->db->where('gebruiker_ID', $gebruiker_ID);
        $query = $this->db->get();
        return $query->result();
    }

    public function getAanwezigheidByGroepLesID($item_ID) {
        $this->db->select('*');
        $this->db->from('aanwezigheid');
        $this->db->where('les_ID', $item_ID);
        $query = $this->db->get();
        return $query->result();
    }

    public function getAanwezigheidByGroeplesIDGebruikerID($item_ID, $gebruiker_ID) {
        $this->db->select('*');
        $this->db->from('aanwezigheid');
        $this->db->where('les_ID', $item_ID);
        $this->db->where('gebruiker_ID', $gebruiker_ID);
        $query = $this->db->get();
        return $query->result();
    }

    public function insertAanwezigheid($data) {
        $this->db->insert('aanwezigheid', $data);
        if($this->db->affected_rows() == 1) return $this->db->insert_id();
        else return 0;
    }

    public function updateAanwezigheid($les_ID, $gebruiker_ID, $data) {
        $this->db->where('les_ID', $les_ID);
        $this->db->where('gebruiker_ID', $gebruiker_ID);
        $this->db->update('aanwezigheid', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    public function deleteAanwezigheid($les_ID, $gebruiker_ID) {
        $this->db->where('les_ID', $les_ID);
        $this->db->where('gebruiker_ID', $gebruiker_ID);
        $this->db->delete('aanwezigheid');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

}
