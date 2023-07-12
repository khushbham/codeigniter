<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kortingscodes_model extends CI_Model
{
    /////////
    // GET //
    /////////

    function getWorkshops()
    {
        $this->db->select('*');
        $this->db->from('workshops');
        $query = $this->db->get();
        return $query->result();
    }

    function getKortingscodeByID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('kortingscodes');
        $this->db->where('kortingscode_ID', $item_ID);
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

    function getUpsellingByID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('upselling');
        $this->db->join('workshops', 'upselling.workshop_ID = workshops.workshop_ID', 'left');
        $this->db->where('upselling_ID', $item_ID);
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

    function getUpsellingByworkshopID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('upselling');
        $this->db->join('workshops', 'upselling.workshop_ID = workshops.workshop_ID', 'left');
        $this->db->where('upselling.workshop_ID', $item_ID);
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

    function updateKortingscodesLimiet($item_ID, $data)
    {
        $this->db->where('kortingscode_ID', $item_ID);
        $this->db->update('kortingscodes', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function updateUpselling($item_ID, $data)
    {
        $this->db->where('upselling_ID', $item_ID);
        $this->db->update('upselling', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }
    function updateUpsellingConnectie($item_ID, $product_ID, $data)
    {
        $this->db->where('upselling_connectie_ID', $item_ID);
        $this->db->where('product_ID', $product_ID);
        $this->db->update('upselling_connecties', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }


    function insertKortingscode($data)
    {
        $this->db->insert('kortingscodes', $data);
        if($this->db->affected_rows() == 1) return $this->db->insert_id();
        else return false;
    }

    function insertUpselling($data)
    {
        $this->db->insert('upselling', $data);
        if($this->db->affected_rows() == 1) return $this->db->insert_id();
        else return false;
    }

    function insertUpsellingConnectie($data)
    {
        $this->db->insert('upselling_connecties', $data);
        if($this->db->affected_rows() == 1) return $this->db->insert_id();
        else return false;
    }

    function koppelKortingscodes($data)
    {
        $this->db->insert('korting_connecties', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function getKortingConnectiesByID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('korting_connecties');
        $this->db->where('kortingscode_ID', $item_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function getKortingscodesByCode($kortingscode)
    {
        $this->db->select('*');
        $this->db->from('kortingscodes');
        $this->db->where('kortingscode', $kortingscode);
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

    function getVerlopenKortingscodes($vandaag) {
        $this->db->select('*');
        $this->db->from('kortingscodes');
        $this->db->where('kortingscode_einddatum < ', $vandaag);
        $this->db->where('kortingscode_einddatum != ', '0000-00-00');
        $query = $this->db->get();
        return $query->result();
    }

    function getKortingscodesCMS()
    {
        $this->db->select('*');
        $this->db->from('kortingscodes');
        $this->db->order_by('kortingscode_ID', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    function getUpselling()
    {
        $this->db->select('*');
        $this->db->from('upselling');
        $this->db->join('workshops', 'upselling.workshop_ID = workshops.workshop_ID', 'left');
        $this->db->order_by('upselling_ID', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    function getUpsellingConnectieByIDs($item_ID, $product_ID) {
        $this->db->select('*');
        $this->db->from('upselling_connecties');
        $this->db->join('producten', 'upselling_connecties.product_ID = producten.product_ID', 'left');
        $this->db->join('upselling', 'upselling_connecties.upselling_ID = upselling.upselling_ID', 'left');
        $this->db->where('upselling_connecties.upselling_ID', $item_ID);
        $this->db->where('producten.product_ID', $product_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function getUpsellingConnectiesByIDs($item_ID) {
        $this->db->select('*');
        $this->db->from('upselling_connecties');
        $this->db->join('upselling', 'upselling_connecties.upselling_ID = upselling.upselling_ID', 'left');
        $this->db->join('workshops', 'upselling.workshop_ID = workshops.workshop_ID', 'left');
        $this->db->where('upselling.upselling_ID', $item_ID);
        $query = $this->db->get();
        return $query->result();
    }

    ////////////
    // DELETE //
    ////////////

    function deleteKortingscode($item_ID)
    {
        $this->db->where('kortingscode_ID', $item_ID);
        $this->db->delete('kortingscodes');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function deleteKortingConnecties($item_ID)
    {
        $this->db->where('kortingscode_ID', $item_ID);
        $this->db->delete('korting_connecties');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function deleteUpselling($item_ID)
    {
        $this->db->where('upselling_ID', $item_ID);
        $this->db->delete('upselling');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function deleteUpsellingConnecties($item_ID)
    {
        $this->db->where('upselling_ID', $item_ID);
        $this->db->delete('upselling_connecties');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }
}
