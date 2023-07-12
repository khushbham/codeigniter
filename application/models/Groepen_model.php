<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Groepen_model extends CI_Model
{
	/////////
	// GET //
	/////////

	function getGroepen($aantal = 0, $pagina = 1)
	{
		$this->db->select('*');
		$this->db->from('groepen');
		$this->db->join('workshops', 'workshops.workshop_ID = groepen.workshop_ID');
		if($aantal > 0) $this->db->limit($aantal, (($pagina - 1) * $aantal));
		$query = $this->db->get();
		return $query->result();
	}

	function getGroepenActief($aantal = 0, $pagina = 1)
	{
		$this->db->select('*');
		$this->db->from('groepen');
		$this->db->where('groepen.groep_archiveren', 0);
		$this->db->where('groep_actief_datum !=', 0000-00-00);
		$this->db->where('groep_actief_datum <=', date('Y-m-d'));
		$this->db->where('groep_archief_datum >=', date('Y-m-d'));
		$this->db->where('groep_actief_datum !=', NULL);
		$this->db->join('workshops', 'workshops.workshop_ID = groepen.workshop_ID');
		if($aantal > 0) $this->db->limit($aantal, (($pagina - 1) * $aantal));
		$query = $this->db->get();
		return $query->result();
	}

	function getGroepenByLesID($item_ID) {
        $this->db->select('*');
        $this->db->from('groepen');
        $this->db->join('groepen_lessen', 'groepen.groep_ID = groepen_lessen.groep_ID');
        $this->db->where('groepen_lessen.les_ID', $item_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function getAlleGroepen($aantal = 0, $pagina = 1)
    {
        $this->db->select('*');
        $this->db->from('groepen');
        $this->db->join('workshops', 'workshops.workshop_ID = groepen.workshop_ID');
        $this->db->where('groepen.groep_archiveren', 0);
        if($aantal > 0) $this->db->limit($aantal, (($pagina - 1) * $aantal));
        $query = $this->db->get();
        return $query->result();
	}

	function getGroepenAanmelden()
	{
		$this->db->select('*');
		$this->db->from('groepen');
		$this->db->where('groep_aanmelden', 'ja');
		$this->db->join('workshops', 'workshops.workshop_ID = groepen.workshop_ID');
		$this->db->where('groep_startdatum >', date('Y-m-d H:i:s'));
		$query = $this->db->get();
		return $query->result();
	}

    function getGroepenArchief($aantal = 0, $pagina = 1)
    {
        $this->db->select('*');
        $this->db->from('groepen');
        $this->db->join('workshops', 'workshops.workshop_ID = groepen.workshop_ID');
		$this->db->where('groepen.groep_archiveren', 1);
        if($aantal > 0) $this->db->limit($aantal, (($pagina - 1) * $aantal));
        $query = $this->db->get();
        return $query->result();
    }

	function GroepArchiverenByID($groep_ID, $data) {
        $this->db->where('groep_ID', $groep_ID);
        $this->db->update('groepen', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

	function getGroepenMetCursisten()
	{
		$this->db->select('*');
		$this->db->from('groepen');
		$query = $this->db->get();
		$groepen = $query->result();

		foreach($groepen as $key => $groep)
		{
			// Controleren of de groep aanmeldingen heeft

			$this->db->select('*');
			$this->db->from('aanmeldingen');
			$this->db->where('groep_ID', $groep->groep_ID);
			$query_aanmeldingen = $this->db->get();
			$aanmeldingen = $query_aanmeldingen->result();


			// Groep verwijderen uit resultaten

			if(sizeof($aanmeldingen) == 0)
			{
				unset($groepen[$key]);
			}
		}

		return $groepen;
	}

    function getGroepenMetCursistenByGroepID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('groepen');
        $this->db->where('groep_ID', $item_ID);
        $query = $this->db->get();
        $groepen = $query->result();

        foreach($groepen as $key => $groep)
        {
            // Controleren of de groep aanmeldingen heeft

            $this->db->select('*');
            $this->db->from('aanmeldingen');
            $this->db->where('groep_ID', $groep->groep_ID);
            $query_aanmeldingen = $this->db->get();
            $aanmeldingen = $query_aanmeldingen->result();


            // Groep verwijderen uit resultaten

            if(sizeof($aanmeldingen) == 0)
            {
                unset($groepen[$key]);
            }
        }

        return $groepen;
    }

	function getGroepenAantal()
	{
		$this->db->select('*');
		$this->db->from('groepen');

		return $this->db->count_all_results();
	}

	function getGroepenActiefAantal()
	{
		$this->db->select('*');
		$this->db->from('groepen');
		$this->db->where('groep_archiveren', 0);
		$this->db->where('groep_actief_datum !=', 0000-00-00);
		$this->db->where('groep_actief_datum <=', date('Y-m-d'));
		$this->db->where('groep_archief_datum >=', date('Y-m-d'));
		$this->db->join('workshops', 'workshops.workshop_ID = groepen.workshop_ID');
		return $this->db->count_all_results();
	}

    function getGroepenArchiefAantal()
    {
        $this->db->select('*');
        $this->db->from('groepen');
		$this->db->where('groep_archiveren', 1);
		$this->db->or_where('groep_archief_datum <', date('Y-m-d'));

        return $this->db->count_all_results();
    }

	function getGroepenByWorkshopID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('groepen');
        $this->db->where('groep_archiveren', 0);
		$this->db->where('workshop_ID', $item_ID);
		$query = $this->db->get();
		return $query->result();
	}

    function getGroepenArchiefByWorkshop_ID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('groepen');
        $this->db->where('groep_archiveren', 1);
        $this->db->where('workshop_ID', $item_ID);
        $query = $this->db->get();
        return $query->result();
    }

	function getGroepenAanmeldenByWorkshopID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('groepen');
		$this->db->where('groep_aanmelden', 'ja');
		$this->db->where('groep_startdatum >', date('Y-m-d H:i:s'));
		$this->db->where('workshop_ID', $item_ID);
		$query = $this->db->get();
		return $query->result();
	}

	function getGroepenLessenByDocentGebruikerID($item_ID) {
		$vandaag = new DateTime();
		$vorige_week = new DateTime();
		$volgende_week = new DateTime();

		$volgende_week->add(DateInterval::createFromDateString('14 days'));
		$vorige_week->add(DateInterval::createFromDateString('-2 days'));

        $this->db->select('*, groepen.groep_ID, docenten.docent_ID');
        $this->db->from('docenten');
		$this->db->join('groepen_lessen', 'groepen_lessen.docent_ID = docenten.docent_ID');
		$this->db->join('locaties', 'groepen_lessen.les_locatie_ID = locaties.locatie_ID', 'Left');
		$this->db->join('groepen', 'groepen_lessen.groep_ID = groepen.groep_ID');
		$this->db->join('lessen', 'groepen_lessen.les_ID = lessen.les_ID');
		$this->db->join('workshops', 'lessen.workshop_ID = workshops.workshop_ID');
		$this->db->where('docenten.gebruiker_ID', $item_ID);
		$this->db->where('groep_les_datum >=', $vorige_week->format('Y-m-d H:i:s'));
		$this->db->where('groep_les_datum <=', $volgende_week->format('Y-m-d H:i:s'));
		$this->db->order_by('groep_les_datum', 'asc');
        $query = $this->db->get();
        return $query->result();
	}

	function getGroepLocatie($item_ID)
	{
		$this->db->select('*');
		$this->db->from('groepen_lessen');
        $this->db->where('groepen_lessen.groep_ID', $item_ID);
        $this->db->join('locaties', 'groepen_lessen.les_locatie_ID = locaties.locatie_ID', 'left');
		$query = $this->db->get();
		return $query->result();
	}

	function getLocaties()
	{
		$this->db->select('*');
		$this->db->from('locaties');
		$query = $this->db->get();
		return $query->result();
    }


	function getStartdatumByGroepID($item_ID)
	{
		$this->db->select('groep_startdatum');
		$this->db->from('groepen');
		$this->db->where('groep_ID', $item_ID);
		$this->db->join('workshops', 'workshops.workshop_ID = groepen.workshop_ID');
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$groep = $query->row();
			return $groep->groep_startdatum;
		}
		else return null;
	}

	function getGroepByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('groepen');
		$this->db->where('groep_ID', $item_ID);
		$this->db->join('workshops', 'workshops.workshop_ID = groepen.workshop_ID');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getGroepDeelnemers($item_ID)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
		$this->db->where('aanmelding_type', 'workshop');
		$this->db->where('groep_ID', $item_ID);
		$this->db->where('gebruiker_status !=', "concept");
		// $this->db->where('aanmeldingen.aanmelding_betaald_datum !=', '0000-00-00 00:00:00');
		$this->db->where('aanmelding_verlopen', "0");
		$this->db->order_by('aanmelding_datum');
		$query = $this->db->get();
		return $query->result();
	}


	function zoekGroepen($zoekterm)
	{
		$this->db->select('*');
		$this->db->from('groepen');
		$this->db->like('groep_naam', $zoekterm);
		$query = $this->db->get();
		return $query->result();
	}

    function getGroepenMetCursistenBerichtenLijst()
    {
        $this->db->select('*');
        $this->db->from('groepen');
        $this->db->where('groep_archiveren', 0);
        $query = $this->db->get();
        $groepen = $query->result();

        foreach($groepen as $key => $groep)
        {
            // Controleren of de groep aanmeldingen heeft

            $this->db->select('*');
            $this->db->from('aanmeldingen');
            $this->db->where('groep_ID', $groep->groep_ID);
            $query_aanmeldingen = $this->db->get();
            $aanmeldingen = $query_aanmeldingen->result();


            // Groep verwijderen uit resultaten

            if(sizeof($aanmeldingen) == 0)
            {
                unset($groepen[$key]);
            }
        }

        return $groepen;
    }


	////////////
	// INSERT //
	////////////

	function insertGroep($data)
	{
		$this->db->insert('groepen', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}



	////////////
	// UPDATE //
	////////////

	function updateGroep($item_ID, $data)
	{
		$this->db->where('groep_ID', $item_ID);
		$this->db->update('groepen', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}



	////////////
	// DELETE //
	////////////

	function deleteGroep($item_ID)
	{
		$this->db->where('groep_ID', $item_ID);
		$this->db->delete('groepen');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

	function deleteLessenByGroepID($item_ID)
	{
		$this->db->where('groep_ID', $item_ID);
		$this->db->delete('groepen_lessen');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	function getGroepDeelnemersVermelding($item_ID)
	{
		$this->db->select('groepen.groep_naam,groepen.groep_startdatum,workshops.workshop_titel,workshops.workshop_locatie,aanmeldingen.aanmelding_ID,aanmeldingen.aanmelding_type,gebruikers.gebruiker_naam,gebruikers.gebruiker_geboortedatum');
		$this->db->from('aanmeldingen');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
		$this->db->join('groepen', 'groepen.groep_ID = aanmeldingen.groep_ID');
		$this->db->join('workshops','workshops.workshop_ID = aanmeldingen.workshop_ID');
		$this->db->where('aanmeldingen.aanmelding_type', 'workshop');
		$this->db->where('aanmeldingen.groep_ID', $item_ID);
		$this->db->where('gebruikers.gebruiker_status !=', "concept");
		$this->db->where('aanmeldingen.aanmelding_verlopen', "0");
		$this->db->order_by('aanmeldingen.aanmelding_datum');
		$query = $this->db->get();
		return $query->result();
	}

	//get the data of gebrukers pay the all deposite
	function getpaidStatus($gebruiker_id){
		$this->db->select();
		$this->db->from('stem_bestellingen_los');
		$this->db->where('stem_bestellingen_los.gebruiker_ID', $gebruiker_id);
		$query = $this->db->get();
		return $query->row();
	}
}


