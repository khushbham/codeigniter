<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Privacyverklaring extends CI_Controller
{
	private $data = array();
	
	public function __construct()
	{
		parent::__construct();
		
		// Inloggen
		
		$this->load->library('algemeen');
		$this->algemeen->inloggen();
		$this->data['gegevens'] = $this->algemeen->gegevens();
	}
	
	public function index()
	{
		$this->load->model('paginas_model');
		$content = $this->paginas_model->getPaginaByID(11);
		$this->data['content'] = $content;
		
		if(!empty($content->meta_title)) $this->data['meta_title'] = $content->meta_title;
		if(!empty($content->meta_description)) $this->data['meta_description'] = $content->meta_description;

		$this->load->model('media_model');
		$meta_media = $this->media_model->getMediaByMediaID($content->meta_media_ID);
        if(sizeof($meta_media) > 0) $this->data['og_image'] = base_url('/media/afbeeldingen/origineel/'.$meta_media[0]->media_src);
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'privacyverklaring';
		$this->load->view('template', $pagina);	
	}
}