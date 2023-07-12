<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nieuws extends CI_Controller
{
	private $data = array();
	
	public function __construct()
	{
		parent::__construct();
		
		// Inloggen
		
		$this->load->library('algemeen');
		$this->algemeen->inloggen();
		$this->data['gegevens'] = $this->algemeen->gegevens();

		$gegevens = $this->algemeen->gegevens();
			
		if(!empty($gegevens)) {
			foreach($gegevens as $gegeven) {
				if($gegeven->gegeven_naam == 'onderhoud publieke site') {
					if ($gegeven->gegeven_waarde == 'ja') {
						redirect('onderhoud');
					}
				}
			}
		}
	}
	
	public function index()
	{
		$this->pagina();
	}
	
	public function pagina($p = 1)
	{
		$this->load->model('nieuws_model');
		$aantal_items = $this->nieuws_model->getNieuwsGepubliceerdAantal();
		$per_pagina = 5;
		$aantal_paginas = ceil($aantal_items / $per_pagina);
		$huidige_pagina = $p;
		$nieuws = $this->nieuws_model->getNieuwsGepubliceerd($per_pagina, $huidige_pagina);
		
		// Controleren of de paginanummer te hoog is
		if(sizeof($nieuws) == 0) redirect('nieuws');
		
		
		// PAGINA TONEN
		
		$this->data['nieuws'] 				= $nieuws;
		$this->data['aantal_paginas'] 		= $aantal_paginas;
		$this->data['huidige_pagina']		= $huidige_pagina;
		
		
		$this->data['meta_title'] = 'Nieuws - localhost';
		$this->data['meta_description'] = '';
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'nieuws';
		$this->load->view('template', $pagina);
	}
	
	public function artikel($url = null)
	{
		// NIEUWSBERICHT OPHALEN
		
		$this->load->model('nieuws_model');
		$nieuwsbericht = $this->nieuws_model->getNieuwsByURL($url);
		if(!$nieuwsbericht) redirect('nieuws');
		
		
		// MEDIA OPHALEN
		
		$this->load->model('media_model');
		$media = $this->media_model->getMediaByContentID('nieuws', $nieuwsbericht->nieuws_ID);
		$this->data['media'] = $media;
		
		
		// VORIGE EN VOLGENDE BERICHT OPHALEN
		
		$vorige_bericht = $this->nieuws_model->getVorigeBericht($nieuwsbericht->nieuws_datum);
		$volgende_bericht = $this->nieuws_model->getVolgendeBericht($nieuwsbericht->nieuws_datum);
		
		
		// PAGINA TONEN
		
		$this->data['og_type'] 				= 'article';
		$this->data['nieuwsbericht'] 		= $nieuwsbericht;
		$this->data['vorige_bericht'] 		= $vorige_bericht;
		$this->data['volgende_bericht'] 	= $volgende_bericht;
		
		
		$this->data['meta_title'] = $nieuwsbericht->nieuws_titel.' - Nieuws - localhost';
		$this->data['meta_description'] = '';
		
		if(!empty($nieuwsbericht->meta_title)) $this->data['meta_title'] = $nieuwsbericht->meta_title;
		if(!empty($nieuwsbericht->meta_description)) $this->data['meta_description'] = $nieuwsbericht->meta_description;

		$meta_media = $this->media_model->getMediaByMediaID($nieuwsbericht->meta_media_ID);
        if(sizeof($meta_media) > 0) $this->data['og_image'] = base_url('/media/afbeeldingen/origineel/'.$meta_media[0]->media_src);
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'nieuwsbericht';
		$this->load->view('template', $pagina);
	}
}