<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
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
		$content = $this->paginas_model->getPaginaByID(1);
		$this->data['content'] = $content;

		$this->load->model('nieuws_model');
		$nieuws = $this->nieuws_model->getNieuwsGepubliceerd(1);
		$this->data['nieuws'] = $nieuws;

        $this->load->model('blogs_model');
        $blog = $this->blogs_model->getBlogsGepubliceerd(1);
        $this->data['blog'] = $blog;

		$this->load->model('reacties_model');
		$reacties = $this->reacties_model->getReactiesGepubliceerd(1);
		$this->data['reacties'] = $reacties;

		$this->load->model('kennismakingsworkshop_model');
        $kennismakingsworkshop = $this->kennismakingsworkshop_model->getVolgendeGepubliceerdeKennismakingsworkshopHome();
		$this->data['kennismakingsworkshop'] = $kennismakingsworkshop;

        $this->load->model('gegevens_model');
        $gegevens = $this->gegevens_model->getGegevens();

        $demo_account = false;
        $gratis_workshop = false;

        if(!empty($gegevens)) {
            foreach($gegevens as $gegeven) {
                if($gegeven->gegeven_naam == 'Demo account actief') {
                    if ($gegeven->gegeven_waarde == 'ja') {
                        $demo_account = true;
                    }
                }

                if($gegeven->gegeven_naam == 'Gratis workshop aan') {
                    if ($gegeven->gegeven_waarde == 'ja') {
                        $gratis_workshop = true;
                    }
                }
            }
        }

        $this->data['demo_account'] = $demo_account;
        $this->data['gratis_workshop'] = $gratis_workshop;
		$this->data['meta_title'] = 'Leren localhost van de beste! Doe mee met de onvervalste workshop localhost - localhost';
		$this->data['meta_description'] = 'Droom jij er van om je stem te mogen lenen aan commercials, documentaires, tekenfilms en andere audiovisuele producties? Leer localhost van de besten en doe mee met de workshop localhost.';

		if(!empty($content->meta_title)) $this->data['meta_title'] = $content->meta_title;
		if(!empty($content->meta_description)) $this->data['meta_description'] = $content->meta_description;

		$this->load->model('media_model');
		$meta_media = $this->media_model->getMediaByMediaID($content->meta_media_ID);
        if(sizeof($meta_media) > 0) $this->data['og_image'] = base_url('/media/afbeeldingen/origineel/'.$meta_media[0]->media_src);

		// PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'home';
		$this->load->view('template', $pagina);
	}
}