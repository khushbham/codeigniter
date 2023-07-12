<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inschrijfvoorwaarden extends CI_Controller
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
		$this->load->model('paginas_model');
		$content = $this->paginas_model->getPaginaByID(6);
		$this->data['content'] = $content;
		
		if(!empty($content->meta_title)) $this->data['meta_title'] = $content->meta_title;
		if(!empty($content->meta_description)) $this->data['meta_description'] = $content->meta_description;

		$this->load->model('media_model');
		$meta_media = $this->media_model->getMediaByMediaID($content->meta_media_ID);
        if(sizeof($meta_media) > 0) $this->data['og_image'] = base_url('/media/afbeeldingen/origineel/'.$meta_media[0]->media_src);
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'inschrijfvoorwaarden';
		$this->load->view('template', $pagina);	
	}
}