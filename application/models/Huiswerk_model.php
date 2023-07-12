<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Huiswerk_model extends CI_Model
{
	/////////
	// GET //
	/////////
	
	function getHuiswerkByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('huiswerk');
		$this->db->where('huiswerk_ID', $item_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}
	
	function getHuiswerk($gebruiker_ID, $les_ID)
	{
		$this->db->select('*');
		$this->db->from('huiswerk');
		$this->db->where('huiswerk_opnieuw', 'nee');
		$this->db->where('gebruiker_ID', $gebruiker_ID);
		$this->db->where('les_ID', $les_ID);
		$this->db->order_by('huiswerk_datum', 'asc');
		$query = $this->db->get();
		return $query->result();
	}
	
	function getHuiswerkOpnieuw($gebruiker_ID, $les_ID)
	{
		$this->db->select('*');
		$this->db->from('huiswerk');
		$this->db->where('huiswerk_opnieuw', 'ja');
		$this->db->where('gebruiker_ID', $gebruiker_ID);
		$this->db->where('les_ID', $les_ID);
		$this->db->order_by('huiswerk_datum', 'asc');
		$query = $this->db->get();
		return $query->result();
	}
	
	function getResultaatByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('resultaten');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = resultaten.gebruiker_ID');
		$this->db->join('lessen', 'lessen.les_ID = resultaten.les_ID');
		$this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
		$this->db->where('resultaat_ID', $item_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}
	
	function getResultaat($gebruiker_ID, $les_ID)
	{
		$this->db->select('*');
		$this->db->from('resultaten');
		$this->db->where('gebruiker_ID', $gebruiker_ID);
		$this->db->where('les_ID', $les_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getResultatenByGebruikerIDandWorkshop_ID($item_ID, $workshop_ID)
	{
		$this->db->select('*');
		$this->db->from('resultaten');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = resultaten.gebruiker_ID');
		$this->db->join('lessen', 'lessen.les_ID = resultaten.les_ID');
		$this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
		$this->db->where('resultaten.gebruiker_ID', $item_ID);
		$this->db->where('workshops.workshop_ID', $workshop_ID);
		$this->db->order_by('resultaat_ingestuurd_datum', 'asc');
		$query = $this->db->get();
		return $query->result();
	}
	
	function getResultatenByGebruikerID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('resultaten');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = resultaten.gebruiker_ID');
		$this->db->join('lessen', 'lessen.les_ID = resultaten.les_ID');
		$this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
		$this->db->where('resultaten.gebruiker_ID', $item_ID);
		$this->db->order_by('resultaat_ingestuurd_datum', 'asc');
		$query = $this->db->get();
		return $query->result();
	}
	
	function getResultatenOnbekend()
	{
		$this->db->select('*');
		$this->db->from('resultaten');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = resultaten.gebruiker_ID');
		$this->db->join('lessen', 'lessen.les_ID = resultaten.les_ID');
		$this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
		$this->db->where('resultaat_beoordelen', 'ja');
		$this->db->order_by('resultaat_opnieuw_ingestuurd_datum', 'asc');
		$this->db->order_by('resultaat_ingestuurd_datum', 'asc');
		$query = $this->db->get();
		return $query->result();
	}

    function getResultatenOnbekendDocent($docent_ID)
    {
        $this->db->select('*');
        $this->db->from('resultaten');
        $this->db->join('gebruikers', 'gebruikers.gebruiker_ID = resultaten.gebruiker_ID');
        $this->db->join('lessen', 'lessen.les_ID = resultaten.les_ID');
        $this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
        $this->db->where('resultaat_beoordelen', 'ja');
        $this->db->where('lessen.docent_ID', $docent_ID);
        $this->db->order_by('resultaat_opnieuw_ingestuurd_datum', 'asc');
        $this->db->order_by('resultaat_ingestuurd_datum', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    function getAantalResultatenOnbekendDocent($docent_ID)
    {
        $this->db->select('*');
        $this->db->from('resultaten');
        $this->db->join('gebruikers', 'gebruikers.gebruiker_ID = resultaten.gebruiker_ID');
        $this->db->join('lessen', 'lessen.les_ID = resultaten.les_ID');
        $this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
        $this->db->where('resultaat_beoordelen', 'ja');
        $this->db->where('lessen.docent_ID', $docent_ID);
        $this->db->order_by('resultaat_opnieuw_ingestuurd_datum', 'asc');
        $this->db->order_by('resultaat_ingestuurd_datum', 'asc');
        $query = $this->db->get();
        return $query->num_rows();
    }
	
	function getAantalResultatenOnbekend()
	{
		$this->db->select('*');
		$this->db->from('resultaten');
		$this->db->where('resultaat_beoordelen', 'ja');
		$query = $this->db->get();
	return $query->num_rows();
}

function getResultatenBeoordeeld()
	{
		$this->db->select('*');
		$this->db->from('resultaten');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = resultaten.gebruiker_ID');
		$this->db->join('lessen', 'lessen.les_ID = resultaten.les_ID');
		$this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
		$this->db->where('resultaat_beoordelen', 'nee');
		$this->db->order_by('resultaat_beoordeeld_datum', 'desc');
		$this->db->limit(15);
		$query = $this->db->get();
		return $query->result();
	}

    function getResultatenBeoordeeldDocent($docent_ID)
    {
        $this->db->select('*');
        $this->db->from('resultaten');
        $this->db->join('gebruikers', 'gebruikers.gebruiker_ID = resultaten.gebruiker_ID');
        $this->db->join('lessen', 'lessen.les_ID = resultaten.les_ID');
        $this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
        $this->db->where('resultaat_beoordelen', 'nee');
        $this->db->where('lessen.docent_ID', $docent_ID);
        $this->db->order_by('resultaat_beoordeeld_datum', 'desc');
        $this->db->limit(15);
        $query = $this->db->get();
        return $query->result();
    }
	
	
	
	////////////
	// INSERT //
	////////////
	
	function insertHuiswerk($data)
	{
		$this->db->insert('huiswerk', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	function insertResultaat($data)
	{
		$this->db->insert('resultaten', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	
	
	////////////
	// UPDATE //
	////////////
	
	function updateResultaat($gebruiker_ID, $les_ID, $data)
	{
		$this->db->where('gebruiker_ID', $gebruiker_ID);
		$this->db->where('les_ID', $les_ID);
		$this->db->update('resultaten', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	
	
	
	////////////
	// DELETE //
	////////////
	
	function deleteHuiswerkByID($item_ID)
	{
		$this->db->where('huiswerk_ID', $item_ID);
		$this->db->delete('huiswerk');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	function deleteResultaat($item_ID)
	{
		// Resultaat ophalen
		
		$this->db->select('*');
		$this->db->from('resultaten');
		$this->db->where('resultaat_ID', $item_ID);
		$query = $this->db->get();
		$resultaat = $query->row();
		
		// Huiswerk ophalen
		
		$huiswerk = $this->getHuiswerk($resultaat->gebruiker_ID, $resultaat->les_ID);
		
		// Huiswerk verwijderen
		
		foreach($huiswerk as $bestand)
		{
			if(file_exists('./media/huiswerk/'.$bestand->huiswerk_src)) unlink('./media/huiswerk/'.$bestand->huiswerk_src);
			$this->deleteHuiswerkByID($bestand->huiswerk_ID);
		}
		
		// Feedback verwijderen
		
		if(!empty($resultaat->resultaat_feedback_src)) if(file_exists('./media/huiswerk/'.$resultaat->resultaat_feedback_src)) unlink('./media/huiswerk/'.$resultaat->resultaat_feedback_src);
		if(!empty($resultaat->resultaat_opnieuw_feedback_src)) if(file_exists('./media/huiswerk/'.$resultaat->resultaat_opnieuw_feedback_src)) unlink('./media/huiswerk/'.$resultaat->resultaat_opnieuw_feedback_src);
		
		$this->db->where('resultaat_ID', $item_ID);
		$this->db->delete('resultaten');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

	function deleteHuiswerk($huiswerk_ID, $item_ID){
        // Resultaat ophalen
        $this->db->select('*');
        $this->db->from('resultaten');
        $this->db->where('resultaat_ID', $item_ID);
        $query = $this->db->get();
        $resultaat = $query->row();

        // Huiswerk ophalen
        $huiswerk = $this->getHuiswerk($resultaat->gebruiker_ID, $resultaat->les_ID);

        // Huiswerk verwijderen
        foreach($huiswerk as $bestand)
        {
            if($huiswerk_ID == $bestand->huiswerk_ID) {
                if (file_exists('./media/huiswerk/' . $bestand->huiswerk_src)) unlink('./media/huiswerk/' . $bestand->huiswerk_src);
                $this->deleteHuiswerkByID($bestand->huiswerk_ID);
            }
        }

        $this->db->where('resultaat_ID', $item_ID);
        $this->db->delete('resultaten');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }
}