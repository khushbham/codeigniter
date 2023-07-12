<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bestellingen_model extends CI_Model
{
	/////////
	// GET //
	/////////
	
	function getBestellingen()
	{
		$this->db->select('*');
		$this->db->from('bestellingen');
		$this->db->join('aanmeldingen', 'aanmeldingen.aanmelding_ID = bestellingen.aanmelding_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID', 'left');
		$this->db->order_by('aanmeldingen.aanmelding_datum', 'asc');
		$query = $this->db->get();
		return $query->result();
	}

	function getBestellingenVerzenden()
	{
		$this->db->select('*');
		$this->db->from('bestellingen');
		$this->db->join('aanmeldingen', 'aanmeldingen.aanmelding_ID = bestellingen.aanmelding_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID', 'left');
		$this->db->join('bestellingen_producten', 'bestellingen.bestelling_ID = bestellingen_producten.bestelling_ID', 'left');
		$this->db->join('stem_producten', 'stem_producten.product_ID = bestellingen_producten.product_ID');
		$this->db->where('bestelling_verzonden_datum', '0000-00-00 00:00:00');
		$this->db->where('bestellingen.aanmelding_ID IS NOT NULL');
		$this->db->where('bestellingen_producten.bestellingen_huur', 0);
		$this->db->order_by('bestellingen.bestelling_ID', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getBestellingenVerzendenZoeken($zoekterm)
	{
		$this->db->select('*');
		$this->db->from('bestellingen');
		$this->db->join('aanmeldingen', 'aanmeldingen.aanmelding_ID = bestellingen.aanmelding_ID', 'left');
		$this->db->join('groepen', 'aanmeldingen.groep_ID = groepen.groep_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID', 'left');
		$this->db->join('bestellingen_producten', 'bestellingen.bestelling_ID = bestellingen_producten.bestelling_ID', 'left');
		$this->db->join('producten', 'bestellingen_producten.product_ID = producten.product_ID');
		$this->db->where('bestelling_verzonden_datum', '0000-00-00 00:00:00');
		$this->db->where('bestellingen.aanmelding_ID IS NOT NULL');
		$this->db->where("(gebruiker_naam LIKE '%".$zoekterm."%' OR gebruiker_voornaam LIKE '%".$zoekterm."%'
		 OR gebruiker_telefoonnummer LIKE '%".$zoekterm."%' OR gebruiker_mobiel LIKE '%".$zoekterm."%' OR gebruiker_plaats LIKE '%".$zoekterm."%' OR gebruiker_postcode LIKE '%".$zoekterm."%' OR gebruiker_adres LIKE '%".$zoekterm."%' OR gebruiker_emailadres LIKE '%".$zoekterm."%' OR groep_titel LIKE '%".$zoekterm."%' OR groep_naam LIKE '%".$zoekterm."%' OR product_naam LIKE '%".$zoekterm."%')", NULL, False);
		$this->db->order_by('bestellingen.bestelling_ID', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getBestellingenVerzendenHuur()
	{
		$this->db->select('*');
		$this->db->from('bestellingen');
		$this->db->join('aanmeldingen', 'aanmeldingen.aanmelding_ID = bestellingen.aanmelding_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID', 'left');
		$this->db->join('bestellingen_producten', 'bestellingen.bestelling_ID = bestellingen_producten.bestelling_ID', 'left');
		$this->db->join('producten', 'bestellingen_producten.product_ID = producten.product_ID');
		$this->db->where('bestelling_verzonden_datum', '0000-00-00 00:00:00');
		$this->db->where('bestellingen_producten.bestellingen_huur', 1);
		$this->db->where('bestellingen.aanmelding_ID IS NOT NULL');
		$this->db->order_by('bestellingen.bestelling_ID', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getBestellingenVerzondenHuur()
	{
		$this->db->select('*');
		$this->db->from('bestellingen');
		$this->db->join('aanmeldingen', 'aanmeldingen.aanmelding_ID = bestellingen.aanmelding_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID', 'left');
		$this->db->join('bestellingen_producten', 'bestellingen.bestelling_ID = bestellingen_producten.bestelling_ID', 'left');
		$this->db->join('stem_producten', 'stem_producten.product_ID = bestellingen_producten.product_ID');
		$this->db->where('bestelling_verzonden_datum !=', '0000-00-00 00:00:00');
		$this->db->where('bestellingen_producten.bestellingen_huur', 1);
		$this->db->where('bestellingen.aanmelding_ID IS NOT NULL');
		$this->db->order_by('bestellingen.bestelling_verzonden_datum', 'desc');
		$this->db->limit(10);
		$query = $this->db->get();
		return $query->result();
	}
	
	function getBestellingenLosVerzenden()
	{
		$this->db->select('*');
		$this->db->from('bestellingen');
		$this->db->join('bestellingen_los', 'bestellingen_los.bestelling_ID = bestellingen.bestelling_ID');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = bestellingen_los.gebruiker_ID', 'left');
		$this->db->where('bestelling_verzonden_datum', '0000-00-00 00:00:00');
		$this->db->where('bestellingen.aanmelding_ID IS NULL');
		$this->db->order_by('bestellingen.bestelling_ID', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getBestellingenLosVerzendenZoeken($zoekterm)
	{
		$this->db->select('*');
		$this->db->from('bestellingen');
		$this->db->join('bestellingen_los', 'bestellingen_los.bestelling_ID = bestellingen.bestelling_ID');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = bestellingen_los.gebruiker_ID', 'left');
		$this->db->join('bestellingen_producten', 'bestellingen_los.bestelling_ID = bestellingen_producten.bestelling_ID', 'left');
		$this->db->join('producten', 'bestellingen_producten.product_ID = producten.product_ID');
		$this->db->where('bestelling_verzonden_datum', '0000-00-00 00:00:00');
		$this->db->where('bestellingen.aanmelding_ID IS NULL');
		$this->db->where("(gebruiker_naam LIKE '%".$zoekterm."%' OR gebruiker_voornaam LIKE '%".$zoekterm."%'
		OR gebruiker_telefoonnummer LIKE '%".$zoekterm."%' OR gebruiker_mobiel LIKE '%".$zoekterm."%' OR gebruiker_plaats LIKE '%".$zoekterm."%' OR gebruiker_postcode LIKE '%".$zoekterm."%' OR gebruiker_adres LIKE '%".$zoekterm."%' OR gebruiker_emailadres LIKE '%".$zoekterm."%' OR product_naam LIKE '%".$zoekterm."%')", NULL, False);
		$this->db->order_by('bestellingen.bestelling_ID', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getAantalBestellingenVerzenden()
	{
		$this->db->select('*');
		$this->db->from('bestellingen');
		$this->db->where('bestelling_verzonden_datum', '0000-00-00 00:00:00');
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	function getBestellingenVerzonden()
	{
		$this->db->select('*');
		$this->db->from('bestellingen');
		$this->db->join('aanmeldingen', 'aanmeldingen.aanmelding_ID = bestellingen.aanmelding_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID', 'left');
		$this->db->join('bestellingen_producten', 'bestellingen.bestelling_ID = bestellingen_producten.bestelling_ID', 'left');
		$this->db->where('bestelling_verzonden_datum !=', '0000-00-00 00:00:00');
		$this->db->where('bestellingen.aanmelding_ID IS NOT NULL');
		$this->db->where('bestellingen_producten.bestellingen_huur', 0);
		$this->db->order_by('bestellingen.bestelling_verzonden_datum', 'desc');
		$this->db->limit(10);
		$query = $this->db->get();
		return $query->result();
	}
	
	function getBestellingenLosVerzonden()
	{
		$this->db->select('*');
		$this->db->from('bestellingen');
		$this->db->join('bestellingen_los', 'bestellingen_los.bestelling_ID = bestellingen.bestelling_ID');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = bestellingen_los.gebruiker_ID', 'left');
		$this->db->where('bestelling_verzonden_datum !=', '0000-00-00 00:00:00');
		$this->db->where('bestellingen.aanmelding_ID IS NULL');
		$this->db->order_by('bestellingen.bestelling_verzonden_datum', 'DESC');
		$this->db->limit(10);
		$query = $this->db->get();
		return $query->result();
	}
	function getBestellingenByGebruikerID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('bestellingen');
		$this->db->join('aanmeldingen', 'aanmeldingen.aanmelding_ID = bestellingen.aanmelding_ID');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID', 'left');
		$this->db->where('aanmeldingen.gebruiker_ID', $item_ID);
		$this->db->where('bestellingen.aanmelding_ID IS NOT NULL');
		$this->db->order_by('aanmeldingen.aanmelding_datum', 'asc');
		$query = $this->db->get();
		return $query->result();
	}
	
	function getBestellingenLosByGebruikerID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('bestellingen');
		$this->db->join('bestellingen_los', 'bestellingen.bestelling_ID = bestellingen_los.bestelling_ID');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = bestellingen_los.gebruiker_ID', 'left');
		$this->db->where('bestellingen.aanmelding_ID IS NULL');
		$this->db->where('gebruikers.gebruiker_ID', $item_ID);
		$query = $this->db->get();
		return $query->result();
	}
	function getBestellingByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('bestellingen');
		$this->db->join('aanmeldingen', 'aanmeldingen.aanmelding_ID = bestellingen.aanmelding_ID');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
		$this->db->join('workshops', 'workshops.workshop_ID = aanmeldingen.workshop_ID');
		$this->db->where('bestelling_ID', $item_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}
	
	function getBestellingLosByID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('bestellingen');
		$this->db->join('bestellingen_los', 'bestellingen_los.bestelling_ID = bestellingen.bestelling_ID');
		$this->db->where('bestellingen.bestelling_ID', $item_ID);
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = bestellingen_los.gebruiker_ID');
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}

	function getBestellingByAanmeldingID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('bestellingen');
		$this->db->join('aanmeldingen', 'aanmeldingen.aanmelding_ID = bestellingen.aanmelding_ID');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID');
		$this->db->where('bestellingen.aanmelding_ID', $item_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) return $query->row();
		else return null;
	}
	
	function getProductenByBestellingID($item_ID)
	{
		$this->db->select('*');
		$this->db->from('bestellingen_producten');
		$this->db->where('bestelling_ID', $item_ID);
		$this->db->join('producten', 'producten.product_ID = bestellingen_producten.product_ID');
		$query = $this->db->get();
		return $query->result();
	}
	
	function getBestellingenLosNietBetaald()
	{
		$this->db->select('*');
		$this->db->from('bestellingen');
		$this->db->join('bestellingen_los', 'bestellingen_los.bestelling_ID = bestellingen.bestelling_ID', 'right');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = bestellingen_los.gebruiker_ID', 'left');
		$this->db->where('bestellingen_los.bestelling_datum <=', date('Y-m-d H:i:s', time() - 3600)); // 3600 = 1 uur
		$this->db->where('bestellingen_los.bestelling_herinnering_datum', '0000-00-00 00:00:00');
		$this->db->where('bestellingen_los.bestelling_betaald_datum', '0000-00-00 00:00:00');
		$this->db->order_by('bestellingen_los.bestelling_datum', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function getBestellingenLosVerlopen()
	{
		$this->db->select('*');
		$this->db->from('bestellingen');
		$this->db->join('bestellingen_los', 'bestellingen_los.bestelling_ID = bestellingen.bestelling_ID', 'right');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = bestellingen_los.gebruiker_ID', 'left');
		$this->db->where('bestellingen_los.bestelling_betaald_datum', '0000-00-00 00:00:00');
		$this->db->where('bestellingen_los.bestelling_herinnering_datum !=', '0000-00-00 00:00:00');
		$this->db->where('bestellingen_los.bestelling_herinnering_datum <=', date('Y-m-d H:i:s', time() - 86400)); // 86400 = 1 dag - 24 uur
		$this->db->order_by('bestellingen_los.bestelling_datum', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}
	
	////////////
	// INSERT //
	////////////
	
	function insertBestelling($data)
	{
		$this->db->insert('bestellingen', $data);
		if($this->db->affected_rows() == 1) return $this->db->insert_id();
		else return 0;
	}
	
	function insertBestellingLos($data)
	{
		$this->db->insert('bestellingen_los', $data);
		if($this->db->affected_rows() == 1) return $this->db->insert_id();
		else return 0;
	}
	function insertProductBestelling($data)
	{
		$this->db->insert('bestellingen_producten', $data);
		if($this->db->affected_rows() == 1) return $this->db->insert_id();
		else return 0;
	}
	
	
	
	////////////
	// UPDATE //
	////////////
	
	function updateBestelling($item_ID, $data)
	{
		$this->db->where('bestelling_ID', $item_ID);
		$this->db->update('bestellingen', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	function updateBestellingLos($item_ID, $data)
	{
		$this->db->where('bestelling_ID', $item_ID);
		$this->db->update('bestellingen_los', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

		
	function updateBestellingProducten($item_ID, $data)
	{
		$this->db->where('bestelling_product_ID', $item_ID);
		$this->db->update('bestellingen_producten', $data);
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	////////////
	// DELETE //
	////////////
	
	function deleteProductenByBestellingID($item_ID)
	{
		$this->db->where('bestelling_ID', $item_ID);
		$this->db->delete('bestellingen_producten');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	
	function deleteBestelling($item_ID)
	{
		// Gekoppelde producten verwijderen
		
		$this->db->where('bestelling_ID', $item_ID);
		$this->db->delete('bestellingen_producten');
		
		// Bestelling verwijderen
		
		$this->db->where('bestelling_ID', $item_ID);
		$this->db->delete('bestellingen');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}
	function deleteBestellingLos($item_ID)
	{
		// Gekoppelde producten verwijderen
		$this->db->where('bestelling_ID', $item_ID);
		$this->db->delete('bestellingen_producten');
		// Bestelling verwijderen
		$this->db->where('bestelling_ID', $item_ID);
		$this->db->delete('bestellingen');
		// Bestelling "los" verwijderen
		$this->db->where('bestelling_ID', $item_ID);
		$this->db->delete('bestellingen_los');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

	function getBestellingenExport($bestelling_ID)
	{
		$this->db->select('*');
		$this->db->from('bestellingen');
		$this->db->join('aanmeldingen', 'aanmeldingen.aanmelding_ID = bestellingen.aanmelding_ID', 'left');
		$this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID', 'left');
		$this->db->where('bestelling_verzonden_datum', '0000-00-00 00:00:00');
		$this->db->where('bestellingen.bestelling_ID', $bestelling_ID);
		$this->db->order_by('bestellingen.bestelling_ID', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getBestellingenLosByID($bestelling_ID)
	{
		$this->db->select('*');
		$this->db->from('bestellingen_los');
		$this->db->join('gebruikers', 'bestellingen.gebruiker_ID = bestellingen.gebruiker_ID');
		$this->db->where('bestelling_ID', $bestelling_ID);
		$query = $this->db->get();
		return $query->result();
	}
	//get all order names
	function getAll_details(){
		$this->db->select('*');
		$this->db->from('stem_producten');
		$query = $this->db->get();
		return $query->result();
	}
}
