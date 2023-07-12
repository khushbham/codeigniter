<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reacties extends CI_Controller
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
		$this->load->model('reacties_model');
		$aantal_items = $this->reacties_model->getReactiesAantal();
		$per_pagina = 10;
		$aantal_paginas = ceil($aantal_items / $per_pagina);
		$huidige_pagina = $p;
		$reacties = $this->reacties_model->getReacties($per_pagina, $huidige_pagina);
		
		// Controleren of de paginanummer te hoog is
		if($p > 1 && sizeof($reacties) == 0) redirect('cms/reacties');
		
		
		// PAGINA TONEN
		
		$this->data['reacties'] 			= $reacties;
		$this->data['aantal_paginas'] 		= $aantal_paginas;
		$this->data['huidige_pagina']		= $huidige_pagina;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/reacties';
		$this->load->view('cms/template', $pagina);
	}
	
	
	
	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */
	
	public function detail($item_ID = null)
	{
		if($item_ID == null) redirect('cms/reacties');
		
		$this->load->model('reacties_model');
		$this->load->model('media_model');
		
		$item = $this->reacties_model->getReactieByID($item_ID);
		if($item == null) redirect('cms/reacties');
		
		$media = $this->media_model->getMediaByMediaID($item->media_ID);
		
		
		// PAGINA LADEN
		
		$this->data['item'] = $item;
		$this->data['media'] = $media;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/reacties_reactie';
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
		$this->load->model('reacties_model');
		$this->load->model('media_model');
		
		$item_url 				= '';
		$item_titel 			= '';
		$item_bericht 			= '';
		$item_deelnemer		 	= '';
		$item_datum_dag			= '';
		$item_datum_maand		= '';
		$item_datum_jaar		= '';
		$item_tijd_uren			= '';
		$item_tijd_minuten		= '';
		$item_tijd_seconden		= '';
		$item_gepubliceerd 		= '';
		$media					= '';
		$media_tonen			= 'ja';
		$media_link				= '';
		$item_meta_title		= '';
		$item_meta_description	= '';
		
		$item_url_feedback 				= '';
		$item_titel_feedback 			= '';
		$item_bericht_feedback 			= '';
		$item_deelnemer_feedback 		= '';
		$item_datum_feedback 			= '';
		$item_tijd_feedback 			= '';
		$item_gepubliceerd_feedback 	= '';
		$item_meta_title_feedback		= '';
		$item_meta_description_feedback	= '';
		
		
		// FORMULIER VERZONDEN
		
		if(isset($_POST['item_titel']))
		{
			$fouten 				= 0;
			$item_url 				= trim($_POST['item_url']);
			$item_titel 			= trim($_POST['item_titel']);
			$item_bericht 			= trim($_POST['item_bericht']);
			$item_deelnemer 		= trim($_POST['item_deelnemer']);
			$item_datum_dag 		= trim($_POST['item_datum_dag']);
			$item_datum_maand 		= trim($_POST['item_datum_maand']);
			$item_datum_jaar 		= trim($_POST['item_datum_jaar']);
			$item_tijd_uren 		= trim($_POST['item_tijd_uren']);
			$item_tijd_minuten 		= trim($_POST['item_tijd_minuten']);
			$item_tijd_seconden 	= trim($_POST['item_tijd_seconden']);
			$item_media		 		= trim($_POST['item_media']);
			$media_ID		 		= trim($_POST['media_ID']);
			$media_link	 			= trim($_POST['media_link']);
			$item_meta_title		= trim($_POST['item_meta_title']);
			$item_meta_description	= trim($_POST['item_meta_description']);
			
			if(isset($_POST['item_gepubliceerd'])) $item_gepubliceerd = $_POST['item_gepubliceerd'];
			if(isset($_POST['media_tonen'])) $media_tonen = $_POST['media_tonen'];
			
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
			
			if(empty($item_bericht))
			{
				$fouten++;
				$item_bericht_feedback = 'Graag invullen';
			}
			
			if(empty($item_deelnemer))
			{
				$fouten++;
				$item_deelnemer_feedback = 'Graag invullen';
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
			
			if(empty($item_gepubliceerd))
			{
				$fouten++;
				$item_gepubliceerd_feedback = 'Graag selecteren';
			}
			
			if($fouten == 0)
			{
				// TOEVOEGEN / UPDATEN
				
				$data = array(
					'reactie_url' => $item_url,
					'reactie_titel' => $item_titel,
					'reactie_bericht' => $item_bericht,
					'reactie_deelnemer' => $item_deelnemer,
					'reactie_gepubliceerd' => $item_gepubliceerd,
					'reactie_datum' => $item_datum_jaar.'-'.$item_datum_maand.'-'.$item_datum_dag.' '.$item_tijd_uren.':'.$item_tijd_minuten.':'.$item_tijd_seconden,
					'media_ID' => str_replace(',', '', $item_media),
					'media_tonen' => $media_tonen,
					'media_link' => $media_link,
					'meta_title' => $item_meta_title,
					'meta_description' => $item_meta_description
				);
				
				if($actie == 'toevoegen') $q = $this->reacties_model->insertReactie($data);
				else $q = $this->reacties_model->updateReactie($item_ID, $data);
				
				if($q)
				{
					if($actie == 'toevoegen') redirect('cms/reacties');
					else redirect('cms/reacties/'.$item_ID);
				}
				else
				{
					echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
				}
			}
		}
		
		if($actie == 'wijzigen')
		{
			$item = $this->reacties_model->getReactieByID($item_ID);
			if($item == null) redirect('cms/reacties');
			
			$item_url 				= $item->reactie_url;
			$item_titel 			= $item->reactie_titel;
			$item_bericht 			= $item->reactie_bericht;
			$item_deelnemer 		= $item->reactie_deelnemer;
			$item_datum 			= $item->reactie_datum;
			$item_gepubliceerd 		= $item->reactie_gepubliceerd;
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
			$media_tonen		 	= $item->media_tonen;
			$media_link				= $item->media_link;
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
		$this->data['item_titel'] 				= $item_titel;
		$this->data['item_bericht'] 			= $item_bericht;
		$this->data['item_deelnemer'] 			= $item_deelnemer;
		$this->data['item_datum_dag'] 			= $item_datum_dag;
		$this->data['item_datum_maand'] 		= $item_datum_maand;
		$this->data['item_datum_jaar'] 			= $item_datum_jaar;
		$this->data['item_tijd_uren'] 			= $item_tijd_uren;
		$this->data['item_tijd_minuten'] 		= $item_tijd_minuten;
		$this->data['item_tijd_seconden'] 		= $item_tijd_seconden;
		$this->data['item_gepubliceerd'] 		= $item_gepubliceerd;
		$this->data['item_meta_title'] 			= $item_meta_title;
		$this->data['item_meta_description'] 	= $item_meta_description;
		
		$this->data['media']			 	= $media;
		$this->data['media_tonen'] 			= $media_tonen;
		$this->data['media_link'] 			= $media_link;
		
		$this->data['item_url_feedback'] 				= $item_url_feedback;
		$this->data['item_titel_feedback'] 				= $item_titel_feedback;
		$this->data['item_bericht_feedback'] 			= $item_bericht_feedback;
		$this->data['item_deelnemer_feedback']	 		= $item_deelnemer_feedback;
		$this->data['item_datum_feedback'] 				= $item_datum_feedback;
		$this->data['item_tijd_feedback'] 				= $item_tijd_feedback;
		$this->data['item_gepubliceerd_feedback'] 		= $item_gepubliceerd_feedback;
		$this->data['item_meta_title_feedback'] 		= $item_meta_title_feedback;
		$this->data['item_meta_description_feedback'] 	= $item_meta_description_feedback;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/reacties_wijzigen';
		$this->load->view('cms/template', $pagina);
	}
	
	
	
	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */
	
	public function verwijderen($item_ID = null, $bevestiging = null)
	{
		if($item_ID == null) redirect('cms/reacties');
		
		$this->load->model('reacties_model');
		$item = $this->reacties_model->getReactieByID($item_ID);
		if($item == null) redirect('cms/reacties');
		$this->data['item'] = $item;
		
		
		// ITEM VERWIJDEREN
		
		if($bevestiging == 'ja')
		{
			$q = $this->reacties_model->deleteReactie($item_ID);
			if($q) redirect('cms/reacties');
			else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
		}
		
		
		// PAGINA TONEN
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/reacties_verwijderen';
		$this->load->view('cms/template', $pagina);
	}
}