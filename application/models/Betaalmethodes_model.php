<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Betaalmethodes_model extends CI_Model
{
    /////////
    // GET //
    /////////

    function getMethodes()
    {
        $this->db->select('*');
        $this->db->from('betaalmethodes');
        $query = $this->db->get();
        return $query->result();
    }



    ////////////
    // UPDATE //
    ////////////

    function updateMethodes($item_ID, $data)
    {
        $this->db->where('methode_ID', $item_ID);
        $this->db->update('betaalmethodes', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }
}