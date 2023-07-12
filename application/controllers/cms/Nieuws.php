<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nieuws extends CI_Controller
{
	private $data = array();
	
	public function __construct()
	{
		parent::__construct();
		
		// Rechten controleren en aantal nieuwe items ophalen
		
		$this->load->library('algemeen');
		$this->algemeen->cms();
		if($this->session->userdata('beheerder_rechten') != 'admin') redirect('cms/rechten');
	}
	
	
	
	/* ============= */
	/* = OVERZICHT = */
	/* ============= */
	
	public function index()
	{
		$this->pagina();
	}
	
	public function pagina($p = 1)
	{
		$this->load->model('nieuws_model');
		$aantal_items = $this->nieuws_model->getNieuwsAantal();
		$per_pagina = 10;
		$aantal_paginas = ceil($aantal_items / $per_pagina);
		$huidige_pagina = $p;
		$nieuws = $this->nieuws_model->getNieuws($per_pagina, $huidige_pagina);
		
		// Controleren of de paginanummer te hoog is
		
		if($p > 1 && sizeof($nieuws) == 0) redirect('cms/nieuws');
		
		
		// PAGINA TONEN
		
		$this->data['nieuws'] 				= $nieuws;
		$this->data['aantal_paginas'] 		= $aantal_paginas;
		$this->data['huidige_pagina']		= $huidige_pagina;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/nieuws';
		$this->load->view('cms/template', $pagina);
	}
	
	
	
	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */
	
	public function detail($item_ID = null)
	{
		if($item_ID == null) redirect('cms/nieuws');
		
		$this->load->model('nieuws_model');
		$this->load->model('media_model');
		
		$item = $this->nieuws_model->getNieuwsByID($item_ID);
		if($item == null) redirect('cms/nieuws');
		
		$media = $this->media_model->getMediaByMediaID($item->media_ID);
		$meta_media = $this->media_model->getMediaByMediaID($item->meta_media_ID);
		
		
		// PAGINA TONEN
		
		$this->data['item'] = $item;
		$this->data['media'] = $media;
		$this->data['meta_media'] = $meta_media;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/nieuws_bericht';
		$this->load->view('cms/template', $pagina);
	}
	
	
	
	/* ========================= */
	/* = TOEVOEGEN EN WIJZIGEN = */
	/* ========================= */
	
	public function toevoegen()
	{
		$this->_toevoegen_wijzigen('toevoegen');
	}
	
	public function wijzigen($item_ID = null)
	{
		if($item_ID == null) redirect('cms/nieuws');
		$this->_toevoegen_wijzigen('wijzigen', $item_ID);
	}
	
	private function _toevoegen_wijzigen($actie, $item_ID = null)
	{
		$this->load->model('nieuws_model');
		$this->load->model('media_model');
		
		$item_url 				= '';
		$item_titel 			= '';
		$item_datum_dag			= '';
		$item_datum_maand		= '';
		$item_datum_jaar		= '';
		$item_tijd_uren			= '';
		$item_tijd_minuten		= '';
		$item_tijd_seconden		= '';
		$item_inleiding		 	= '';
		$item_bericht 			= '';
		$item_gepubliceerd 		= '';
		$media					= '';
		$item_meta_title		= '';
		$item_meta_description	= '';
		$meta_media       		= '';
		
		$item_url_feedback 				= '';
		$item_titel_feedback 			= '';
		$item_datum_feedback 			= '';
		$item_tijd_feedback 			= '';
		$item_inleiding_feedback 		= '';
		$item_bericht_feedback 			= '';
		$item_gepubliceerd_feedback 	= '';
		$item_meta_title_feedback		= '';
		$item_meta_description_feedback	= '';
		
		
		// FORMULIER VERZONDEN
		
		if(isset($_POST['item_titel']))
		{
			$fouten 				= 0;
			$item_url 				= trim($_POST['item_url']);
			$item_titel 			= trim($_POST['item_titel']);
			$item_datum_dag 		= trim($_POST['item_datum_dag']);
			$item_datum_maand 		= trim($_POST['item_datum_maand']);
			$item_datum_jaar 		= trim($_POST['item_datum_jaar']);
			$item_tijd_uren 		= trim($_POST['item_tijd_uren']);
			$item_tijd_minuten 		= trim($_POST['item_tijd_minuten']);
			$item_tijd_seconden 	= trim($_POST['item_tijd_seconden']);
			$item_inleiding 		= trim($_POST['item_inleiding']);
			$item_bericht 			= trim($_POST['item_bericht']);
			$item_media		 		= trim($_POST['item_media']);
			$item_meta_title		= trim($_POST['item_meta_title']);
			$item_meta_description	= trim($_POST['item_meta_description']);
			$item_meta_media  		= trim($_POST['item_media_uitgelicht']);
			
			if(isset($_POST['item_gepubliceerd'])) $item_gepubliceerd = $_POST['item_gepubliceerd'];
			
			if(empty($item_url))
			{
				$fouten++;
				$item_url_feedback = 'Graag invullen';
			}
			
			if(empty($item_titel))
			{
				$fouten++;
				$item_titel_feedback = 'Graag invullen';
			}
			
			if(empty($item_datum_dag) || empty($item_datum_maand) || empty($item_datum_jaar))
			{
				$fouten++;
				$item_datum_feedback = 'Graag invullen';
			}
			
			if(empty($item_tijd_uren) || empty($item_tijd_minuten) || empty($item_tijd_seconden))
			{
				$fouten++;
				$item_tijd_feedback = 'Graag invullen';
			}
			
			if(empty($item_inleiding))
			{
				$fouten++;
				$item_inleiding_feedback = 'Graag invullen';
			}
			
			if(empty($item_bericht))
			{
				$fouten++;
				$item_bericht_feedback = 'Graag invullen';
			}
			
			if(empty($item_gepubliceerd))
			{
				$fouten++;
				$item_gepubliceerd_feedback = 'Graag selecteren';
			}
			
			if($fouten == 0)
			{
				// TOEVOEGEN / UPDATEN
				
				$data = array(
					'nieuws_url' => $item_url,
					'nieuws_titel' => $item_titel,
					'nieuws_inleiding' => $item_inleiding,
					'nieuws_bericht' => $item_bericht,
					'nieuws_gepubliceerd' => $item_gepubliceerd,
					'nieuws_datum' => $item_datum_jaar.'-'.$item_datum_maand.'-'.$item_datum_dag.' '.$item_tijd_uren.':'.$item_tijd_minuten.':'.$item_tijd_seconden,
					'media_ID' => str_replace(',', '', $item_media),
					'meta_media_ID' => str_replace(',', '', $item_meta_media),
					'meta_title' => $item_meta_title,
					'meta_description' => $item_meta_description
				);
				
				if($actie == 'toevoegen') $q = $this->nieuws_model->insertNieuws($data);
				else $q = $this->nieuws_model->updateNieuws($item_ID, $data);
				
				if($q)
				{
					if($actie == 'toevoegen') redirect('cms/nieuws');
					else redirect('cms/nieuws/'.$item_ID);
				}
				else
				{
					echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
				}
			}
		}
		
		if($actie == 'wijzigen')
		{
			// NIEUWS OPHALEN
			
			$item = $this->nieuws_model->getNieuwsByID($item_ID);
			if($item == null) redirect('cms/nieuws');
			
			$item_url 				= $item->nieuws_url;
			$item_titel 			= $item->nieuws_titel;
			$item_datum 			= $item->nieuws_datum;
			$item_inleiding 		= $item->nieuws_inleiding;
			$item_bericht 			= $item->nieuws_bericht;
			$item_gepubliceerd 		= $item->nieuws_gepubliceerd;
			$item_meta_title		= $item->meta_title;
			$item_meta_description	= $item->meta_description;
			
			$datum_tijd = explode(' ', $item_datum);
			$datum = explode('-', $datum_tijd[0]);
			$tijd = explode(':', $datum_tijd[1]);
			
			$item_datum_dag 		= $datum[2];
			$item_datum_maand 		= $datum[1];
			$item_datum_jaar 		= $datum[0];
			$item_tijd_uren 		= $tijd[0];
			$item_tijd_minuten 		= $tijd[1];
			$item_tijd_seconden 	= $tijd[2];
			
			// MEDIA OPHALEN
			
			$media 					= $this->media_model->getMediaByMediaID($item->media_ID);
			$meta_media       		= $this->media_model->getMediaByMediaID($item->meta_media_ID);
		}
		else
		{
			$item_datum_dag 		= date('d');
			$item_datum_maand 		= date('m');
			$item_datum_jaar 		= date('Y');
			$item_tijd_uren 		= date('H');
			$item_tijd_minuten 		= date('i');
			$item_tijd_seconden 	= date('s');
		}
		
		
		// PAGINA TONEN
		
		$this->data['actie'] = $actie;
		
		$this->data['item_ID'] 					= $item_ID;
		$this->data['item_url'] 				= $item_url;
		$this->data['item_datum_dag'] 			= $item_datum_dag;
		$this->data['item_datum_maand'] 		= $item_datum_maand;
		$this->data['item_datum_jaar'] 			= $item_datum_jaar;
		$this->data['item_tijd_uren'] 			= $item_tijd_uren;
		$this->data['item_tijd_minuten'] 		= $item_tijd_minuten;
		$this->data['item_tijd_seconden'] 		= $item_tijd_seconden;
		$this->data['item_titel'] 				= $item_titel;
		$this->data['item_inleiding'] 			= $item_inleiding;
		$this->data['item_bericht'] 			= $item_bericht;
		$this->data['item_gepubliceerd'] 		= $item_gepubliceerd;
		$this->data['media']			 		= $media;
		$this->data['item_meta_title'] 			= $item_meta_title;
		$this->data['item_meta_description'] 	= $item_meta_description;
		$this->data['meta_media']          		= $meta_media;
		
		$this->data['item_url_feedback'] 				= $item_url_feedback;
		$this->data['item_titel_feedback'] 				= $item_titel_feedback;
		$this->data['item_datum_feedback'] 				= $item_datum_feedback;
		$this->data['item_tijd_feedback'] 				= $item_tijd_feedback;
		$this->data['item_inleiding_feedback']	 		= $item_inleiding_feedback;
		$this->data['item_bericht_feedback'] 			= $item_bericht_feedback;
		$this->data['item_gepubliceerd_feedback'] 		= $item_gepubliceerd_feedback;
		$this->data['item_meta_title_feedback'] 		= $item_meta_title_feedback;
		$this->data['item_meta_description_feedback'] 	= $item_meta_description_feedback;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/nieuws_wijzigen';
		$this->load->view('cms/template', $pagina);
	}
	
	
	
	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */
	
	public function verwijderen($item_ID = null, $bevestiging = null)
	{
		if($item_ID == null) redirect('cms/nieuws');
		
		$this->load->model('nieuws_model');
		$item = $this->nieuws_model->getNieuwsByID($item_ID);
		if($item == null) redirect('cms/nieuws');
		$this->data['item'] = $item;
		
		
		// ITEM VERWIJDEREN
		
		if($bevestiging == 'ja')
		{
			$q = $this->nieuws_model->deleteNieuws($item_ID);
			if($q) redirect('cms/nieuws');
			else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
		}
		
		
		// PAGINA TONEN
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/nieuws_verwijderen';
		$this->load->view('cms/template', $pagina);
	}
}