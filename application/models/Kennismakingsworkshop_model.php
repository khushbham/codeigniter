<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kennismakingsworkshop_model extends CI_Model
{
	/////////
	// GET //
	/////////
	
	function getKennismakingsworkshops()
	{
		$this->db->select('*');
		$this->db->from('kennismakingsworkshops');
		$this->db->order_by('kennismakingsworkshop_datum', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	function getKennismakingsworkshopAantal()
	{
		$this->db->select('*');
		$this->db->from('kennismakingsworkshops');
		return $this->db->count_all_results();
	}
	
	function getKennismakingsworkshopByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('kennismakingsworkshops');
		$this->db->join('media', 'media.media_ID = kennismakingsworkshops.media_ID', 'left');
		$this->db->where('kennismakingsworkshop_ID', $item_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getKennismakingsworkshopByDatum($datum)
	{
		$this->db->select('*');
		$this->db->from('kennismakingsworkshops');
		$this->db->join('media', 'media.media_ID = kennismakingsworkshops.media_ID', 'left');
		$this->db->where('DATE_FORMAT(kennismakingsworkshop_datum, "%d-%m-%Y") =', $datum);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getKennismakingsworkshopDeelnemersByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('kennismakingsworkshop_ID', $item_ID);
		$this->db->where('aanmelding_type', 'kennismakingsworkshop');
		$this->db->where('aanmelding_betaald_datum !=', '0000-00-00 00:00:00');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
		$query = $this->db->get();
		return $query->result();
	}

	function getKennismakingsworkshopDeelnemersAantal($item_ID)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('aanmelding_type', 'kennismakingsworkshop');
		$this->db->where('kennismakingsworkshop_ID', $item_ID);
		$this->db->order_by('aanmelding_datum');
		return $this->db->count_all_results();
	}

    function getKennismakingsworkshopsByGebruikerID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('aanmeldingen');
        $this->db->where('aanmeldingen.gebruiker_ID', $item_ID);
        $this->db->where('aanmeldingen.aanmelding_type', 'kennismakingsworkshop');
        $this->db->where('aanmeldingen.aanmelding_betaald_datum !=', '0000-00-00 00:00:00');
        $this->db->join('kennismakingsworkshops', 'kennismakingsworkshops.kennismakingsworkshop_ID = aanmeldingen.kennismakingsworkshop_ID');
        $this->db->join('media', 'media.media_ID = kennismakingsworkshops.media_uitgelicht_ID', 'left');
        $this->db->order_by('kennismakingsworkshops.kennismakingsworkshop_datum', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function getKennismakingsworkshopMediaByWorkshopID($item_ID) {
        $this->db->select('*');
        $this->db->from('kennismakingsworkshop_media');
        $this->db->where('kennismakingsworkshop_media.kennismakingsworkshop_ID', $item_ID);
        $this->db->join('gebruikers', 'kennismakingsworkshop_media.gebruiker_ID = gebruikers.gebruiker_ID');
        $query = $this->db->get();
        return $query->result();
    }

    function getKennismakingsworkshopMediaByWorkshopIDandGebruikerID($item_ID, $gebruiker_ID) {
        $this->db->select('*');
        $this->db->from('kennismakingsworkshop_media');
        $this->db->where('kennismakingsworkshop_media.kennismakingsworkshop_ID', $item_ID);
        $this->db->where('kennismakingsworkshop_media.gebruiker_ID', $gebruiker_ID);
        $this->db->join('gebruikers', 'kennismakingsworkshop_media.gebruiker_ID = gebruikers.gebruiker_ID');
        $query = $this->db->get();
        return $query->result();
    }

    function insertMedia($data)
    {
        $this->db->insert('kennismakingsworkshop_media', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function deleteKennismakingsMedia($item_ID) {
        $this->db->where('kennismakings_media_ID', $item_ID);
        $this->db->delete('kennismakingsworkshop_media');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function getKennismakingsworkkshopMediaByID($item_ID) {
        $this->db->select('*');
        $this->db->from('kennismakingsworkshop_media');
        $this->db->where('kennismakingsworkshop_media.kennismakings_media_ID', $item_ID);
        $this->db->join('gebruikers', 'kennismakingsworkshop_media.gebruiker_ID = gebruikers.gebruiker_ID');
        $query = $this->db->get();
        return $query->result();
    }


	function getVolgendeGepubliceerdeKennismakingsworkshopHome()
	{
		$now = date('Y-m-d H:i:s', time());

		$this->db->select('*');
		$this->db->from('kennismakingsworkshops');
		$this->db->join('media', 'media.media_ID = kennismakingsworkshops.media_uitgelicht_ID', 'left');
		$this->db->order_by('kennismakingsworkshop_datum', 'asc');
		$this->db->where('kennismakingsworkshop_publicatiedatum <= ', $now); // al gepubliceerd?
		$this->db->where('DATE_SUB(kennismakingsworkshop_datum, INTERVAL 1 HOUR) >= ', $now); // is de kennismakingsworkshop nog niet geweest? max. 1 uur van tevoren aanmelden
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getVolgendeGepubliceerdeKennismakingsworkshop()
	{
		$now = date('Y-m-d H:i:s', time());
		
		$this->db->select('*');
		$this->db->from('kennismakingsworkshops');
		$this->db->join('media', 'media.media_ID = kennismakingsworkshops.media_ID', 'left');
		$this->db->order_by('kennismakingsworkshop_datum', 'asc');
		$this->db->where('kennismakingsworkshop_publicatiedatum <= ', $now); // al gepubliceerd?
		$this->db->where('DATE_SUB(kennismakingsworkshop_datum, INTERVAL 1 HOUR) >= ', $now); // is de kennismakingsworkshop nog niet geweest? max. 1 uur van tevoren aanmelden
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function zoekKennismakingsworkshop($zoekterm)
	{
		$this->db->select('*');
		$this->db->from('kennismakingsworkshops');
		$this->db->like('kennismakingsworkshop_titel', $zoekterm);
		$query = $this->db->get();
		return $query->result();
	}

	////////////
	// INSERT //
	////////////
	
	function insertKennismakingsworkshop($data)
	{
		$this->db->insert('kennismakingsworkshops', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	
	
	////////////
	// UPDATE //
	////////////
	
	function updateKennismakingsworkshop($item_ID, $data)
	{
		$this->db->where('kennismakingsworkshop_ID', $item_ID);
		$this->db->update('kennismakingsworkshops', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	
	
	////////////
	// DELETE //
	////////////
	
	function deleteKennismakingsworkshop($item_ID)
	{
		$this->db->where('kennismakingsworkshop_ID', $item_ID);
		$this->db->delete('kennismakingsworkshops');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
}
