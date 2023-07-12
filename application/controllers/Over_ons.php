<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Over_ons extends CI_Controller
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
		$content = $this->paginas_model->getPaginaByID(3);
		$content->pagina_tekst = $this->ReplaceTags($content->pagina_tekst);
		$this->data['content'] = $content;

		$over = $this->paginas_model->getPaginaByID(5);
		$over->pagina_tekst = $this->ReplaceTags($over->pagina_tekst);
		$this->data['over'] = $over;

		$this->load->model('docenten_model');
		$docenten = $this->docenten_model->getDocenten();

		foreach($docenten as $docent) {
		    $docent->docent_tekst = $this->ReplaceTags($docent->docent_tekst);
        }

		$this->data['docenten'] = $docenten;

		$this->data['meta_title'] = 'Over localhost';
		$this->data['meta_description'] = 'Wij willen jou de leukste workshop van je leven geven. Onze passie voor het vak overdragen. Je laten ervaren hoe moeilijk localhost is. En jou leren hoe je dat vervolgens goed doet.';

		if(!empty($content->meta_title)) $this->data['meta_title'] = $content->meta_title;
		if(!empty($content->meta_description)) $this->data['meta_description'] = $content->meta_description;

		$this->load->model('media_model');
		$meta_media = $this->media_model->getMediaByMediaID($content->meta_media_ID);
        if(sizeof($meta_media) > 0) $this->data['og_image'] = base_url('/media/afbeeldingen/origineel/'.$meta_media[0]->media_src);

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'overons';
		$this->load->view('template', $pagina);
	}

    function ReplaceTags($tekst) {
        $tekst = str_replace('[TAB-TITEL-BEGIN]', '<ul id="tab"><li><label>', $tekst);
        $tekst = str_replace('[TAB-TITEL-EIND]', '<span class="changeArrow arrow"></span></label>', $tekst);
        $tekst = str_replace('[TAB-BEGIN]', '<div class="content">', $tekst);
        $tekst = str_replace('[TAB-EIND]', '</div></li></ul>', $tekst);

        $tekst = str_replace('[BORDER-BEGIN]', '<div class="border">', $tekst);
        $tekst = str_replace('[BORDER-EIND]', '</div>', $tekst);

        $tekst = str_replace('[BLAUWE-ACHTERGROND-BEGIN]', '<div class="blauwe_achtergrond">', $tekst);
        $tekst = str_replace('[BLAUWE-ACHTERGROND-EIND]', '</div>', $tekst);

        $tekst = str_replace('[VINKJE]', '<img src="'. base_url('assets/images/vinkje.png') .'">', $tekst);

        $tekst = str_replace('[LINK-BEGIN]', '<a class="button button--orange" href="', $tekst);
        $tekst = str_replace('[LINK-EIND]', '">', $tekst);
        $tekst = str_replace('[LINK-TEKST-BEGIN]', '', $tekst);
        $tekst = str_replace('[LINK-TEKST-EIND]', '</a>', $tekst);

        $tekst = str_replace('[BUTTON-BEGIN]', '<a class="button button--orange" href="', $tekst);
        $tekst = str_replace('[BUTTON-EIND]', '">', $tekst);
        $tekst = str_replace('[BUTTON-TEKST-BEGIN]', '', $tekst);
        $tekst = str_replace('[BUTTON-TEKST-EIND]', '</a>', $tekst);
        return  $tekst;
    }
}