<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aanmeldingen_model extends CI_Model
{
	/////////
	// GET //
	/////////

	function getAanmeldingen($limit = 0)
	{
		$this->db->select('aanmeldingen.aanmelding_ID, aanmeldingen.aanmelding_archief, aanmeldingen.aanmelding_betaald_datum, aanmeldingen.aanmelding_datum, aanmeldingen.aanmelding_type, workshops.workshop_ID, kennismakingsworkshops.kennismakingsworkshop_ID, workshops.workshop_titel, kennismakingsworkshops.kennismakingsworkshop_titel, gebruikers.gebruiker_ID, gebruikers.gebruiker_naam, aanmeldingen.aanmelding_herinnering_datum, aanmeldingen.annuleringsverzekering');
		$this->db->from('aanmeldingen');
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID', 'left');
		$this->db->join('kennismakingsworkshops', 'kennismakingsworkshops.kennismakingsworkshop_ID = aanmeldingen.kennismakingsworkshop_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
		$this->db->where('aanmelding_verlopen', 0);
		$this->db->order_by('aanmeldingen.aanmelding_datum', 'DESC');
		if($limit > 0) $this->db->limit($limit);
		$query = $this->db->get();
		return $query->result();
	}

	function getAanmeldingenNietBetaald()
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('aanmeldingen.aanmelding_datum <=', date('Y-m-d H:i:s', time() - 3600)); // 3600 = 1 uur
		$this->db->where('aanmeldingen.aanmelding_herinnering_datum', '0000-00-00 00:00:00');
		$this->db->where('aanmeldingen.aanmelding_betaald_datum', '0000-00-00 00:00:00');
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID', 'left');
		$this->db->join('kennismakingsworkshops', 'kennismakingsworkshops.kennismakingsworkshop_ID = aanmeldingen.kennismakingsworkshop_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID', 'left');
		$this->db->join('groepen', 'groepen.groep_ID = aanmeldingen.groep_ID', 'left');
		$this->db->order_by('aanmeldingen.aanmelding_datum', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}

	function getAanmeldingenVerlopen()
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('aanmeldingen.aanmelding_betaald_datum', '0000-00-00 00:00:00');
		$this->db->where('aanmeldingen.aanmelding_herinnering_datum !=', '0000-00-00 00:00:00');
		$this->db->where('aanmeldingen.aanmelding_herinnering_datum <=', date('Y-m-d H:i:s', time() - 86400)); // 86400 = 1 dag - 24 uur
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID', 'left');
		$this->db->join('kennismakingsworkshops', 'kennismakingsworkshops.kennismakingsworkshop_ID = aanmeldingen.kennismakingsworkshop_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID', 'left');
        $this->db->where('gebruikers.gebruiker_status', 'concept');
        $this->db->where('aanmelding_verlopen', 1);
		$this->db->join('groepen', 'groepen.groep_ID = aanmeldingen.groep_ID', 'left');
		$this->db->order_by('aanmeldingen.aanmelding_datum', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}

    function getAanmeldingenVoorArchief()
    {
        $this->db->select('*');
        $this->db->from('aanmeldingen');
        $this->db->where('aanmelding_verlopen', 0);
        $this->db->where('aanmelding_archief', 0);
        $this->db->order_by('aanmelding_datum', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    function getAanmeldingenZijnVerlopen()
    {
        $this->db->select('*');
        $this->db->from('aanmeldingen');
        $this->db->where('aanmeldingen.aanmelding_betaald_datum', '0000-00-00 00:00:00');
        $this->db->where('aanmeldingen.aanmelding_herinnering_datum !=', '0000-00-00 00:00:00');
        $this->db->where('aanmeldingen.aanmelding_herinnering_datum <=', date('Y-m-d H:i:s', time() - 86400)); // 86400 = 1 dag - 24 uur
        $this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID', 'left');
        $this->db->join('kennismakingsworkshops', 'kennismakingsworkshops.kennismakingsworkshop_ID = aanmeldingen.kennismakingsworkshop_ID', 'left');
        $this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID', 'left');
        $this->db->join('groepen', 'groepen.groep_ID = aanmeldingen.groep_ID', 'left');
        $this->db->order_by('aanmeldingen.aanmelding_datum', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

	function getAanmeldingenWorkshopsActief($limit = 0)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
		$this->db->where('aanmelding_afgerond', 0);
		$this->db->where('aanmelding_type', 'workshop');
		$this->db->order_by('aanmeldingen.aanmelding_datum', 'DESC');
		if($limit > 0) $this->db->limit($limit);
		$query = $this->db->get();
		return $query->result();
	}

	function getAanmeldingenWorkshopsAfgerond($limit = 0)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
		$this->db->where('aanmelding_afgerond', 1);
		$this->db->where('aanmelding_type', 'workshop');
		$this->db->order_by('aanmeldingen.aanmelding_datum', 'DESC');
		if($limit > 0) $this->db->limit($limit);
		$query = $this->db->get();
		return $query->result();
	}

	function getAanmeldingenAfspraken($limit = 0)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where_in('aanmelding_type', array('intake', 'stemtest'));
		$this->db->where('aanmelding_afspraak', NULL);
		$this->db->where('aanmelding_verlopen', 0);
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID');
		$this->db->order_by('aanmeldingen.aanmelding_datum', 'DESC');
		if($limit > 0) $this->db->limit($limit);
		$query = $this->db->get();
		return $query->result();
	}

	function getAantalAfspraken()
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where_in('aanmelding_type', array('intake', 'stemtest'));
		$this->db->where('aanmelding_afspraak', NULL);
        $this->db->where('aanmelding_verlopen', 0);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function getAanmeldingenWorkshops($limit = 0)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('aanmelding_type', 'workshop');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID');
		$this->db->order_by('aanmeldingen.aanmelding_datum', 'DESC');
		if($limit > 0) $this->db->limit($limit);
		$query = $this->db->get();
		return $query->result();
	}

	function getAanmeldingByID($aanmelding_ID)
	{
		$this->db->select('aanmeldingen.*, aanmeldingen.workshop_ID AS aanmelding_workshop_ID, workshops.*, groepen.*, gebruikers.*');
		$this->db->from('aanmeldingen');
		$this->db->where('aanmeldingen.aanmelding_ID', $aanmelding_ID);
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID', 'left');
		$this->db->join('kennismakingsworkshops', 'kennismakingsworkshops.kennismakingsworkshop_ID = aanmeldingen.kennismakingsworkshop_ID', 'left');
		$this->db->join('groepen', 'groepen.groep_ID = aanmeldingen.groep_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID', 'left');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getAanmeldingByIDAndCode($aanmelding_ID, $aanmelding_code)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('aanmeldingen.aanmelding_ID', $aanmelding_ID);
		$this->db->where('aanmeldingen.aanmelding_code', $aanmelding_code);
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID', 'left');
		$this->db->join('kennismakingsworkshops', 'kennismakingsworkshops.kennismakingsworkshop_ID = aanmeldingen.kennismakingsworkshop_ID', 'left');
		$this->db->join('groepen', 'groepen.groep_ID = aanmeldingen.groep_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID', 'left');
		$this->db->join('bestellingen', 'bestellingen.aanmelding_ID = aanmeldingen.aanmelding_ID', 'left');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getAanmeldingByIDsAndCode($aanmelding_ID, $workshop_ID, $aanmelding_code)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('aanmeldingen.aanmelding_ID', $aanmelding_ID);
		$this->db->where('aanmeldingen.workshop_ID', $workshop_ID);
		$this->db->where('aanmeldingen.aanmelding_code', $aanmelding_code);
		$this->db->join('groepen', 'groepen.groep_ID = aanmeldingen.groep_ID', 'left');
        $this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID', 'left');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getKennismakingsworkshopAanmeldingByIDsAndCode($aanmelding_ID, $kennismakingsworkshops_ID, $aanmelding_code)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('aanmeldingen.aanmelding_ID', $aanmelding_ID);
		$this->db->where('aanmeldingen.kennismakingsworkshop_ID', $kennismakingsworkshops_ID);
		$this->db->where('aanmeldingen.aanmelding_code', $aanmelding_code);
		$this->db->join('kennismakingsworkshops', 'kennismakingsworkshops.kennismakingsworkshop_ID = aanmeldingen.kennismakingsworkshop_ID', 'left');
		$this->db->join('groepen', 'groepen.groep_ID = aanmeldingen.groep_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID', 'left');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getAanmeldingByGebruikerIDAndWorkshopID($gebruiker_ID, $workshop_ID)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('aanmeldingen.gebruiker_ID', $gebruiker_ID);
		$this->db->where('aanmeldingen.workshop_ID', $workshop_ID);
		$this->db->where('aanmeldingen.aanmelding_type', 'workshop');
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID', 'left');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getAanmeldingDummy($workshop_ID)
	{
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->where('workshops.workshop_ID', $workshop_ID);
		$this->db->join('groepen', 'workshops.workshop_ID = groepen.workshop_ID');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

    function getNietGeexporteerdeAanmeldingen()
    {
        $this->db->select('*');
        $this->db->from('aanmeldingen');
        $this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
        $this->db->where('aanmelding_geexporteerd', 0);
        $this->db->where('aanmelding_betaald_datum !=', '0000-00-00 00:00:00');
        $this->db->order_by('aanmeldingen.aanmelding_ID', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

	function getAanmeldingByGebruikerIDAndWorkshopIDAndAanmeldingType($gebruiker_ID, $workshop_ID, $aanmelding_type)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('aanmeldingen.gebruiker_ID', $gebruiker_ID);
		$this->db->where('aanmeldingen.workshop_ID', $workshop_ID);
		$this->db->where('aanmeldingen.aanmelding_type', $aanmelding_type);
		$this->db->where('aanmeldingen.aanmelding_type', 'workshop');
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID', 'left');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getHoogsteID()
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->order_by('aanmeldingen.aanmelding_ID', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getAfspraken($limit = 0)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where_in('aanmelding_type', array('intake', 'stemtest'));
		$this->db->where('aanmelding_afspraak >=', date('Y-m-d 00:00:00'));
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID', 'left');
		$this->db->order_by('aanmeldingen.aanmelding_afspraak', 'ASC');
		if($limit > 0) $this->db->limit($limit);
		$query = $this->db->get();
		return $query->result();
	}

	function getAfsprakenGeweest($limit = 0)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where_in('aanmelding_type', array('intake', 'stemtest'));
		$this->db->where('aanmelding_afspraak <', date('Y-m-d 00:00:00'));
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID', 'left');
		$this->db->order_by('aanmeldingen.aanmelding_afspraak', 'DESC');
		if($limit > 0) $this->db->limit($limit);
		$query = $this->db->get();
		return $query->result();
	}

	function getAanmeldingenAfsprakenByGebruikerID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where_in('aanmelding_type', array('intake', 'stemtest'));
		$this->db->where('gebruiker_ID', $item_ID);
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID', 'left');
		$this->db->order_by('aanmeldingen.aanmelding_datum', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}

	function getAanmeldingenWorkshopsByGebruikerID($item_ID)
	{
		$this->db->select('aanmeldingen.*, groepen.*, workshops.workshop_ID, workshops.workshop_type, workshops.workshop_titel');
		$this->db->from('aanmeldingen');
		$this->db->where('aanmelding_type', 'workshop');
		$this->db->where('gebruiker_ID', $item_ID);
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID');
		$this->db->join('groepen', 'groepen.groep_ID = aanmeldingen.groep_ID', 'left');
		$this->db->order_by('aanmeldingen.aanmelding_datum', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}

	function getAanmeldingByGebruikerIDAndWorkshopIDAndType($gebruiker_ID, $workshop_ID, $aanmelding_type)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('gebruiker_ID', $gebruiker_ID);
		$this->db->where('workshop_ID', $workshop_ID);
		$this->db->where('aanmelding_type', $aanmelding_type);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

    function getAanmeldingByGebruikerIDAndKennismakingsworkshopID($gebruiker_ID, $kennismakingsworkshop_ID)
    {
        $this->db->select('*');
        $this->db->from('aanmeldingen');
        $this->db->where('gebruiker_ID', $gebruiker_ID);
        $this->db->where('kennismakingsworkshop_ID', $kennismakingsworkshop_ID);
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

	function getAanmeldingByGebruikerIDAndKennismakingsworkshopIDAndType($gebruiker_ID, $kennismakingsworkshop_ID, $aanmelding_type)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('gebruiker_ID', $gebruiker_ID);
		$this->db->where('kennismakingsworkshop_ID', $kennismakingsworkshop_ID);
		$this->db->where('aanmelding_type', $aanmelding_type);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getAanmeldingByGebruikerIDAndWorkshopIDAndAanmeldingCode($gebruiker_ID, $workshop_ID, $aanmelding_code)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('gebruiker_ID', $gebruiker_ID);
		$this->db->where('workshop_ID', $workshop_ID);
		$this->db->where('aanmelding_code', $aanmelding_code);
        $this->db->where('aanmelding_betaald_datum !=', '0000-00-00 00:00:00');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getAanmeldingByGebruikersIDAndAanmeldingsCode($gebruiker_ID, $aanmelding_code) {
			$this->db->select('*');
			$this->db->from('aanmeldingen');
			$this->db->where('gebruiker_ID', $gebruiker_ID);
			$this->db->where('aanmelding_code', $aanmelding_code);
			$this->db->where('aanmelding_betaald_datum !=', '0000-00-00 00:00:00');
			$query = $this->db->get();
			if($query->num_rows() > 0) return $query->row();
			else return null;
	}

	function getAanmeldingAfspraakByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('aanmelding_ID', $item_ID);
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getAanmeldingenByGroepID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('groep_ID', $item_ID);
		$query = $this->db->get();
		return $query->result();
	}

	function getAanmeldingStemtestByEmail($email)
	{
		$this->db->select('aanmeldingen.*, workshops.*');
		$this->db->from('gebruikers');
		$this->db->join('aanmeldingen', 'aanmeldingen.gebruiker_ID = gebruikers.gebruiker_ID', 'left');
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID');
		$this->db->where('gebruiker_emailadres', $email);
		$this->db->where('aanmelding_betaald_datum !=', '0000-00-00 00:00:00');
		$this->db->where('aanmelding_type', 'stemtest');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getAanmeldingAantalMetStemtestKortingByEmail($email)
	{
		$this->db->select('aanmeldingen.*');
		$this->db->from('gebruikers');
		$this->db->join('aanmeldingen', 'aanmeldingen.gebruiker_ID = gebruikers.gebruiker_ID', 'left');
		$this->db->where('gebruiker_emailadres', $email);
		$this->db->where('aanmelding_betaald_datum !=', '0000-00-00 00:00:00');
		$this->db->where('aanmelding_stemtest_korting', 'ja');
		return $this->db->count_all_results();
	}

	function getAanmeldingenByWorkshopID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('workshop_ID', $item_ID);
		$query = $this->db->get();
		return $query->result();
	}

	function getAanmeldingKennismakingsworkshopByEmail($email)
	{
		$this->db->select('aanmeldingen.*, kennismakingsworkshops.*');
		$this->db->from('gebruikers');
		$this->db->join('aanmeldingen', 'aanmeldingen.gebruiker_ID = gebruikers.gebruiker_ID', 'left');
		$this->db->join('kennismakingsworkshops', 'kennismakingsworkshops.kennismakingsworkshop_ID = aanmeldingen.kennismakingsworkshop_ID', 'left');
		$this->db->where('gebruiker_emailadres', $email);
		$this->db->where('aanmelding_betaald_datum !=', '0000-00-00 00:00:00');
		$this->db->where('aanmelding_type', 'kennismakingsworkshop');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getAanmeldingAantalMetKennismakingsworkshopKortingByEmail($email)
	{
		$this->db->select('aanmeldingen.*');
		$this->db->from('gebruikers');
		$this->db->join('aanmeldingen', 'aanmeldingen.gebruiker_ID = gebruikers.gebruiker_ID', 'left');
		$this->db->where('gebruiker_emailadres', $email);
		$this->db->where('aanmelding_betaald_datum !=', '0000-00-00 00:00:00');
		$this->db->where('aanmelding_kennismakingsworkshop_korting', 'ja');
		return $this->db->count_all_results();
	}

	function getKalenderAfspraken()
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where_in('aanmelding_type', array('intake', 'stemtest'));
		$this->db->where('aanmelding_betaald_datum !=', '0000-00-00 00:00:00');
		$this->db->where('aanmelding_afspraak !=', '0000-00-00 00:00:00');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID');
		$query = $this->db->get();
		return $query->result();
	}



	////////////
	// INSERT //
	////////////

	function insertAanmelding($data)
	{
		$this->db->insert('aanmeldingen', $data);
		if($this->db->affected_rows() == 1) return $this->db->insert_id();
		else return 0;
	}



	////////////
	// UPDATE //
	////////////

	function updateAanmeldingAfspraak($item_ID, $data)
	{
		$this->db->where('aanmelding_ID', $item_ID);
		$this->db->update('aanmeldingen', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

	function updateAanmeldingByID($aanmelding_ID, $data)
	{
		$this->db->where('aanmelding_ID', $aanmelding_ID);
		$this->db->update('aanmeldingen', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

	function updateAanmelding($gebruiker_ID, $workshop_ID, $aanmelding_code, $data)
	{
		$this->db->where('gebruiker_ID', $gebruiker_ID);
		$this->db->where('workshop_ID', $workshop_ID);
		$this->db->where('aanmelding_code', $aanmelding_code);
		$this->db->update('aanmeldingen', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

	function updateAanmeldingByGebruikerWorkshop($gebruiker_ID, $groep_ID, $workshop_ID, $data)
	{
		$this->db->where('gebruiker_ID', $gebruiker_ID);
		$this->db->where('workshop_ID', $workshop_ID);
		$this->db->where('groep_ID', $groep_ID);
		$this->db->update('aanmeldingen', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

	function getExportGeschiedenis() {
        $this->db->select('*');
        $this->db->from('exports');
        $this->db->order_by('export_ID', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    function getExportByID($item_ID) {
        $this->db->select('*');
        $this->db->from('exports');
        $this->db->where('export_ID', $item_ID);
        $query = $this->db->get();
        return $query->row();
    }

    function insertExport($data) {
        $this->db->insert('exports', $data);
        if($this->db->affected_rows() == 1) return $this->db->insert_id();
        else return 0;
    }

    function deleteExport($item_ID) {
        $this->db->where('export_ID', $item_ID);
        $this->db->delete('exports');

        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function getExportAanmeldingen($begin_ID, $eind_ID) {
        $this->db->select('*');
        $this->db->from('aanmeldingen');
        $this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
        $this->db->where('aanmelding_betaald_datum !=', '0000-00-00 00:00:00');
        $this->db->where('aanmelding_ID >=', $begin_ID);
        $this->db->where('aanmelding_ID <=', $eind_ID);
        $this->db->order_by('aanmeldingen.aanmelding_ID', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

	////////////
	// DELETE //
	////////////

	function deleteAanmelding($aanmelding_ID)
	{
		// Aanmelding ophalen

		$aanmelding = $this->getAanmeldingByID($aanmelding_ID);

		if($aanmelding != null)
		{
			// Bestelling ophalen

			$this->db->select('*');
			$this->db->from('bestellingen');
			$this->db->where('aanmelding_ID', $aanmelding->aanmelding_ID);
			$bestellingen_query = $this->db->get();

			if($bestellingen_query->num_rows() > 0)
			{
				$bestelling = $bestellingen_query->row();

				// Bestelling producten verwijderen

				$this->db->where('bestelling_ID', $bestelling->bestelling_ID);
				$this->db->delete('bestellingen_producten');

				// Bestelling verwijderen

				$this->db->where('bestelling_ID', $bestelling->bestelling_ID);
				$this->db->delete('bestellingen');
			}

			// Aanmelding verwijderen

			$this->db->where('aanmelding_ID', $aanmelding_ID);
			$this->db->delete('aanmeldingen');

			if($this->db->affected_rows() == 1) return true;
			else return false;
		}
		else
		{
			return false;
		}
	}
	public function get_permissions()
	{
		$query = $this->db->get('permissions');
		return $query->result();
	}
}
