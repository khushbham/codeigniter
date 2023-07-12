<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workshops_model extends CI_Model
{
	/////////
	// GET //
	/////////

	function getWorkshops()
	{
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->where('workshop_gepubliceerd', 'ja');
		$this->db->where('workshop_ID !=', 10);
        $this->db->where('workshop_archiveren', 0);
		$this->db->order_by('workshop_positie', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getAlleWorkshops()
	{
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->where('workshop_ID !=', 10);
        $this->db->where('workshop_archiveren', 0);
		$this->db->order_by('workshop_positie', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getWorkshopsCMS()
	{
		$this->db->select('*');
		$this->db->from('workshops');
        $this->db->where('workshop_archiveren', 0);
		$this->db->order_by('workshop_positie', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getWorkshopsLessenOverzicht()
	{
		$this->db->select('*');
		$this->db->from('workshops');
        $this->db->where('workshop_archiveren', 0);
		$this->db->order_by('workshop_titel', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getWorkshopsStandaard()
	{
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->where('workshop_specialty', 'nee');
		$this->db->where('workshop_gepubliceerd', 'ja');
        $this->db->where('workshop_archiveren', 0);
		$this->db->where('workshop_ID !=', 10);
		$this->db->order_by('workshop_positie', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getWorkshopsStandaardCMS()
	{
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->where('workshop_specialty', 'nee');
        $this->db->where('workshop_archiveren', 0);
		$this->db->order_by('workshop_positie', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

    function getWorkshopsStandaardCMSArchief()
    {
        $this->db->select('*');
        $this->db->from('workshops');
        $this->db->where('workshop_specialty', 'nee');
        $this->db->where('workshop_archiveren', 1);
        $this->db->order_by('workshop_positie', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

	function getWorkshopsSpecialties()
	{
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->where('workshop_specialty', 'ja');
		$this->db->where('workshop_gepubliceerd', 'ja');
		$this->db->where('workshop_ID !=', 10);
        $this->db->where('workshop_archiveren', 0);
		$this->db->order_by('workshop_positie', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

    function getWorkshopsSpecialtiesArchief()
    {
        $this->db->select('*');
        $this->db->from('workshops');
        $this->db->where('workshop_specialty', 'ja');
        $this->db->where('workshop_gepubliceerd', 'ja');
        $this->db->where('workshop_ID !=', 10);
        $this->db->where('workshop_archiveren', 1);
        $this->db->order_by('workshop_positie', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

	function getWorkshopsSpecialtiesCMS()
	{
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->where('workshop_specialty', 'ja');
		$this->db->where('workshop_ID !=', 10);
        $this->db->where('workshop_archiveren', 0);
		$this->db->order_by('workshop_positie', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

    function getWorkshopsSpecialtiesCMSArchief()
    {
        $this->db->select('*');
        $this->db->from('workshops');
        $this->db->where('workshop_specialty', 'ja');
        $this->db->where('workshop_ID !=', 10);
        $this->db->where('workshop_archiveren', 1);
        $this->db->order_by('workshop_positie', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

	function getWorkshopsUitgelicht()
	{
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->join('media', 'media.media_ID = workshops.media_uitgelicht_ID', 'left');
		$this->db->where('workshop_uitgelicht', 'ja');
		$this->db->where('workshop_gepubliceerd', 'ja');
		$this->db->where('workshop_ID !=', 10);
        $this->db->where('workshop_archiveren', 0);
		$this->db->order_by('workshop_positie', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

    function getWorkshopsUitgelichtArchief()
    {
        $this->db->select('*');
        $this->db->from('workshops');
        $this->db->join('media', 'media.media_ID = workshops.media_uitgelicht_ID', 'left');
        $this->db->where('workshop_uitgelicht', 'ja');
        $this->db->where('workshop_gepubliceerd', 'ja');
        $this->db->where('workshop_ID !=', 10);
        $this->db->where('workshop_archiveren', 1);
        $this->db->order_by('workshop_positie', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

	function getWorkshopsUitgelichtCMS()
	{
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->where('workshop_uitgelicht', 'ja');
		$this->db->where('workshop_ID !=', 10);
        $this->db->where('workshop_archiveren', 0);
		$this->db->order_by('workshop_positie', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

    function getWorkshopsUitgelichtCMSArchief()
    {
        $this->db->select('*');
        $this->db->from('workshops');
        $this->db->where('workshop_uitgelicht', 'ja');
        $this->db->where('workshop_ID !=', 10);
        $this->db->where('workshop_archiveren', 1);
        $this->db->order_by('workshop_positie', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

	function getWorkshopsVervolg()
	{
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->join('media', 'media.media_ID = workshops.media_uitgelicht_ID', 'left');
		$this->db->where('workshop_uitgelicht', 'nee');
		$this->db->where('workshop_gepubliceerd', 'ja');
		$this->db->where('workshop_ID !=', 6);
		$this->db->where('workshop_ID !=', 10);
        $this->db->where('workshop_archiveren', 0);
		$this->db->order_by('workshop_positie', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getWorkshopsVervolgCMS()
	{
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->where('workshop_uitgelicht', 'nee');
		$this->db->where('workshop_ID !=', 6);
		$this->db->where('workshop_ID !=', 10);
        $this->db->where('workshop_archiveren', 0);
		$this->db->order_by('workshop_positie', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getWorkshopByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->where('workshop_ID', $item_ID);
		$this->db->join('media', 'media.media_ID = workshops.media_ID', 'left');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getWorkshopsByGebruikerID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
        $this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID');
        $this->db->join('media', 'media.media_ID = workshops.media_uitgelicht_ID', 'left');
		$this->db->where('aanmeldingen.gebruiker_ID', $item_ID);
		$this->db->where('aanmeldingen.aanmelding_type', 'workshop');
		$this->db->where('aanmeldingen.aanmelding_betaald_datum !=', '0000-00-00 00:00:00');
		//$this->db->order_by('aanmeldingen.aanmelding_betaald_datum', 'DESC');
		$this->db->order_by('ABS( DATEDIFF( workshop_startdatum, NOW() ) )');
		$query = $this->db->get();
		return $query->result();
	}

	function getWorkshopsByGebruikerIDOrderByLes($item_ID)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
        $this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID');
        $this->db->join('media', 'media.media_ID = workshops.media_uitgelicht_ID', 'left');
		$this->db->where('aanmeldingen.gebruiker_ID', $item_ID);
		$this->db->where('aanmeldingen.aanmelding_type', 'workshop');
		$this->db->where('aanmeldingen.aanmelding_betaald_datum !=', '0000-00-00 00:00:00');
		$this->db->order_by('aanmeldingen.aanmelding_betaald_datum', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}

	function getWorkshopsDummy()
	{
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->where('workshops.workshop_uitgelicht', 'ja');
		$this->db->where('workshops.workshop_archiveren', '0');
        $this->db->join('media', 'media.media_ID = workshops.media_uitgelicht_ID', 'left');
		$query = $this->db->get();
		return $query->result();
	}

	function getWorkshopsNietGevolgd($gebruiker_ID)
	{
		// Ophalen van workshops die gevolgd zijn
		$this->db->select('workshop_ID');
		$this->db->from('aanmeldingen');
		$this->db->where('aanmeldingen.gebruiker_ID', $gebruiker_ID);
		$this->db->where('aanmeldingen.aanmelding_type', 'workshop');
		$this->db->where('aanmeldingen.aanmelding_betaald_datum !=', '0000-00-00 00:00:00');

		$query = $this->db->get();

		// Ophalen van workshops die NIET gevolgd zijn. Geen workshops gevolgd = alle workshops ophalen.
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->where('workshop_gepubliceerd', 'ja');
        $this->db->where('workshop_ID !=', 6);
        $this->db->where('workshop_ID !=', 10);
        $this->db->where('workshop_ID !=', 29);
        $this->db->where('workshop_archiveren', 0);
        // $this->db->where('workshop_ID !=', 33);
		$this->db->join('media', 'media.media_ID = workshops.media_uitgelicht_ID', 'left');

		// Opslaan van de gevolgde workshop ID's in een array en deze filteren uit de workshops lijst (als er workshops gevolgd zijn)
		if ($query->num_rows() > 0) {
			$gevolgdeWorkshopIDs = array();

			foreach ($query->result() as $workshop) {
				$gevolgdeWorkshopIDs[] = $workshop->workshop_ID;
			}

			$this->db->where_not_in('workshop_ID', $gevolgdeWorkshopIDs);
		}

		$query = $this->db->get();
		return $query->result();
	}

    function getKandidaatWorkshopsNietGevolgd($gebruiker_ID)
    {
        // Ophalen van workshops die gevolgd zijn
        $this->db->select('workshop_ID');
        $this->db->from('aanmeldingen');
        $this->db->where('aanmeldingen.gebruiker_ID', $gebruiker_ID);
        $this->db->where('aanmeldingen.aanmelding_type', 'workshop');
        $this->db->where('aanmeldingen.aanmelding_betaald_datum !=', '0000-00-00 00:00:00');

        $query = $this->db->get();

        // Ophalen van workshops die NIET gevolgd zijn. Geen workshops gevolgd = alle workshops ophalen.
        $this->db->select('*');
        $this->db->from('workshops');
        $this->db->where('workshop_gepubliceerd', 'ja');
        $this->db->where('workshops.workshop_ID !=', 6);
        $this->db->where('workshops.workshop_ID !=', 10);
        $this->db->where('workshops.workshop_ID !=', 29);
        $this->db->where('workshop_archiveren', 0);
        // $this->db->where('workshop_ID !=', 33);
        $this->db->join('media', 'media.media_ID = workshops.media_uitgelicht_ID', 'left');
        $this->db->join('kandidaat_workshops', 'kandidaat_workshops.workshop_ID = workshops.workshop_ID');

        // Opslaan van de gevolgde workshop ID's in een array en deze filteren uit de workshops lijst (als er workshops gevolgd zijn)
        if ($query->num_rows() > 0) {
            $gevolgdeWorkshopIDs = array();

            foreach ($query->result() as $workshop) {
                $gevolgdeWorkshopIDs[] = $workshop->workshop_ID;
            }

            $this->db->where_not_in('workshops.workshop_ID', $gevolgdeWorkshopIDs);
        }

        $query = $this->db->get();
        return $query->result();
    }

	function getWorkshopByGebruikerID($workshop_ID, $gebruiker_ID)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('aanmeldingen.workshop_ID', $workshop_ID);
		$this->db->where('aanmeldingen.gebruiker_ID', $gebruiker_ID);
		$this->db->where('aanmeldingen.aanmelding_type', 'workshop');
		$this->db->where('aanmeldingen.aanmelding_betaald_datum !=', '0000-00-00 00:00:00');
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getLessenWorkshops()
	{
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->order_by('workshop_positie', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getGroepWorkshops()
	{
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->where('workshop_type', 'groep');
		$this->db->or_where('workshop_type', 'online');
		$this->db->order_by('workshop_positie', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getWorkshopLessenByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('lessen');
		$this->db->where('workshop_ID', $item_ID);
		$this->db->where('groep_ID', null);
		$this->db->order_by('les_positie', 'asc');
		$query = $this->db->get();
		return $query->result();
	}

	function getWorkshopByGroepID($item_ID) {
        $this->db->select('*');
        $this->db->from('groepen');
        $this->db->where('groep_ID', $item_ID);
        $this->db->join('workshops', 'groepen.workshop_ID = workshops.workshop_ID');
        $query = $this->db->get();
        return $query->result();
    }

	function getWorkshopGroepenByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('groepen');
		$this->db->where('workshop_ID', $item_ID);
		$this->db->where('groep_archiveren', 0);
		$query = $this->db->get();
		return $query->result();
	}

    function getWorkshopGroepenByIDArchief($item_ID)
    {
        $this->db->select('*');
        $this->db->from('groepen');
        $this->db->where('workshop_ID', $item_ID);
        $this->db->where('groep_archiveren', 1);
        $query = $this->db->get();
        return $query->result();
    }

	function getWorkshopDeelnemersByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('workshop_ID', $item_ID);
		$this->db->where('aanmelding_type', 'workshop');
		$this->db->where('aanmelding_betaald_datum !=', '0000-00-00 00:00:00');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
		$query = $this->db->get();
		return $query->result();
	}

	function getWorkshopProductenByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('workshops_producten');
		$this->db->where('workshop_ID', $item_ID);
		$this->db->join('producten', 'producten.product_ID = workshops_producten.product_ID');
		$this->db->where('producten.product_beschikbaar', 'ja');
		$this->db->join('media', 'media.media_ID = producten.media_ID', 'left');
		$this->db->order_by('producten.product_positie', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getWorkshopsGevolgdProductenByGebruikerID($gebruiker_ID)
	{
		$this->db->select('producten.product_ID, producten.*, media.*, workshops_producten.workshop_ID, workshops_producten.wanneer_beschikbaar');
		$this->db->distinct();
		$this->db->from('aanmeldingen');
		$this->db->join('workshops_producten', 'aanmeldingen.workshop_ID = workshops_producten.workshop_ID');
		$this->db->join('producten', 'workshops_producten.product_ID = producten.product_ID');
		$this->db->join('media', 'media.media_ID = producten.media_ID', 'left');
		$this->db->where('aanmeldingen.gebruiker_ID', $gebruiker_ID);
		$this->db->where('aanmeldingen.aanmelding_type', 'workshop');
		// $this->db->where('producten.product_ID !=', 9);
		$this->db->where('producten.product_beschikbaar', 'ja');
		$this->db->order_by('producten.product_positie', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getKandidaatProducten()
    {
        $this->db->select('*');
        $this->db->from('kandidaat_producten');
        $this->db->join('producten', 'kandidaat_producten.product_ID = producten.product_ID', 'left');
        $this->db->join('media', 'media.media_ID = producten.media_ID', 'left');
        $this->db->where('producten.product_beschikbaar', 'ja');
        $this->db->order_by('producten.product_positie', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

	function getWorkshopsGevolgdProductenAantalByGebruikerID($gebruiker_ID)
	{
		$this->db->select('producten.product_ID, producten.*');
		$this->db->distinct();
		$this->db->from('aanmeldingen');
        $this->db->join('workshops_producten', 'aanmeldingen.workshop_ID = workshops_producten.workshop_ID');
        $this->db->join('producten', 'workshops_producten.product_ID = producten.product_ID');
		$this->db->where('aanmeldingen.gebruiker_ID', $gebruiker_ID);
		$this->db->where('aanmeldingen.aanmelding_type', 'workshop');
		$this->db->where('producten.product_beschikbaar', 'ja');
		return $this->db->get();
	}

	function getWorkshop($item_url)
	{
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->join('media', 'media.media_ID = workshops.media_ID', 'left');
		$this->db->where('workshop_url', $item_url);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getWorkshopByURL($item_url)
	{
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->join('media', 'media.media_ID = workshops.media_ID', 'left');
		$this->db->where('workshop_url', $item_url);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function zoekWorkshops($zoekterm)
	{
		$this->db->select('*');
		$this->db->from('workshops');
		$this->db->like('workshop_titel', $zoekterm);
		$query = $this->db->get();
		return $query->result();
	}



	////////////
	// INSERT //
	////////////

	function insertWorkshop($data)
	{
		$this->db->insert('workshops', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

    function insertWorkshopReturnID($data)
    {
        $this->db->insert('workshops', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

	function koppelWorkshopProduct($item_ID, $product_ID)
	{
		$data = array('workshop_ID' => $item_ID, 'product_ID' => $product_ID);
		$this->db->insert('workshops_producten', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}



	////////////
	// UPDATE //
	////////////

	function updateWorkshop($item_ID, $data)
	{
		$this->db->where('workshop_ID', $item_ID);
		$this->db->update('workshops', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

    function updateProductBeschikbaar($item_ID, $product_ID, $data)
    {
        $this->db->where('product_ID', $product_ID);
        $this->db->where('workshop_ID', $item_ID);
        $this->db->update('workshops_producten', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }



	////////////
	// DELETE //
	////////////

	function deleteWorkshopProductenByID($item_ID)
	{
		$this->db->where('workshop_ID', $item_ID);
		$this->db->delete('workshops_producten');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

	function deleteWorkshopByID($item_ID)
	{
		// Gekoppelde producten verwijderen

		$this->db->where('workshop_ID', $item_ID);
		$this->db->delete('workshops_producten');


		// Workshop lessen verwijderen

		$this->db->where('workshop_ID', $item_ID);
		$this->db->delete('lessen');


		// Groepslessen verwijderen

		$groepen = $this->getWorkshopGroepenByID($item_ID);

		foreach($groepen as $groep)
		{
			// Groep lessen verwijderen

			$this->db->where('groep_ID', $groep->groep_ID);
			$this->db->delete('groepen_lessen');
		}


		// Groepen verwijderen

		$this->db->where('workshop_ID', $item_ID);
		$this->db->delete('groepen');


		// Workshop verwijderen

		$this->db->where('workshop_ID', $item_ID);
		$this->db->delete('workshops');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
}
