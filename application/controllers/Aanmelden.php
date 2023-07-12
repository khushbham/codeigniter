<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aanmelden extends CI_Controller
{
	private $data = array();

	// DATA

	private $workshop;
	private $kennismakingsworkshop;
	private $groepen;
	private $producten;
	private $systeem_kortingen = array(); // kortingen uitgereikt door het systeem, bijv. aanmelden binnen een X aantal dagen na een kennismakingsworkshop
    private $code;
	private $groep_ID;
	private $annuleringsverzekering = null;
	private $aanmelden_annuleringsverzekering = null;
	private $annulering_prijs = null;

	// AANMELDPROCES

	private $aanmelden_voor					= '';
	private $aanmelden_url					= '';
	private $aanmelden_formulier			= 'lang';


	// VELDEN

	private $aanmelden_ID					= ''; // Aanmelding ID in de database
	private $aanmelden_startdatum			= ''; // groepen.groep_ID voor de workshops van type 'groep' en 'online'
	private $aanmelden_bedrijfsnaam 		= '';
	private $aanmelden_voornaam 			= '';
	private $aanmelden_tussenvoegsel 		= '';
	private $aanmelden_achternaam 			= '';
	private $aanmelden_geslacht 			= '';
	private $aanmelden_geboortedatum_dag 	= '';
	private $aanmelden_geboortedatum_maand 	= '';
	private $aanmelden_geboortedatum_jaar 	= '';
	private $aanmelden_adres 				= '';
	private $aanmelden_postcode 			= '';
	private $aanmelden_plaats 				= '';
	private $aanmelden_telefoon 			= '';
	private $aanmelden_mobiel 				= '';
	private $aanmelden_emailadres 			= '';
	private $aanmelden_code_oud				= ''; // Oude aanmelding code (vorige aanmelding)
	private $aanmelden_code_nieuw			= ''; // Nieuwe aanmelding code
	private $aanmelden_wachtwoord			= '';
	private $aanmelden_kortingscode 		= '';
	private $aanmelden_kennismakingsworkshop_korting 		= '';
	private $aanmelden_stemtest_korting		= '';
	private $aanmelden_akkoord 				= false;

	private $aanmelden_producten			= '';
	private $aanmelden_afleveren_adres 		= '';
	private $aanmelden_afleveren_postcode 	= '';
	private $aanmelden_afleveren_plaats 	= '';


	// FEEDBACK

	private $aanmelden_startdatum_feedback				= '';
	private $aanmelden_bedrijfsnaam_feedback 			= '';
	private $aanmelden_voornaam_feedback 				= '';
	private $aanmelden_tussenvoegsel_feedback 			= '';
	private $aanmelden_achternaam_feedback 				= '';
	private $aanmelden_geslacht_feedback 				= '';
	private $aanmelden_geboortedatum_feedback 			= '';
	private $aanmelden_adres_feedback 					= '';
	private $aanmelden_postcode_feedback 				= '';
	private $aanmelden_plaats_feedback 					= '';
	private $aanmelden_telefoon_feedback 				= '';
	private $aanmelden_mobiel_feedback 					= '';
	private $aanmelden_emailadres_feedback 				= '';
	private $aanmelden_code_feedback					= '';
	private $aanmelden_wachtwoord_feedback 				= '';
	private $aanmelden_kortingscode_feedback 			= '';
	private $aanmelden_akkoord_feedback 				= '';

	private $aanmelden_afleveren_adres_feedback 		= '';
	private $aanmelden_afleveren_postcode_feedback 		= '';
	private $aanmelden_afleveren_plaats_feedback 		= '';



	public function __construct()
	{
		parent::__construct();

		// Inloggen

		$this->load->library('algemeen');
		$this->algemeen->inloggen();
		$this->data['gegevens'] = $this->algemeen->gegevens();

		require_once(APPPATH.'libraries/vendor/autoload.php');

        // Paynl\Config::setApiToken('d9b947ac5e523c975f3e727988467fd01080d954');
     	// Paynl\Config::setServiceId('SL-2635-1031');

        Paynl\Config::setApiToken('2719c9cefc49c2699e193afa61db758aca8c504d');
		Paynl\Config::setServiceId('SL-2836-8790');

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



	///////////////
	// AANMELDEN //
	///////////////


	public function index($aanmelden_voor = null, $url = null, $code = null)
	{
		// MODELS LADEN
		$this->load->model('gebruikers_model');
		$this->load->model('kennismakingsworkshop_model');
		$this->load->model('groepen_model');
		$this->load->model('uitnodigingen_model');

		// CONTROLEREN OF ER EEN GELDIG AANMELDINGSTYPE GEKOZEN IS

		if ($aanmelden_voor != 'kennismakingsworkshop')
		{
			if(!in_array($aanmelden_voor, array('intake', 'stemtest', 'workshop')) || $url == null)
			{
				redirect('workshops');
			}
		}
		else
		{
			if($url == null) redirect('');
		}

		// GEKOZEN WORKSHOP OF KENNISMAKINGSWORKSHOP OPSLAAN EN OPHALEN

        $this->code = $code;
		$this->aanmelden_voor = $aanmelden_voor;
		$this->aanmelden_url = $url; // URL waarvoor wordt aangemeld (kennismakingsworkshop = de datum, workshop = de titel v.d. workshop)
		$this->session->set_userdata(array('aanmelden_voor' => $this->aanmelden_voor, 'aanmelden_url' => $this->aanmelden_url));

		if ($aanmelden_voor == 'kennismakingsworkshop')
		{
			$this->_gegevens_kennismakingsworkshop_ophalen();

			// Controleren of de workshop nog niet vol is, indien deze wel vol is dan terugsturen naar de workshopspagina
			if($this->kennismakingsworkshop_model->getKennismakingsworkshopDeelnemersAantal($this->kennismakingsworkshop->kennismakingsworkshop_ID) >= $this->kennismakingsworkshop->kennismakingsworkshop_capaciteit)
			{
				redirect('');
			}
		}
		else
		{
			$this->_gegevens_workshop_ophalen();

            $link = '';

            if(isset($code)) {
                $link = $this->uitnodigingen_model->getLinkByCode($code);
                if(!empty($link) && $link->uitnodiging_limiet > 0 || $link->uitnodiging_limiet == null) {
                    $this->session->set_userdata('link', $link);
                } else {
					redirect('workshops');
				}

				$this->load->model('lessen_model');

				$groep_lessen = $this->lessen_model->getLessenByGroepID($link->groep_ID);

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
				$this->data['groep_lessen'] = $groep_lessen;
            }

			// Controleren of de workshop nog niet vol is, indien deze wel vol is dan terugsturen naar de workshopspagina
			if(($this->aanmelden_voor == 'workshop' || $this->aanmelden_voor == 'kennismakingsworkshop') &&  in_array($this->workshop->workshop_type, array('groep', 'online')))
			{
				$link = $this->session->userdata('link');

                if(empty($link)) {
                    $groepen = $this->groepen_model->getGroepenAanmeldenByWorkshopID($this->workshop->workshop_ID);

                    // Geen groepen vrij? dan terugsturen naar workshops pagina
                    if (sizeof($groepen) == 0) {
                        redirect('workshops');
                    }
                }
			}
		}

		// CONTROLEREN WELK FORMULIER MOET WORDEN GETOOND EN GEVALIDEERD

		if(isset($_POST['aanmelden_formulier']))
		{
			$this->aanmelden_formulier = $this->input->post('aanmelden_formulier');
			if($this->aanmelden_formulier == 'kort') $this->_aanmelden_formulier_kort();
			else $this->_aanmelden_formulier_lang();
		}
		else
		{
			// Direct het korte formulier tonen bij aanmelden voor een workshop met een stemtest
			// if($this->aanmelden_voor == 'workshop' && $this->workshop->workshop_stemtest) $this->aanmelden_formulier = 'kort';

			// Stap terug gedaan? Ingevulde gegevens ophalen uit sessie
			if($this->session->userdata('aanmelden_voornaam')) $this->_gegevens_formulier_ophalen();
		}



		// PAGINA TONEN

		$this->data['aanmelden_voor']						= $this->aanmelden_voor;
		$this->data['aanmelden_url']						= $this->aanmelden_url;
		$this->data['aanmelden_formulier']					= $this->aanmelden_formulier;
		$this->data['code']					                = $this->code;

		$this->data['aanmelden_ID']			 				= $this->aanmelden_ID;
		$this->data['aanmelden_startdatum'] 				= $this->aanmelden_startdatum;
		$this->data['aanmelden_bedrijfsnaam'] 				= $this->aanmelden_bedrijfsnaam;
		$this->data['aanmelden_voornaam'] 					= $this->aanmelden_voornaam;
		$this->data['aanmelden_tussenvoegsel'] 				= $this->aanmelden_tussenvoegsel;
		$this->data['aanmelden_achternaam'] 				= $this->aanmelden_achternaam;
		$this->data['aanmelden_geslacht'] 					= $this->aanmelden_geslacht;
		$this->data['aanmelden_geboortedatum_dag'] 			= $this->aanmelden_geboortedatum_dag;
		$this->data['aanmelden_geboortedatum_maand'] 		= $this->aanmelden_geboortedatum_maand;
		$this->data['aanmelden_geboortedatum_jaar'] 		= $this->aanmelden_geboortedatum_jaar;
		$this->data['aanmelden_adres'] 						= $this->aanmelden_adres;
		$this->data['aanmelden_postcode'] 					= $this->aanmelden_postcode;
		$this->data['aanmelden_plaats'] 					= $this->aanmelden_plaats;
		$this->data['aanmelden_telefoon'] 					= $this->aanmelden_telefoon;
		$this->data['aanmelden_mobiel'] 					= $this->aanmelden_mobiel;
		$this->data['aanmelden_emailadres'] 				= $this->aanmelden_emailadres;
		$this->data['aanmelden_code_oud']					= $this->aanmelden_code_oud;
		$this->data['aanmelden_code_nieuw']					= $this->aanmelden_code_nieuw;
		$this->data['aanmelden_wachtwoord']					= $this->aanmelden_wachtwoord;
		$this->data['aanmelden_kortingscode'] 				= $this->aanmelden_kortingscode;
		$this->data['aanmelden_akkoord'] 					= $this->aanmelden_akkoord;

		$this->data['aanmelden_startdatum_feedback'] 		= $this->aanmelden_startdatum_feedback;
		$this->data['aanmelden_bedrijfsnaam_feedback'] 		= $this->aanmelden_bedrijfsnaam_feedback;
		$this->data['aanmelden_voornaam_feedback'] 			= $this->aanmelden_voornaam_feedback;
		$this->data['aanmelden_tussenvoegsel_feedback'] 	= $this->aanmelden_tussenvoegsel_feedback;
		$this->data['aanmelden_achternaam_feedback'] 		= $this->aanmelden_achternaam_feedback;
		$this->data['aanmelden_geslacht_feedback'] 			= $this->aanmelden_geslacht_feedback;
		$this->data['aanmelden_geboortedatum_feedback'] 	= $this->aanmelden_geboortedatum_feedback;
		$this->data['aanmelden_adres_feedback'] 			= $this->aanmelden_adres_feedback;
		$this->data['aanmelden_postcode_feedback'] 			= $this->aanmelden_postcode_feedback;
		$this->data['aanmelden_plaats_feedback'] 			= $this->aanmelden_plaats_feedback;
		$this->data['aanmelden_telefoon_feedback'] 			= $this->aanmelden_telefoon_feedback;
		$this->data['aanmelden_mobiel_feedback'] 			= $this->aanmelden_mobiel_feedback;
		$this->data['aanmelden_emailadres_feedback'] 		= $this->aanmelden_emailadres_feedback;
		$this->data['aanmelden_code_feedback'] 				= $this->aanmelden_code_feedback;
		$this->data['aanmelden_wachtwoord_feedback'] 		= $this->aanmelden_wachtwoord_feedback;
		$this->data['aanmelden_kortingscode_feedback'] 		= $this->aanmelden_kortingscode_feedback;
		$this->data['aanmelden_akkoord_feedback']	 		= $this->aanmelden_akkoord_feedback;

		// META TITEL BEPALEN AFHANKELIJK VAN WAARVOOR ER AANGEMELD WORDT

		if ($aanmelden_voor == 'kennismakingsworkshop')
		{
			$this->data['meta_title'] = 'Aanmelden '.$this->kennismakingsworkshop->kennismakingsworkshop_titel.' - localhost';
		}
		else
		{
			$this->data['meta_title'] = 'Aanmelden '.$this->workshop->workshop_titel.' - localhost';
		}

		$this->data['meta_description'] = '';

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'aanmelden';
		$this->load->view('template', $pagina);
	}



	////////////////////
	// LANG FORMULIER //
	////////////////////

	private function _aanmelden_formulier_lang()
	{
		// GEGEVENS OPHALEN

		$this->aanmelden_bedrijfsnaam 			= trim($this->input->post('aanmelden_bedrijfsnaam'));
		$this->aanmelden_voornaam 				= ucfirst(trim($this->input->post('aanmelden_voornaam')));
		$this->aanmelden_tussenvoegsel 			= trim($this->input->post('aanmelden_tussenvoegsel'));
		$this->aanmelden_achternaam 			= ucfirst(trim($this->input->post('aanmelden_achternaam')));
		$this->aanmelden_geboortedatum_dag 		= trim($this->input->post('aanmelden_geboortedatum_dag'));
		$this->aanmelden_geboortedatum_maand 	= trim($this->input->post('aanmelden_geboortedatum_maand'));
		$this->aanmelden_geboortedatum_jaar 	= trim($this->input->post('aanmelden_geboortedatum_jaar'));
		$this->aanmelden_adres 					= trim($this->input->post('aanmelden_adres'));
		$this->aanmelden_postcode 				= trim($this->input->post('aanmelden_postcode'));
		$this->aanmelden_plaats 				= trim($this->input->post('aanmelden_plaats'));
		$this->aanmelden_telefoon 				= trim($this->input->post('aanmelden_telefoon'));
		$this->aanmelden_mobiel 				= trim($this->input->post('aanmelden_mobiel'));
		$this->aanmelden_emailadres 			= trim($this->input->post('aanmelden_emailadres'));

        $this->session->unset_userdata('kortingscode_connecties');
		$this->session->unset_userdata('kortingscode');

		// GEGEVENS VALIDEREN

		$fouten = 0;


		// Startdatum

		if($this->aanmelden_voor == 'workshop' && in_array($this->workshop->workshop_type, array('groep', 'online')))
		{
		    $link = '';
			$link = $this->session->userdata('link');

            if(empty($link)) {
                if (!isset($_POST['aanmelden_startdatum'])) {
                    $this->aanmelden_startdatum_feedback = 'Graag een startdatum selecteren';
                    $fouten++;
                } else {
                    $this->aanmelden_startdatum = $this->input->post('aanmelden_startdatum');
                }
            } else {
                $this->aanmelden_startdatum = $link->groep_ID;
            }
		}

		// Voornaam

		if(empty($this->aanmelden_voornaam))
		{
			$this->aanmelden_voornaam_feedback = 'Graag je voornaam invullen';
			$fouten++;
		}

		// Achternaam

		if(empty($this->aanmelden_achternaam))
		{
			$this->aanmelden_achternaam_feedback = 'Graag je achternaam invullen';
			$fouten++;
		}

		// Geslacht

		if(!isset($_POST['aanmelden_geslacht']))
		{
			$this->aanmelden_geslacht_feedback = 'Graag je geslacht selecteren';
			$fouten++;
		}
		else
		{
			$this->aanmelden_geslacht = $this->input->post('aanmelden_geslacht');
		}

		// Geboortedatum

		if(empty($this->aanmelden_geboortedatum_dag))
		{
			$this->aanmelden_geboortedatum_feedback = 'Graag de dag invullen';
			$fouten++;
		}
		else
		{
			if(!is_numeric($this->aanmelden_geboortedatum_dag) || $this->aanmelden_geboortedatum_dag > 31)
			{
				$this->aanmelden_geboortedatum_feedback = 'Graag een geldige dag invullen';
				$fouten++;
			}
			else
			{
				if(empty($this->aanmelden_geboortedatum_maand))
				{
					$this->aanmelden_geboortedatum_feedback = 'Graag de maand invullen';
					$fouten++;
				}
				else
				{
					if(!is_numeric($this->aanmelden_geboortedatum_maand) || $this->aanmelden_geboortedatum_maand > 12)
					{
						$this->aanmelden_geboortedatum_feedback = 'Graag een geldige maand invullen';
						$fouten++;
					}
					else
					{
						if(empty($this->aanmelden_geboortedatum_jaar))
						{
							$this->aanmelden_geboortedatum_feedback = 'Graag het jaartal invullen';
							$fouten++;
						}
						else
						{
							if(!is_numeric($this->aanmelden_geboortedatum_jaar))
							{
								$this->aanmelden_geboortedatum_feedback = 'Graag een geldig jaar invullen';
								$fouten++;
							}
							else
							{
								if($this->aanmelden_geboortedatum_jaar < (date('Y') - 120) || $this->aanmelden_geboortedatum_jaar > date('Y'))
								{
									$this->aanmelden_geboortedatum_feedback = 'Graag een realistisch geboortejaar invullen';
									$fouten++;
								}
							}
						}
					}
				}
			}
		}

		// Adres

		if(empty($this->aanmelden_adres))
		{
			$this->aanmelden_adres_feedback = 'Graag je adres invullen';
			$fouten++;
		}

		// Postcode

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];}
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $url = "http://api.wipmania.com/".$ip;
        $country = file_get_contents($url);

		if(empty($this->aanmelden_postcode) && $country == "NL")
		{
			$this->aanmelden_postcode_feedback = 'Graag je postcode invullen';
			$fouten++;
		}
		else
		{
			$postcode = str_replace(' ', '', $this->aanmelden_postcode);
			$postcode_cijfers = substr($postcode, 0, 4);
			$postcode_letters = strtoupper(substr($postcode, -2));

			if(strlen($postcode) == 6 && $country == "NL")
			{
				if(!is_numeric($postcode_cijfers))
				{
					$this->aanmelden_postcode_feedback = 'Graag een geldige postcode invullen';
					$fouten++;
				}
				elseif(!preg_match("/^[A-Z]+$/", $postcode_letters))
				{
					$this->aanmelden_postcode_feedback = 'Graag een geldige postcode invullen';
					$fouten++;
				}
			}
			elseif($country != "NL") {
                $postcode = "";
            }
			else
			{
				$this->aanmelden_postcode_feedback = 'Graag een geldige postcode invullen';
				$fouten++;
			}
		}

		// Plaats

		if(empty($this->aanmelden_plaats))
		{
			$this->aanmelden_plaats_feedback = 'Graag je plaats invullen';
			$fouten++;
		}

		// Telefoonnummer

		if(empty($this->aanmelden_telefoon))
		{
			$this->aanmelden_telefoon_feedback = 'Graag je telefoonnummer invullen';
			$fouten++;
		}
		else
		{
			$telefoonnummer = preg_replace("/[^0-9]/", '', $this->aanmelden_telefoon);

			if(strlen($telefoonnummer) < 10 || strlen($telefoonnummer) > 11)
			{
				$this->aanmelden_telefoon_feedback = 'Graag een geldig nummer invullen';
				$fouten++;
			}
		}

		// Mobiel

		if(!empty($this->aanmelden_mobiel))
		{
			$telefoonnummer = preg_replace("/[^0-9]/", '', $this->aanmelden_mobiel);

			if(strlen($telefoonnummer) < 10 || strlen($telefoonnummer) > 11)
			{
				$this->aanmelden_mobiel_feedback = 'Graag een geldig nummer invullen';
				$fouten++;
			}
		}

		// E-mailadres

		if(empty($this->aanmelden_emailadres))
		{
			$this->aanmelden_emailadres_feedback = 'Graag je e-mailadres invullen';
			$fouten++;
		}
		else
		{
			if(!filter_var($this->aanmelden_emailadres, FILTER_VALIDATE_EMAIL))
			{
				$this->aanmelden_emailadres_feedback = 'Graag een geldig e-mailadres invullen';
				$fouten++;
			}
			else
			{
                if($_POST['aanmelden_formulier'] == 'lang')
                {
					$this->load->model('gebruikers_model');
					$gebruiker = $this->gebruikers_model->checkEmailadres($this->aanmelden_emailadres);

                    if($this->session->userdata('gebruiker_rechten') == 'test') {
                        $gebruiker = null;
                    }

					if($gebruiker != null)
					{
                        $email_in_gebruik = $this->gebruikers_model->checkEmailadresInGebruik($gebruiker->gebruiker_ID);

                        if($email_in_gebruik) {
                            $this->aanmelden_emailadres_feedback = 'Dit e-mailadres is al in gebruik';
                            $fouten++;
                        }
					}
				}
			}
		}

		// Kortingscode

        if(isset($_POST['aanmelden_kortingscode']))
        {
            $this->aanmelden_kortingscode = $_POST['aanmelden_kortingscode'];

            if(!empty($this->aanmelden_kortingscode))
            {
                $this->load->model('kortingscodes_model');

                $kortingscode = $this->kortingscodes_model->getKortingscodesByCode($this->aanmelden_kortingscode);

                if (!empty($kortingscode)) {
                    if (!empty($kortingscode->kortingscode_percentage) || !empty($kortingscode->kortingscode_vast_bedrag)) {
                        $connecties = $this->kortingscodes_model->getKortingConnectiesByID($kortingscode->kortingscode_ID);
                    }
                }

                $now = date('Y-m-d');

                if(empty($connecties) || $kortingscode->kortingscode_limiet === '0' || (empty($kortingscode->kortingscode_percentage) && empty($kortingscode->kortingscode_vast_bedrag)) || $now < $kortingscode->kortingscode_startdatum || ($now > $kortingscode->kortingscode_einddatum && $kortingscode->kortingscode_einddatum != '0000-00-00'))
                    {
                    $this->aanmelden_kortingscode_feedback = 'Graag een geldige code invullen';
                    $fouten++;
                } else {
                    if (!empty($kortingscode->kortingscode_limiet)) {
                        $limiet = $kortingscode->kortingscode_limiet - 1;

                        $data = array(
                            'kortingscode_limiet' => $limiet,
                        );

                        $this->kortingscodes_model->updateKortingscodesLimiet($kortingscode->kortingscode_ID, $data);
                    }

                    $this->session->set_userdata('kortingscode_connecties', $connecties);
                    $this->session->set_userdata('kortingscode', $kortingscode);

                }
            }
        }

		// Akkoord

		if(!isset($_POST['aanmelden_akkoord']))
		{
			$this->aanmelden_akkoord_feedback = 'Een akkoord is verplicht';
			$fouten++;
		}
		else
		{
			$this->aanmelden_akkoord = true;
		}


		// FORMULIER GOED INGEVULD?

		if($fouten == 0)
		{
			// GEGEVENS OPSLAAN IN SESSIE

			$this->_gegevens_formulier_opslaan();

			if($this->aanmelden_voor == 'workshop') {
				$this->load->model('annuleringen_model');
				$this->annuleringsverzekering = $this->annuleringen_model->getAnnuleringByWorkshopIDActief($this->workshop->workshop_ID);
			}

			// VOLGENDE STAP

			if($this->aanmelden_voor == 'workshop' && $this->annuleringsverzekering != null)
			{
				redirect('aanmelden/annuleringsverzekering');
			}
			elseif($this->aanmelden_voor == 'workshop' && sizeof($this->producten) > 0)
			{
				redirect('aanmelden/producten');
			}
			else
			{
				redirect('aanmelden/bevestigen');
			}
		} else if(!empty($link)) {
		    $this->session->unset_userdata('link');
            redirect(base_url('aanmelden/'.$this->aanmelden_voor.'/'.$this->aanmelden_url.'/'.$link->link_code));
        }
	}



	////////////////////
	// KORT FORMULIER //
	////////////////////

	private function _aanmelden_formulier_kort()
	{
		// GEGEVENS OPHALEN

		$this->aanmelden_emailadres 			= trim($this->input->post('aanmelden_kort_emailadres'));
		$this->aanmelden_code_oud	 			= trim($this->input->post('aanmelden_kort_code'));
		$this->aanmelden_kortingscode			= trim($this->input->post('aanmelden_kort_kortingscode'));
		$this->aanmelden_wachtwoord		 		= trim($this->input->post('aanmelden_kort_wachtwoord'));


		// GEGEVENS VALIDEREN

		$fouten = 0;

		// Startdatum

		if($this->aanmelden_voor == 'workshop' && in_array($this->workshop->workshop_type, array('groep', 'online')))
		{
		    $link = '';
		    $link = $this->session->userdata('link');

		    if(empty($link)) {
                if (!isset($_POST['aanmelden_kort_startdatum'])) {
                    $this->aanmelden_startdatum_feedback = 'Graag een startdatum selecteren';
                    $fouten++;
                } else {
                    $this->aanmelden_startdatum = $this->input->post('aanmelden_kort_startdatum');
                }
            } else {
		        $this->aanmelden_startdatum = $link->groep_ID;
            }
		}

		// E-mailadres

		if(empty($this->aanmelden_emailadres))
		{
			$this->aanmelden_emailadres_feedback = 'Graag je e-mailadres invullen';
			$fouten++;
		}
		else
		{
			if(!filter_var($this->aanmelden_emailadres, FILTER_VALIDATE_EMAIL))
			{
				$this->aanmelden_emailadres_feedback = 'Graag een geldig e-mailadres invullen';
				$fouten++;
			}
		}

		// Code / inloggen

		if(isset($_POST['aanmelden_kort_code']))
		{
			// Code

			// if(empty($this->aanmelden_code_oud))
			// {
			// 	$this->aanmelden_code_feedback = 'Graag invullen';
			// 	$fouten++;
			// }
		}
		else
		{
			// Wachtwoord

			if(empty($this->aanmelden_wachtwoord))
			{
				$this->aanmelden_wachtwoord_feedback = 'Graag invullen';
				$fouten++;
			}
		}

		// Kortingscode

        if(isset($_POST['aanmelden_kort_kortingscode']))
        {

            $this->aanmelden_kortingscode = trim($this->input->post('aanmelden_kort_kortingscode'));

            if(!empty($this->aanmelden_kortingscode))
            {
                $this->load->model('kortingscodes_model');
								$kortingscode = $this->kortingscodes_model->getKortingscodesByCode($this->aanmelden_kortingscode);

                if (!empty($kortingscode)) {
                    if (!empty($kortingscode->kortingscode_percentage) || !empty($kortingscode->kortingscode_vast_bedrag)) {
                        $connecties = $this->kortingscodes_model->getKortingConnectiesByID($kortingscode->kortingscode_ID);
                    }
                }

				$now = date('Y-m-d');
								if(empty($connecties) || $kortingscode->kortingscode_limiet === '0' || (empty($kortingscode->kortingscode_percentage) && empty($kortingscode->kortingscode_vast_bedrag)) || $now < $kortingscode->kortingscode_startdatum || ($now > $kortingscode->kortingscode_einddatum && $kortingscode->kortingscode_einddatum != '0000-00-00'))
                {
                    $this->aanmelden_kortingscode_feedback = 'Graag een geldige code invullen';
                    $fouten++;
                } else {
                    if (!empty($kortingscode->kortingscode_limiet)) {
                        $limiet = $kortingscode->kortingscode_limiet - 1;

                        $data = array(
                            'kortingscode_limiet' => $limiet,
                        );

                        $this->kortingscodes_model->updateKortingscodesLimiet($kortingscode->kortingscode_ID, $data);
                    }

                    $this->session->set_userdata('kortingscode_connecties', $connecties);
                    $this->session->set_userdata('kortingscode', $kortingscode);
                }
            }
        }

		// Akkoord

		if(!isset($_POST['aanmelden_akkoord']))
		{
			$this->aanmelden_akkoord_feedback = 'Een akkoord is verplicht';
			$fouten++;
		}
		else
		{
			$this->aanmelden_akkoord = true;
		}


		// FORMULIER GOED INGEVULD?

		if($fouten == 0)
		{
			// E-mailadres controleren in database en gegevens gebruiker ophalen

			$this->load->model('gebruikers_model');
			$gebruiker = $this->gebruikers_model->getGebruikerByEmailadres($this->aanmelden_emailadres);


			if($this->aanmelden_voor == 'workshop') {
				$this->load->model('annuleringen_model');
				$this->annuleringsverzekering = $this->annuleringen_model->getAnnuleringByWorkshopID($this->workshop->workshop_ID);
			}

			if($gebruiker != null)
			{
				if(isset($_POST['aanmelden_kort_code']))
				{
					if(!empty($this->aanmelden_code_oud)) {
					if($this->aanmelden_code_oud == $this->workshop->workshop_stemtest_code)
					{
						// Controleren of de cursist de standaard stemtest code heeft ingevuld

						$this->_gegevens_gebruiker_opslaan($gebruiker);
					}
					else
					{
						// Gegevens aanmelding ophalen

						$this->load->model('aanmeldingen_model');

						$aanmelding = $this->aanmeldingen_model->getAanmeldingByGebruikerIDAndWorkshopIDAndAanmeldingCode($gebruiker->gebruiker_ID, $this->workshop->workshop_ID, $this->aanmelden_code_oud);

						if($this->session->userdata('gebruiker_rechten') != 'test') {
							if($aanmelding == null && ($this->workshop->workshop_ID == 59 || $this->workshop->workshop_ID == 61 || $this->workshop->workshop_ID == 57 || $this->workshop->workshop_ID == 63 || $this->workshop->workshop_ID == 69 || $this->workshop->workshop_ID == 67)) {
								$aanmelding = $this->aanmeldingen_model->getAanmeldingByGebruikersIDAndAanmeldingsCode($gebruiker->gebruiker_ID, $this->aanmelden_code_oud);
							}
						}

						if($aanmelding != null)
						{
							if($aanmelding->aanmelding_voldoende == 'ja')
							{
								$this->_gegevens_gebruiker_opslaan($gebruiker);
							}
							elseif($aanmelding->aanmelding_voldoende == 'nee')
							{
								$this->aanmelden_code_feedback = 'Intake/stemtest onvoldoende';
							}
							else
							{
								$this->aanmelden_code_feedback = 'Intake/stemtest nog niet beoordeeld';
							}
						}
						else
						{
                            $this->aanmelden_code_feedback = 'Graag een geldige code';
						}
					}
					} else {
						$this->_gegevens_gebruiker_opslaan($gebruiker);
					}
				}
				else
				{
					// Gebruiker controleren

					$gebruiker = $this->gebruikers_model->checkGebruiker($this->aanmelden_emailadres, sha1($this->aanmelden_wachtwoord));

					if($gebruiker != null)
					{
						$this->_gegevens_gebruiker_opslaan($gebruiker);
					}
					else
					{
						$this->aanmelden_wachtwoord_feedback = 'Verkeerd wachtwoord';
					}
				}
			}
			else
			{
				$this->aanmelden_emailadres_feedback = 'E-mailadres onbekend';
			}
		} else if(!empty($link)) {
            $this->session->unset_userdata('link');
            redirect(base_url('aanmelden/'.$this->aanmelden_voor.'/'.$this->aanmelden_url.'/'.$link->link_code));
        }
	}

	private function _gegevens_gebruiker_opslaan($gebruiker)
	{
		// GEGEVENS GEBRUIKER OPHALEN EN OPSLAAN

		$aanmelden_geboortedatum = explode('-', $gebruiker->gebruiker_geboortedatum);

		$this->aanmelden_bedrijfsnaam = $gebruiker->gebruiker_bedrijfsnaam;
		$this->aanmelden_voornaam = $gebruiker->gebruiker_voornaam;
		$this->aanmelden_tussenvoegsel = $gebruiker->gebruiker_tussenvoegsel;
		$this->aanmelden_achternaam = $gebruiker->gebruiker_achternaam;
		$this->aanmelden_geslacht = $gebruiker->gebruiker_geslacht;
		$this->aanmelden_geboortedatum_dag = $aanmelden_geboortedatum[2];
		$this->aanmelden_geboortedatum_maand = $aanmelden_geboortedatum[1];
		$this->aanmelden_geboortedatum_jaar = $aanmelden_geboortedatum[0];
		$this->aanmelden_adres = $gebruiker->gebruiker_adres;
		$this->aanmelden_postcode = $gebruiker->gebruiker_postcode;
		$this->aanmelden_plaats = $gebruiker->gebruiker_plaats;
		$this->aanmelden_telefoon = $gebruiker->gebruiker_telefoonnummer;
		$this->aanmelden_mobiel = $gebruiker->gebruiker_mobiel;
		$this->aanmelden_emailadres = $gebruiker->gebruiker_emailadres;

		$this->_gegevens_formulier_opslaan();

		if($this->aanmelden_voor == 'workshop') {
			$this->load->model('annuleringen_model');
			$this->annuleringsverzekering = $this->annuleringen_model->getAnnuleringByWorkshopID($this->workshop->workshop_ID);
		}

		// VOLGENDE STAP

		if($this->aanmelden_voor == 'workshop' && $this->annuleringsverzekering != null)
		{
			redirect('aanmelden/annuleringsverzekering');
		}
		elseif($this->aanmelden_voor == 'workshop' && sizeof($this->producten) > 0)
		{
			redirect('aanmelden/producten');
		}
		else
		{
			redirect('aanmelden/bevestigen');
		}
	}

	public function annuleringsverzekering() {
		$this->session->unset_userdata('annuleringsverzekering');
		$this->session->unset_userdata('annuleringsverzekering_actief');
		$this->load->model('annuleringen_model');

		$this->_gegevens_workshop_ophalen();

		$aanmelden_annuleringsverzekering = 'Ja';

		if($this->workshop != null) {
			$annulering = $this->annuleringen_model->getAnnuleringByWorkshopID($this->workshop->workshop_ID);
			$this->annulering_prijs = ($annulering->workshop_prijs / 100) * $annulering->annulering_percentage;
		}

		if(isset($_POST['aanmelden_annuleringsverzekering'])) {
			$this->aanmelden_annuleringsverzekering = trim($_POST['aanmelden_annuleringsverzekering']);
			$this->session->set_userdata('annuleringsverzekering_actief', $this->aanmelden_annuleringsverzekering);
			$this->session->set_userdata('annuleringsverzekering', $annulering);
			redirect('aanmelden/producten');
		}

		$this->load->model('paginas_model');
		$content = $this->paginas_model->getPaginaByID(13);
		$this->data['content'] = $content;

		$this->data['aanmelden_annuleringsverzekering']			= $aanmelden_annuleringsverzekering;
		$this->data['aanmelden_voor']							= $this->aanmelden_voor;
		$this->data['aanmelden_workshop']						= $this->aanmelden_url;
		$this->data['workshop']									= $this->workshop;
		$this->data['annulering']								= $annulering;
		$this->data['annulering_prijs']							= $this->annulering_prijs;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'aanmelden_annuleringsverzekering';
		$this->load->view('template', $pagina);
	}

	public function annuleringsverzekering_detail() {
		$this->load->model('paginas_model');
		$content = $this->paginas_model->getPaginaByID(14);

		$this->data['content'] = $content;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'annuleringsverzekering_detail';
		$this->load->view('template', $pagina);
	}


	///////////////
	// PRODUCTEN //
	///////////////

	public function producten()
	{
		$this->_gegevens_workshop_ophalen();

		// Controleren of het formulier verzonden is

		$fouten = 0;

		if(isset($_POST['aanmelden_afleveren_adres']))
		{
			if(isset($_POST['producten'])) $this->aanmelden_producten = $this->input->post('producten');

			$this->aanmelden_afleveren_adres 		= trim($this->input->post('aanmelden_afleveren_adres'));
			$this->aanmelden_afleveren_postcode	 	= trim($this->input->post('aanmelden_afleveren_postcode'));
			$this->aanmelden_afleveren_plaats 		= trim($this->input->post('aanmelden_afleveren_plaats'));

			if(!empty($this->aanmelden_afleveren_adres) || !empty($this->aanmelden_afleveren_postcode) || !empty($this->aanmelden_afleveren_plaats))
			{
				// Adres

				if(empty($this->aanmelden_afleveren_adres))
				{
					$this->aanmelden_afleveren_adres_feedback = 'Graag een adres invullen';
					$fouten++;
				}

				// Postcode

				if(empty($this->aanmelden_afleveren_postcode))
				{
					$this->aanmelden_afleveren_postcode_feedback = 'Graag een postcode invullen';
					$fouten++;
				}
				else
				{
					$postcode = str_replace(' ', '', $this->aanmelden_afleveren_postcode);
					$postcode_cijfers = substr($postcode, 0, 4);
					$postcode_letters = strtoupper(substr($postcode, -2));

					if(strlen($postcode) == 6)
					{
						if(!is_numeric($postcode_cijfers))
						{
							$this->aanmelden_afleveren_postcode_feedback = 'Graag een geldige postcode invullen';
							$fouten++;
						}
						elseif(!preg_match("/^[A-Z]+$/", $postcode_letters))
						{
							$this->aanmelden_afleveren_postcode_feedback = 'Graag een geldige postcode invullen';
							$fouten++;
						}
					}
					else
					{
						$this->aanmelden_afleveren_postcode_feedback = 'Graag een geldige postcode invullen';
						$fouten++;
					}
				}

				// Plaats

				if(empty($this->aanmelden_afleveren_plaats))
				{
					$this->aanmelden_afleveren_plaats_feedback = 'Graag een plaats invullen';
					$fouten++;
				}
			}

			if($fouten == 0)
			{
				$userdata = array(
					'aanmelden_producten' => $this->aanmelden_producten,
					'aanmelden_afleveren_adres' => $this->aanmelden_afleveren_adres,
					'aanmelden_afleveren_postcode' => $this->aanmelden_afleveren_postcode,
					'aanmelden_afleveren_plaats' => $this->aanmelden_afleveren_plaats
				);

				$this->session->set_userdata($userdata);

				redirect('aanmelden/bevestigen');
			}
		}
		else
		{
			// Stap terug gedaan? Ingevulde gegevens ophalen uit sessie
			if($this->session->userdata('aanmelden_voornaam')) $this->_gegevens_formulier_ophalen();
		}


		// PAGINA TEKST OPHALEN

		$this->load->model('paginas_model');
		$content = $this->paginas_model->getPaginaByID(7);
		$this->data['content'] = $content;


		$this->data['aanmelden_voor']							= $this->aanmelden_voor;
		$this->data['aanmelden_workshop']						= $this->aanmelden_url;

		$this->data['aanmelden_producten'] 						= $this->aanmelden_producten;
		$this->data['aanmelden_afleveren_adres'] 				= $this->aanmelden_afleveren_adres;
		$this->data['aanmelden_afleveren_postcode'] 			= $this->aanmelden_afleveren_postcode;
		$this->data['aanmelden_afleveren_plaats'] 				= $this->aanmelden_afleveren_plaats;
		$this->data['aanmelden_afleveren_adres_feedback'] 		= $this->aanmelden_afleveren_adres_feedback;
		$this->data['aanmelden_afleveren_postcode_feedback'] 	= $this->aanmelden_afleveren_postcode_feedback;
		$this->data['aanmelden_afleveren_plaats_feedback'] 		= $this->aanmelden_afleveren_plaats_feedback;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'aanmelden_producten';
		$this->load->view('template', $pagina);
	}



	////////////////
	// BEVESTIGEN //
	////////////////

	public function bevestigen()
	{
		$this->load->model('gebruikers_model');
		$this->load->model('aanmeldingen_model');
		$this->load->model('groepen_model');
		$this->load->model('bestellingen_model');
		$this->load->model('producten_model');
		$this->load->model('kortingscodes_model');
		$this->load->model('annuleringen_model');

		// GEGEVENS OPHALEN UIT SESSIE

		$this->_gegevens_formulier_ophalen();

		if($this->session->userdata('aanmelden_voor') == 'kennismakingsworkshop')
		{
			$this->_gegevens_kennismakingsworkshop_ophalen();
		}
		else
		{
			$this->_gegevens_workshop_ophalen();
		}

		$in3_aan = 0;

		// KOSTEN

		$aanmelden_bedrag = 0;

		if($this->aanmelden_voor == 'workshop')
		{
			$aanmelden_bedrag = $this->workshop->workshop_prijs;

			// Producten

			if(sizeof($this->producten) > 0 && !empty($this->aanmelden_producten) && sizeof($this->aanmelden_producten) > 0)
			{
				foreach($this->aanmelden_producten as $product_ID)
				{
					$product = $this->producten_model->getProductByID($product_ID);
					$aanmelden_bedrag += $product->product_prijs;
				}
			}

			// Stemtest korting (als je binnen een X aantal dagen aanmeld voor workshop) en nog niet eerder de korting hebt gehad

			$deelgenomen_stemtest = $this->aanmeldingen_model->getAanmeldingStemtestByEmail($this->aanmelden_emailadres);
			$aanmelding_met_stemtest_korting = $this->aanmeldingen_model->getAanmeldingAantalMetStemtestKortingByEmail($this->aanmelden_emailadres);

			if(!empty($deelgenomen_stemtest) && empty($aanmelding_met_stemtest_korting))
			{
				if($deelgenomen_stemtest->workshop_stemtest_dagen_korting_na_afloop > 0)
				{
					$vandaag = new DateTime();
					$korting_einddatum = new DateTime($deelgenomen_stemtest->aanmelding_afspraak);
					$korting_einddatum->add(DateInterval::createfromdatestring(sprintf('+%s days', $deelgenomen_stemtest->workshop_stemtest_dagen_korting_na_afloop)));

					if ($vandaag->getTimestamp() < $korting_einddatum->getTimestamp())
					{
                        if($this->aanmelden_code_oud == $deelgenomen_stemtest->aanmelding_code) {

                            // Toevoegen aan lijst met systeem kortingen

                            $this->systeem_kortingen[] = array(
                                'titel' => 'stemtest',
                                'bedrag' => $this->workshop->workshop_stemtest_prijs
                            );

                            $this->aanmelden_stemtest_korting = 'ja';
                        }
					}
				}
			}
		}
		else if($this->aanmelden_voor == 'kennismakingsworkshop')
		{
			$aanmelden_bedrag = $this->kennismakingsworkshop->kennismakingsworkshop_prijs;
		}
		else
		{
			$aanmelden_bedrag = $this->workshop->workshop_stemtest_prijs;
		}

		if ($this->aanmelden_voor != 'kennismakingsworkshop')
		{
			// Kennismakingsworkshop korting (als je binnen een X aantal dagen aanmeld) en nog niet eerder de korting hebt gehad

			$deelgenomen_kennismakingsworkshop = $this->aanmeldingen_model->getAanmeldingkennismakingsworkshopByEmail($this->aanmelden_emailadres);
			$aanmelding_met_kennismakingsworkshop_korting = $this->aanmeldingen_model->getAanmeldingAantalMetKennismakingsworkshopKortingByEmail($this->aanmelden_emailadres);

			if(!empty($deelgenomen_kennismakingsworkshop) && empty($aanmelding_met_kennismakingsworkshop_korting))
			{
				if($deelgenomen_kennismakingsworkshop->kennismakingsworkshop_dagen_korting_na_afloop > 0)
				{
					$vandaag = new DateTime();
					$korting_einddatum = new DateTime($deelgenomen_kennismakingsworkshop->kennismakingsworkshop_datum);
					$korting_einddatum->add(DateInterval::createfromdatestring(sprintf('+%s days', $deelgenomen_kennismakingsworkshop->kennismakingsworkshop_dagen_korting_na_afloop)));

					if ($vandaag->getTimestamp() < $korting_einddatum->getTimestamp())
					{
						$aanmelden_bedrag -= $deelgenomen_kennismakingsworkshop->aanmelding_betaald_bedrag;

						// Toevoegen aan lijst met systeem kortingen

						$this->systeem_kortingen[] = array(
							'titel' => 'Kennismakingsworkshop',
							'bedrag' => $deelgenomen_kennismakingsworkshop->aanmelding_betaald_bedrag
						);

						$this->aanmelden_kennismakingsworkshop_korting = 'ja';
					}
				}
			}
		}

		// STARTDATUM

		if($this->aanmelden_startdatum != '')
		{
			$startdatum = $this->groepen_model->getStartdatumByGroepID($this->aanmelden_startdatum);
			$this->data['startdatum'] = $startdatum;
		}


		/////////////
		// BETALEN //
		/////////////


		// GEBRUIKER TOEVOEGEN OF UPDATEN

		$data = array(
			'gebruiker_bedrijfsnaam' => $this->aanmelden_bedrijfsnaam,
			'gebruiker_naam' => str_replace('  ', ' ', $this->aanmelden_voornaam.' '.$this->aanmelden_tussenvoegsel.' '.$this->aanmelden_achternaam),
			'gebruiker_voornaam' => $this->aanmelden_voornaam,
			'gebruiker_tussenvoegsel' => $this->aanmelden_tussenvoegsel,
			'gebruiker_achternaam' => $this->aanmelden_achternaam,
			'gebruiker_geslacht' => $this->aanmelden_geslacht,
			'gebruiker_geboortedatum' => $this->aanmelden_geboortedatum_jaar.'-'.$this->aanmelden_geboortedatum_maand.'-'.$this->aanmelden_geboortedatum_dag,
			'gebruiker_adres' => $this->aanmelden_adres,
			'gebruiker_postcode' => $this->aanmelden_postcode,
			'gebruiker_plaats' => $this->aanmelden_plaats,
			'gebruiker_telefoonnummer' => $this->aanmelden_telefoon,
			'gebruiker_mobiel' => $this->aanmelden_mobiel,
			'gebruiker_emailadres' => $this->aanmelden_emailadres
		);

		$gebruiker = $this->gebruikers_model->checkConceptEmailadres($this->aanmelden_emailadres);

		if($gebruiker == null && $this->session->userdata('gebruiker_rechten') != 'test')
		{
			// Nieuwe gebruiker toevoegen
			$data['gebruiker_aangemeld'] = date('Y-m-d H:i:s');
			$query = $this->gebruikers_model->insertGebruiker($data);
			$gebruiker_ID = $query;
			//$this->session->set_userdata('gebruiker_ID', $query);
		}
		else
		{
			// Gebruiker updaten
            if($this->session->userdata('gebruiker_rechten') != 'test') {
                $query = $this->gebruikers_model->updateGebruiker($gebruiker->gebruiker_ID, $data);
                $gebruiker_ID = $gebruiker->gebruiker_ID;
            }
			//$this->session->set_userdata('gebruiker_ID', $gebruiker->gebruiker_ID);
		}

        // AANMELDING TOEVOEGEN OF UPDATEN
        if($this->session->userdata('gebruiker_rechten') != 'test') {
            $data = array(
                'aanmelding_type' => $this->aanmelden_voor,
                'aanmelding_betaald_kortingscode' => $this->aanmelden_kortingscode,
                'aanmelding_kennismakingsworkshop_korting' => $this->aanmelden_kennismakingsworkshop_korting,
                'aanmelding_stemtest_korting' => $this->aanmelden_stemtest_korting,
                'aanmelding_betaald_bedrag' => $aanmelden_bedrag,
                'gebruiker_ID' => $gebruiker_ID,
            );
        }

		if($this->aanmelden_voor == 'kennismakingsworkshop')
		{
		    if($this->session->userdata('gebruiker_rechten') != 'test') {
                $aanmelding = $this->aanmeldingen_model->getAanmeldingByGebruikerIDAndKennismakingsworkshopIDAndType($gebruiker_ID, $this->kennismakingsworkshop->kennismakingsworkshop_ID, $this->session->userdata('aanmelden_voor'));

                $data = array_merge($data, array(
                    'kennismakingsworkshop_ID' => $this->kennismakingsworkshop->kennismakingsworkshop_ID
                ));
                $this->session->set_userdata('aanmelden_kennismakingsworkshop_ID', $this->kennismakingsworkshop->kennismakingsworkshop_ID);
            } else {
		        $aanmelding = null;
            }
		}
		else
		{
            if($this->session->userdata('gebruiker_rechten') != 'test') {
                $aanmelding = $this->aanmeldingen_model->getAanmeldingByGebruikerIDAndWorkshopIDAndType($gebruiker_ID, $this->workshop->workshop_ID, $this->session->userdata('aanmelden_voor'));

                $data = array_merge($data, array(
                    'workshop_ID' => $this->workshop->workshop_ID,
                    'groep_ID' => $this->aanmelden_startdatum
                ));
                $this->session->set_userdata('aanmelden_workshop_ID', $this->workshop->workshop_ID);
            } else {
				$aanmelding = null;
				$this->session->set_userdata('aanmelden_workshop_ID', $this->workshop->workshop_ID);
            }
		}

		if($aanmelding == null && $this->session->userdata('gebruiker_rechten') != 'test')
		{
			// Nieuwe aanmelding toevoegen

			if(!empty($this->aanmelden_code_oud)) $data['aanmelding_code'] = $this->aanmelden_code_oud;
			else $data['aanmelding_code'] = substr(str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);

			$data['aanmelding_datum'] = date('Y-m-d H:i:s');

			$query = $this->aanmeldingen_model->insertAanmelding($data);

			$this->aanmelden_ID = $query;
			$this->aanmelden_code_nieuw = $data['aanmelding_code'];


			// Controleren of de kennismakingsworkshop vol is

			if($this->aanmelden_voor == 'kennismakingsworkshop')
			{
				$aanmelding = $this->aanmeldingen_model->getKennismakingsworkshopAanmeldingByIDsAndCode($this->aanmelden_ID, $data['kennismakingsworkshop_ID'], $data['aanmelding_code']);

				if($this->kennismakingsworkshop_model->getKennismakingsworkshopDeelnemersAantal($aanmelding->kennismakingsworkshop_ID) >= $aanmelding->kennismakingsworkshop_capaciteit)
				{
					// Beheerder op de hoogte stellen

					$this->_email_waarschuwing($aanmelding);
				}
			}
		}
		else
		{
			// Aanmelding updaten
            if($this->session->userdata('gebruiker_rechten') != 'test') {
                $query = $this->aanmeldingen_model->updateAanmeldingByID($aanmelding->aanmelding_ID, $data);


			$this->aanmelden_ID = $aanmelding->aanmelding_ID;
			$this->aanmelden_code_nieuw = $aanmelding->aanmelding_code;
            }
		}


		if($this->session->userdata('gebruiker_rechten') != 'test') {
            $this->session->set_userdata('aanmelden_ID', $this->aanmelden_ID);
            $this->session->set_userdata('aanmelden_code_nieuw', $this->aanmelden_code_nieuw);
        }

		// BESTELLING TOEVOEGEN, UPDATEN OF VERWIJDEREN
        if($this->session->userdata('gebruiker_rechten') != 'test') {
            $bestelling = $this->bestellingen_model->getBestellingByAanmeldingID($this->aanmelden_ID);

            if ($this->aanmelden_producten != '' && sizeof($this->aanmelden_producten) > 0) {
                // Afleveradres initialeren

                if (!empty($this->aanmelden_afleveren_adres)) {
                    $aanmelden_afleveren_adres = $this->aanmelden_afleveren_adres;
                    $aanmelden_afleveren_postcode = $this->aanmelden_afleveren_postcode;
                    $aanmelden_afleveren_plaats = $this->aanmelden_afleveren_plaats;
                } else {
                    $aanmelden_afleveren_adres = $this->aanmelden_adres;
                    $aanmelden_afleveren_postcode = $this->aanmelden_postcode;
                    $aanmelden_afleveren_plaats = $this->aanmelden_plaats;
                }

                $data = array(
                    'bestelling_adres' => $aanmelden_afleveren_adres,
                    'bestelling_postcode' => $aanmelden_afleveren_postcode,
                    'bestelling_plaats' => $aanmelden_afleveren_plaats,
                    'aanmelding_ID' => $this->aanmelden_ID
                );

                if ($bestelling == null) {
                    // Nieuwe bestelling toevoegen

                    $bestelling_ID = $this->bestellingen_model->insertBestelling($data);
                } else {
                    // Bestelling updaten

                    $bestelling_ID = $bestelling->bestelling_ID;

                    $this->bestellingen_model->updateBestelling($bestelling_ID, $data);

                    // Huidige producten loskoppelen

                    $this->bestellingen_model->deleteProductenByBestellingID($bestelling_ID);
                }

                // Producten koppelen aan bestelling

                foreach ($this->aanmelden_producten as $product) {
                    $data = array(
                        'product_ID' => $product,
                        'bestelling_ID' => $bestelling_ID
                    );

                    $this->bestellingen_model->insertProductBestelling($data);
                }
            } else {
                if ($bestelling != null) {
                    // Huidige bestelling verwijderen

                    $this->bestellingen_model->deleteBestelling($bestelling->bestelling_ID);
                }
            }
        }

        $this->load->model('betaalmethodes_model');
        $betaal_methodes = $this->betaalmethodes_model->getMethodes();

        $pay_images = array(
            array ('id' => 10, 'img' => 'https://admin.pay.nl/images/payment_profiles/10.gif'),
            array ('id' => 1813, 'img' => 'https://admin.pay.nl/images/payment_profiles/1813.gif'),
            array ('id' => 436, 'img' => 'https://admin.pay.nl/images/payment_profiles/436.gif'),
            array ('id' => 706, 'img' => 'https://admin.pay.nl/images/payment_profiles/706.gif')
            );

        $this->data['betaal_methodes'] = $betaal_methodes;
        $this->data['pay_images'] = $pay_images;
        $this->data['paymentList'] = Paynl\Paymentmethods::getList();

        if (!empty($this->producten)) {
            foreach ($this->producten as $product) {
                if (!empty($this->aanmelden_producten)) {
                    foreach ($this->aanmelden_producten as $checked_ID) {
                        if ($checked_ID == $product->product_ID) {
                            if (!empty($product->korting_prijs)) {
                                array_push($this->systeem_kortingen, array('titel' => $product->product_naam, 'bedrag' => $product->korting));
                            }

                            if(!empty($product->upselling_korting_prijs)) {
                                array_push($this->systeem_kortingen, array('titel' => $product->product_naam, 'bundelkorting' => 1, 'bedrag' =>  $product->upselling_korting));
                            }
                        }
                    }
                }
            }
        }

		if(!empty($this->systeem_kortingen)) {
			if (count($this->systeem_kortingen) > 1) {
				foreach ($this->systeem_kortingen as $korting) {
					$aanmelden_bedrag -= $korting['bedrag'];
				}
			} else {
				if (!empty($this->systeem_kortingen)) {
					$aanmelden_bedrag -= $this->systeem_kortingen[0]['bedrag'];
				}
			}
		}


		if(!empty($this->aanmelden_kortingscode)) {
			if(count($this->aanmelden_kortingscode) > 1) {
				foreach ($this->aanmelden_kortingscode as $korting) {
					array_push($this->systeem_kortingen, array('titel' => $product->product_naam, 'bedrag' => $korting));
					$aanmelden_bedrag -= $korting['bedrag'];
				}
			} else {
				if(!empty($this->workshop->korting)) {
					$aanmelden_bedrag -= $this->workshop->korting;
				}
			}
		}

		if($this->session->userdata('annuleringsverzekering_actief') == "Ja") {
			$annulering = $this->session->userdata('annuleringsverzekering');
			$annulering_prijs = ($this->workshop->workshop_prijs / 100) * $annulering->annulering_percentage;
			$aanmelden_bedrag += $annulering_prijs;
			$this->data['annulering_prijs'] = $annulering_prijs;
		}

        $items = array();

        if($this->aanmelden_voor == "workshop") {
            $items[] = array("productId" => $this->workshop->workshop_ID,
                "productType" => $this->workshop->workshop_titel,
                "description" => $this->workshop->workshop_titel,
                "price" => $this->workshop->workshop_prijs,
                "quantity" => "1",
                "vatCode" => "NL",
                "vatPercentage" => "21",
                "discount" => "0"
            );
        } elseif ($this->aanmelden_voor == "kennismakingsworkshop") {
            $items[] = array("productId" => $this->kennismakingsworkshop->kennismakingsworkshop_ID,
                "productType" => $this->kennismakingsworkshop->kennismakingsworkshop_titel,
                "description" => $this->kennismakingsworkshop->kennismakingsworkshop_titel,
                "price" => $this->kennismakingsworkshop->kennismakingsworkshop_prijs,
                "quantity" => "1",
                "vatCode" => "NL",
                "vatPercentage" => "21",
                "discount" => "0"
            );
        }

        if (!empty($this->producten)) {
            foreach ($this->producten as $product) {
                $discount = 0;

                if (!empty($product->korting_prijs)) {
                    $discount = $product->product_prijs - $product->korting_prijs;
                }

                $items[] = array("productId" => $product->product_ID,
                    "productType" => $product->product_naam,
                    "description" => $product->product_naam,
                    "price" => $product->product_prijs,
                    "quantity" => "1",
                    "vatCode" => "NL",
                    "vatPercentage" => "21",
                    "discount" => $discount
                );
            }
        }

        // AANMELDING_BEDRAG UPDATEN NA KORTING
        if($this->session->userdata('gebruiker_rechten') != 'test') {
            $data = array(
                'aanmelding_betaald_bedrag' => $aanmelden_bedrag,
            );

            $query = $this->aanmeldingen_model->updateAanmeldingByID($this->aanmelden_ID, $data);
        }

        $this->session->set_userdata('aanmelden_items', $items);
        $this->session->set_userdata('aanmelden_bedrag', $aanmelden_bedrag);
        $this->session->set_userdata('systeem_kortingen', $this->systeem_kortingen);

        if(!empty($this->aanmelden_kortingscode)) {
            $this->load->model('kortingscodes_model');
            $kortingscode = $this->kortingscodes_model->getKortingscodesByCode($this->aanmelden_kortingscode);
        }

        if($this->aanmelden_voor == "workshop") {
            if ((!empty($kortingscode) && $kortingscode->kortingscode_in3 == 1) && $this->workshop->workshop_in3 == 1 || (empty($kortingscode) && $this->workshop->workshop_in3 == 1)) {
                $in3_aan = 1;
            }
        } else {
            if ((!empty($kortingscode) && $kortingscode->kortingscode_in3 == 1) || empty($kortingscode)) {
                $in3_aan = 1;
            }
        }

		// PAGINA TONEN

		$this->data['aanmelden_voor']					= $this->aanmelden_voor;
		$this->data['aanmelden_url']					= $this->aanmelden_url;

		$this->data['aanmelden_startdatum'] 			= $this->aanmelden_startdatum;
		$this->data['aanmelden_bedrijfsnaam'] 			= $this->aanmelden_bedrijfsnaam;
		$this->data['aanmelden_voornaam'] 				= $this->aanmelden_voornaam;
		$this->data['aanmelden_tussenvoegsel'] 			= $this->aanmelden_tussenvoegsel;
		$this->data['aanmelden_achternaam'] 			= $this->aanmelden_achternaam;
		$this->data['aanmelden_geslacht'] 				= $this->aanmelden_geslacht;
		$this->data['aanmelden_geboortedatum_dag'] 		= $this->aanmelden_geboortedatum_dag;
		$this->data['aanmelden_geboortedatum_maand'] 	= $this->aanmelden_geboortedatum_maand;
		$this->data['aanmelden_geboortedatum_jaar'] 	= $this->aanmelden_geboortedatum_jaar;
		$this->data['aanmelden_adres'] 					= $this->aanmelden_adres;
		$this->data['aanmelden_postcode'] 				= $this->aanmelden_postcode;
		$this->data['aanmelden_plaats'] 				= $this->aanmelden_plaats;
		$this->data['aanmelden_telefoon'] 				= $this->aanmelden_telefoon;
		$this->data['aanmelden_mobiel'] 				= $this->aanmelden_mobiel;
		$this->data['aanmelden_emailadres'] 			= $this->aanmelden_emailadres;
		$this->data['aanmelden_kortingscode'] 			= $this->aanmelden_kortingscode;
		$this->data['systeem_kortingen']				= $this->systeem_kortingen;
		$this->data['aanmelden_akkoord'] 				= $this->aanmelden_akkoord;
		$this->data['in3_aan'] 				    = $in3_aan;

        if (!empty($this->workshop->korting)) {
            $this->data['workshop_korting'] 			= $this->workshop->korting;
            $this->session->set_userdata('workshop_korting', $this->workshop->korting);
        }

        $this->data['aanmelden_producten'] 				= $this->aanmelden_producten;
		$this->data['aanmelden_bedrag'] 				= $aanmelden_bedrag;

        if (isset($this->workshop)) {
            $this->data['aanmelden_workshop_ID'] = $this->workshop->workshop_ID;
        }

        if (isset($this->kennismakingsworkshop)) {
            $this->data['aanmelden_kennismakingsworkshop_ID'] = $this->kennismakingsworkshop->kennismakingsworkshop_ID;
        }

		$this->data['aanmelden_ID'] 					= $this->aanmelden_ID;
		$this->data['aanmelden_code_nieuw'] 			= $this->aanmelden_code_nieuw;

		$this->data['aanmelden_producten'] 				= $this->aanmelden_producten;
		$this->data['aanmelden_afleveren_adres'] 		= $this->aanmelden_afleveren_adres;
		$this->data['aanmelden_afleveren_postcode'] 	= $this->aanmelden_afleveren_postcode;
		$this->data['aanmelden_afleveren_plaats'] 		= $this->aanmelden_afleveren_plaats;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'aanmelden_bevestigen';
		$this->load->view('template', $pagina);
	}


	private function _removeSpecialCharacters($str)
	{
		$invalid = array('Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e',  'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y',  'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', "`" => "'", "´" => "'", "„" => ",", "`" => "'", "´" => "'", "“" => "\"", "”" => "\"", "´" => "'", "&acirc;€™" => "'", "{" => "", "~" => "", "–" => "-", "’" => "'");
		$str = str_replace(array_keys($invalid), array_values($invalid), $str);
		return $str;
	}



	//////////////////////////////////////////////
	// GEGEVENS WORKSHOP, KENNISMAKINGSWORKSHOP EN FORMULIER //
	//////////////////////////////////////////////

	private function _gegevens_workshop_ophalen()
	{
		if($this->session->userdata('aanmelden_voor'))
		{
			$this->aanmelden_voor = $this->session->userdata('aanmelden_voor');
			$this->aanmelden_url = $this->session->userdata('aanmelden_url');


			// WORKSHOP OPHALEN

			$this->load->model('workshops_model');
			$this->load->model('kortingscodes_model');
			$this->workshop = $this->workshops_model->getWorkshopByURL($this->aanmelden_url);
			if($this->workshop == null) redirect('workshops');
			$this->data['workshop'] = $this->workshop;


			// INTAKE EN STEMTEST CONTROLEREN

			// Controleren of de url geldig is, heeft de workshop een intake of stemtest?
			if($this->aanmelden_voor == 'intake') if($this->workshop->workshop_ID != 6) redirect('workshops');
			if($this->aanmelden_voor == 'stemtest') if($this->workshop->workshop_stemtest == 'nee') redirect('workshops');


			// GROEPEN OPHALEN

			if($this->workshop->workshop_type == 'groep' || $this->workshop->workshop_type == 'online')
			{
				$this->load->model('groepen_model');
				$this->load->model('uitnodigingen_model');
				$link = $this->uitnodigingen_model->getLinkByCode($this->code);

				if(empty($link)) {
                    $this->groepen = $this->groepen_model->getGroepenAanmeldenByWorkshopID($this->workshop->workshop_ID);
                    $this->data['groepen'] = $this->groepen;
                } else {
				    $this->groepen = $this->groepen_model->getGroepByID($link->groep_ID);
                    $this->data['groepen'] = $this->groepen;
                }
			}


			// PRODUCTEN OPHALEN

            $this->producten = array();
            $this->producten = $this->workshops_model->getWorkshopProductenByID($this->workshop->workshop_ID);
            $upselling = $this->kortingscodes_model->getUpsellingByworkshopID($this->workshop->workshop_ID);

            if(!empty($upselling)) {
                $upselling_connecties = $this->kortingscodes_model->getUpsellingConnectiesByIDs($upselling->upselling_ID);
            }

			$connecties = $this->session->userdata('kortingscode_connecties');
            $kortingscode = $this->session->userdata('kortingscode');

            if (!empty($connecties) && !empty($upselling_connecties)) {
                foreach ($this->producten as $product) {
                    foreach ($upselling_connecties as $upsell_connectie) {
                        if ($product->product_ID == $upsell_connectie->product_ID) {
                            if ($upsell_connectie->upselling_prijs != $product->product_prijs) {
                                $upselling_korting = $product->product_prijs - $upsell_connectie->upselling_prijs;
                                $upselling_kortingsprijs = $upsell_connectie->upselling_prijs;

                                $product->upselling_korting_prijs = $upselling_kortingsprijs;
                                $product->upselling_korting = $upselling_korting;
                            }
                        }
                    }

                    foreach ($connecties as $connectie) {
                        if ($product->product_ID == $connectie->product_ID) {
                            if(!empty($product->upselling_korting_prijs) && !empty($product->upselling_korting)) {
                                if ($kortingscode->kortingscode_percentage > 0) {
                                    $korting = $product->upselling_korting_prijs / 100 * $kortingscode->kortingscode_percentage;
                                    $kortingsprijs = $product->upselling_korting_prijs - $korting;
                                } elseif ($kortingscode->kortingscode_vast_bedrag > 0) {
                                    $korting = $kortingscode->kortingscode_vast_bedrag;
                                    $kortingsprijs = $product->upselling_korting_prijs - $kortingscode->kortingscode_vast_bedrag;
                                }
                            } else {
                                if ($kortingscode->kortingscode_percentage > 0) {
                                    $korting = $product->product_prijs / 100 * $kortingscode->kortingscode_percentage;
                                    $kortingsprijs = $product->product_prijs - $korting;
                                } elseif ($kortingscode->kortingscode_vast_bedrag > 0) {
                                    $korting = $kortingscode->kortingscode_vast_bedrag;
                                    $kortingsprijs = $product->product_prijs - $kortingscode->kortingscode_vast_bedrag;
                                }
                            }

                            $product->korting_prijs = $kortingsprijs;
                            $product->korting = $korting;
                        }
                    }
                }

                foreach ($connecties as $connectie) {
                    if ($this->workshop->workshop_ID == $connectie->workshop_ID) {
                        if ($kortingscode->kortingscode_percentage > 0) {
                            $korting = $this->workshop->workshop_prijs / 100 * $kortingscode->kortingscode_percentage;
                            $kortingsprijs = $this->workshop->workshop_prijs - $korting;
                        } elseif ($kortingscode->kortingscode_vast_bedrag > 0) {
                            $korting = $kortingscode->kortingscode_vast_bedrag;
                            $kortingsprijs = $this->workshop->workshop_prijs - $kortingscode->kortingscode_vast_bedrag;
                        }

                        $this->workshop->korting_prijs = $kortingsprijs;
                        $this->workshop->korting = $korting;
                    }
                }
            } elseif(!empty($connecties)) {
                $kortingscode = $this->session->userdata('kortingscode');

                if(!empty($this->producten)) {
                    foreach ($this->producten as $product) {
                        foreach ($connecties as $connectie) {
                            if ($product->product_ID == $connectie->product_ID) {
                                if ($kortingscode->kortingscode_percentage > 0) {
                                    $korting = $product->product_prijs / 100 * $kortingscode->kortingscode_percentage;
                                    $kortingsprijs = $product->product_prijs - $korting;
                                } elseif ($kortingscode->kortingscode_vast_bedrag > 0) {
                                    $korting = $kortingscode->kortingscode_vast_bedrag;
                                    $kortingsprijs = $product->product_prijs - $kortingscode->kortingscode_vast_bedrag;
                                }

                                $product->korting_prijs = $kortingsprijs;
                                $product->korting = $korting;
                            }
                        }
                    }
                }

                foreach ($connecties as $connectie) {
                    if ($this->workshop->workshop_ID == $connectie->workshop_ID) {
                        if ($kortingscode->kortingscode_percentage > 0) {
                            $korting = $this->workshop->workshop_prijs / 100 * $kortingscode->kortingscode_percentage;
                            $kortingsprijs = $this->workshop->workshop_prijs - $korting;
                        } elseif ($kortingscode->kortingscode_vast_bedrag > 0) {
                            $korting = $kortingscode->kortingscode_vast_bedrag;
                            $kortingsprijs = $this->workshop->workshop_prijs - $kortingscode->kortingscode_vast_bedrag;
                        }

                        $this->workshop->korting_prijs = $kortingsprijs;
                        $this->workshop->korting = $korting;
                    }
                }
            } elseif(!empty($upselling_connecties)){
                foreach ($this->producten as $product) {
                    foreach ($upselling_connecties as $connectie) {
                        if ($product->product_ID == $connectie->product_ID) {
                            if ($connectie->upselling_prijs != $product->product_prijs) {
                                $upselling_korting = $product->product_prijs - $connectie->upselling_prijs;
                                $upselling_kortingsprijs = $connectie->upselling_prijs;
                            } else {
                                $upselling_korting = 0;
                                $upselling_kortingsprijs = 0;
                            }

                            $product->upselling_korting_prijs = $upselling_kortingsprijs;
                            $product->upselling_korting = $upselling_korting;
                        }
                    }
                }
            }

			$this->data['producten'] = $this->producten;
		}
		else
		{
			redirect('workshops');
		}
	}

	private function _gegevens_kennismakingsworkshop_ophalen()
	{
		if($this->session->userdata('aanmelden_voor'))
		{
			$this->aanmelden_voor = $this->session->userdata('aanmelden_voor');
			$this->aanmelden_url = $this->session->userdata('aanmelden_url');


			// KENNISMAKINGSWORKSHOP OPHALEN

			$this->load->model('kennismakingsworkshop_model');
			$this->kennismakingsworkshop = $this->kennismakingsworkshop_model->getKennismakingsworkshopByDatum($this->aanmelden_url);

			if($this->kennismakingsworkshop == null) redirect('');
			$this->data['kennismakingsworkshop'] = $this->kennismakingsworkshop;
		}
		else
		{
			redirect('');
		}
	}

	private function _gegevens_formulier_ophalen()
	{
		// GEGEVENS OPHALEN UIT SESSIE

		$this->aanmelden_ID						= $this->session->userdata('aanmelden_ID');
		$this->aanmelden_startdatum	 			= $this->session->userdata('aanmelden_startdatum');
		$this->aanmelden_bedrijfsnaam 			= $this->session->userdata('aanmelden_bedrijfsnaam');
		$this->aanmelden_voornaam 				= $this->session->userdata('aanmelden_voornaam');
		$this->aanmelden_tussenvoegsel 			= $this->session->userdata('aanmelden_tussenvoegsel');
		$this->aanmelden_achternaam 			= $this->session->userdata('aanmelden_achternaam');
		$this->aanmelden_geslacht 				= $this->session->userdata('aanmelden_geslacht');
		$this->aanmelden_geboortedatum_dag 		= $this->session->userdata('aanmelden_geboortedatum_dag');
		$this->aanmelden_geboortedatum_maand 	= $this->session->userdata('aanmelden_geboortedatum_maand');
		$this->aanmelden_geboortedatum_jaar 	= $this->session->userdata('aanmelden_geboortedatum_jaar');
		$this->aanmelden_adres 					= $this->session->userdata('aanmelden_adres');
		$this->aanmelden_postcode 				= $this->session->userdata('aanmelden_postcode');
		$this->aanmelden_plaats 				= $this->session->userdata('aanmelden_plaats');
		$this->aanmelden_telefoon 				= $this->session->userdata('aanmelden_telefoon');
		$this->aanmelden_mobiel 				= $this->session->userdata('aanmelden_mobiel');
		$this->aanmelden_emailadres 			= $this->session->userdata('aanmelden_emailadres');
		$this->aanmelden_code_oud				= $this->session->userdata('aanmelden_code_oud');
		$this->aanmelden_code_nieuw				= $this->session->userdata('aanmelden_code_nieuw');
		$this->aanmelden_wachtwoord		 		= $this->session->userdata('aanmelden_wachtwoord');
		$this->aanmelden_kortingscode 			= $this->session->userdata('aanmelden_kortingscode');
		$this->aanmelden_akkoord 				= $this->session->userdata('aanmelden_akkoord');
		$this->aanmelden_producten				= $this->session->userdata('aanmelden_producten');
		$this->aanmelden_afleveren_adres		= $this->session->userdata('aanmelden_afleveren_adres');
		$this->aanmelden_afleveren_postcode		= $this->session->userdata('aanmelden_afleveren_postcode');
		$this->aanmelden_afleveren_plaats		= $this->session->userdata('aanmelden_afleveren_plaats');
	}

	private function _gegevens_formulier_opslaan()
	{
		// GEGEVENS OPSLAAN IN SESSIE

		$userdata = array(
			'aanmelden_ID' => $this->aanmelden_ID,
			'aanmelden_startdatum' => $this->aanmelden_startdatum,
			'aanmelden_bedrijfsnaam' => $this->aanmelden_bedrijfsnaam,
			'aanmelden_voornaam' => $this->aanmelden_voornaam,
			'aanmelden_tussenvoegsel' => $this->aanmelden_tussenvoegsel,
			'aanmelden_achternaam' => $this->aanmelden_achternaam,
			'aanmelden_geslacht' => $this->aanmelden_geslacht,
			'aanmelden_geboortedatum_dag' => $this->aanmelden_geboortedatum_dag,
			'aanmelden_geboortedatum_maand' => $this->aanmelden_geboortedatum_maand,
			'aanmelden_geboortedatum_jaar' => $this->aanmelden_geboortedatum_jaar,
			'aanmelden_adres' => $this->aanmelden_adres,
			'aanmelden_postcode' => $this->aanmelden_postcode,
			'aanmelden_plaats' => $this->aanmelden_plaats,
			'aanmelden_telefoon' => $this->aanmelden_telefoon,
			'aanmelden_mobiel' => $this->aanmelden_mobiel,
			'aanmelden_emailadres' => $this->aanmelden_emailadres,
			'aanmelden_code_oud' => $this->aanmelden_code_oud,
			'aanmelden_code_nieuw' => $this->aanmelden_code_nieuw,
			'aanmelden_wachtwoord' => $this->aanmelden_wachtwoord,
			'aanmelden_kortingscode' => $this->aanmelden_kortingscode,
			'aanmelden_akkoord' => $this->aanmelden_akkoord,
			'aanmelden_producten' => $this->aanmelden_producten,
			'aanmelden_afleveren_adres' => $this->aanmelden_afleveren_adres,
			'aanmelden_afleveren_postcode' => $this->aanmelden_afleveren_postcode,
			'aanmelden_afleveren_plaats' => $this->aanmelden_afleveren_plaats
		);

		$this->session->set_userdata($userdata);
	}

	private function _gegevens_formulier_verwijderen()
	{
		// GEGEVENS VERWIJDEREN UIT SESSIE

		$this->session->unset_userdata('aanmelden_ID');
		$this->session->unset_userdata('aanmelden_startdatum');
		$this->session->unset_userdata('aanmelden_bedrijfsnaam');
		$this->session->unset_userdata('aanmelden_voornaam');
		$this->session->unset_userdata('aanmelden_tussenvoegsel');
		$this->session->unset_userdata('aanmelden_achternaam');
		$this->session->unset_userdata('aanmelden_geslacht');
		$this->session->unset_userdata('aanmelden_geboortedatum_dag');
		$this->session->unset_userdata('aanmelden_geboortedatum_maand');
		$this->session->unset_userdata('aanmelden_geboortedatum_jaar');
		$this->session->unset_userdata('aanmelden_adres');
		$this->session->unset_userdata('aanmelden_postcode');
		$this->session->unset_userdata('aanmelden_plaats');
		$this->session->unset_userdata('aanmelden_telefoon');
		$this->session->unset_userdata('aanmelden_mobiel');
		$this->session->unset_userdata('aanmelden_emailadres');
		$this->session->unset_userdata('aanmelden_code_oud');
		$this->session->unset_userdata('aanmelden_code_nieuw');
		$this->session->unset_userdata('gebruiker_wachtwoord');
		$this->session->unset_userdata('aanmelden_kortingscode');
		$this->session->unset_userdata('aanmelden_akkoord');
		$this->session->unset_userdata('aanmelden_producten');
		$this->session->unset_userdata('aanmelden_afleveren_adres');
		$this->session->unset_userdata('aanmelden_afleveren_postcode');
		$this->session->unset_userdata('aanmelden_afleveren_plaats');
		$this->session->unset_userdata('aanmelden_bedrag');
		$this->session->unset_userdata('aanmelden_workshop_ID');
		$this->session->unset_userdata('aanmelden_kennismakingsworkshop_ID');
		$this->session->unset_userdata('annuleringsverzekering');
		$this->session->unset_userdata('annuleringsverzekering_actief');
	}



	///////////////////////////////////////////
	// AANMELDEN AFRONDEN VIA LINK IN E-MAIL //
	///////////////////////////////////////////

	public function afronden($aanmelden_voor = null, $url = null, $aanmelding_ID = null, $aanmelding_code = null, $status = null)
	{
		if($aanmelden_voor == null || $url == null || $aanmelding_ID == null || $aanmelding_code == null) redirect('workshops');

		// AANMELDING OPHALEN

		$this->load->model('aanmeldingen_model');
		$aanmelding = $this->aanmeldingen_model->getAanmeldingByIDAndCode($aanmelding_ID, $aanmelding_code);

		if($aanmelding == null)
		{
			if($aanmelden_voor == 'kennismakingsworkshop') redirect('');
			else redirect('workshops');
		}


		//-- Controleren of hij betaald is of niet



		// GEGEVENS WORKSHOP OF KENNISMAKINGSWORKSHOP INITIALISEREN

		$this->aanmelden_voor = $aanmelden_voor;
		$this->aanmelden_url = $url;
		$this->session->set_userdata(array('aanmelden_voor' => $this->aanmelden_voor, 'aanmelden_url' => $this->aanmelden_url));

		if($aanmelden_voor == 'kennismakingsworkshop')
		{
			$this->_gegevens_kennismakingsworkshop_ophalen();
		}
		else
		{
			$this->_gegevens_workshop_ophalen();
		}



		// GEGEVENS FORMULIER INITIALISEREN

		$aanmelden_geboortedatum = explode('-', $aanmelding->gebruiker_geboortedatum);

		$this->aanmelden_ID = $aanmelding->aanmelding_ID;
		$this->aanmelden_startdatum = $aanmelding->groep_ID;
		$this->aanmelden_bedrijfsnaam = $aanmelding->gebruiker_bedrijfsnaam;
		$this->aanmelden_voornaam = $aanmelding->gebruiker_voornaam;
		$this->aanmelden_tussenvoegsel = $aanmelding->gebruiker_tussenvoegsel;
		$this->aanmelden_achternaam = $aanmelding->gebruiker_achternaam;
		$this->aanmelden_geslacht = $aanmelding->gebruiker_geslacht;
		$this->aanmelden_geboortedatum_dag = $aanmelden_geboortedatum[2];
		$this->aanmelden_geboortedatum_maand = $aanmelden_geboortedatum[1];
		$this->aanmelden_geboortedatum_jaar = $aanmelden_geboortedatum[0];
		$this->aanmelden_adres = $aanmelding->gebruiker_adres;
		$this->aanmelden_postcode = $aanmelding->gebruiker_postcode;
		$this->aanmelden_plaats = $aanmelding->gebruiker_plaats;
		$this->aanmelden_telefoon = $aanmelding->gebruiker_telefoonnummer;
		$this->aanmelden_mobiel = $aanmelding->gebruiker_mobiel;
		$this->aanmelden_emailadres = $aanmelding->gebruiker_emailadres;
		$this->aanmelden_code_oud = $aanmelding->aanmelding_code;
		$this->aanmelden_kortingscode = $aanmelding->aanmelding_betaald_kortingscode;

		if(!empty($aanmelding->bestelling_ID))
		{
			$this->load->model('bestellingen_model');
			$producten = $this->bestellingen_model->getProductenByBestellingID($aanmelding->bestelling_ID);

			$this->aanmelden_producten = array();
			foreach($producten as $product) $this->aanmelden_producten[] = $product->product_ID;

			$this->aanmelden_afleveren_adres = $aanmelding->bestelling_adres;
			$this->aanmelden_afleveren_postcode = $aanmelding->bestelling_postcode;
			$this->aanmelden_afleveren_plaats = $aanmelding->bestelling_plaats;
		}

		$this->_gegevens_formulier_opslaan();

		redirect('aanmelden/bevestigen?status=' . $status);
	}






	///////////////
	// AANGEMELD //
	///////////////

	public function aangemeld($aanmelding_ID = null, $aanmelden_voor = null, $ID = null, $aanmelding_code = null, $code = null)
	{
		// Controleren of alle gegevens zijn meegestuurd
    if($this->session->userdata('gebruiker_rechten') != 'test') {
        if ($aanmelding_ID == null || $aanmelden_voor == null || $ID == null || $aanmelding_code == null) redirect('workshops');
    }
		// Models laden

		$this->load->model('gebruikers_model');
		$this->load->model('aanmeldingen_model');
		$this->load->model('kennismakingsworkshop_model');
		$this->load->model('groepen_model');
		$this->load->model('workshops_model');
		$this->load->model('uitnodigingen_model');

		if ($aanmelden_voor == 'kennismakingsworkshop') {

			// Aanmelding ophalen
            if($this->session->userdata('gebruiker_rechten') != 'test') {
                $aanmelding = $this->aanmeldingen_model->getKennismakingsworkshopAanmeldingByIDsAndCode($aanmelding_ID, $ID, $aanmelding_code);
            }
			// Controleren of de aanmelding bestaat

            if($this->session->userdata('gebruiker_rechten') != 'test') {
                if ($aanmelding == null) redirect('');
            }
		} else {
			// Aanmelding ophalen
            if($this->session->userdata('gebruiker_rechten') != 'test') {
                $aanmelding = $this->aanmeldingen_model->getAanmeldingByIDsAndCode($aanmelding_ID, $ID, $aanmelding_code);
            }
			// Controleren of de aanmelding bestaat

            if($this->session->userdata('gebruiker_rechten') != 'test') {
                if ($aanmelding == null) redirect('workshops');
            }
		}

		if($this->session->userdata('gebruiker_rechten') != 'test') {
            try {
                $transaction = \Paynl\Transaction::getForReturn();

                if ($transaction->isPaid() || $transaction->isPending()) {
                    if ($transaction->isPaid()) {
                        if ($aanmelding->aanmelding_betaald_datum == '0000-00-00 00:00:00') {
							// Aanmelding betaald datum updaten

							$betalings_ID = $_GET['orderId'];

                            // $update_aanmelding = $this->aanmeldingen_model->updateAanmeldingByID($aanmelding->aanmelding_ID, array('aanmelding_betaald_datum' => date('Y-m-d H:i:s'), 'aanmelding_verlopen' => 0, 'betalings_ID' => $betalings_ID));
								 $update_aanmelding = $this->aanmeldingen_model->updateAanmeldingByID($aanmelding->aanmelding_ID, array('aanmelding_betaald_datum' => date('Y-m-d H:i:s'), 'aanmelding_verlopen' => 0, 'betalings_ID' => $betalings_ID,'aanmelding_afgerond' => '1','aanmelding_afgerond_datum'=> date('Y-m-d H:i:s')));


                            if ($update_aanmelding) {
                                // Controleren of de gebruiker een wachtwoord nodig heeft en of hij deze heeft

                                if ($aanmelding->gebruiker_wachtwoord == '') {
                                    // Wachtwoord genereren en gebruiker activeren
                                    $this->aanmelden_wachtwoord = substr(str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);

                                    if ($aanmelden_voor == 'workshop' || 'stemtest') {
                                        if (empty($aanmelding->volledige_cursistenmodule)) {
                                            $workshops = $this->workshops_model->getWorkshopsByGebruikerID($aanmelding->gebruiker_ID);
                                            $deelnemmer = false;
                                            if (!empty($workshops)) {
                                                foreach ($workshops as $workshop) {
                                                    if (!empty($workshop->volledige_cursistenmodule)) {
                                                        $deelnemer = true;
                                                    }
                                                }
                                            }

                                            if (!empty($deelnemer)) {
                                                $rechten = 'deelnemer';
                                            } else {
                                                $rechten = 'kandidaat';
                                            }

                                            $update_gebruiker = $this->gebruikers_model->updateGebruiker($aanmelding->gebruiker_ID, array('gebruiker_rechten' => $rechten, 'gebruiker_wachtwoord' => sha1($this->aanmelden_wachtwoord), 'gebruiker_status' => 'actief'));
                                            $this->session->set_userdata('gebruiker_rechten', $rechten);
                                        } else {
                                            $update_gebruiker = $this->gebruikers_model->updateGebruiker($aanmelding->gebruiker_ID, array('gebruiker_rechten' => 'deelnemer', 'gebruiker_wachtwoord' => sha1($this->aanmelden_wachtwoord), 'gebruiker_status' => 'actief'));
                                        }
                                    } else {
                                        $update_gebruiker = $this->gebruikers_model->updateGebruiker($aanmelding->gebruiker_ID, array('gebruiker_rechten' => 'deelnemer', 'gebruiker_wachtwoord' => sha1($this->aanmelden_wachtwoord), 'gebruiker_status' => 'actief'));
                                    }

                                    if ($update_gebruiker) {
                                        // E-mail bevestiging van aanmelding incl. wachtwoord

                                        $this->_email_gebruiker($aanmelding, $this->aanmelden_wachtwoord);
                                    }

                                } else {
                                    // Controleren of de gebruiker geactiveerd moet worden

                                    if ($aanmelding->gebruiker_status != 'actief') {
                                        // Gebruiker activeren

                                        $this->gebruikers_model->updateGebruiker($aanmelding->gebruiker_ID, array('gebruiker_status' => 'actief'));
                                    }

                                    // E-mail bevestiging van aanmelding

                                    $this->_email_gebruiker($aanmelding);
                                }

                                // Bevestiging van aanmelding sturen naar localhost

                                $this->_email_overzicht($aanmelding, 'Deelnemer');

                                $this->_mail_welkomstmail($aanmelding);

                                // Controleren of de workshop vol is

                                if ($aanmelden_voor == 'workshop' && in_array($aanmelding->workshop_type, array('groep', 'online'))) {
                                    $workshop_capaciteit = $aanmelding->workshop_capaciteit;
                                    $workshop_deelnemers = sizeof($this->groepen_model->getGroepDeelnemers($aanmelding->groep_ID));

                                    // Groep sluiten wanneer er genoeg deelnemers zich hebben aangemeld
									if ($workshop_deelnemers >= $workshop_capaciteit) {
										$this->groepen_model->updateGroep($aanmelding->groep_ID, array('groep_aanmelden' => 'nee'));
										$this->_mail_groep_vol($aanmelding);
									}
                                }

								//limiet uitnodigingscode
								if(!empty($this->session->userdata('link'))) {
								$link = $this->session->userdata('link');

									if($link->uitnodiging_limiet > 0) {
										$uitnodging_limiet = $link->uitnodiging_limiet - 1;

										$data = array(
											'uitnodiging_limiet' => $uitnodging_limiet
										);

										// Data toevoegen/updaten afhankelijk van het type
										$q = $this->uitnodigingen_model->updateUitnodigingsLink($link->link_ID, $data);
									}
								}

                                $this->toevoegen_mailchimp($aanmelding);

                                // Gegevens uit sessie verwijderen
                                $this->_gegevens_formulier_verwijderen();
                            }
                        }
                    }
                } elseif ($transaction->isCanceled()) {
                    $status = 'canceled';
                    if ($aanmelding->aanmelding_type == 'kennismakingsworkshop') {
                        redirect('aanmelden/afronden/' . $aanmelding->aanmelding_type . '/' . date('d-m-Y', strtotime($aanmelding->kennismakingsworkshop_datum)) . '/' . $aanmelding->aanmelding_ID . '/' . $aanmelding->aanmelding_code . '/' . $status);
                    } else {
                        redirect('aanmelden/afronden/' . $aanmelding->aanmelding_type . '/' . $aanmelding->workshop_url . '/' . $aanmelding->aanmelding_ID . '/' . $aanmelding->aanmelding_code . '/' . $status);
                    }
                }
            } catch (\Paynl\Error\Error $e) {
                echo "Fout: " . $e->getMessage();
            }
        } else {
                // Gegevens uit sessie verwijderen
            $this->_gegevens_formulier_verwijderen();
    }

		$producten = array();

		if(@$workshop_ID != null)
		{
			$producten = $this->workshops_model->getWorkshopProductenByID($workshop_ID);
		}

        if(!empty($code)) {
            $this->load->model('uitnodigingen_model');

            $link = $this->uitnodigingen_model->getLinkByCode($code);

            if(!empty($link) && $link->link_gebruikt != 1) {
                $data = array(
                    'link_gebruikt' => 1
                );

                $this->uitnodigingen_model->updateUitnodigingsLink($link->link_ID, $data);
            }
        }

		// Pagina weergeven

		$this->load->model('bestellingen_model');
		if($this->session->userdata('gebruiker_rechten') != 'test') {
			$bestelling = $this->bestellingen_model->getBestellingByAanmeldingID($aanmelding->aanmelding_ID);
		} else {
			$bestelling = null;
		}

        $workshop_korting = $this->session->userdata('workshop_korting');
        $systeem_kortingen = $this->session->userdata('systeem_kortingen');
        $betaal_methode = $this->session->userdata('betaal_methode');

        $this->session->unset_userdata('workshop_korting');
        $this->session->unset_userdata('systeem_kortingen');
        $this->session->unset_userdata('betaal_methode');

		if($this->session->userdata('gebruiker_rechten') != 'test') {
			if($aanmelden_voor == 'workshop') {
				$betaald_bedrag = $aanmelding->workshop_prijs;
			} elseif($aanmelden_voor == 'kennismakingsworkshop') {
				$betaald_bedrag = $aanmelding->kennismakingsworkshop_prijs;
			} else {
				$betaald_bedrag = $aanmelding->workshop_stemtest_prijs;
			}
		}

		if($this->session->userdata('gebruiker_rechten') != 'test') {
			if(!empty($betaal_methode)) {
				$betaald_bedrag = $betaald_bedrag + $betaal_methode;
			}

			if($bestelling != null) {
				// PRODUCTEN OPHALEN
				$producten = $this->bestellingen_model->getProductenByBestellingID($bestelling->bestelling_ID);

				if(!empty($producten)) {
					foreach ($producten as $product) {
						$betaald_bedrag = $betaald_bedrag + $product->product_prijs;
					}
				}
			}

			if(!empty($workshop_korting)) {
				$betaald_bedrag = $betaald_bedrag - $workshop_korting;
			}

			if(!empty($systeem_kortingen)) {
				foreach ($systeem_kortingen as $korting) {
					$betaald_bedrag = $betaald_bedrag - $korting['bedrag'];
				}
			}
		}

        if($this->session->userdata('gebruiker_rechten') != 'test') {
            $aanmelding->betaald_bedrag = $betaald_bedrag;

            // AANMELDING_BEDRAG UPDATEN NA KORTING
            $data = array(
                'aanmelding_betaald_bedrag' => $betaald_bedrag,
            );

            $query = $this->aanmeldingen_model->updateAanmeldingByID($aanmelding->aanmelding_ID, $data);

            $this->data['aanmelding'] = $aanmelding;
        }

		$this->data['producten'] = $producten;
        if($this->session->userdata('gebruiker_rechten') != 'test') {
            $this->data['aanmelding'] = $aanmelding;
        }

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'aanmelden_aangemeld';
		$this->load->view('template', $pagina);
	}

    public function toevoegen_mailchimp($aanmelding){
	    if($aanmelding->aanmelding_type == "stemtest") {
	        $type = "Ja";
        } else {
	        $type = "";
        }

        // MailChimp API credentials
        $apiKey = 'd64e65814f08ffb01a43beced5404ff6-us7';
        $listID = '76cbccab02';

        $memberID = md5(strtolower($aanmelding->gebruiker_emailadres));
        $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $memberID;

        if(!empty($aanmelding->workshop_titel)) {
            $workshop_titel = $aanmelding->workshop_titel;
        } else if(!empty($aanmelding->kennismakingsworkshop_titel)) {
            $workshop_titel = $aanmelding->kennismakingsworkshop_titel;
        } else {
            $workshop_titel = "";
        }

        $json = json_encode([
            'email_address' => $aanmelding->gebruiker_emailadres,
            'status'        => 'subscribed',
            'merge_fields'  => [
                'EMAIL'     => $aanmelding->gebruiker_emailadres,
                'NAME'     => $aanmelding->gebruiker_naam,
                'WORKSHOP' => $workshop_titel,
                'SOORT'  => "ingeschreven",
                'DATUM'  => '',
                'STEMTEST' => $type
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

	////////////////////////
	// IDEAL STATUSUPDATE //
	////////////////////////

	/*
	public function statusupdate()
	{
		// Controleren of iDEAL waardes heeft gepost

		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			// Waardes ophalen

			$AMOUNT 	= $_POST['amount'];
			$NCERROR 	= $_POST['NCERROR'];
			$ORDERID 	= $_POST['orderID'];
			$PAYID 		= $_POST['PAYID'];
			$STATUS 	= $_POST['STATUS'];
			$SHASIGN	= $_POST['SHASIGN'];

			// Sha opbouwen aan de hand van waardes

			$SHAPASS 	= 'MaFl2105He0312WeBe8609';

			$SHASTR		 = 'AMOUNT='.$AMOUNT.$SHAPASS;
			$SHASTR		.= 'NCERROR='.$NCERROR.$SHAPASS;
			$SHASTR		.= 'ORDERID='.$ORDERID.$SHAPASS;
			$SHASTR		.= 'PAYID='.$PAYID.$SHAPASS;
			$SHASTR		.= 'STATUS='.$STATUS.$SHAPASS;

			// Controleren of onze sha gelijk is aan die van iDEAL

			if(strtoupper(sha1($SHASTR)) == $SHASIGN)
			{
				// Models laden

				$this->load->model('gebruikers_model');
				$this->load->model('aanmeldingen_model');


				// Aanmelding ophalen

				$ORDERID_explode = explode('-', $ORDERID);
				$ID = $ORDERID_explode[0];

				$aanmelding = $this->aanmeldingen_model->getAanmeldingByID($ID);


				// Controleren of de aanmelding bestaat

				if($aanmelding != null)
				{
					// Aanmelding status updaten

					$data = array(
						'aanmelding_ideal_ID' => $PAYID,
						'aanmelding_ideal_status' => $STATUS,
						'aanmelding_ideal_tijdstip' => date('Y-m-d H:i:s')
					);

					$this->aanmeldingen_model->updateAanmeldingByID($aanmelding->aanmelding_ID, $data);


					// Controleren of de aanmelding is betaald

					if($STATUS == 9)
					{
						// Controleren of de aanmelding nog niet is betaald

						if($aanmelding->aanmelding_betaald_datum == '0000-00-00 00:00:00')
						{
							// Aanmelding betaald datum updaten

							$update_aanmelding = $this->aanmeldingen_model->updateAanmeldingByID($aanmelding->aanmelding_ID, array('aanmelding_betaald_datum' => date('Y-m-d H:i:s')));

							if($update_aanmelding)
							{
								// Controleren of de gebruiker een wachtwoord nodig heeft en of hij deze heeft

								if($aanmelding->aanmelding_type == 'workshop' && $aanmelding->gebruiker_wachtwoord == '')
								{
									// Wachtwoord genereren en gebruiker activeren

									$this->aanmelden_wachtwoord = substr(str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);

									$update_gebruiker = $this->gebruikers_model->updateGebruiker($aanmelding->gebruiker_ID, array('gebruiker_wachtwoord' => sha1($this->aanmelden_wachtwoord), 'gebruiker_status' => 'actief'));

									if($update_gebruiker)
									{
										// E-mail bevestiging van aanmelding incl. wachtwoord

										$this->_email_gebruiker($aanmelding, $this->aanmelden_wachtwoord);
									}
								}
								else
								{
									// Controleren of de gebruiker geactiveerd moet worden

									if($aanmelding->gebruiker_status != 'actief')
									{
										// Gebruiker activeren

										$this->gebruikers_model->updateGebruiker($aanmelding->gebruiker_ID, array('gebruiker_status' => 'actief'));
									}


									// E-mail bevestiging van aanmelding

									$this->_email_gebruiker($aanmelding);
								}


								// Bevestiging van aanmelding sturen naar localhost

								$this->_email_klant($aanmelding, 'iDEAL');
							}
						}
					}
				}
			}
		}
	}
	*/


	///////////////
	// KORTINGEN //
	///////////////

	private function _laad_systeem_kortingen($aanmelding)
	{
		$this->systeem_kortingen = array();

		// Automatische kortingen ophalen die op deze aanmelding van toepassing zijn en opslaan in een variabele

		if($aanmelding->aanmelding_kennismakingsworkshop_korting == 'ja')
		{
			// Toevoegen aan lijst met systeem kortingen

			$deelgenomen_kennismakingsworkshop = $this->aanmeldingen_model->getAanmeldingKennismakingsworkshopByEmail($aanmelding->gebruiker_emailadres);

			$this->systeem_kortingen[] = array(
				'titel' => 'kennismakingsworkshop',
				'bedrag' => $deelgenomen_kennismakingsworkshop->aanmelding_betaald_bedrag
			);
		}
		else if($aanmelding->aanmelding_stemtest_korting == 'ja')
		{
			// Toevoegen aan lijst met systeem kortingen

			$deelgenomen_stemtest = $this->aanmeldingen_model->getAanmeldingStemtestByEmail($aanmelding->gebruiker_emailadres);

			$this->systeem_kortingen[] = array(
				'titel' => 'stemtest',
				'bedrag' => $deelgenomen_stemtest->aanmelding_betaald_bedrag
			);
		}
	}

	//////////////
	// E-MAILEN //
	//////////////

	private function _email_waarschuwing($aanmelding)
	{
		// BEHEERDER E-MAILEN

		 $email_titel = '';
		 $email_bericht = '';

	 	 $email_van_emailadres = 'info@localhost';
		 $email_van_naam = 'localhost';
		 $email_aan_emailadres = 'info@localhost';
		 $email_aan_naam = 'localhost';

		// INHOUD OPSTELLEN

		if($aanmelding->aanmelding_type == 'kennismakingsworkshop')
		{
			$email_titel = 'Maximum aantal aanmeldingen bereikt voor de kennismakingsworkshop op ' . date('d-m-Y', strtotime($aanmelding->kennismakingsworkshop_datum));
			$email_bericht = '
					<h1>'.$email_titel.'</h1>
					<p>Ga naar het <a href="https://localhost" title="Ga naar het CMS" target="_blank">CMS</a> om de kennismakingsworkshop te bekijken en eventueel het aantal deelnemers te verhogen.</p>';
		}

		// E-MAIL

		$email = array(
			'html' => $email_bericht,
			'subject' => $email_titel,
			'from_email' => $email_van_emailadres,
			'from_name' => $email_van_naam,
			'to' => array(
				array(
					'email' => $email_aan_emailadres,
					'name' => $email_aan_naam,
					'type' => 'to'
				)
			),
			//'bcc_address' => 'mark@flitsend-webdesign.nl',
			'headers' => array('Reply-To' => $email_van_emailadres),
			'track_opens' => true,
			'track_clicks' => true,
			'auto_text' => true
		);

		$this->_email($email);
	}

    private function _email_overzicht($aanmelding, $type)
    {
        // KLANT E-MAILEN

        $email_van_emailadres = 'info@localhost';
        $email_van_naam = 'localhost';
        $email_aan_emailadres = 'deelnameformulier@localhost';
        $email_aan_naam = 'localhost';
        if($aanmelding->aanmelding_type =='workshop') {
            $betaald_bedrag = $aanmelding->workshop_prijs;
        } else if($aanmelding->aanmelding_type =='kennismakingsworkshop') {
            $betaald_bedrag = $aanmelding->kennismakingsworkshop_prijs;
        }

        // BERICHT OPSTELLEN

        if($aanmelding->aanmelding_type == 'workshop')
        {
            $email_titel = 'Aanmelding '.$aanmelding->workshop_titel.' #'.$aanmelding->aanmelding_ID;
            $email_tekst = ucfirst($aanmelding->gebruiker_voornaam).' heeft zich zojuist aangemeld voor de volgende workshop: "'.$aanmelding->workshop_titel.'".';
        }
        else if($aanmelding->aanmelding_type =='kennismakingsworkshop')
        {
            $email_titel = 'Aanmelding '.$aanmelding->aanmelding_type.' '.$aanmelding->kennismakingsworkshop_titel.' #'.$aanmelding->aanmelding_ID;
            $email_tekst = ucfirst($aanmelding->gebruiker_voornaam).' heeft zich zojuist aangemeld voor de '.$aanmelding->aanmelding_type.' van de volgende kennismakingsworkshop: "'.$aanmelding->kennismakingsworkshop_titel.'".<br />Ga naar het CMS om deze te <a href="https://localhost" title="Aanmelding bekijken">bekijken</a>';
        }
        else
        {
            $betaald_bedrag = $aanmelding->workshop_stemtest_prijs;
            $email_titel = 'Aanmelding '.$aanmelding->aanmelding_type.' '.$aanmelding->workshop_titel.' #'.$aanmelding->aanmelding_ID;
            $email_tekst = ucfirst($aanmelding->gebruiker_voornaam).' heeft zich zojuist aangemeld voor de '.$aanmelding->aanmelding_type.' van de volgende workshop: "'.$aanmelding->workshop_titel.'".<br />Ga naar het CMS en <a href="https://localhost" title="Plan een afspraak">plan een afspraak</a>';
        }

        $email_bericht = '
			<h1>'.$email_titel.'</h1>
			<p>'.$email_tekst.'</p>
			<table cellpadding="10" cellspacing="0" width="100%" border="1">
				<tr>
					<td>Aanmelding / Order</td>
					<td>'.$aanmelding->aanmelding_ID.'</td>
				</tr>
				<tr>
					<td>Bedrijfsnaam</td>
					<td>'.$aanmelding->gebruiker_bedrijfsnaam.'</td>
				</tr>
				<tr>
					<td>Naam</td>
					<td>'.$aanmelding->gebruiker_voornaam.' '.$aanmelding->gebruiker_tussenvoegsel.' '.$aanmelding->gebruiker_achternaam.'</td>
				</tr>
				<tr>
					<td>Geslacht</td>
					<td>'.ucfirst($aanmelding->gebruiker_geslacht).'</td>
				</tr>
				<tr>
					<td>Geboortedatum</td>
					<td>'.date('d/m/Y', strtotime($aanmelding->gebruiker_geboortedatum)).'</td>
				</tr>
				<tr>
					<td>Adres</td>
					<td>'.$aanmelding->gebruiker_adres.'</td>
				</tr>
				<tr>
					<td>Postcode</td>
					<td>'.$aanmelding->gebruiker_postcode.'</td>
				</tr>
				<tr>
					<td>Plaats</td>
					<td>'.$aanmelding->gebruiker_plaats.'</td>
				</tr>
				<tr>
					<td>Telefoonnummer</td>
					<td>'.$aanmelding->gebruiker_telefoonnummer.'</td>
				</tr>
				<tr>
					<td>Mobiel</td>
					<td>'.$aanmelding->gebruiker_mobiel.'</td>
				</tr>
				<tr>
					<td>E-mailadres</td>
					<td>'.$aanmelding->gebruiker_emailadres.'</td>
				</tr>
				<tr>
					<td>Bevestigd door</td>
					<td>'.$type.'</td>
				</tr>';

		if(!empty($aanmelding->groep_naam) && !empty($aanmelding->groep_startdatum)) {
			$email_bericht .= '<tr>
					<td>Groep</td>
					<td>'.$aanmelding->groep_naam .'</td>
				</tr>
				<tr>
					<td>Groep startdatum</td>
					<td>'.$aanmelding->groep_startdatum.'</td>
				</tr>';
		}

        $email_bericht .= '</table>';

        $email_bericht .='<h3>Betalingsoverzicht</h3>';
        $email_bericht .='<table cellpadding="10" cellspacing="0" width="100%" border="1">';

        $this->load->model('bestellingen_model');
        $bestelling = $this->bestellingen_model->getBestellingByAanmeldingID($aanmelding->aanmelding_ID);

        $workshop_korting = $this->session->userdata('workshop_korting');
		$systeem_kortingen = $this->session->userdata('systeem_kortingen');
		$annuleringsverzekering = $this->session->userdata('annuleringsverzekering');
		$annuleringsverzekering_actief = $this->session->userdata('annuleringsverzekering_actief');
        $betaal_methode = $this->session->userdata('betaal_methode');

        $email_bericht .= '<tr>';
        $email_bericht .= '<td>'.ucfirst($aanmelding->aanmelding_type).'</td>';
        $email_bericht .= '<td align="right">&euro; ' . money_format('%.2n' , $betaald_bedrag) . '</td>';
        $email_bericht .= '</tr>';

        if(!empty($betaal_methode)) {
            $betaald_bedrag = $betaald_bedrag + $betaal_methode;

            $email_bericht .= '<tr>';
            $email_bericht .= '<td>Betaalmethode toeslag</td>';
            $email_bericht .= '<td align="right">&euro; '. money_format('%.2n' , $betaal_methode).'</td>';
            $email_bericht .= '</tr>';
        }

        if($bestelling != null) {
            // PRODUCTEN OPHALEN

            $producten = $this->bestellingen_model->getProductenByBestellingID($bestelling->bestelling_ID);

            if(!empty($producten)) {
                foreach ($producten as $product) {
                    $betaald_bedrag = $betaald_bedrag + $product->product_prijs;
                }
            }

            foreach($producten as $product)
            {
                $email_bericht .= '<tr>';
                $email_bericht .= '<td>'.$product->product_naam.'</td>';
                $email_bericht .= '<td align="right">&euro; '. money_format('%.2n' , $product->product_prijs).'</td>';
                $email_bericht .= '</tr>';
            }
        }

        if(!empty($workshop_korting)) {
            $betaald_bedrag = $betaald_bedrag - $workshop_korting;

            $email_bericht .= '<tr>';
            $email_bericht .= '<td>Kortingscode</td>';
            $email_bericht .= '<td align="right">- &euro; ' . money_format('%.2n' , $workshop_korting) . '</td>';
            $email_bericht .= '</tr>';
        }

        if(!empty($systeem_kortingen)) {
            foreach ($systeem_kortingen as $korting) {
                $betaald_bedrag = $betaald_bedrag - $korting['bedrag'];
            }
        }

        if(!empty($systeem_kortingen)) {
            foreach($systeem_kortingen as $korting) {
                $email_bericht .= '<tr><td>Korting (' . $korting['titel'] . ')</td><td class="prijs" align="right">- &euro;' . money_format('%.2n', $korting['bedrag']) . '</td></tr>';
            }
		}

		if(!empty($annuleringsverzekering_actief)) {
			if($annuleringsverzekering_actief == "Ja") {
				if($aanmelding->aanmelding_type =='workshop') {
					$workshop_prijs = $aanmelding->workshop_prijs;
				} else if($aanmelding->aanmelding_type =='kennismakingsworkshop') {
					$workshop_prijs = $aanmelding->kennismakingsworkshop_prijs;
				}

				$annulering_prijs = ($workshop_prijs / 100) * $annuleringsverzekering->annulering_percentage;
				$email_bericht .= '<tr><td>Annuleringsverzekering</td><td class="prijs" align="right"> &euro;' .
				money_format('%.2n', $annulering_prijs) . '</td></tr>';
				$betaald_bedrag = $betaald_bedrag + $annulering_prijs;
			}
        }

        $email_bericht .= '<tr>';
        $email_bericht .= '<td>Totaal</td>';
        $email_bericht .= '<td align="right">&euro; '.  money_format('%.2n' , $betaald_bedrag) .'</td>';
        $email_bericht .= '</tr>';

        $email_bericht .= '</table>';

        // E-MAIL

        $email = array(
            'html' => $email_bericht,
            'subject' => $email_titel,
            'from_email' => $email_van_emailadres,
            'from_name' => $email_van_naam,
            'to' => array(
                array(
                    'email' => $email_aan_emailadres,
                    'name' => $email_aan_naam,
                    'type' => 'to'
                )
            ),
            //'bcc_address' => 'mark@flitsend-webdesign.nl',
            'headers' => array('Reply-To' => $email_van_emailadres),
            'track_opens' => true,
            'track_clicks' => true,
            'auto_text' => true
        );

        $this->_email($email);
    }

    private function _mail_welkomstmail($aanmelding) {
		if($aanmelding->aanmelding_type == "workshop") {
				$this->load->model('media_model');
				$this->load->model('aanmeldingen_model');
				$this->load->model('berichten_model');

			if(!empty($aanmelding->workshop_welkomstmail)) {
				$media = array();
				$welkomstmail_media = $this->media_model->getMediaByContentID('welkomstmail', $aanmelding->workshop_ID);
				$item_tekst = $this->ReplaceTags($aanmelding->gebruiker_ID, $aanmelding->workshop_welkomstmail, $aanmelding->groep_ID);

				/////////////////////
				// BERICHT OPSLAAN //
				/////////////////////
				$bericht = array(
					'bericht_onderwerp' => $aanmelding->workshop_titel,
					'bericht_tekst' => $item_tekst,
					'bericht_datum' => date('Y-m-d H:i:s'),
					'bericht_afzender_ID' => 1610,
					'bericht_afzender_type' => 'deelnemer',
					'bericht_ontvanger_ID' => $aanmelding->gebruiker_ID,
					'bericht_no_reply' => 1
				);

				$verzonden = $this->berichten_model->verzendBericht($bericht);

				if (!empty($welkomstmail_media)) {
					foreach($welkomstmail_media as $item) {
						$connectie = array('media_ID' => $item->media_ID, 'media_positie' => $item->media_positie, 'content_type' => 'bericht', 'content_ID' => $verzonden);
						$this->media_model->insertMediaConnectie($connectie);
						}
							}

				$this->_verstuur_email($aanmelding->workshop_titel, 1610, $aanmelding->gebruiker_ID);
			}
		}
	}

	private function _verstuur_email($onderwerp, $afzender_ID, $ontvanger_ID)
    {
        // Gegevens van afzender en ontvanger ophalen

        $this->load->model('gebruikers_model');
        $afzender	= $this->gebruikers_model->getGebruikerByID($afzender_ID);
        $ontvanger 	= $this->gebruikers_model->getGebruikerByID($ontvanger_ID);


        // Afzender en ontvanger initialiseren

        $email_van_emailadres = 'info@localhost';
        $email_van_naam = 'localhost';
        $email_aan_emailadres = $ontvanger->gebruiker_emailadres;
        $email_aan_naam = $ontvanger->gebruiker_naam;


        // Ander e-mailadres instellen voor berichten aan de docent

        if($ontvanger->gebruiker_ID == 1610) $email_aan_emailadres = 'berichten@localhost';


        // E-mail bericht opstellen

        $email_bericht = '
			<h1>'.$onderwerp.'</h1>
			<p>Beste '.$ontvanger->gebruiker_voornaam.',</p>
			<p>Je hebt een nieuw bericht ontvangen van '.$afzender->gebruiker_naam.'. Ga naar de <a href="https://localhost" title="Bezoek de website van localhost" target="_blank">Cursistenmodule</a> om het bericht te lezen en eventueel te beantwoorden.</p>
			<p>Met vriendelijke groet,</p>
			<p>localhost</p>';


        // E-mail instellingen

                $email = array(
            'html' => $email_bericht,
            'subject' => $onderwerp,
            'from_email' => $email_van_emailadres,
            'from_name' => $email_van_naam,
                    'to' => array(
                        array(
                        'email' => $email_aan_emailadres,
                        'name' => $email_aan_naam,
                            'type' => 'to'
                        )
                    ),
            'headers' => array('Reply-To' => $email_van_emailadres),
                    'track_opens' => true,
                    'track_clicks' => true,
            'auto_text' => true
                );

        /////////////////////////////
        // E-MAIL UPDATE VERZENDEN //
        /////////////////////////////

        $this->load->helper('mandrill');
	 	$mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
       	// $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
        $feedback = $mandrill->messages->send($email);

        if($feedback[0]['status'] == 'sent')
        {
            // echo 'E-mail verzonden';
            }
        else
        {
            // echo 'Er kon geen e-mail worden verzonden';
        }
	}

	private function _mail_groep_vol($aanmelding) {
		$item_tekst = "Groep " . $aanmelding->groep_naam . " is vol.";

		$email = array(
			'html' => $item_tekst,
			'subject' => "Groep vol: ". $aanmelding->groep_titel,
			'from_email' => 'info@localhost',
			'from_name' => 'info@localhost',
			'to' => array(
				array(
					'email' => "deelnameformulier@localhost",
					'name' => "info@localhost",
					'type' => 'to'
				)
			),
			'bcc_address' => '',
			'headers' => array('Reply-To' => 'info@localhost'),
			'track_opens' => true,
			'track_clicks' => true,
			'auto_text' => true,
		);

		$this->_email($email);
    }


	private function _email_gebruiker($aanmelding, $wachtwoord = null)
	{
		$this->_laad_systeem_kortingen($aanmelding);

		// GEBRUIKER E-MAILEN

		$email_titel = '';
		$email_tekst = '';
		$email_bericht = '';

		$email_van_emailadres = 'info@localhost';
		$email_van_naam = 'localhost';
		$email_aan_emailadres = $aanmelding->gebruiker_emailadres;
		$email_aan_naam = $aanmelding->gebruiker_naam;


		// BERICHT OPSTELLEN

		if($aanmelding->aanmelding_type == 'workshop')
		{
			$email_titel = 'Aanmelding '.$aanmelding->workshop_titel;
			if($wachtwoord == null) $email_tekst = '<p>Je hebt je zojuist aangemeld voor de volgende workshop: "'.$aanmelding->workshop_titel.'".<br /><a href="https://www.localhost/inschrijfvoorwaarden" target="_blank">Hier</a> vind je de inschrijfvoorwaarden die van toepassing zijn op jouw inschrijving/bestelling.</p><p>Wij wensen je alvast veel plezier met je deelname.</p>';
			else $email_tekst = '<p>Je hebt je zojuist aangemeld voor de volgende workshop: "'.$aanmelding->workshop_titel.'".<br /><a href="https://www.localhost/inschrijfvoorwaarden" target="_blank">Hier</a> vind je de inschrijfvoorwaarden die van toepassing zijn op jouw inschrijving/bestelling.</p><p>Je kunt inloggen op de <a href="https://localhost" title="Bezoek de website van localhost" target="_blank">Cursistenmodule</a> met je e-mailadres "'.$aanmelding->gebruiker_emailadres.'" en wachtwoord "'.$wachtwoord.'".</p><p>Wij wensen je alvast veel plezier met je deelname.</p>';
		}
		else if($aanmelding->aanmelding_type == 'kennismakingsworkshop')
		{
			$email_titel = 'Aanmelding '.$aanmelding->kennismakingsworkshop_titel;
			if($wachtwoord == null) $email_tekst = '<p>Je hebt je zojuist aangemeld voor de volgende kennismakingsworkshop: "'.$aanmelding->kennismakingsworkshop_titel.'".<br /><a href="https://www.localhost/inschrijfvoorwaarden" target="_blank">Hier</a> vind je de inschrijfvoorwaarden die van toepassing zijn op jouw inschrijving/bestelling.</p><p>Wij wensen je alvast veel plezier met je deelname.</p>';
			else $email_tekst = '<p>Je hebt je zojuist aangemeld voor de volgende kennismakingsworkshop: "'.$aanmelding->kennismakingsworkshop_titel.'".<br /><a href="https://www.localhost/inschrijfvoorwaarden" target="_blank">Hier</a> vind je de inschrijfvoorwaarden die van toepassing zijn op jouw inschrijving/bestelling.</p><p>Je kunt inloggen op de <a href="https://localhost" title="Bezoek de website van localhost" target="_blank">Cursistenmodule</a> met je e-mailadres "'.$aanmelding->gebruiker_emailadres.'" en wachtwoord "'.$wachtwoord.'".</p><p>Wij wensen je alvast veel plezier met je deelname.</p>';
		}
		else
		{
			$email_titel = 'Aanmelding '.$aanmelding->aanmelding_type.' '.$aanmelding->workshop_titel;
			$email_tekst = '<p>Je hebt je zojuist aangemeld voor de '.$aanmelding->aanmelding_type.' van de volgende workshop: "'.$aanmelding->workshop_titel.'". Binnen twee werkdagen zullen wij contact met je opnemen voor het plannen van een afspraak.<br /><a href="https://www.localhost/inschrijfvoorwaarden" target="_blank">Hier</a> vind je de inschrijfvoorwaarden die van toepassing zijn op jouw inschrijving/bestelling.</p>';
		}

		$email_bericht = '
			<h1>'.$email_titel.'</h1>
			<p>Beste '.$aanmelding->gebruiker_voornaam.',</p>
			'.$email_tekst.'
			<p>Met vriendelijke groet,</p>
			<p>localhost</p>';


		// E-MAIL

		$email = array(
			'html' => $email_bericht,
			'subject' => $email_titel,
		    'from_email' => $email_van_emailadres,
		    'from_name' => $email_van_naam,
		    'to' => array(
		    	array(
		    		'email' => $email_aan_emailadres,
		    		'name' => $email_aan_naam,
		    		'type' => 'to'
				)
			),
			//'bcc_address' => 'mark@flitsend-webdesign.nl',
		    'headers' => array('Reply-To' => $email_van_emailadres),
		    'track_opens' => true,
		    'track_clicks' => true,
		    'auto_text' => true
		);

		$this->_email($email);
	}

	private function _email($email)
	{
		// E-MAIL VERZENDEN VIA MANDRILL

		$this->load->helper('mandrill');
    //    $mandrill = new  Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
		$mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw'); // prod

		$feedback = $mandrill->messages->send($email);

		if($feedback[0]['status'] == 'sent')
		{
			// echo 'E-mail verzonden';
		}
		else
		{
			// echo 'Er kon geen e-mail worden verzonden';
		}
	}

	public function betaal_optie()
    {

        $this->load->model('betaalmethodes_model');
        $betaal_methodes = $this->betaalmethodes_model->getMethodes();

        // PAYNL INSTELLINGEN
        if(isset($_POST['payment_option'])) {
            $paymentOptie = $_POST['payment_option'];

            $userdata = $this->session->all_userdata();

            if ($userdata['aanmelden_voor'] == 'kennismakingsworkshop') {
                $normalReturnUrl = base_url('aanmelden/aangemeld/' . $userdata['aanmelden_ID'] . '/' . $userdata['aanmelden_voor'] . '/' . $userdata['aanmelden_kennismakingsworkshop_ID'] . '/' . $userdata['aanmelden_code_nieuw']);
            } else {
                $link = $this->session->userdata('link');
                if (!empty($link)) {
                    $normalReturnUrl = base_url('aanmelden/aangemeld/' . $userdata['aanmelden_ID'] . '/' . $userdata['aanmelden_voor'] . '/' . $userdata['aanmelden_workshop_ID'] . '/' . $userdata['aanmelden_code_nieuw'] . '/' . $link->link_code);
                } else {
                    $normalReturnUrl = base_url('aanmelden/aangemeld/' . $userdata['aanmelden_ID'] . '/' . $userdata['aanmelden_voor'] . '/' . $userdata['aanmelden_workshop_ID'] . '/' . $userdata['aanmelden_code_nieuw']);
                }
            }

            if($this->session->userdata('gebruiker_rechten') != 'test') {

            foreach ($betaal_methodes as $methode) {
                if ($methode->pay_ID == $paymentOptie) {
                    $procent = ($methode->percentage / 100) * $userdata['aanmelden_bedrag'];
                    $userdata['aanmelden_bedrag'] = $userdata['aanmelden_bedrag'] + $procent;

                    $this->session->set_userdata('betaal_methode', $procent);
                }
            }

            if (empty($userdata['aanmelden_url'])) {
                $userdata['aanmelden_url'] = $userdata['aanmelden_voor'];
            }

            $vandaag = new DateTime();

            if (preg_match('/([^\d]+)\s?(.+)/i', $userdata['aanmelden_adres'], $result)) {
                $straatNaam = $result[1];
                $huisNummer = $result[2];
            }

            $transaction = Paynl\Transaction::start(array(
                'paymentMethod' => $paymentOptie,
                'amount' => $userdata['aanmelden_bedrag'],
                'returnUrl' => $normalReturnUrl,
                'description' => "#" . $userdata['aanmelden_ID'] . ": " . $userdata['aanmelden_url'],
                'language' => "NL",
                'ipAddress' => \Paynl\Helper::getIp(),
                'enduser' => array(
                    'initials' => substr($userdata['aanmelden_voornaam'], 0, 1),
                    'lastName' => $userdata['aanmelden_tussenvoegsel'] ? $userdata['aanmelden_tussenvoegsel'] . ' ' . $userdata['aanmelden_achternaam'] : $userdata['aanmelden_achternaam'],
                    'gender' => substr($userdata['aanmelden_geslacht'], 0, 1),
                    'birthDate' => $userdata['aanmelden_geboortedatum_dag'] . '-' . $userdata['aanmelden_geboortedatum_maand'] . '-' . $userdata['aanmelden_geboortedatum_jaar'],
                    'phoneNumber' => $userdata['aanmelden_telefoon'],
                    'emailAddress' => $userdata['aanmelden_emailadres'],
                    'address' => array(
                        'streetName' => $straatNaam,
                        'zipCode' => $userdata['aanmelden_postcode'],
                        'streetNumber' => $huisNummer,
                        'city' => $userdata['aanmelden_plaats'],
                        'country' => 'NL',
                    ),

                    'invoiceAddress' => array(
                        'initials' => substr($userdata['aanmelden_voornaam'], 0, 1),
                        'lastName' => $userdata['aanmelden_achternaam'],
                        'streetName' => $straatNaam,
                        'streetNumber' => $huisNummer,
                        'zipCode' => $userdata['aanmelden_postcode'],
                        'city' => $userdata['aanmelden_plaats'],
                        'country' => 'NL',
                    ),
                ),
                'saleData' => array(
                    'orderData' => $userdata['aanmelden_items'],
                    "deliveryDate" => $vandaag->format('d-m-Y'),
                    "invoiceDate" => $vandaag->format('d-m-Y')
                ),
            ));

            $this->session->unset_userdata('aanmelden_bedrag');
            $this->session->unset_userdata('aanmelden_workshop_ID');
            $this->session->unset_userdata('aanmelden_kennismakingsworkshop_ID');
            $this->session->unset_userdata('aanmelden_items');

            redirect($transaction->getRedirectUrl());
        } else {
                redirect($normalReturnUrl);
            }
        }
    }

    function ReplaceTags($gebruiker_ID, $email_tekst, $groep_ID = null) {
        $this->load->model('gebruikers_model');
        $this->load->model('groepen_model');
        $this->load->model('workshops_model');

        $deelnemer = $this->gebruikers_model->getGebruikerByID($gebruiker_ID);

        $email_tekst = str_replace('[voornaam]', $deelnemer->gebruiker_voornaam, $email_tekst);
        $email_tekst = str_replace('[achternaam]', $deelnemer->gebruiker_achternaam, $email_tekst);

        if($groep_ID) {
            $start_datum  = $this->groepen_model->getStartdatumByGroepID($groep_ID);
            $workshop = $this->workshops_model->getWorkshopByGroepID($groep_ID);

            $start_datum = new DateTime($start_datum);

            $email_tekst = str_replace('[workshop]', $workshop[0]->workshop_titel, $email_tekst);
            $email_tekst = str_replace('[startdatum]', $start_datum->format('d-m-Y'), $email_tekst);
        }

        return  $email_tekst;
    }
}
