<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paginas_model extends CI_Model
{
	/////////
	// GET //
	/////////
	
	function getPaginas($aantal = 0, $pagina = 1)
	{
		$this->db->select('*');
		$this->db->from('paginas');
		$this->db->order_by('pagina_ID', 'asc');
		if($aantal > 0) $this->db->limit($aantal, (($pagina - 1) * $aantal));
		$query = $this->db->get();
		return $query->result();
	}
	
	function getPaginasAantal()
	{
		$this->db->select('*');
		$this->db->from('paginas');
		return $this->db->count_all_results();
	}
	
	function getPaginaByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('paginas');
		$this->db->join('media', 'media.media_ID = paginas.media_ID', 'left');
		$this->db->where('paginas.pagina_ID', $item_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}
	
	function zoekPaginas($zoekterm)
	{
		$this->db->select('*');
		$this->db->from('paginas');
		$this->db->like('pagina_titel_menu', $zoekterm);
		$this->db->or_like('pagina_titel', $zoekterm);
		$query = $this->db->get();
		return $query->result();
	}
	
	
	
	////////////
	// INSERT //
	////////////
	
	
	
	////////////
	// UPDATE //
	////////////
	
	function updatePagina($item_ID, $data, $noChange = false)
	{
		// Added to return correct status even without affected changes
        if($noChange) return $this->db->where('pagina_ID', $item_ID)->update('paginas', $data);

		$this->db->where('pagina_ID', $item_ID);
		$this->db->update('paginas', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	
	
	////////////
	// DELETE //
	////////////
}