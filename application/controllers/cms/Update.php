<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update extends CI_Controller
{
	private $data = array();
	
	public function __construct()
	{
		parent::__construct();
		
		// Rechten controleren en aantal nieuwe items ophalen
		
		$this->load->library('algemeen');
		$this->algemeen->cms();
	}
	
	public function posities()
	{
		$items = $_POST['items'];
		$update = $_POST['update'];
		
		for($i = 0; $i < sizeof($update); $i++)
		{
			$item_ID = $update[$i][0];
			$item_positie = $update[$i][1];
			
			switch($items)
			{
				case 'workshops':
				$this->db->where('workshop_ID', $item_ID);
				$this->db->update('workshops', array('workshop_positie' => $item_positie));
				break;
				
				case 'lessen':
				$this->db->where('les_ID', $item_ID);
				$this->db->update('lessen', array('les_positie' => $item_positie));
				break;
				
				case 'producten':
				$this->db->where('product_ID', $item_ID);
				$this->db->update('producten', array('product_positie' => $item_positie));
				break;
				
				case 'docenten':
				$this->db->where('docent_ID', $item_ID);
				$this->db->update('docenten', array('docent_positie' => $item_positie));
				break;
				
				case 'vragen':
				$this->db->where('vraag_ID', $item_ID);
				$this->db->update('vragen', array('vraag_positie' => $item_positie));
				break;
				
				case 'media':
				$this->db->where('media_connectie_ID', $item_ID);
				$this->db->update('media_connecties', array('media_positie' => $item_positie));
				break;
			}
		}
	}
	
	public function zoeken()
	{
		$zoekterm = $_POST['zoekterm'];
		
		$resultaten = array();
		
		// Deelnemers zoeken
		
		$this->load->model('gebruikers_model');
		$deelnemers = $this->gebruikers_model->zoekDeelnemers($zoekterm);
		$resultaten['deelnemers'] = $deelnemers;

        $kandidaten = $this->gebruikers_model->zoekKandidaten($zoekterm);
        $resultaten['kandidaten'] = $kandidaten;
		
		// Beheerders zoeken
		
		$beheerders = $this->gebruikers_model->zoekBeheerders($zoekterm);
		$resultaten['beheerders'] = $beheerders;
		
		// Docenten zoeken
		
		$this->load->model('docenten_model');
		$docenten = $this->docenten_model->zoekDocenten($zoekterm);
		$resultaten['docenten'] = $docenten;
		
		// Workshops zoeken
		
		$this->load->model('workshops_model');
		$workshops = $this->workshops_model->zoekWorkshops($zoekterm);
		$resultaten['workshops'] = $workshops;
		
		// kennismakingsworkshops zoeken

		$this->load->model('kennismakingsworkshop_model');
		$workshops = $this->kennismakingsworkshop_model->zoekKennismakingsworkshop($zoekterm);
		$resultaten['kennismakingsworkshops'] = $workshops;

		// Groepen zoeken
		
		$this->load->model('groepen_model');
		$groepen = $this->groepen_model->zoekGroepen($zoekterm);
		$resultaten['groepen'] = $groepen;
		
		// Producten
		
		$this->load->model('producten_model');
		$producten = $this->producten_model->zoekProducten($zoekterm);
		$resultaten['producten'] = $producten;
		
		// Pagina's
		
		$this->load->model('paginas_model');
		$paginas = $this->paginas_model->zoekPaginas($zoekterm);
		$resultaten['paginas'] = $paginas;
		
		// Nieuws
		
		$this->load->model('nieuws_model');
		$nieuws = $this->nieuws_model->zoekNieuws($zoekterm);
		$resultaten['nieuws'] = $nieuws;
		
		// Reacties
		
		$this->load->model('reacties_model');
		$reacties = $this->reacties_model->zoekReacties($zoekterm);
		$resultaten['reacties'] = $reacties;
		
		// Vragen
		
		$this->load->model('vragen_model');
		$vragen = $this->vragen_model->zoekVragen($zoekterm);
		$resultaten['vragen'] = $vragen;
		
		// Media
		
		$this->load->model('media_model');
		$media = $this->media_model->zoekMedia($zoekterm);
		$resultaten['media'] = $media;
		
		echo json_encode($resultaten);
	}
}