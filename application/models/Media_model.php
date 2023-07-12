<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Media_model extends CI_Model
{
	/////////
	// GET //
	/////////
	
	function getMedia($aantal = 0, $pagina = 1, $media_soort = null)
	{
		$this->db->select('*');
		$this->db->from('media');

        if(!empty($media_soort)) {
            $this->db->where('media_type', $media_soort);
        }

		$this->db->order_by('media_ID', 'desc');
		if($aantal > 0) $this->db->limit($aantal, (($pagina - 1) * $aantal));
		$query = $this->db->get();
		return $query->result();
	}
	
	function getMediaAantal($media_soort)
	{
		$this->db->select('*');
		$this->db->from('media');

        if(!empty($media_soort)) {
            $this->db->where('media_type', $media_soort);
        }

		return $this->db->count_all_results();
	}
	
	function getMediaByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('media');
		$this->db->where('media_ID', $item_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}
	
	function getMediaByContentID($content_type, $content_ID)
	{
		$this->db->select('*');
		$this->db->from('media_connecties');
		$this->db->where('content_type', $content_type);
		$this->db->where('content_ID', $content_ID);
		$this->db->where('media_ingang =', '0000-00-00');
		$this->db->join('media', 'media.media_ID = media_connecties.media_ID');
		$this->db->order_by('media_positie', 'asc');
		$query = $this->db->get();
		return $query->result();
	}

    function getMediaNieuwByContentID($content_type, $content_ID)
    {
        $this->db->select('*');
        $this->db->from('media_connecties');
        $this->db->where('content_type', $content_type);
        $this->db->where('content_ID', $content_ID);
        $this->db->where('media_ingang !=', '0000-00-00');
        $this->db->join('media', 'media.media_ID = media_connecties.media_ID');
        $this->db->order_by('media_positie', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    function getMediaConnectie($content_type, $content_ID)
    {
        $this->db->select('*');
        $this->db->from('media_connecties');
        $this->db->where('content_type', $content_type);
        $this->db->where('content_ID', $content_ID);
        $query = $this->db->get();
        return $query->result();
    }
	
	function getMediaByMediaID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('media');
		$this->db->where('media_ID', $item_ID);
		$query = $this->db->get();
		return $query->result();
	}
	
	function zoekMedia($zoekterm)
	{
		$this->db->select('*');
		$this->db->from('media');
		$this->db->like('media_titel', $zoekterm);
        $this->db->or_like('media_type', $zoekterm);
        $this->db->or_like('media_datum', $zoekterm);
		$query = $this->db->get();
		return $query->result();
	}
	
	function getMediaProfielByGebruikerID($gebruiker_ID)
	{
		$this->db->select('media_src');
		$this->db->from('profiel_media');
		$this->db->where('gebruiker_ID', $gebruiker_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}
	
	function DeleteProfielMedia($gebruiker_ID)
	{
		$this->db->where('gebruiker_ID', $gebruiker_ID);
		$this->db->delete('profiel_media');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	////////////
	// INSERT //
	////////////
	
	function insertMedia($data)
	{
		$this->db->insert('media', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

	function insertProfielMedia($data)
	{
		$this->db->insert('profiel_media', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	function insertMediaConnectie($data)
	{
		$this->db->insert('media_connecties', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	
	
	////////////
	// UPDATE //
	////////////
	
	function updateMedia($item_ID, $data)
	{
		$this->db->where('media_ID', $item_ID);
		$this->db->update('media', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	
	
	////////////
	// DELETE //
	////////////
	
	function deleteMedia($item_ID)
	{
		// DELETE MEDIA CONNECTIES !!!
		
		$this->db->where('media_ID', $item_ID);
		$this->db->delete('media');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	function verwijderConnecties($type, $item_ID)
	{
		$this->db->where('content_type', $type);
		$this->db->where('content_ID', $item_ID);
        $this->db->where('media_ingang =', '0000-00-00');
		$this->db->delete('media_connecties');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

    function verwijderNieuwConnecties($type, $item_ID)
    {
        $this->db->where('content_type', $type);
        $this->db->where('content_ID', $item_ID);
        $this->db->where('media_ingang !=', '0000-00-00');
        $this->db->delete('media_connecties');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }
}