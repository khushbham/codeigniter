<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Docenten extends CI_Controller
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
		$this->load->model('docenten_model');
		$aantal_items = $this->docenten_model->getDocentenAantal();
		$per_pagina = 50;
		$aantal_paginas = ceil($aantal_items / $per_pagina);
		$huidige_pagina = $p;
		$docenten = $this->docenten_model->getDocenten($per_pagina, $huidige_pagina);

		// Controleren of de paginanummer te hoog is
		if($p > 1 && sizeof($docenten) == 0) redirect('cms/docenten');


		// PAGINA TONEN

		$this->data['docenten'] 			= $docenten;
		$this->data['aantal_paginas'] 		= $aantal_paginas;
		$this->data['huidige_pagina']		= $huidige_pagina;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/docenten';
		$this->load->view('cms/template', $pagina);
	}



	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */

	public function detail($item_ID = null)
	{
		if($item_ID == null) redirect('cms/docenten');

        $docent = '';
		$this->load->model('docenten_model');
		$this->load->model('media_model');

		$item = $this->docenten_model->getDocentByID($item_ID);
		$item->docent_tekst = $this->ReplaceTags($item->docent_tekst);
		if($item == null) redirect('cms/docenten');

		$media = $this->media_model->getMediaByMediaID($item->media_ID);

        if (!empty($item->gebruiker_ID)) {
            $docent = $this->docenten_model->getDocentByGebruikerID($item->gebruiker_ID);
        }

		// PAGINA TONEN

		$this->data['item'] = $item;
		$this->data['docent'] = $docent;
		$this->data['media'] = $media;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/docenten_docent';
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
		if($item_ID == null) redirect('cms/docenten');
		$this->_toevoegen_wijzigen('wijzigen', $item_ID);
	}

	private function _toevoegen_wijzigen($actie, $item_ID = null)
	{
		$this->load->model('docenten_model');
		$this->load->model('media_model');
		$this->load->model('gebruikers_model');

		$item_naam 				= '';
		$item_inleiding			= '';
		$item_tekst			 	= '';
		$media					= '';
		$item_naam_feedback 			= '';
		$item_inleiding_feedback 		= '';
        $item_tekst_feedback 			= '';
        $item_rechten                   = 'docent';

        $item_bedrijfsnaam 				= '';
        $item_voornaam 					= '';
        $item_tussenvoegsel 			= '';
        $item_achternaam 				= '';
        $item_geslacht 					= '';
        $item_geboortedatum_dag 		= '';
        $item_geboortedatum_maand 		= '';
        $item_geboortedatum_jaar 		= '';
        $item_adres 					= '';
        $item_postcode_cijfers 			= '';
        $item_postcode_letters 			= '';
        $item_plaats 					= '';
        $item_telefoonnummer 			= '';
        $item_mobiel 					= '';
        $item_emailadres 				= '';
        $item_notities 					= '';
        $item_instelling_anoniem 		= '';
        $item_instelling_email_updates 	= '';
        $item_instelling_berichten_aan      = '';

        $item_bedrijfsnaam_feedback 				= '';
        $item_voornaam_feedback 					= '';
        $item_tussenvoegsel_feedback 				= '';
        $item_achternaam_feedback 					= '';
        $item_geslacht_feedback 					= '';
        $item_geboortedatum_feedback 				= '';
        $item_adres_feedback 						= '';
        $item_postcode_feedback 					= '';
        $item_plaats_feedback 						= '';
        $item_telefoonnummer_feedback 				= '';
        $item_mobiel_feedback 						= '';
        $item_emailadres_feedback 					= '';
        $item_notities_feedback 					= '';
        $item_instelling_anoniem_feedback 			= '';
        $item_instelling_email_updates_feedback 	= '';
        $item_instelling_berichten_aan_feedback     = '';


		// FORMULIER VERZONDEN

		if(isset($_POST['item_naam']))
		{
			$fouten 			= 0;
			$item_naam 			= trim($_POST['item_naam']);
			$item_inleiding 	= trim($_POST['item_inleiding']);
			$item_tekst		 	= trim($_POST['item_tekst']);
            $item_media			= trim($_POST['item_media']);
            $item_rechten       = trim($_POST['item_rechten']);

            $item_bedrijfsnaam 				= trim($_POST['item_bedrijfsnaam']);
            $item_voornaam 					= ucfirst(trim($_POST['item_voornaam']));
            $item_tussenvoegsel 			= trim($_POST['item_tussenvoegsel']);
            $item_achternaam 				= ucfirst(trim($_POST['item_achternaam']));
            $item_geslacht 				    = ucfirst(trim($_POST['item_geslacht']));
            $item_geboortedatum_dag 		= trim($_POST['item_geboortedatum_dag']);
            $item_geboortedatum_maand 		= trim($_POST['item_geboortedatum_maand']);
            $item_geboortedatum_jaar 		= trim($_POST['item_geboortedatum_jaar']);
            $item_adres 					= trim($_POST['item_adres']);
            $item_postcode_cijfers 			= trim($_POST['item_postcode_cijfers']);
            $item_postcode_letters 			= trim($_POST['item_postcode_letters']);
            $item_plaats 					= trim($_POST['item_plaats']);
            $item_telefoonnummer 			= trim($_POST['item_telefoonnummer']);
            $item_mobiel 					= trim($_POST['item_mobiel']);
            $item_emailadres 				= trim($_POST['item_emailadres']);
            $item_notities 					= trim($_POST['item_notities']);
            $item_instelling_anoniem 		= trim($_POST['item_instelling_anoniem']);
            $item_instelling_email_updates 	= trim($_POST['item_instelling_email_updates']);
            $item_instelling_berichten_aan  = trim($_POST['item_instelling_berichten_aan']);

			if(empty($item_naam))
			{
				$fouten++;
				$item_naam_feedback = 'Graag invullen';
			}

			if(empty($item_inleiding))
			{
				$fouten++;
				$item_inleiding_feedback = 'Graag invullen';
			}

			if(empty($item_tekst))
			{
				$fouten++;
				$item_tekst_feedback = 'Graag invullen';
			}

            // Verplicht

            if(empty($item_voornaam))
            {
                $fouten++;
                $item_voornaam_feedback = 'Graag invullen';
            }

            if(empty($item_achternaam))
            {
                $fouten++;
                $item_achternaam_feedback = 'Graag invullen';
            }

            if(empty($item_instelling_berichten_aan)) {
                $fouten++;
                $item_instelling_berichten_aan = 'Graag een keuze maken';
            }

            if(empty($item_instelling_anoniem)) {
                $fouten++;
                $item_instelling_berichten_aan = 'Graag een keuze maken';
            }

            if(empty($item_instelling_email_updates)) {
                $fouten++;
                $item_instelling_berichten_aan = 'Graag een keuze maken';
            }

            if(empty($item_emailadres))
            {
                $fouten++;
                $item_emailadres_feedback = 'Graag invullen';
            }
            else
            {
                if(!filter_var($item_emailadres, FILTER_VALIDATE_EMAIL))
                {
                    $fouten++;
                    $item_emailadres_feedback = 'Graag een geldig e-mailadres';
                }
                elseif($actie == 'toevoegen')
                {
                    // Controleren of e-mailadres al gebruikt wordt

                    $gebruiker = $this->gebruikers_model->checkEmailadres($item_emailadres);

                    if($gebruiker != null)
                    {
                        $fouten++;
                        $item_emailadres_feedback = 'E-mailadres al in gebruik';
                    }
                }
            }

            if(isset($_POST['item_geslacht']))
            {
                $item_geslacht = $_POST['item_geslacht'];
            }

			if($fouten == 0)
			{
				// TOEVOEGEN / UPDATEN
                $data = array(
                    'gebruiker_rechten' => $item_rechten,
                    'gebruiker_status' => 'actief',
                    'gebruiker_bedrijfsnaam' => $item_bedrijfsnaam,
                    'gebruiker_naam' => str_replace('  ', ' ', $item_voornaam.' '.$item_tussenvoegsel.' '.$item_achternaam),
                    'gebruiker_voornaam' => $item_voornaam,
                    'gebruiker_tussenvoegsel' => $item_tussenvoegsel,
                    'gebruiker_achternaam' => $item_achternaam,
                    'gebruiker_geslacht' => $item_geslacht,
                    'gebruiker_geboortedatum' => $item_geboortedatum_jaar.'-'.$item_geboortedatum_maand.'-'.$item_geboortedatum_dag,
                    'gebruiker_adres' => $item_adres,
                    'gebruiker_postcode' => $item_postcode_cijfers.' '.$item_postcode_letters,
                    'gebruiker_plaats' => $item_plaats,
                    'gebruiker_telefoonnummer' => $item_telefoonnummer,
                    'gebruiker_mobiel' => $item_mobiel,
                    'gebruiker_emailadres' => $item_emailadres,
                    'gebruiker_notities' => $item_notities,
                    'gebruiker_instelling_anoniem' => $item_instelling_anoniem,
                    'gebruiker_instelling_email_updates' => $item_instelling_email_updates
                );

                $item_wachtwoord = substr(str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);

                if($actie == 'toevoegen')
                {
                    $data['gebruiker_wachtwoord'] = sha1($item_wachtwoord);
                    $query = $this->gebruikers_model->insertDeelnemer($data);
                }
                else
                {
                    if (!empty($item_ID)) {
                        $docent = $this->docenten_model->getDocentByID($item_ID);
                        if ($docent) {
                            $gebruiker = $this->gebruikers_model->getGebruikerByID($docent->gebruiker_ID);

                            $query = $this->gebruikers_model->updateDeelnemer($docent->gebruiker_ID, $data);
                        } else {
                            echo 'Er ging iets mis. Probeer het nog eens.';
                        }
                    }
                }

                    if ($actie == 'toevoegen') {
                        // Wachtwoord e-mailen naar docent

                        $this->load->helper('mandrill');
                        // $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
                        $mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');

                        $email_titel = 'Aanmelding CMS localhost';
                        $email_tekst = '<p>Wij hebben je zojuist aangemeld voor het docenten CMS van localhost. Je kunt inloggen met je e-mailadres "' . $item_emailadres . '" en wachtwoord "' . $item_wachtwoord . '".</p><p>Controleer na het inloggen s.v.p. direct de gegevens die wij voor je hebben ingevuld op je profiel. Wijzig je gegevens of vul deze indien nodig aan.</p>';

                        $email_bericht = '
							<h1>' . $email_titel . '</h1>
							<p>Beste ' . $item_voornaam . ',</p>
							' . $email_tekst . '
							<p>Met vriendelijke groet,</p>
							<p>localhost</p>';

                        $email = array(
                            'html' => $email_bericht,
                            'subject' => $email_titel,
                            'from_email' => 'info@localhost',
                            'from_name' => 'localhost',
                            'to' => array(
                                array(
                                    'email' => $item_emailadres,
                                    'name' => str_replace('  ', '', $item_voornaam . ' ' . $item_tussenvoegsel . ' ' . $item_achternaam),
                                    'type' => 'to'
                                )
                            ),
                            'headers' => array('Reply-To' => $email_van_emailadres),
                            'track_opens' => true,
                            'track_clicks' => true,
                            'auto_text' => true
                        );

                        $mandrill->messages->send($email);
                    }

               if($query && $actie == 'toevoegen') {
                   $data = array(
                       'docent_naam' => $item_naam,
                       'docent_inleiding' => $item_inleiding,
                       'docent_tekst' => $item_tekst,
                       'media_ID' => str_replace(',', '', $item_media),
                       'gebruiker_ID' => $query,
                   );

                   if ($actie == 'toevoegen') $q = $this->docenten_model->insertDocent($data);
               }

                if($actie == 'wijzigen') {
                    $data = array(
                        'docent_naam' => $item_naam,
                        'docent_inleiding' => $item_inleiding,
                        'docent_tekst' => $item_tekst,
                        'media_ID' => str_replace(',', '', $item_media),
                        'docent_berichten_aan' => $item_instelling_berichten_aan
                    );
                    $q = $this->docenten_model->updateDocent($item_ID, $data);
                }

                if (!$q && !$query) {
                    echo 'Er ging iets mis. Probeer het nog eens.';
                } else {
                    if ($actie == 'toevoegen') redirect('cms/docenten');
                    else redirect('cms/docenten/');
                }
			} else {
                echo 'Er ging iets mis. Probeer het nog eens.';
            }
		}

		if($actie == 'wijzigen')
		{
			$item = $this->docenten_model->getDocentByID($item_ID);

            if ($item != null) {
                $gebruiker = $this->gebruikers_model->getGebruikerByID($item->gebruiker_ID);
            }

			$item_naam 			= $item->docent_naam;
			$item_inleiding 	= $item->docent_inleiding;
            $item_tekst		 	= $item->docent_tekst;
            $item_rechten       = $gebruiker->gebruiker_rechten;

            $item_bedrijfsnaam 				= $gebruiker->gebruiker_bedrijfsnaam;
            $item_voornaam 					= $gebruiker->gebruiker_voornaam;
            $item_tussenvoegsel 			= $gebruiker->gebruiker_tussenvoegsel;
            $item_achternaam 				= $gebruiker->gebruiker_achternaam;
            $item_geslacht 					= $gebruiker->gebruiker_geslacht;
            $item_geboortedatum 			= explode('-', $gebruiker->gebruiker_geboortedatum);
            $item_geboortedatum_dag 		= $item_geboortedatum[2];
            $item_geboortedatum_maand 		= $item_geboortedatum[1];
            $item_geboortedatum_jaar 		= $item_geboortedatum[0];
            $item_adres 					= $gebruiker->gebruiker_adres;
            $item_postcode 					= explode(' ', $gebruiker->gebruiker_postcode);
            $item_postcode_cijfers 			= $item_postcode[0];
            $item_postcode_letters 			= $item_postcode[1];
            $item_plaats 					= $gebruiker->gebruiker_plaats;
            $item_telefoonnummer 			= $gebruiker->gebruiker_telefoonnummer;
            $item_mobiel 					= $gebruiker->gebruiker_mobiel;
            $item_emailadres 				= $gebruiker->gebruiker_emailadres;
            $item_notities 					= $gebruiker->gebruiker_notities;
            $item_instelling_anoniem 		= $gebruiker->gebruiker_instelling_anoniem;
            $item_instelling_email_updates 	= $gebruiker->gebruiker_instelling_email_updates;
            $item_instelling_berichten_aan  = $item->docent_berichten_aan;

			// MEDIA OPHALEN

			$media = $this->media_model->getMediaByMediaID($item->media_ID);
		}


		// PAGINA TONEN

		$this->data['actie'] = $actie;

		$this->data['item_ID'] 				= $item_ID;
		$this->data['item_naam'] 			= $item_naam;
		$this->data['item_inleiding'] 		= $item_inleiding;
		$this->data['item_tekst'] 			= $item_tekst;
        $this->data['media'] 				= $media;
        $this->data['item_rechten']         = $item_rechten;
        $this->data['item_geslacht_feedback'] 		= $item_geslacht_feedback;
		$this->data['item_naam_feedback'] 			= $item_naam_feedback;
		$this->data['item_inleiding_feedback'] 		= $item_inleiding_feedback;
		$this->data['item_tekst_feedback']	 		= $item_tekst_feedback;

        $this->data['item_bedrijfsnaam'] 				= $item_bedrijfsnaam;
        $this->data['item_voornaam'] 					= $item_voornaam;
        $this->data['item_tussenvoegsel'] 				= $item_tussenvoegsel;
        $this->data['item_achternaam'] 					= $item_achternaam;
        $this->data['item_geslacht'] 					= $item_geslacht;
        $this->data['item_geboortedatum_dag'] 			= $item_geboortedatum_dag;
        $this->data['item_geboortedatum_maand'] 		= $item_geboortedatum_maand;
        $this->data['item_geboortedatum_jaar'] 			= $item_geboortedatum_jaar;
        $this->data['item_adres'] 						= $item_adres;
        $this->data['item_postcode_cijfers'] 			= $item_postcode_cijfers;
        $this->data['item_postcode_letters'] 			= $item_postcode_letters;
        $this->data['item_plaats'] 						= $item_plaats;
        $this->data['item_telefoonnummer'] 				= $item_telefoonnummer;
        $this->data['item_mobiel'] 						= $item_mobiel;
        $this->data['item_emailadres'] 					= $item_emailadres;
        $this->data['item_notities'] 					= $item_notities;
        $this->data['item_instelling_anoniem'] 			= $item_instelling_anoniem;
        $this->data['item_instelling_email_updates'] 	= $item_instelling_email_updates;
        $this->data['item_instelling_berichten_aan']    = $item_instelling_berichten_aan;

        $this->data['item_bedrijfsnaam_feedback'] 				= $item_bedrijfsnaam_feedback;
        $this->data['item_voornaam_feedback'] 					= $item_voornaam_feedback;
        $this->data['item_tussenvoegsel_feedback'] 				= $item_tussenvoegsel_feedback;
        $this->data['item_achternaam_feedback'] 				= $item_achternaam_feedback;
        $this->data['item_geslacht_feedback'] 					= $item_geslacht_feedback;
        $this->data['item_geboortedatum_feedback'] 				= $item_geboortedatum_feedback;
        $this->data['item_adres_feedback'] 						= $item_adres_feedback;
        $this->data['item_postcode_feedback'] 					= $item_postcode_feedback;
        $this->data['item_plaats_feedback'] 					= $item_plaats_feedback;
        $this->data['item_telefoonnummer_feedback'] 			= $item_telefoonnummer_feedback;
        $this->data['item_mobiel_feedback'] 					= $item_mobiel_feedback;
        $this->data['item_emailadres_feedback'] 				= $item_emailadres_feedback;
        $this->data['item_notities_feedback'] 					= $item_notities_feedback;
        $this->data['item_instelling_anoniem_feedback'] 		= $item_instelling_anoniem_feedback;
        $this->data['item_instelling_email_updates_feedback'] 	= $item_instelling_email_updates_feedback;
        $this->data['item_instelling_berichten_aan_feedback']   = $item_instelling_berichten_aan_feedback;



		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/docenten_wijzigen';
		$this->load->view('cms/template', $pagina);
	}



	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */

	public function verwijderen($item_ID = null, $bevestiging = null)
	{
		if($item_ID == null) redirect('cms/docenten');

		$this->load->model('docenten_model');
		$this->load->model('gebruikers_model');
		$item = $this->docenten_model->getDocentByID($item_ID);
		if($item == null) redirect('cms/docenten');
		$this->data['item'] = $item;


		// ITEM VERWIJDEREN

		if($bevestiging == 'ja')
		{
            $data = array(
                'gebruiker_status' => 'inactief'
            );

            $q = $this->gebruikers_model->updateDeelnemer($item->gebruiker_ID, $data);
			if($q) redirect('cms/docenten');
			else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
		}

		// PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/docenten_verwijderen';
		$this->load->view('cms/template', $pagina);
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