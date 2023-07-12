<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workshops extends CI_Controller
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
		$content = $this->paginas_model->getPaginaByID(2);
		$this->data['content'] = $content;

		$this->load->model('workshops_model');
		$workshops = $this->workshops_model->getWorkshopsStandaard();
		$specialties = $this->workshops_model->getWorkshopsSpecialties();
		$uitgelicht = $this->workshops_model->getWorkshopsUitgelicht();
		$vervolg_workshops = $this->workshops_model->getWorkshopsVervolg();

		$this->load->model('groepen_model');

        if(sizeof($workshops)) {
            foreach ($workshops as $workshop) {
                $groepen = $this->groepen_model->getGroepenAanmeldenByWorkshopID($workshop->workshop_ID);

                if (!empty($groepen)) {
                    $workshop->plekken_over = sizeof($this->groepen_model->getGroepDeelnemers($groepen[0]->groep_ID));
                    $workshop->plekken_over = $workshop->workshop_capaciteit - $workshop->plekken_over;
                } else {
                    $workshop->plekken_over = 0;
                }
            }
        }

        if(sizeof($specialties)) {
            foreach ($specialties as $workshop) {
                $specialties_groepen = $this->groepen_model->getGroepenAanmeldenByWorkshopID($workshop->workshop_ID);

                if (!empty($specialties_groepen)) {
                    $workshop->plekken_over = sizeof($this->groepen_model->getGroepDeelnemers($specialties_groepen[0]->groep_ID));
                    $workshop->plekken_over = $workshop->workshop_capaciteit - $workshop->plekken_over;
                } else {
                    $workshop->plekken_over = 0;
                }
            }
        }

        if(sizeof($uitgelicht)) {
            foreach ($uitgelicht as $workshop) {
                $uitgelicht_groepen = $this->groepen_model->getGroepenAanmeldenByWorkshopID($workshop->workshop_ID);

                if (!empty($uitgelicht_groepen)) {
                    $workshop->plekken_over = sizeof($this->groepen_model->getGroepDeelnemers($uitgelicht_groepen[0]->groep_ID));
                    $workshop->plekken_over = $workshop->workshop_capaciteit - $workshop->plekken_over;
                } else {
                    $workshop->plekken_over = 0;
                }
            }
        }

        if(sizeof($vervolg_workshops)) {
            foreach ($vervolg_workshops as $workshop) {
                $vervolg_groepen = $this->groepen_model->getGroepenAanmeldenByWorkshopID($workshop->workshop_ID);

                if (!empty($vervolg_groepen)) {
                    $workshop->plekken_over = sizeof($this->groepen_model->getGroepDeelnemers($vervolg_groepen[0]->groep_ID));
                    $workshop->plekken_over = $workshop->workshop_capaciteit - $workshop->plekken_over;
                } else {
                    $workshop->plekken_over = 0;
                }
            }
        }

		$this->data['workshops'] = $workshops;
		$this->data['specialties'] = $specialties;
		$this->data['uitgelicht'] = $uitgelicht;
		$this->data['vervolg_workshops'] = $vervolg_workshops;

		$this->data['meta_title'] = 'Workshops - localhost';
		$this->data['meta_description'] = 'Leren localhost in drie stappen: introductieworkshop localhost, basisworkshop localhost en vervolgworkshop localhost. Online videolessen en praktijklessen in een écht geluidsstudio.';

		if(!empty($content->meta_title)) $this->data['meta_title'] = $content->meta_title;
		if(!empty($content->meta_description)) $this->data['meta_description'] = $content->meta_description;

        $this->load->model('media_model');
        $meta_media = $this->media_model->getMediaByMediaID($content->meta_media_ID);
        if(sizeof($meta_media) > 0) $this->data['og_image'] = base_url('/media/afbeeldingen/origineel/'.$meta_media[0]->media_src);

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'workshops';
		$this->load->view('template', $pagina);
	}

	public function detail($workshop_url = null)
	{
		if($workshop_url == null) redirect('workshops');

		$this->load->model('workshops_model');
		$workshop = $this->workshops_model->getWorkshop($workshop_url);
		if(!$workshop) redirect('workshops');

        $workshop->workshop_beschrijving = $this->ReplaceTags($workshop->workshop_beschrijving);

		$this->load->model('groepen_model');
		$groepen = $this->groepen_model->getGroepenAanmeldenByWorkshopID($workshop->workshop_ID);

        if(!empty($groepen)) {
            $plekken_over = sizeof($this->groepen_model->getGroepDeelnemers($groepen[0]->groep_ID));
            $plekken_over = $workshop->workshop_capaciteit - $plekken_over;
        } else {
            $plekken_over = 0;
        }

        $this->load->model('lessen_model');

        if($workshop->workshop_soort == 'normaal') {
            $groep_lessen = $this->lessen_model->getGroepAanmeldenLessenByWorkshopID($workshop->workshop_ID);
        } else {
            $groep_lessen = $this->lessen_model->getGroepAanmeldenLessenByWorkshopIDUitgebreid($workshop->workshop_ID);
        }

        if(!empty($groep_lessen)) {
            foreach($groep_lessen as $les) {
                if($les->les_type_ID != 0) {
                    $les_type = $this->lessen_model->getLesTypeByID($les->les_type_ID);
                    if(!empty($les_type[0]->les_type_soort)) {
                        $les->les_type = $les_type[0]->les_type_soort;
                    }
                }
            }
        }

		$this->load->model('workshops_model');
		$workshops = $this->workshops_model->getWorkshopsStandaard();
		$specialties = $this->workshops_model->getWorkshopsSpecialties();
		$uitgelicht = $this->workshops_model->getWorkshopsUitgelicht();
		$vervolg_workshops = $this->workshops_model->getWorkshopsVervolg();

		$this->data['workshops'] = $workshops;
		$this->data['plekken_over'] = $plekken_over;
		$this->data['specialties'] = $specialties;
		$this->data['uitgelicht'] = $uitgelicht;
		$this->data['vervolg_workshops'] = $vervolg_workshops;

		$this->data['workshop'] = $workshop;
		$this->data['groepen'] = $groepen;
		$this->data['groep_lessen'] = $groep_lessen;

		$this->data['meta_title'] = $workshop->workshop_titel.' - Workshops - localhost';
		$this->data['meta_description'] = '';

		if(!empty($workshop->meta_title)) $this->data['meta_title'] = $workshop->meta_title;
		if(!empty($workshop->meta_description)) $this->data['meta_description'] = $workshop->meta_description;

		$aanmeld_url = base_url('aanmelden/workshop/'.$workshop->workshop_url);

		if(($this->session->userdata('gebruiker_ID') || $this->session->userdata('beheerder_ID')) && $this->session->userdata('gebruiker_rechten') != 'test') {
            $aanmeld_url = base_url('cursistenmodule/aanmelden/workshop/'.$workshop->workshop_url);
        }

        $this->data['aanmeld_url'] = $aanmeld_url;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'workshop';
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

    public function gratis_workshop() {
        $cookie                     = false;
        $item_emailadres            = '';
        $item_emailadres_feedback   = '';

        $this->load->model('lessen_model');
        $this->load->model('paginas_model');
        $content = $this->paginas_model->getPaginaByID(9);
        $this->data['content'] = $content;

        if(isset($_COOKIE['gratisworkshop']) && !empty($_COOKIE['gratisworkshop'])) {
            $item_emailadres = $_COOKIE['gratisworkshop'];
            $cookie = true;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fouten = 0;
            if(!empty($_POST['item_emailadres'])) {  $item_emailadres = trim($_POST['item_emailadres']); } else {  $item_emailadres = ''; };

            if(!filter_var($item_emailadres, FILTER_VALIDATE_EMAIL) || empty($item_emailadres))
            {
                $item_emailadres_feedback = 'Graag een geldig e-mailadres invullen';
                $fouten++;
            }

            if($fouten == 0) {
                setcookie("gratisworkshop", $item_emailadres, strtotime('+30 days'));
                $cookie = true;

                // MailChimp API credentials
                $apiKey = 'd64e65814f08ffb01a43beced5404ff6-us7';
                $listID = '76cbccab02';

                $memberID = md5(strtolower($item_emailadres));
                $dataCenter = substr($apiKey, strpos($apiKey, '-') + 1);
                $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $memberID;

                $json = json_encode([
                    'email_address' => $item_emailadres,
                    'status' => 'subscribed',
                    'merge_fields' => [
                        'EMAIL' => $item_emailadres,
                        'NAME' => '',
                        'WORKSHOP' => 'Gratis workshop',
                        'SOORT' => 'Gratis-workshop'
                    ]
                ]);

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                $result = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
            }
        }

        $gratis_lessen = $this->lessen_model->getGratisLessen();

        // PAGINA TONEN
        $this->data['gratis_lessen'] = $gratis_lessen;
        $this->data['cookie'] = $cookie;
        $this->data['item_emailadres'] = $item_emailadres;
        $this->data['item_emailadres_feedback'] = $item_emailadres_feedback;
        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'gratis_workshop';
        $this->load->view('template', $pagina);
    }
}