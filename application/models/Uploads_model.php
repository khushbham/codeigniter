<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uploads_model extends CI_Model
{
	/////////
	// GET //
	/////////

	function getUploadByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('uploads');
		$this->db->where('upload_ID', $item_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getUploads($gebruiker_ID, $opdracht_ID)
	{
		$this->db->select('*');
		$this->db->from('uploads');
		$this->db->where('gebruiker_ID', $gebruiker_ID);
		$this->db->where('opdracht_ID', $opdracht_ID);
		$this->db->order_by('upload_datum', 'asc');
		$query = $this->db->get();
		return $query->result();
	}



	////////////
	// INSERT //
	////////////

	function insertUpload($data)
	{
		$this->db->insert('uploads', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

	function insertOpdrachtResultaat($data)
	{
		$this->db->insert('opdrachten_beoordelingen', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}



	////////////
	// UPDATE //
	////////////

	function updateResultaat($gebruiker_ID, $opdracht_ID, $data)
	{
		$this->db->where('gebruiker_ID', $gebruiker_ID);
		$this->db->where('opdracht_ID', $opdracht_ID);
		$this->db->update('resultaten', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}




	////////////
	// DELETE //
	////////////

	function deleteUploadByID($item_ID)
	{
		$this->db->where('upload_ID', $item_ID);
		$this->db->delete('uploads');
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

		// Upload ophalen

		$uploads = $this->getUploads($resultaat->gebruiker_ID, $resultaat->opdracht_ID);

		// Upload verwijderen

		foreach($uploads as $bestand)
		{
			if(file_exists('./media/opdrachten/'.$bestand->upload_src)) unlink('./media/opdrachten/'.$bestand->upload_src);
			$this->deleteUploadByID($bestand->upload_ID);
		}

		// Feedback verwijderen

		if(!empty($resultaat->resultaat_feedback_src)) if(file_exists('./media/opdrachten/'.$resultaat->resultaat_feedback_src)) unlink('./media/opdrachten/'.$resultaat->resultaat_feedback_src);
		if(!empty($resultaat->resultaat_opnieuw_feedback_src)) if(file_exists('./media/opdrachten/'.$resultaat->resultaat_opnieuw_feedback_src)) unlink('./media/opdrachten/'.$resultaat->resultaat_opnieuw_feedback_src);

		$this->db->where('resultaat_ID', $item_ID);
		$this->db->delete('resultaten');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

	function deleteUpload($upload_ID, $item_ID){
        // Resultaat ophalen
        $this->db->select('*');
        $this->db->from('resultaten');
        $this->db->where('resultaat_ID', $item_ID);
        $query = $this->db->get();
        $resultaat = $query->row();

        // Upload ophalen
        $uploads = $this->getUploads($resultaat->gebruiker_ID, $resultaat->opdracht_ID);

        // Upload verwijderen
        foreach($uploads as $bestand)
        {
            if($upload_ID == $bestand->upload_ID) {
                if (file_exists('./media/opdrachten/' . $bestand->upload_src)) unlink('./media/opdrachten/' . $bestand->upload_src);
                $this->deleteUploadByID($bestand->upload_ID);
            }
        }

        $this->db->where('resultaat_ID', $item_ID);
        $this->db->delete('resultaten');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }
}