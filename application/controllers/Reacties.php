<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reacties extends CI_Controller
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
		$this->load->model('reacties_model');
		$aantal_items = $this->reacties_model->getReactiesGepubliceerdAantal();
		$per_pagina = 5;
		$aantal_paginas = ceil($aantal_items / $per_pagina);
		$huidige_pagina = $p;
		$reacties = $this->reacties_model->getReactiesGepubliceerd($per_pagina, $huidige_pagina);
		
		// Controleren of de paginanummer te hoog is
		if(sizeof($reacties) == 0) redirect('reacties');
		
		
		// PAGINA TONEN
		
		$this->data['reacties'] 			= $reacties;
		$this->data['aantal_paginas'] 		= $aantal_paginas;
		$this->data['huidige_pagina']		= $huidige_pagina;
		
		$this->data['meta_title'] = 'Reacties - localhost';
		$this->data['meta_description'] = '';
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'reacties';
		$this->load->view('template', $pagina);
	}
	
	public function reactie($url = null)
	{
		$this->load->model('reacties_model');
		$reactie = $this->reacties_model->getReactieByURL($url);
		$this->data['reactie'] = $reactie;
		if(!$reactie) redirect('reacties');
		
		
		// PAGINA TONEN
		
		$this->data['meta_title'] = $reactie->reactie_titel.' - Reacties - localhost';
		$this->data['meta_description'] = '';
		
		if(!empty($reactie->meta_title)) $this->data['meta_title'] = $reactie->meta_title;
		if(!empty($reactie->meta_description)) $this->data['meta_description'] = $reactie->meta_description;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'reactie';
		$this->load->view('template', $pagina);
	}
}