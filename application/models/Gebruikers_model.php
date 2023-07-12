<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gebruikers_model extends CI_Model
{
	/////////
	// GET //
	/////////

	function filterDeelnemers($status = null, $ingelogd = null, $geslacht = null, $workshop = null, $groep = null, $kennismakingsworkshop = null)
	{
        $this->db->select('*');
		$this->db->from('gebruikers');
		$this->db->where('gebruiker_rechten !=', 'admin');
		$this->db->where('gebruiker_rechten !=', 'support');
		$this->db->where('gebruiker_rechten !=', 'opleidingsmedewerker');
		$this->db->where('gebruiker_rechten !=', 'docent');
		$this->db->where('gebruiker_rechten !=', 'demo');
		$this->db->where('gebruiker_rechten !=', 'test');

		if($status != null)
		{
			$this->db->where('gebruiker_status', $status);
		}

		if($ingelogd != null)
		{
			if($ingelogd == 'ja')
			{
				$this->db->where('gebruiker_online !=', '0000-00-00 00:00:00');
			}
			else
			{
				$this->db->where('gebruiker_online', '0000-00-00 00:00:00');
			}
		}

		if($geslacht != null)
		{
			$this->db->where('gebruiker_geslacht', $geslacht);
		}

		if($workshop != null)
		{
			$this->db->join('aanmeldingen', 'aanmeldingen.gebruiker_ID = gebruikers.gebruiker_ID', 'left');
			$this->db->where('aanmeldingen.aanmelding_type', 'workshop');
			$this->db->where('aanmeldingen.workshop_ID', $workshop);
		}

        if($kennismakingsworkshop != null)
        {
            $this->db->join('aanmeldingen', 'aanmeldingen.gebruiker_ID = gebruikers.gebruiker_ID', 'left');
            $this->db->where('aanmeldingen.aanmelding_type', 'kennismakingsworkshop');
            $this->db->where('aanmeldingen.kennismakingsworkshop_ID', $kennismakingsworkshop);
        }


        if($groep != null && $workshop == null)
        {
            $this->db->join('aanmeldingen', 'aanmeldingen.gebruiker_ID = gebruikers.gebruiker_ID', 'left');
            $this->db->where('groep_ID', $groep);
        }

        if($groep != null && $workshop != null)
        {
            $this->db->where('groep_ID', $groep);
        }

        if($groep != null || $workshop != null || $kennismakingsworkshop != null) {
            $this->db->where('aanmeldingen.aanmelding_verlopen', 0);
        }

        $this->db->order_by('gebruiker_voornaam', 'ASC');

        $query = $this->db->get();
        return $query->result();
	}

	function getDeelnemersArachiefGroep() {
        $this->db->select('*');
        $this->db->from('gebruikers');
        $this->db->join('aanmeldingen', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
        $this->db->join('groepen', 'aanmeldingen.groep_ID = groepen.groep_ID');
        $this->db->where('groep_archiveren', '1');
        $query = $this->db->get();
        return $query->result();
	}

	function getKandidaten()
    {
        $this->db->select('*');
        $this->db->from('gebruikers');
        $this->db->where('gebruiker_rechten', 'kandidaat');
        $query = $this->db->get();
        return $query->result();
	}

	function getGebruikerAanmeldingenBetaald($gebruiker_ID) {
		$this->db->select('*');
		$this->db->from('gebruikers');
		$this->db->join('aanmeldingen', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
		$this->db->join('workshops', 'aanmeldingen.workshop_ID = workshops.workshop_ID');
		$this->db->where('gebruikers.gebruiker_ID', $gebruiker_ID);
		$this->db->where('aanmelding_betaald_datum !=', "0000-00-00 00:00:00");
		$this->db->where('aanmelding_type', "workshop");
        $query = $this->db->get();
        return $query->result();
	}

    function getDeelnemersGroep()
    {
        $this->db->select('*');
        $this->db->from('gebruikers');
        $this->db->join('aanmeldingen', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
        $this->db->join('groepen', 'aanmeldingen.groep_ID = groepen.groep_ID');
        $this->db->where('groep_archiveren', '0');
        $query = $this->db->get();
        return $query->result();
    }

    function getDeelnemersGroepnonArchief($item_ID)
    {
        $this->db->select('*');
        $this->db->from('gebruikers');
        $this->db->join('aanmeldingen', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
        $this->db->join('groepen', 'aanmeldingen.groep_ID = groepen.groep_ID');
        $this->db->where('groep_archiveren', '0');
        $this->db->where('gebruikers.gebruiker_ID', $item_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function GetWorkshopsDeelnemerIndividueel($item_ID) {
        $this->db->select('*');
        $this->db->from('gebruikers');
        $this->db->join('aanmeldingen', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
        $this->db->join('workshops', 'aanmeldingen.workshop_ID = workshops.workshop_ID');
        $this->db->where('workshop_archiveren', '0');
        $this->db->where('workshop_type', 'individueel');
        $this->db->where('gebruikers.gebruiker_ID', $item_ID);
        $query = $this->db->get();
        return $query->result();
    }

	function getDeelnemersByIDArray($gebruiker_IDs)
	{
		$this->db->select('*');
		$this->db->from('gebruikers');
		$this->db->where_in('gebruiker_ID', $gebruiker_IDs);
		$this->db->order_by('gebruiker_voornaam', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getDeelnemersAantal()
	{
		$this->db->select('*');
		$this->db->from('gebruikers');
		$this->db->where('gebruiker_rechten', 'deelnemer');
		$this->db->or_where('gebruiker_rechten', 'kandidaat');
		$this->db->order_by('gebruiker_voornaam', 'ASC');
		$query = $this->db->get();
		return $query->num_rows();
	}

	function getDemoGebruiker() {
        $this->db->where('gebruiker_rechten', 'demo');
        $this->db->select('*');
        $this->db->from('gebruikers');
        $query = $this->db->get();
        return $query->result();
    }

    function insertDemo($data)
    {
        $this->db->insert('gebruikers', $data);
        if($this->db->affected_rows() == 1) return $this->db->insert_id();
        else return 0;
    }

    function updateDemo($item_ID, $data)
    {
        $this->db->where('gebruiker_ID', $item_ID);
        $this->db->where('gebruiker_rechten', 'demo');
        $this->db->update('gebruikers', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

	function checkGebruiker($emailadres, $wachtwoord)
	{
		$this->db->select('*');
		$this->db->from('gebruikers');
		$this->db->where('gebruiker_emailadres', $emailadres);
		$this->db->where('gebruiker_wachtwoord', $wachtwoord);
		$query = $this->db->get();

		if($query->num_rows() > 0)
		{
			$gebruiker = $query->row();
			$this->updateGebruiker($gebruiker->gebruiker_ID, array('gebruiker_online' => date('Y-m-d H:i:s')));
			return $gebruiker;
		}
		else return null;
	}

	function checkEmailadres($emailadres)
	{
		$this->db->select('*');
		$this->db->from('gebruikers');
		$this->db->where('gebruiker_emailadres', $emailadres);
		$this->db->where('gebruiker_status !=', 'concept');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function checkEmailadresInGebruik($gebruiker_ID) {
        $this->db->select('*');
        $this->db->from('aanmeldingen');
        $this->db->where('gebruiker_ID', $gebruiker_ID);
        $this->db->where('aanmelding_verlopen', 0);
        $this->db->where('aanmelding_betaald_datum !=', '0000-00-00 00:00:00');
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
	}
	function checkConceptEmailadres($emailadres)
	{
		$this->db->select('*');
		$this->db->from('gebruikers');
		$this->db->where('gebruiker_emailadres', $emailadres);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getGebruikerByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('gebruikers');
		$this->db->where('gebruiker_ID', $item_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getGebruikerByEmailadres($item_emailadres)
	{
		$this->db->select('*');
		$this->db->from('gebruikers');
		$this->db->where('gebruiker_emailadres', $item_emailadres);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getDeelnemers()
	{
		$this->db->select('*');
		$this->db->from('gebruikers');
		$this->db->where('gebruiker_rechten', 'deelnemer');
		$this->db->or_where('gebruiker_rechten', 'kandidaat');
		$this->db->order_by('gebruiker_voornaam', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getBeheerders()
	{
		$this->db->select('*');
		$this->db->from('gebruikers');
		$this->db->where('gebruiker_rechten !=', 'deelnemer');
		$this->db->where('gebruiker_rechten !=', 'docent');
		$this->db->where('gebruiker_rechten !=', 'kandidaat');
		$this->db->where('gebruiker_rechten !=', 'demo');
		$this->db->order_by('gebruiker_voornaam', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getMedecursisten()
	{
		$medecursisten = array();

		// Groepen ophalen waar de deelnemer lid van is

		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('gebruiker_ID', $this->session->userdata('gebruiker_ID'));
		$this->db->where('groep_ID !=', '');
		$query = $this->db->get();
		$aanmeldingen = $query->result();

		if(sizeof($aanmeldingen) > 0)
		{
			foreach($aanmeldingen as $aanmelding)
			{
				// Deelnemers van groepen ophalen

				$this->db->select('*');
				$this->db->from('aanmeldingen');
				$this->db->where('groep_ID', $aanmelding->groep_ID);
				$this->db->where('gebruiker_ID !=', $this->session->userdata('gebruiker_ID'));
				$query = $this->db->get();
				$deelnemers = $query->result();

				if(sizeof($deelnemers))
				{
					foreach($deelnemers as $deelnemer)
					{
						$medecursisten[] = $deelnemer->gebruiker_ID;
					}
				}
			}
		}

		// Deelnemers ophalen die niet anoniem zijn

		if(sizeof($medecursisten) > 0)
		{
			$this->db->select('*');
			$this->db->from('gebruikers');
			$this->db->where_in('gebruiker_ID', $medecursisten);
			$this->db->where('gebruiker_rechten', 'deelnemer');
			$this->db->where('gebruiker_instelling_anoniem', 'nee');
			$this->db->order_by('gebruiker_voornaam', 'ASC');
			$query = $this->db->get();
			return $query->result();
		}
		else
		{
			return null;
		}
	}

	function zoekDeelnemers($zoekterm)
	{
		$this->db->select('*');
		$this->db->from('gebruikers');
		$this->db->where('gebruiker_rechten', 'deelnemer');
        $this->db->where("(gebruiker_naam LIKE '%".$zoekterm."%' OR gebruiker_voornaam LIKE '%".$zoekterm."%' OR gebruiker_telefoonnummer LIKE '%".$zoekterm."%' OR gebruiker_mobiel LIKE '%".$zoekterm."%' OR gebruiker_plaats LIKE '%".$zoekterm."%' OR gebruiker_postcode LIKE '%".$zoekterm."%' OR gebruiker_adres LIKE '%".$zoekterm."%' OR gebruiker_emailadres LIKE '%".$zoekterm."%')", NULL, False);
		$query = $this->db->get();
		return $query->result();
	}

    function zoekKandidaten($zoekterm)
    {
        $this->db->select('*');
        $this->db->from('gebruikers');
        $this->db->where('gebruiker_rechten', 'kandidaat');
        $this->db->where("(gebruiker_naam LIKE '%".$zoekterm."%' OR gebruiker_voornaam LIKE '%".$zoekterm."%' OR gebruiker_telefoonnummer LIKE '%".$zoekterm."%' OR gebruiker_mobiel LIKE '%".$zoekterm."%' OR gebruiker_plaats LIKE '%".$zoekterm."%' OR gebruiker_postcode LIKE '%".$zoekterm."%' OR gebruiker_adres LIKE '%".$zoekterm."%' OR gebruiker_emailadres LIKE '%".$zoekterm."%')", NULL, False);
        $query = $this->db->get();
        return $query->result();
    }

	function zoekBeheerders($zoekterm)
	{
		$this->db->select('*');
		$this->db->from('gebruikers');
		$this->db->where('gebruiker_rechten !=', 'deelnemer');
		$this->db->where('gebruiker_rechten !=', 'kandidaat');
        $this->db->where("(gebruiker_naam LIKE '%".$zoekterm."%' OR gebruiker_voornaam LIKE '%".$zoekterm."%' OR gebruiker_achternaam LIKE '%".$zoekterm."%')", NULL, False);
		$query = $this->db->get();
		return $query->result();
	}



	////////////
	// INSERT //
	////////////

	function insertDeelnemer($data)
	{
		$this->db->insert('gebruikers', $data);
		if($this->db->affected_rows() == 1) return $this->db->insert_id();
		else return 0;
	}

	function insertGebruiker($data)
	{
		$this->db->insert('gebruikers', $data);
		if($this->db->affected_rows() == 1) return $this->db->insert_id();
		else return 0;
	}

	function insertDeelnemerWorkshop($data)
	{
		$this->db->insert('gebruikers_workshops', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}



	////////////
	// UPDATE //
	////////////

	function updateDeelnemer($item_ID, $data)
	{
		$this->db->where('gebruiker_ID', $item_ID);
		$this->db->update('gebruikers', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

	function updateGebruiker($item_ID, $data)
	{
		$this->db->where('gebruiker_ID', $item_ID);
		$this->db->update('gebruikers', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

	function updateGebruikerBetaald($gebruiker_ID, $workshop_ID)
	{
		$this->db->where('gebruiker_ID', $gebruiker_ID);
		$this->db->where('workshop_ID', $workshop_ID);
		$this->db->update('gebruikers_workshops', array('gebruiker_workshop_betaald' => date('Y-m-d H:i:s')));
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}



	////////////
	// DELETE //
	////////////

	function deleteDeelnemer($item_ID)
	{
		// Aanmeldingen ophalen

		$this->db->select('*');
		$this->db->from('aanmeldingen');
		$this->db->where('gebruiker_ID', $item_ID);
		$query = $this->db->get();
		$aanmeldingen = $query->result();

		foreach($aanmeldingen as $aanmelding)
		{
			// Bestelling ophalen

			$this->db->select('*');
			$this->db->from('bestellingen');
			$this->db->where('aanmelding_ID', $aanmelding->aanmelding_ID);
			$query = $this->db->get();

			if($query->num_rows() > 0)
			{
				$bestelling = $query->row();

				// Bestelling producten verwijderen

				$this->db->where('bestelling_ID', $bestelling->bestelling_ID);
				$this->db->delete('bestellingen_producten');

				// Bestelling verwijderen

				$this->db->where('bestelling_ID', $bestelling->bestelling_ID);
				$this->db->delete('bestellingen');
			}
		}


		// Aanmeldingen verwijderen

		$this->db->where('gebruiker_ID', $item_ID);
		$this->db->delete('aanmeldingen');

		// Lessen verwijderen

		$this->db->where('gebruiker_ID', $item_ID);
		$this->db->delete('individuen_lessen');


		// Huiswerk verwijderen

		$this->db->select('*');
		$this->db->from('huiswerk');
		$this->db->where('gebruiker_ID', $item_ID);
		$query = $this->db->get();
		$huiswerk = $query->result();

		foreach($huiswerk as $bestand) unlink('./media/huiswerk/'.$bestand_src);

		$this->db->where('gebruiker_ID', $item_ID);
		$this->db->delete('huiswerk');


		// Resultaten verwijderen

		$this->db->where('gebruiker_ID', $item_ID);
		$this->db->delete('resultaten');


		// Ontvangen berichten verwijderen

		$this->db->where('bericht_ontvanger_ID', $item_ID);
		$this->db->delete('berichten');


		// Verzonden berichten verwijderen

		$this->db->where('bericht_afzender_ID', $item_ID);
		$this->db->delete('berichten');


		// Gebruiker verwijderen

		$this->db->where('gebruiker_ID', $item_ID);
		$this->db->delete('gebruikers');

		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

	function deleteConceptGebruikerByID($gebruiker_ID)
	{
		// Gebruiker verwijderen

		$this->db->where('gebruiker_ID', $gebruiker_ID);
		$this->db->where('gebruiker_status', 'concept');
		$this->db->delete('gebruikers');

		if($this->db->affected_rows() == 1) return true;
		else return false;
	}



	/////////////
	// CHECKED //
	/////////////

	function getGebruikers()
	{
		$this->db->select('*');
		$this->db->from('gebruikers');
		$query = $this->db->get();
		return $query->result();
	}

	function getGebruikersRecentIngelogd($gebruiker_rechten = null, $limit = 0)
	{
		$this->db->select('gebruiker_ID, gebruiker_naam, gebruiker_emailadres, gebruiker_online');

		$this->db->from('gebruikers');

		if($gebruiker_rechten != null)
		{
			$this->db->where('gebruiker_rechten', 'deelnemer');
		}

		$this->db->where('gebruiker_online !=', '0000-00-00 00:00:00');
		$this->db->order_by('gebruiker_online', 'DESC');

		if($limit > 0)
		{
			$this->db->limit($limit);
		}

		$query = $this->db->get();

		return $query->result();
	}

	function updateGebruikerByID($item_ID, $data)
	{
		$this->db->where('gebruiker_ID', $item_ID);
		$this->db->update('gebruikers', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}


}