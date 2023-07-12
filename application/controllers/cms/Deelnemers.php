<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deelnemers extends CI_Controller
{
	private $data = array();

	public function __construct()
	{
		parent::__construct();

		// Rechten controleren en aantal nieuwe items ophalen

		$this->load->library('algemeen');
		$this->load->library('utilities');
		$this->algemeen->cms();
		if($this->session->userdata('beheerder_rechten') == 'contentmanager') redirect('cms/rechten');
	}



	/* ============= */
	/* = OVERZICHT = */
	/* ============= */

	public function index()
	{
		$this->load->model('gebruikers_model');
		$this->load->model('workshops_model');
		$this->load->model('groepen_model');
		$this->load->model('media_model');
		$this->load->model('lessen_model');


		// Filter initialiseren

		$filter_status = null;
		$filter_ingelogd = null;
		$filter_geslacht = null;
		$filter_workshop = null;
		$filter_huiswerk = null;
		$filter_groep = null;
		$filter_beoordeling = null;
		$filter_archief = '0';


		// Workshops ophalen

		$workshops = $this->workshops_model->getWorkshops();
		$this->data['workshops'] = $workshops;

        $groepen = $this->groepen_model->getAlleGroepen();
        $this->data['groepen'] = $groepen;

		// Controleren of de deelnemers gefiltert moeten worden

		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if(isset($_POST['filter_status'])) 			$filter_status 			= $_POST['filter_status'];
			if(isset($_POST['filter_ingelogd'])) 		$filter_ingelogd 		= $_POST['filter_ingelogd'];
			if(isset($_POST['filter_geslacht'])) 		$filter_geslacht 		= $_POST['filter_geslacht'];
			if(isset($_POST['filter_workshop'])) 		$filter_workshop 		= $_POST['filter_workshop'];
			if(isset($_POST['filter_huiswerk']))	 	$filter_huiswerk 	 	= $_POST['filter_huiswerk'];
			if(isset($_POST['filter_groep']))	 	    $filter_groep 	 	    = $_POST['filter_groep'];
			if(isset($_POST['filter_archief']))	 	    $filter_archief 	 	= $_POST['filter_archief'];
			if(isset($_POST['filter_beoordeling']))		$filter_beoordeling		= $_POST['filter_beoordeling'];

            if ($filter_archief == 1) {
                $groepen = $this->groepen_model-> getGroepenArchief();
                $this->data['groepen'] = $groepen;
            } else {
                $groepen = $this->groepen_model->getAlleGroepen();
                $this->data['groepen'] = $groepen;
            }

			if($filter_workshop != null)
			{
				$this->data['workshop'] = $this->workshops_model->getWorkshopByID($filter_workshop);

                if ($filter_archief == 1) {
                    $this->data['groepen'] = $this->groepen_model->getGroepenArchiefByWorkshop_ID($filter_workshop);
                } else {
                    $this->data['groepen'] = $this->groepen_model->getGroepenByWorkshopID($filter_workshop);
                }
			}
		}

		$deelnemers = $this->gebruikers_model->filterDeelnemers($filter_status, $filter_ingelogd, $filter_geslacht, $filter_workshop, $filter_groep, $kennismakingsworkshop = null);

        if ($filter_archief == 1) {
            $groepen_archief_deelnemers = $this->gebruikers_model->getDeelnemersArachiefGroep();
            $groepen_deelnemers = $this->gebruikers_model->getDeelnemersGroep();

            $dubbele = array();

            if (!empty($groepen_archief_deelnemers) && !empty($groepen_deelnemers)) {
                foreach ($groepen_archief_deelnemers as $deelnemer) {
                    foreach ($groepen_deelnemers as $groepen_deelnemer) {
                        if ($deelnemer->gebruiker_ID == $groepen_deelnemer->gebruiker_ID) {
                            array_push($dubbele, $deelnemer->gebruiker_ID);
                        }
                    }
                }
            }

            $temp_array = array();

            if (!empty($groepen_archief_deelnemers)) {
                foreach ($groepen_archief_deelnemers as $deelnemer) {
                    $dubbel_bool = false;

                    if (!empty($dubble)) {
                        foreach ($dubbele as $dubbel_ID) {
                            if ($deelnemer->gebruiker_ID == $dubbel_ID) {
                                $dubbel_bool = true;
                            }
                        }
                    }

                    if ($dubbel_bool == false) {
                        array_push($temp_array, $deelnemer);
                    }
                }
            }

            if (!empty($deelnemers)) {
                foreach ($deelnemers as $key => $deelnemer) {
                    $bool = false;

                    if(!empty($temp_array)) {
                        foreach ($temp_array as $tmp) {
                            if ($tmp->gebruiker_ID == $deelnemer->gebruiker_ID) {
                                $bool = true;
                            }
                        }
                    }

                    if ($bool == false) {
                        unset($deelnemers[$key]);
                    }
                }
            }
        }

		if (!empty($deelnemers)) {
			foreach($deelnemers as $deelnemer) {
				$deelnemer->profiel_foto = $this->media_model->getMediaProfielByGebruikerID($deelnemer->gebruiker_ID);
			}
		}

		$aantal_deelnemers = $this->gebruikers_model->getDeelnemersAantal();


		// Deelnemers filteren op extra filters

		if($filter_beoordeling != null) {
			foreach($deelnemers as $key => $deelnemer) {
				if($filter_workshop == null) {
					$AvgBeoordeling = $this->lessen_model->getAVGGebruikerBeoordelingOveral($deelnemer->gebruiker_ID);
				} else {
					$AvgBeoordeling = $this->lessen_model->getAVGGebruikerBeoordeling($deelnemer->gebruiker_ID, $filter_workshop);
				}

				if($AvgBeoordeling[0]->gebruiker_beoordeling != $filter_beoordeling) {
					unset($deelnemers[$key]);
				}
			}
		}

		if($filter_huiswerk != null)
		{
			// Controleren of ze huiswerk moeten insturen voor de workshop
			// Controleren of ze al een les hebben gehad waarbij huiswerk moeten worden ingestuurd
			//
		}

        $verlopen_deelnemers = $this->aanmeldingen_model->getAanmeldingenVerlopen();

		if(!empty($verlopen_deelnemers)) {
		    if(!empty($deelnemers)) {
                foreach ($verlopen_deelnemers as $verlopen_deelnemer) {
                    foreach($deelnemers as $key => $deelnemer) {
                        if ($verlopen_deelnemer->gebruiker_ID == $deelnemer->gebruiker_ID) {
                            unset($deelnemers[$key]);
                        }
                    }
                }
            }
            $aantal_deelnemers = $aantal_deelnemers - count($verlopen_deelnemers);
        }

		// PAGINA TONEN

		$this->data['deelnemers'] 			= $deelnemers;
		$this->data['aantal_deelnemers'] 	= $aantal_deelnemers;

		$this->data['filter_status'] 		= $filter_status;
		$this->data['filter_ingelogd'] 		= $filter_ingelogd;
		$this->data['filter_geslacht']	 	= $filter_geslacht;
		$this->data['filter_workshop'] 		= $filter_workshop;
		$this->data['filter_huiswerk'] 		= $filter_huiswerk;
		$this->data['filter_groep'] 		= $filter_groep;
		$this->data['filter_archief'] 		= $filter_archief;
		$this->data['filter_beoordeling']	= $filter_beoordeling;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/deelnemers';
		$this->load->view('cms/template', $pagina);
	}



	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */

	public function detail($item_ID = null)
	{
		$this->load->model('gebruikers_model');
		$this->load->model('aanmeldingen_model');
		$this->load->model('bestellingen_model');
		$this->load->model('huiswerk_model');
		$this->load->model('lessen_model');
		$this->load->model('media_model');

		if($item_ID == null) redirect('cms/deelnemers');

		// Gegevens gebruiker ophalen

		$deelnemer = $this->gebruikers_model->getGebruikerByID($item_ID);
		if($deelnemer == null) redirect('cms/deelnemers');

		$deelnemer->profiel_foto = $this->media_model->getMediaProfielByGebruikerID($deelnemer->gebruiker_ID);
		// Aanmeldingen ophalen

		$afspraken = $this->aanmeldingen_model->getAanmeldingenAfsprakenByGebruikerID($deelnemer->gebruiker_ID);
		$aanmeldingen = $this->aanmeldingen_model->getAanmeldingenWorkshopsByGebruikerID($deelnemer->gebruiker_ID);

		// Beoordelingen ophalen
        if(!empty($aanmeldingen)) {
            foreach($aanmeldingen as $aanmelding) {
                $beoordeling = $this->lessen_model->getAVGGebruikerBeoordeling($item_ID, $aanmelding->workshop_ID);

                if($beoordeling) {
                    $aanmelding->gebruiker_beoordeling = $beoordeling[0]->gebruiker_beoordeling;
                } else {
                    $aanmelding->gebruiker_beoordeling = 0;
                }
            }
		}

		// Beoordelingen van de gebruiker ophalen
		$beoordelingen = $this->lessen_model->getBeoordelingenCompleetByGebruikerID($item_ID);

		// Bestellingen ophalen

		$bestellingen_met_aanmelding = $this->bestellingen_model->getBestellingenByGebruikerID($deelnemer->gebruiker_ID);
		$bestellingen_zonder_aanmelding = $this->bestellingen_model->getBestellingenLosByGebruikerID($deelnemer->gebruiker_ID);

		// Bestellingen met en zonder aanmelding samenvoegen

		$bestellingen = array_merge($bestellingen_met_aanmelding, $bestellingen_zonder_aanmelding);

		// Bestellingen sorteren

		$bestellingen = Utilities::array_orderby($bestellingen, 'bestelling_ID', SORT_DESC);

		// Resultaten ophalen

		$resultaten = $this->huiswerk_model->getResultatenByGebruikerID($deelnemer->gebruiker_ID);
		//get the product name 
		$productName = [];
		foreach ($bestellingen as $order_id) {
			$producten_naam = $this->bestellingen_model->getProductenByBestellingID($order_id->bestelling_ID);
			if(!empty($producten_naam)){
				array_push($productName ,$producten_naam);
				
			}
		}
		// PAGINA TONEN
		$this->data['deelnemer'] = $deelnemer;
		$this->data['afspraken'] = $afspraken;
		$this->data['aanmeldingen'] = $aanmeldingen;
		$this->data['bestellingen'] = $bestellingen;
		$this->data['resultaten'] = $resultaten;
		$this->data['beoordelingen'] = $beoordelingen;
		$this->data['producten_naam'] = $productName;
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/deelnemers_deelnemer';
		$this->load->view('cms/template', $pagina);
	}



	/* ================ */
	/* = INLOGGEN ALS = */
	/* ================ */

	public function inloggen($item_ID = null)
	{
		if($item_ID == null) redirect('cms/deelnemers');

		$this->load->model('gebruikers_model');
		$gebruiker = $this->gebruikers_model->getGebruikerByID($item_ID);
		if($gebruiker == null) redirect('cms/deelnemers');

		$userdata = array(
			'gebruiker_ID' => $gebruiker->gebruiker_ID,
			'gebruiker_rechten' => $gebruiker->gebruiker_rechten,
			'gebruiker_voornaam' => $gebruiker->gebruiker_voornaam,
			'gebruiker_naam' => $gebruiker->gebruiker_voornaam.' '.$gebruiker->gebruiker_tussenvoegsel.' '.$gebruiker->gebruiker_achternaam,
			'gebruiker_profiel_foto' => $gebruiker->gebruiker_profiel_foto
		);

		$this->session->set_userdata($userdata);

		redirect('cursistenmodule');
	}

	public function uitloggen($item_ID = null)
	{
		$rechten = $this->session->userdata('gebruiker_rechten');

		$this->session->unset_userdata('gebruiker_ID');
		$this->session->unset_userdata('gebruiker_rechten');
		$this->session->unset_userdata('gebruiker_voornaam');
		$this->session->unset_userdata('gebruiker_naam');

		$this->session->unset_userdata('workshop_ID');
		$this->session->unset_userdata('workshop_type');
		$this->session->unset_userdata('workshop_startdatum');
		$this->session->unset_userdata('workshop_frequentie');
		$this->session->unset_userdata('groep_ID');

		if($rechten == "dummy") {
			redirect('cms');
		} else {
			redirect('cms/deelnemers/'.$item_ID);
		}
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
		if($item_ID == null) redirect('cms/groepen');
		$this->_toevoegen_wijzigen('wijzigen', $item_ID);
	}

	private function _toevoegen_wijzigen($actie, $item_ID = null)
	{
		$this->load->model('gebruikers_model');

		$item_status					= '';
		$item_markering					= '';
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
		$item_rechten					= 'deelnemer';

		$item_status_feedback						= '';
		$item_markering_feedback					= '';
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



		// FORMULIER VERZONDEN

		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$fouten = 0;

			$item_status					= trim($_POST['item_status']);
			$item_markering					= trim($_POST['item_markering']);
			$item_bedrijfsnaam 				= trim($_POST['item_bedrijfsnaam']);
			$item_voornaam 					= ucfirst(trim($_POST['item_voornaam']));
			$item_tussenvoegsel 			= trim($_POST['item_tussenvoegsel']);
			$item_achternaam 				= ucfirst(trim($_POST['item_achternaam']));
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
			$item_rechten 					= trim($_POST['item_rechten']);

			// Verplicht

			if(!isset($_POST['item_status']))
			{
				$fouten++;
				$item_status_feedback = 'Graag selecteren';
			}

			if(!isset($_POST['item_markering']))
			{
				$fouten++;
				$item_markering_feedback = 'Graag selecteren';
			}

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


			if(!empty($item_geboortedatum_dag) || !empty($item_geboortedatum_maand) || !empty($item_geboortedatum_jaar))
			{
				if(intval($item_geboortedatum_dag) < 1 || intval($item_geboortedatum_dag) > 31)
				{
					$fouten++;
					$item_geboortedatum_feedback = 'Graag een geldige dag invullen';
				}
				elseif(intval($item_geboortedatum_maand) < 1 || intval($item_geboortedatum_maand) > 12)
				{
					$fouten++;
					$item_geboortedatum_feedback = 'Graag een geldige maand invullen';
				}
				elseif(intval($item_geboortedatum_jaar) < (date('Y') - 100) || intval($item_geboortedatum_maand) > date('Y'))
				{
					$fouten++;
					$item_geboortedatum_feedback = 'Graag een geldig jaar invullen';
				}
			}

			if($fouten == 0)
			{
				// TOEVOEGEN / UPDATEN

				$data = array(
					'gebruiker_rechten' => $item_rechten,
					'gebruiker_status' => $item_status,
					'gebruiker_markering' => $item_markering,
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
					$q = $this->gebruikers_model->insertDeelnemer($data);
				}
				else
				{
					$q = $this->gebruikers_model->updateDeelnemer($item_ID, $data);
				}

				if($q)
				{
					if($actie == 'toevoegen')
					{
						// Wachtwoord e-mailen naar deelnemer

						$this->load->helper('mandrill');

						// $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
						$mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');

						$email_titel = 'Aanmelding Cursistenmodule localhost';
						$email_tekst = '<p>Wij hebben je zojuist aangemeld voor de Cursistenmodule van localhost. Je kunt inloggen met je e-mailadres "'.$item_emailadres.'" en wachtwoord "'.$item_wachtwoord.'". Op dit moment is je profiel nog niet gekoppeld aan een workshop, dit gebeurt zo spoedig mogelijk.</p><p>Controleer na het inloggen s.v.p. direct de gegevens die wij voor je hebben ingevuld op je profiel. Wijzig je gegevens of vul deze indien nodig aan.</p>';

						$email_bericht = '
							<h1>'.$email_titel.'</h1>
							<p>Beste '.$item_voornaam.',</p>
							'.$email_tekst.'
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
						    		'name' => str_replace('  ', '', $item_voornaam.' '.$item_tussenvoegsel.' '.$item_achternaam),
						    		'type' => 'to'
								)
							),
						    'headers' => array('Reply-To' => 'info@localhost'),
						    'track_opens' => true,
						    'track_clicks' => true,
						    'auto_text' => true
						);

						$mandrill->messages->send($email);

						redirect('cms/deelnemers/aanmelden/'.$q);
					}
					else
					{
						redirect('cms/deelnemers/'.$item_ID);
					}
				}
				else
				{
					echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
				}
			}
		}
		else
		{
			if($actie == 'wijzigen')
			{
				$item = $this->gebruikers_model->getGebruikerByID($item_ID);
				if($item == null) redirect('cms/deelnemers');

				$item_rechten					= $item->gebruiker_rechten;
				$item_status	 				= $item->gebruiker_status;
				$item_markering	 				= $item->gebruiker_markering;
				$item_bedrijfsnaam 				= $item->gebruiker_bedrijfsnaam;
				$item_voornaam 					= $item->gebruiker_voornaam;
				$item_tussenvoegsel 			= $item->gebruiker_tussenvoegsel;
				$item_achternaam 				= $item->gebruiker_achternaam;
				$item_geslacht 					= $item->gebruiker_geslacht;
				$item_geboortedatum 			= explode('-', $item->gebruiker_geboortedatum);
				$item_geboortedatum_dag 		= $item_geboortedatum[2];
				$item_geboortedatum_maand 		= $item_geboortedatum[1];
				$item_geboortedatum_jaar 		= $item_geboortedatum[0];
				$item_adres 					= $item->gebruiker_adres;

				$item_postcode 					= str_replace(' ', '', $item->gebruiker_postcode);

				if(strlen($item_postcode) == 6)
				{
					$item_postcode_cijfers = substr($item_postcode, 0, 4);
					$item_postcode_letters = strtoupper(substr($item_postcode, 4, 2));
				}
				else
				{
					$item_postcode_cijfers = '';
					$item_postcode_letters = '';
				}


				$item_plaats 					= $item->gebruiker_plaats;
				$item_telefoonnummer 			= $item->gebruiker_telefoonnummer;
				$item_mobiel 					= $item->gebruiker_mobiel;
				$item_emailadres 				= $item->gebruiker_emailadres;
				$item_notities 					= $item->gebruiker_notities;
				$item_instelling_anoniem 		= $item->gebruiker_instelling_anoniem;
				$item_instelling_email_updates 	= $item->gebruiker_instelling_email_updates;
			}
			else
			{
				$item_instelling_anoniem 		= 'nee';
				$item_instelling_email_updates 	= 'ja';
			}
		}


		// PAGINA TONEN

		$this->data['actie'] = $actie;

		$this->data['item_ID'] 							= $item_ID;
		$this->data['item_status'] 						= $item_status;
		$this->data['item_markering'] 					= $item_markering;
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
		$this->data['item_rechten']						= $item_rechten;

		$this->data['item_status_feedback'] 					= $item_status_feedback;
		$this->data['item_markering_feedback'] 					= $item_markering_feedback;
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

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/deelnemers_wijzigen';
		$this->load->view('cms/template', $pagina);
	}



	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */

	public function verwijderen($item_ID = null, $bevestiging = null)
	{
        if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'support') { redirect('cms/rechten'); }
		if($item_ID == null) redirect('cms/deelnemers');

		$this->load->model('gebruikers_model');
		$item = $this->gebruikers_model->getGebruikerByID($item_ID);
		if($item == null) redirect('cms/deelnemers');
		$this->data['item'] = $item;


		// ITEM VERWIJDEREN

		if($bevestiging == 'ja')
		{
			$q = $this->gebruikers_model->deleteDeelnemer($item_ID);
			if($q) redirect('cms/deelnemers');
			else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
		}


		// PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/deelnemers_verwijderen';
		$this->load->view('cms/template', $pagina);
	}



	/* =========================== */
	/* = AANMELDEN VOOR WORKSHOP = */
	/* =========================== */

	public function aanmelden($gebruiker_ID)
	{
		$this->load->model('gebruikers_model');
		$this->load->model('workshops_model');
		$this->load->model('groepen_model');

		$gebruiker = $this->gebruikers_model->getGebruikerByID($gebruiker_ID);
		if($gebruiker == null) redirect('cms');
		$this->data['gebruiker'] = $gebruiker;

		$workshops = $this->workshops_model->getWorkshopsCMS();
		$this->data['workshops'] = $workshops;


		$workshop_ID 	= 0;
		$groep_ID 		= 0;
		$feedback 		= '';


		// FORMULIER VERZONDEN

		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if(isset($_POST['workshop']))
			{
				$workshop_ID = $_POST['workshop'];

				$workshop = $this->workshops_model->getWorkshopByID($workshop_ID);

				if(in_array($workshop->workshop_type, array('groep', 'online')))
				{
					if(isset($_POST['groep']))
					{
						$groep_ID = $_POST['groep'];

						$groep = $this->groepen_model->getGroepByID($groep_ID);

						// AANMELDEN VOOR GROEP

						$data = array(
							'aanmelding_type' => 'workshop',
							'aanmelding_datum' => date('Y-m-d H:i:s'),
							'aanmelding_betaald_datum' => date('Y-m-d H:i:s'),
							'aanmelding_betaald_bedrag' => 0,
							'gebruiker_ID' => $gebruiker_ID,
							'workshop_ID' => $workshop_ID,
							'groep_ID' => $groep_ID
						);

						$this->load->model('aanmeldingen_model');
						$aanmelden = $this->aanmeldingen_model->insertAanmelding($data);

						if($aanmelden)
						{
							// VERSTUUR E-MAIL

							$this->load->helper('mandrill');

							$mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
							// $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
							$email_titel = 'Aanmelding '.$workshop->workshop_titel;
							$email_tekst = '<p>Je bent aangemeld voor de volgende workshop: "'.$workshop->workshop_titel.'" op localhost.</p>';

							$email_bericht = '
								<h1>'.$email_titel.'</h1>
								<p>Beste '.$gebruiker->gebruiker_voornaam.',</p>
								'.$email_tekst.'
								<p>Met vriendelijke groet,</p>
								<p>localhost</p>';

							$email = array(
								'html' => $email_bericht,
								'subject' => $email_titel,
							    'from_email' => 'info@localhost',
							    'from_name' => 'localhost',
							    'to' => array(
							    	array(
							    		'email' => $gebruiker->gebruiker_emailadres,
							    		'name' => str_replace('  ', '', $gebruiker->gebruiker_voornaam.' '.$gebruiker->gebruiker_tussenvoegsel.' '.$gebruiker->gebruiker_achternaam),
							    		'type' => 'to'
									)
								),
							    'headers' => array('Reply-To' => 'info@localhost'),
							    'track_opens' => true,
							    'track_clicks' => true,
							    'auto_text' => true
							);

							$mandrill->messages->send($email);

							redirect('cms/deelnemers/'.$gebruiker_ID);
						}
						else
						{
							$feedback = 'Aanmelding mislukt';
						}

					}
					else
					{
						$feedback = 'Selecteer een groep';
					}
				}
				else
				{
					// AANMELDEN VOOR INDIVIDUELE WORKSHOP

					$data = array(
						'aanmelding_type' => 'workshop',
						'aanmelding_datum' => date('Y-m-d H:i:s'),
						'aanmelding_betaald_datum' => date('Y-m-d H:i:s'),
						'aanmelding_betaald_bedrag' => 0,
						'gebruiker_ID' => $gebruiker_ID,
						'workshop_ID' => $workshop_ID
					);

					$this->load->model('aanmeldingen_model');
					$aanmelden = $this->aanmeldingen_model->insertAanmelding($data);

					if($aanmelden)
					{
						// VERSTUUR E-MAIL

						$this->load->helper('mandrill');

						$mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
						// $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
						$email_titel = 'Aanmelding '.$workshop->workshop_titel;
						$email_tekst = '<p>Je bent aangemeld voor de volgende workshop: "'.$workshop->workshop_titel.'" op localhost.</p>';

						$email_bericht = '
							<h1>'.$email_titel.'</h1>
							<p>Beste '.$gebruiker->gebruiker_voornaam.',</p>
							'.$email_tekst.'
							<p>Met vriendelijke groet,</p>
							<p>localhost</p>';

						$email = array(
							'html' => $email_bericht,
							'subject' => $email_titel,
						    'from_email' => 'info@localhost',
						    'from_name' => 'localhost',
						    'to' => array(
						    	array(
						    		'email' => $gebruiker->gebruiker_emailadres,
						    		'name' => str_replace('  ', '', $gebruiker->gebruiker_voornaam.' '.$gebruiker->gebruiker_tussenvoegsel.' '.$gebruiker->gebruiker_achternaam),
						    		'type' => 'to'
								)
							),
						    'headers' => array('Reply-To' => 'info@localhost'),
						    'track_opens' => true,
						    'track_clicks' => true,
						    'auto_text' => true
						);

						$mandrill->messages->send($email);

						redirect('cms/deelnemers/'.$gebruiker_ID);
					}
					else
					{
						$feedback = 'Aanmelding mislukt';
					}
				}
			}
			else
			{
				$feedback = 'Selecteer een workshop';
			}
		}


		// PAGINA TONEN

		$this->data['workshop_ID'] 	= $workshop_ID;
		$this->data['groep_ID'] 	= $groep_ID;
		$this->data['feedback']	 	= $feedback;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/deelnemers_aanmelden';
		$this->load->view('cms/template', $pagina);
	}

    public function exporteren($item_ID = null)
    {
        if($item_ID == null) redirect('cms/deelnemers');

        $this->load->model('gebruikers_model');
        $this->load->model('aanmeldingen_model');
        $this->load->model('bestellingen_model');
        $this->load->model('huiswerk_model');

        $deelnemer = $this->gebruikers_model->getGebruikerByID($item_ID);

        // Aanmeldingen ophalen
        $afspraken = $this->aanmeldingen_model->getAanmeldingenAfsprakenByGebruikerID($deelnemer->gebruiker_ID);
        $aanmeldingen = $this->aanmeldingen_model->getAanmeldingenWorkshopsByGebruikerID($deelnemer->gebruiker_ID);

        // Bestellingen ophalen
        $bestellingen_met_aanmelding = $this->bestellingen_model->getBestellingenByGebruikerID($deelnemer->gebruiker_ID);
        $bestellingen_zonder_aanmelding = $this->bestellingen_model->getBestellingenLosByGebruikerID($deelnemer->gebruiker_ID);

        // Bestellingen met en zonder aanmelding samenvoegen
        $bestellingen = array_merge($bestellingen_met_aanmelding, $bestellingen_zonder_aanmelding);

        // Bestellingen sorteren
        $bestellingen = Utilities::array_orderby($bestellingen, 'bestelling_ID', SORT_DESC);

        // Resultaten ophalen
        $resultaten = $this->huiswerk_model->getResultatenByGebruikerID($deelnemer->gebruiker_ID);

        require(dirname(dirname(__FILE__)) . '/cms/Csv.php');

        $body_items['items'] = array("", $deelnemer->gebruiker_voornaam, $deelnemer->gebruiker_tussenvoegsel, $deelnemer->gebruiker_achternaam, $deelnemer->gebruiker_bedrijfsnaam, $deelnemer->gebruiker_geslacht,
            $deelnemer->gebruiker_emailadres, $deelnemer->gebruiker_adres, $deelnemer->gebruiker_postcode, $deelnemer->gebruiker_telefoonnummer, $deelnemer->gebruiker_mobiel, "\n",
        );

        array_push($body_items['items'], "\n");
        array_push($body_items['items'], 'Type');
        array_push($body_items['items'], 'Titel');
        array_push($body_items['items'], 'Afspraak');
        array_push($body_items['items'], 'Voldoende');

        if (count($afspraken) == 0) {
            array_push($body_items['items'], "\n");
            array_push($body_items['items'], "-");
            array_push($body_items['items'], "-");
            array_push($body_items['items'], "-");
            array_push($body_items['items'], "-");
            array_push($body_items['items'], "\n");
        }

        foreach ($afspraken as $afspraak) {
             array_push($body_items['items'], "\n");
             array_push($body_items['items'], $afspraak->workshop_type);
             array_push($body_items['items'], $afspraak->workshop_titel);
             array_push($body_items['items'], $afspraak->aanmelding_afspraak);
             array_push($body_items['items'], $afspraak->aanmelding_voldoende);
        }

        array_push($body_items['items'], "\n");
        array_push($body_items['items'], "\n");
        array_push($body_items['items'], 'Datum');
        array_push($body_items['items'], 'Betaald');
        array_push($body_items['items'], 'Type');
        array_push($body_items['items'], 'Titel');
        array_push($body_items['items'], 'Naam');
        array_push($body_items['items'], 'Afgerond');

        if (count($aanmeldingen) == 0) {
            array_push($body_items['items'], "\n");
            array_push($body_items['items'], "-");
            array_push($body_items['items'], "-");
            array_push($body_items['items'], "-");
            array_push($body_items['items'], "-");
            array_push($body_items['items'], "-");
            array_push($body_items['items'], "-");
            array_push($body_items['items'], "\n");
        }

        foreach ($aanmeldingen as $aanmelding) {
            array_push($body_items['items'], "\n");
            array_push($body_items['items'], $aanmelding->aanmelding_datum);
            array_push($body_items['items'], $aanmelding->aanmelding_betaald_datum);
            array_push($body_items['items'], $aanmelding->aanmelding_type);
            array_push($body_items['items'], $aanmelding->workshop_titel);
            array_push($body_items['items'], $aanmelding->groep_naam);

            if ($aanmelding->aanmelding_afgerond == 0) {
                array_push($body_items['items'], 'nee');
            } else {
                array_push($body_items['items'], 'ja');
            }
        }

        array_push($body_items['items'], "\n");
        array_push($body_items['items'], "\n");
        array_push($body_items['items'], 'Type');
        array_push($body_items['items'], '#');
        array_push($body_items['items'], 'Datum');
        array_push($body_items['items'], 'Deelnemer');
        array_push($body_items['items'], 'Betaald');

        if (count($bestellingen) == 0) {
            array_push($body_items['items'], "\n");
            array_push($body_items['items'], "-");
            array_push($body_items['items'], "-");
            array_push($body_items['items'], "-");
            array_push($body_items['items'], "-");
            array_push($body_items['items'], "-");
            array_push($body_items['items'], "\n");
        }

        foreach ($bestellingen as $bestelling) {
            array_push($body_items['items'], "\n");
            array_push($body_items['items'], "Bestelling");
            array_push($body_items['items'], $bestelling->bestelling_ID);
            array_push($body_items['items'], $bestelling->bestelling_datum);
            array_push($body_items['items'], $bestelling->gebruiker_naam);
            array_push($body_items['items'], $bestelling->bestelling_betaald_datum);
            array_push($body_items['items'], "\n");
        }

        $csv = new CSV();
        $csv->set_header_items(array((''),('Voornaam'), ('Tussenvoegsel'), ('Achternaam'), ('Bedrijfsnaam'),
                                        ('Geslacht'), ('E-mail'), ('Adres'), ('Postcode'),
                                            ('Telefoonnummer'), ('Mobiel')));

        $csv->set_body_items($body_items);
        $csv->output_as_download($deelnemer->gebruiker_voornaam.'-'.$deelnemer->gebruiker_achternaam.'.csv'); // prompts the user to download the file as 'export.csv' by default

        // PAGINA TONEN

        redirect('cms/deelnemers/'.$item_ID);
    }

    public function deelnemersExporteren()
    {
        // Aantal deelnemers ophalen
        if(isset($_POST['deelnemerslijst'])) {
            $deelnemers_IDs = explode(',', $_POST['deelnemerslijst']);

            $this->load->model('gebruikers_model');

            require(dirname(dirname(__FILE__)) . '/cms/Csv.php');

            // Retrieve data to export as CSV
            $cursisten = $this->gebruikers_model->getDeelnemersByIDArray($deelnemers_IDs);

            foreach ($cursisten as $cursist) {
                $body_items[] = array($cursist->gebruiker_voornaam, $cursist->gebruiker_tussenvoegsel, $cursist->gebruiker_achternaam, $cursist->gebruiker_geslacht,
                    $cursist->gebruiker_emailadres, $cursist->gebruiker_telefoonnummer, $cursist->gebruiker_mobiel);
            }

            $csv = new CSV();
            $csv->set_header_items(array(('Voornaam'), ('Tussenvoegsel'), ('Achternaam'),('Gender'), ('E-mail'), ('Telefoonnummer'),('Mobiel')));
            $csv->set_body_items($body_items);
            $csv->output_as_download('Export-' .  date('d-m-Y') . '.csv'); // prompts the user to download the file as 'export.csv' by default
        }
        // PAGINA TONEN

        $pagina['data'] = $this->data;
        redirect('cms/deelnemers/');
    }


	/* ========================== */
	/* = AFMELDEN VOOR WORKSHOP = */
	/* ========================== */

	function afmelden($aanmelding_ID = null, $bevestiging = null)
	{
		if($aanmelding_ID == null) redirect('cms/deelnemers');

		// Aanmelding ophalen

		$this->load->model('aanmeldingen_model');
		$aanmelding = $this->aanmeldingen_model->getAanmeldingByID($aanmelding_ID);
		if($aanmelding == null) redirect('cms/deelnemers');
		$this->data['aanmelding'] = $aanmelding;

		// Bestelling ophalen

		$this->load->model('bestellingen_model');
		$bestelling = $this->bestellingen_model->getBestellingByAanmeldingID($aanmelding_ID);
		$this->data['bestelling'] = $bestelling;

		// ITEM VERWIJDEREN

		if($bevestiging == 'ja')
		{
			$q = $this->aanmeldingen_model->deleteAanmelding($aanmelding_ID);
			if($q) redirect('cms/deelnemers/'.$aanmelding->gebruiker_ID);
			else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
		}

		// PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/deelnemers_afmelden';
		$this->load->view('cms/template', $pagina);
	}

	/* ========================== */
	/* ======== AFDRUKKEN ======= */
	/* ========================== */

	public function afdrukken(){

		// GROEP ID'S
		$item_ID = $this->uri->segment(4);
		$workshop_ID =  $this->uri->segment(5);
		$gebruiker_ID = $this->uri->segment(6);

		if($item_ID == null) redirect('cms/groepen');

		$this->load->model('groepen_model');
		$this->load->model('workshops_model');
		$this->load->model('gebruikers_model');

		// ITEM DETAIL 
		$item = $this->groepen_model->getGroepByID($item_ID);
		$alle_deelnemers = $this->gebruikers_model->getDeelnemers();
		$deelnemers = $this->groepen_model->getGroepDeelnemersVermelding($item_ID);
		$this->data['deelnemers'] = $deelnemers;

		if (!empty($deelnemers)) {
            foreach($deelnemers as $deelnemer) {
                foreach($alle_deelnemers as $key=>$cursist) {
                    if($deelnemer->gebruiker_ID == $cursist->gebruiker_ID) {
                        unset($alle_deelnemers[$key]);
                    }
                }
            }
        }
		$pagina['data'] = $this->data;
		
		require_once(APPPATH.'libraries/vendor/autoload.php');
		require(APPPATH.'libraries/vendor/tecnickcom/tcpdf/examples/tcpdf_include.php');

		$html = '<html><head><title>Certificaten - localhost </title><meta name="viewport" content="width=device-width" /><link rel="preconnect" href="https://fonts.gstatic.com"><link href="https://fonts.googleapis.com/css2?family=Ruda&display=swap" rel="stylesheet">
		     <style type="text/css">*{font-family: "Ruda", sans-serif;}</style> </head> <body>';

		foreach($pagina['data']['deelnemers'] as $key=>$value){
		$gebruiker_geboortedatum = date('j F Y',strtotime($value->gebruiker_geboortedatum));
		$groep_startdatum = date('j F Y',strtotime($value->groep_startdatum));

		$html.='<div class="main-area"> 
	 		<h4 style="text-align:center;font-weight: bold;">'.ucwords($value->workshop_titel).'</h4>
	 		<div class=""></div>
            
			 <table>
                   <tr>
				    <td style="width:30%;">
					</td>
					<td style="width:70%;">
					<div class="main-div" style="display:inline-flex;width:100%;">            
				<div style="width:20%;"></div>
					<div style="text-align:left;margin-top:20px;width:40%;">
						 <p style="line-height:0.5px;word-break: normal;">'.$value->gebruiker_naam.'</p>
						 <p style="line-height:0.5px;word-break: normal;">'.$this->nlDate($gebruiker_geboortedatum).'</p>
						 <p style="line-height:0.5px;word-break: normal;">'.$value->groep_naam.'</p>
						 <p style="line-height:0.5px;word-break: normal;">'.$this->nlDate($groep_startdatum) .'</p> 
						 <p style="line-height:0.5px;word-break: normal;">'.$value->workshop_locatie.'</p> 
					</div>
					<div style="width:20%;"></div>
                 </div>
				 <div>Barnier Geerling</div>
					</td>
				   </tr>
			 </table>
					
					</div>
					</body>
				</html>';
			}
		
		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

		// set document information
		$pdf->SetTitle("Title");
		$pdf->SetHeaderMargin(30);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(20);
		$pdf->SetAutoPageBreak(true);
		$pdf->SetAuthor("TCPDF");
		$pdf->SetDisplayMode('real', 'default');
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);	
		$pdf->SetMargins(50, 100, 50);
		$pdf->SetAutoPageBreak(TRUE, 95);
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$img = "uploads/admin.png";
		$pdf->AddPage();
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();
		ob_end_clean();
		$pdf->Output("certificate.pdf", 'I');
	}


    public function deelnemersStatusActief()
    {
        // Aantal deelnemers ophalen
        if(isset($_POST['deelnemerslijst'])) {
            $deelnemers_IDs = explode(',', $_POST['deelnemerslijst']);

            $this->load->model('gebruikers_model');
            $cursisten = $this->gebruikers_model->getDeelnemersByIDArray($deelnemers_IDs);

            foreach ($cursisten as $cursist) {
                if ($cursist->gebruiker_status != 'actief') {
                    $data = array(
                        'gebruiker_status' => 'actief',
                    );

                    $this->gebruikers_model->updateDeelnemer($cursist->gebruiker_ID, $data);
                }
            }

        }
        // PAGINA TONEN
        $this->data['deelnemers'] 			= $deelnemers_IDs;
        $pagina['data'] = $this->data;
        redirect('cms/deelnemers/');
    }

    public function deelnemersStatusInactief()
    {
        // Aantal deelnemers ophalen
        if(isset($_POST['deelnemerslijst'])) {
            $deelnemers_IDs = explode(',', $_POST['deelnemerslijst']);

            $this->load->model('gebruikers_model');
            $cursisten = $this->gebruikers_model->getDeelnemersByIDArray($deelnemers_IDs);

            foreach ($cursisten as $cursist) {
                if ($cursist->gebruiker_status != 'inactief') {
                    $data = array(
                        'gebruiker_status' => 'inactief',
                    );

                    $this->gebruikers_model->updateDeelnemer($cursist->gebruiker_ID, $data);
                }
            }
        }
        // PAGINA TONEN
        $this->data['deelnemers'] 			= $deelnemers_IDs;
        $pagina['data'] = $this->data;
        redirect('cms/deelnemers/');
	}

	public function bekeken($workshop_ID, $gebruiker_ID) {
		if(empty($workshop_ID) || empty($gebruiker_ID)) {
			redirect('cms/deelnemers/');
		}

		$this->load->model('lessen_model');
		$lessen = $this->lessen_model->getLessenBekeken($gebruiker_ID, $workshop_ID);

		// PAGINA TONEN
		$this->data['lessen'] 	= $lessen;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/lessen_bekeken';
		$this->load->view('cms/template', $pagina);
	}

	public function alleLessenBekeken($gebruiker_ID) {
		if(empty($gebruiker_ID)) {
			redirect('cms/deelnemers/');
		}

		$this->load->model('lessen_model');
		$lessen = $this->lessen_model->getLessenBekekenGebruiker($gebruiker_ID);

		// PAGINA TONEN
		$this->data['lessen'] 	= $lessen;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/alle_lessen_bekeken';
		$this->load->view('cms/template', $pagina);
	}
	public function nlDate($datum){
	
			$datum = str_replace("january",     "januari",         $datum);
			$datum = str_replace("february",     "februari",     $datum);
			$datum = str_replace("march",         "maart",         $datum);
			$datum = str_replace("april",         "april",         $datum);
			$datum = str_replace("may",         "mei",             $datum);
			$datum = str_replace("june",         "juni",         $datum);
			$datum = str_replace("july",         "juli",         $datum);
			$datum = str_replace("august",         "augustus",     $datum);
			$datum = str_replace("september",     "september",     $datum);
			$datum = str_replace("october",     "oktober",         $datum);
			$datum = str_replace("november",     "november",     $datum);
			$datum = str_replace("december",     "december",     $datum);

			// Vervang de maand, hoofdletters
			$datum = str_replace("January",     "Januari",         $datum);
			$datum = str_replace("February",     "Februari",     $datum);
			$datum = str_replace("March",         "Maart",         $datum);
			$datum = str_replace("April",         "April",         $datum);
			$datum = str_replace("May",         "Mei",             $datum);
			$datum = str_replace("June",         "Juni",         $datum);
			$datum = str_replace("July",         "Juli",         $datum);
			$datum = str_replace("August",         "Augustus",     $datum);
			$datum = str_replace("September",     "September",     $datum);
			$datum = str_replace("October",     "Oktober",         $datum);
			$datum = str_replace("November",     "November",     $datum);
			$datum = str_replace("December",     "December",     $datum);

			// Vervang de maand, kort
			$datum = str_replace("Jan",         "Jan",             $datum);
			$datum = str_replace("Feb",         "Feb",             $datum);
			$datum = str_replace("Mar",         "Maa",             $datum);
			$datum = str_replace("Apr",         "Apr",             $datum);
			$datum = str_replace("May",         "Mei",             $datum);
			$datum = str_replace("Jun",         "Jun",             $datum);
			$datum = str_replace("Jul",         "Jul",             $datum);
			$datum = str_replace("Aug",         "Aug",             $datum);
			$datum = str_replace("Sep",         "Sep",             $datum);
			$datum = str_replace("Oct",         "Ok",             $datum);
			$datum = str_replace("Nov",         "Nov",             $datum);
			$datum = str_replace("Dec",         "Dec",             $datum);
		return $datum;
	}
}
