<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Controller
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
		$content = $this->paginas_model->getPaginaByID(4);
		$this->data['content'] = $content;
		
		$this->load->model('vragen_model');
		$vragen = $this->vragen_model->getVragenGepubliceerd('website', 5);
		
		$contact_naam = '';
		$contact_emailadres = '';
		$contact_telefoon = '';
		$contact_onderwerp = '';
		$contact_bericht = '';
		
		$contact_naam_feedback = '';
		$contact_emailadres_feedback = '';
		$contact_telefoon_feedback = '';
		$contact_onderwerp_feedback = '';
		$contact_bericht_feedback = '';
		
		$contact_verzonden = false;
		$contact_feedback = '';
		
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if(!empty($_POST['website'])) die();

			if(isset($_POST['contact_naam']))
			{
				$contact_naam 			= trim($_POST['contact_naam']);
				$contact_emailadres 	= trim($_POST['contact_emailadres']);
				$contact_telefoon 		= trim($_POST['contact_telefoon']);
				$contact_onderwerp 		= trim($_POST['contact_onderwerp']);
				$contact_bericht 		= trim($_POST['contact_bericht']);
				
				$fouten = 0;
				
				if(empty($contact_naam))
				{
					$contact_naam_feedback = 'Graag je naam invullen';
					$fouten++;
				}
				
				if(empty($contact_telefoon))
				{
					$contact_telefoon_feedback = 'Graag je telefoonnummer invullen';
					$fouten++;
				}
				
				if(empty($contact_emailadres))
				{
					$contact_emailadres_feedback = 'Graag je e-mailadres invullen';
					$fouten++;
				}
				else
				{
					if(!filter_var($contact_emailadres, FILTER_VALIDATE_EMAIL))
					{
						$contact_emailadres_feedback = 'Graag een geldig e-mailadres';
						$fouten++;
					}
				}
				
				if(empty($contact_onderwerp))
				{
					$contact_onderwerp_feedback = 'Graag een onderwerp invullen';
					$fouten++;
				}
				
				if(empty($contact_bericht))
				{
					$contact_bericht_feedback = 'Graag een vraag stellen';
					$fouten++;
				}
				
				if($fouten == 0)
				{
					$bericht = $contact_bericht;
					$bericht .= '<br /><br />-----<br />Verzonden via het contactformulier van localhost';

                    // OVERZICHT E-MAILEN

                    $email = array(
                        'html' => $bericht,
                        'subject' => $contact_onderwerp,
                        'from_email' => 'info@localhost',
                        'from_name' => $contact_naam,
                        'to' => array(
                            array(
                                'email' => 'info@localhost',
                                'name' => 'localhost',
                                'type' => 'to'
                            )
                        ),
                        'headers' => array('Reply-To' => $contact_emailadres),
                        'track_opens' => true,
                        'track_clicks' => true,
                        'auto_text' => true
                    );

                    $this->load->helper('mandrill');
					$mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
					// $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
                    $mandrill->messages->send($email);

                    $contact_verzonden = true;
				}
			}
		}
		
		
		// PAGINA TONEN
		
		$this->data['vragen'] = $vragen;
		
		$this->data['contact_naam'] 		= $contact_naam;
		$this->data['contact_emailadres'] 	= $contact_emailadres;
		$this->data['contact_telefoon'] 	= $contact_telefoon;
		$this->data['contact_onderwerp'] 	= $contact_onderwerp;
		$this->data['contact_bericht'] 		= $contact_bericht;
		
		$this->data['contact_naam_feedback'] 		= $contact_naam_feedback;
		$this->data['contact_emailadres_feedback'] 	= $contact_emailadres_feedback;
		$this->data['contact_telefoon_feedback'] 	= $contact_telefoon_feedback;
		$this->data['contact_onderwerp_feedback'] 	= $contact_onderwerp_feedback;
		$this->data['contact_bericht_feedback'] 	= $contact_bericht_feedback;
		
		$this->data['contact_verzonden'] 	= $contact_verzonden;
		$this->data['contact_feedback'] 	= $contact_feedback;
		
		$this->data['meta_title'] = 'Contact localhost';
		$this->data['meta_description'] = 'Voor meer informatie over de leukste workshops van je leven kun je contact opnemen. We staan je graag te woord!';
		
		if(!empty($content->meta_title)) $this->data['meta_title'] = $content->meta_title;
		if(!empty($content->meta_description)) $this->data['meta_description'] = $content->meta_description;

		$this->load->model('media_model');
		$meta_media = $this->media_model->getMediaByMediaID($content->meta_media_ID);
        if(sizeof($meta_media) > 0) $this->data['og_image'] = base_url('/media/afbeeldingen/origineel/'.$meta_media[0]->media_src);
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'contact';
		$this->load->view('template', $pagina);
	}
	
	public function vragen()
	{
		$this->load->model('vragen_model');
		$vragen = $this->vragen_model->getVragenGepubliceerd('website');
		$this->data['vragen'] = $vragen;
		
		
		// PAGINA TONEN
		
		$this->data['meta_title'] = 'Meest gestelde vragen aan localhost';
		$this->data['meta_description'] = '';
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'contact_vragen';
		$this->load->view('template', $pagina);
	}
}