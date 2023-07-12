<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beheerders extends CI_Controller
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
		$this->load->model('gebruikers_model');
		$beheerders = $this->gebruikers_model->getBeheerders();
		$this->data['beheerders'] = $beheerders;
		
		
		// PAGINA TONEN
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/beheerders';
		$this->load->view('cms/template', $pagina);
	}
	
	
	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */
	
	public function detail($item_ID = null)
	{
		if($item_ID == null) redirect('cms/beheerders');
		
		
		// GEBRUIKER OPHALEN
		
		$this->load->model('gebruikers_model');
		$gebruiker = $this->gebruikers_model->getGebruikerByID($item_ID);
		if($gebruiker == null) redirect('cms/beheerders');
		$this->data['gebruiker'] = $gebruiker;
		
		
		// PAGINA TONEN
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/beheerders_detail';
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
		if($item_ID == null) redirect('cms/beheerders');
		$this->_toevoegen_wijzigen('wijzigen', $item_ID);
	}
	
	private function _toevoegen_wijzigen($actie, $item_ID = null)
	{
		$this->load->model('gebruikers_model');
        $this->load->model('docenten_model');
        $this->load->model('media_model');

        $gebruiker_ID           = '';
        $item_naam 				= '';
        $item_inleiding			= '';
        $item_tekst			 	= '';
        $media					= '';
        $item_naam_feedback 			= '';
        $item_inleiding_feedback 		= '';
        $item_tekst_feedback 			= '';
		
		$item_rechten					= '';
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
		
		$item_rechten_feedback						= '';
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

            $item_naam 			= trim($_POST['item_naam']);
            $item_inleiding 	= trim($_POST['item_inleiding']);
            $item_tekst		 	= trim($_POST['item_tekst']);
            $item_media			= trim($_POST['item_media']);

			$item_rechten					= trim($_POST['item_rechten']);
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

			if($item_rechten == 'opleidingsmedewerker') {
                if (empty($item_naam)) {
                    $fouten++;
                    $item_naam_feedback = 'Graag invullen';
                }

                if (empty($item_inleiding)) {
                    $fouten++;
                    $item_inleiding_feedback = 'Graag invullen';
                }

                if (empty($item_tekst)) {
                    $fouten++;
                    $item_tekst_feedback = 'Graag invullen';
                }
            }

			// Verplicht
			
			if(empty($item_rechten))
			{
				$fouten++;
				$item_rechten_feedback = 'Graag selecteren';
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
                    $gebruiker_ID = $this->gebruikers_model->insertDeelnemer($data);
				}
				else
				{
                    $gebruiker_ID = $this->gebruikers_model->updateDeelnemer($item_ID, $data);

                    if($item_rechten != 'opleidingsmedewerker') {
                        $this->docenten_model->deleteDocentByGebruikerID($item_ID);

                        $q = $this->docenten_model->deleteDocentByGebruikerID($item_ID);
                    }
				}

                if($actie == 'toevoegen' && $item_rechten == 'opleidingsmedewerker') {
                    $data = array(
                        'docent_naam' => $item_naam,
                        'docent_inleiding' => $item_inleiding,
                        'docent_tekst' => $item_tekst,
                        'media_ID' => str_replace(',', '', $item_media),
                        'gebruiker_ID' => $gebruiker_ID,
                    );

                    $q = $this->docenten_model->insertDocent($data);
                }

                if($actie == 'wijzigen' && $item_rechten == 'opleidingsmedewerker') {
                    $docent = $this->docenten_model->getDocentByGebruikerID($item_ID);

                    if(!empty($docent)) {
                        $data = array(
                            'docent_naam' => $item_naam,
                            'docent_inleiding' => $item_inleiding,
                            'docent_tekst' => $item_tekst,
                            'media_ID' => str_replace(',', '', $item_media),
                        );

                        $q = $this->docenten_model->updateDocentByGebruikerID($item_ID, $data);
                    } else {
                        $data = array(
                            'docent_naam' => $item_naam,
                            'docent_inleiding' => $item_inleiding,
                            'docent_tekst' => $item_tekst,
                            'media_ID' => str_replace(',', '', $item_media),
                            'gebruiker_ID' => $item_ID,
                        );

                        $q = $this->docenten_model->insertDocent($data);
                    }
                }

                if($actie == 'toevoegen')
                {
                    // Wachtwoord e-mailen naar deelnemer

                    $this->load->helper('mandrill');

                    $mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
                //    $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
                    $email_titel = 'Aanmelding CMS localhost';
                    $email_tekst = '<p>Wij hebben je zojuist aangemeld voor het CMS van localhost. Je kunt inloggen met je e-mailadres "'.$item_emailadres.'" en wachtwoord "'.$item_wachtwoord.'".</p><p>Controleer na het inloggen s.v.p. direct de gegevens die wij voor je hebben ingevuld op je profiel. Wijzig je gegevens of vul deze indien nodig aan.</p>';

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

                    redirect('cms/beheerders');
                }
			}

            redirect('cms/beheerders');
		}
		else
		{
			if($actie == 'wijzigen')
			{
				$item = $this->gebruikers_model->getGebruikerByID($item_ID);
				if($item == null) redirect('cms/deelnemers');

				if($item->gebruiker_rechten == 'opleidingsmedewerker') {
                    $docent = $this->docenten_model->getDocentByGebruikerID($item_ID);

                    $item_naam = $docent->docent_naam;
                    $item_inleiding = $docent->docent_inleiding;
                    $item_tekst = $docent->docent_tekst;

                    $media = $this->media_model->getMediaByMediaID($docent->media_ID);
                }

                $item_rechten					= $item->gebruiker_rechten;
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
				$item_postcode 					= explode(' ', $item->gebruiker_postcode);
				$item_postcode_cijfers 			= $item_postcode[0];
				$item_postcode_letters 			= $item_postcode[1];
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

        $this->data['item_ID'] 				        = $item_ID;
        $this->data['item_naam'] 			        = $item_naam;
        $this->data['item_inleiding'] 		        = $item_inleiding;
        $this->data['item_tekst'] 			        = $item_tekst;
        $this->data['media'] 				        = $media;
        $this->data['item_geslacht_feedback'] 		= $item_geslacht_feedback;
        $this->data['item_naam_feedback'] 			= $item_naam_feedback;
        $this->data['item_inleiding_feedback'] 		= $item_inleiding_feedback;
        $this->data['item_tekst_feedback']	 		= $item_tekst_feedback;

		$this->data['item_ID'] 							= $item_ID;
		$this->data['item_rechten']						= $item_rechten;
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
		
		$this->data['item_rechten_feedback']					= $item_rechten_feedback;
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
		$pagina['pagina'] = 'cms/beheerders_wijzigen';
		$this->load->view('cms/template', $pagina);
	}
	
	
	
	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */
	
	public function verwijderen($item_ID = null, $bevestiging = null)
	{
		if($item_ID == null) redirect('cms/beheerders');
		
		$this->load->model('gebruikers_model');
		$this->load->model('docenten_model');
		$item = $this->gebruikers_model->getGebruikerByID($item_ID);
		if($item == null) redirect('cms/beheerders');
		$this->data['item'] = $item;
		
		
		// ITEM VERWIJDEREN
		
		if($bevestiging == 'ja')
		{
			$q = $this->gebruikers_model->deleteDeelnemer($item_ID);

			if($item->gebruiker_rechten == 'opleidingsmedewerker') {
                $this->docenten_model->deleteDocentByGebruikerID($item_ID);
            }

			if($q) redirect('cms/beheerders');
			else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
		}
		
		
		// PAGINA TONEN
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/beheerders_verwijderen';
		$this->load->view('cms/template', $pagina);
	}
}