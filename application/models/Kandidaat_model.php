<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kandidaat_model extends CI_Model
{
    /////////
    // GET //
    /////////

    function getKandidaatWorkshops()
    {
        $this->db->select('*');
        $this->db->from('kandidaat_workshops');
        $this->db->join('workshops', 'kandidaat_workshops.workshop_ID = workshops.workshop_ID', 'left');
        $query = $this->db->get();
        return $query->result();
    }

    function getKandidaatProducten()
    {
        $this->db->select('*');
        $this->db->from('kandidaat_producten');
        $this->db->join('producten', 'kandidaat_producten.product_ID = producten.product_ID', 'left');
        $query = $this->db->get();
        return $query->result();
    }

    function getKandidaatWorkshopByID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('kandidaat_workshops');
        $this->db->join('workshops', 'kandidaat_workshops.workshop_ID = workshops.workshops_ID', 'left');
        $this->db->where('kandidaat_workshops.kandidaat_workshop_ID', $item_ID);
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

    function getKandidaatProductByID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('kandidaat_products');
        $this->db->join('producten', 'kandidaat_producten.product_ID = producten.product_ID', 'left');
        $this->db->where('kandidaat_product.kandidaat_product_ID', $item_ID);
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

    ////////////
    // INSERT //
    ////////////

    function insertKandidaatProduct($data)
    {
        $this->db->insert('kandidaat_producten', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function insertKandidaatWorkshop($data)
    {
        $this->db->insert('kandidaat_workshops', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    ////////////
    // DELETE //
    ////////////

    function deleteKandidaatProduct($item_ID)
    {
        $this->db->where('product_ID', $item_ID);
        $this->db->delete('kandidaat_producten');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function deleteKandidaatWorkshop($item_ID)
    {
        $this->db->where('workshop_ID', $item_ID);
        $this->db->delete('kandidaat_workshops');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function ClearTableWorkshops()
    {
        $this->db->where('kandidaat_workshop_ID !=', '0');
        $this->db->delete('kandidaat_workshops');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function ClearTableProducten()
    {
        $this->db->where('kandidaat_product_ID !=', '0');
        $this->db->delete('kandidaat_producten');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }
}