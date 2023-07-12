<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profiel extends CI_Controller
{
	private $data = array();
	
	public function __construct()
	{
		parent::__construct();
		
		// Rechten controleren en aantal nieuwe items ophalen
		
		$this->load->library('algemeen');
		$this->algemeen->cms();
	}
	
	
	
	/* ============= */
	/* = OVERZICHT = */
	/* ============= */
	
	public function index()
	{
		$this->load->model('gebruikers_model');
		$gebruiker = $this->gebruikers_model->getGebruikerByID($this->session->userdata('beheerder_ID'));
		
		
		// PAGINA TONEN
		
		$this->data['gebruiker_bedrijfsnaam'] 		= $gebruiker->gebruiker_bedrijfsnaam;
		$this->data['gebruiker_voornaam'] 			= $gebruiker->gebruiker_voornaam;
		$this->data['gebruiker_tussenvoegsel'] 		= $gebruiker->gebruiker_tussenvoegsel;
		$this->data['gebruiker_achternaam'] 		= $gebruiker->gebruiker_achternaam;
		$this->data['gebruiker_adres'] 				= $gebruiker->gebruiker_adres;
		$this->data['gebruiker_postcode'] 			= $gebruiker->gebruiker_postcode;
		$this->data['gebruiker_plaats'] 			= $gebruiker->gebruiker_plaats;
		$this->data['gebruiker_telefoonnummer'] 	= $gebruiker->gebruiker_telefoonnummer;
		$this->data['gebruiker_mobiel'] 			= $gebruiker->gebruiker_mobiel;
		$this->data['gebruiker_emailadres'] 		= $gebruiker->gebruiker_emailadres;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/profiel';
		$this->load->view('cms/template', $pagina);
	}
	
	
	
	/* ============ */
	/* = WIJZIGEN = */
	/* ============ */
	
	public function wijzigen($wijzigen = null)
	{
		switch($wijzigen)
		{
			case '':
			$this->_gegevens_wijzigen();
			break;
			
			case 'wachtwoord':
			$this->_wachtwoord_wijzigen();
			break;
			
			case 'instellingen':
			$this->_instellingen_wijzigen();
			break;
			
			default:
			redirect('cms/profiel');
			break;
		}
	}
	
	private function _gegevens_wijzigen()
	{
		$this->load->model('gebruikers_model');
		
		$fouten = 0;
		$feedback = '';
		
		$gebruiker_bedrijfsnaam = '';
		$gebruiker_voornaam = '';
		$gebruiker_tussenvoegsel = '';
		$gebruiker_achternaam = '';
		$gebruiker_geslacht = '';
		$gebruiker_geboortedatum_dag = '';
		$gebruiker_geboortedatum_maand = '';
		$gebruiker_geboortedatum_jaar = '';
		$gebruiker_adres = '';
		$gebruiker_postcode = '';
		$gebruiker_plaats = '';
		$gebruiker_telefoonnummer = '';
		$gebruiker_mobiel = '';
		$gebruiker_emailadres = '';
		
		$gebruiker_bedrijfsnaam_feedback = '';
		$gebruiker_voornaam_feedback = '';
		$gebruiker_tussenvoegsel_feedback = '';
		$gebruiker_achternaam_feedback = '';
		$gebruiker_geslacht_feedback = '';
		$gebruiker_geboortedatum_feedback = '';
		$gebruiker_adres_feedback = '';
		$gebruiker_postcode_feedback = '';
		$gebruiker_plaats_feedback = '';
		$gebruiker_telefoonnummer_feedback = '';
		$gebruiker_mobiel_feedback = '';
		$gebruiker_emailadres_feedback = '';
		
		
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$gebruiker_bedrijfsnaam 		= $this->input->post('gebruiker_bedrijfsnaam');
			$gebruiker_voornaam 			= ucfirst($this->input->post('gebruiker_voornaam'));
			$gebruiker_tussenvoegsel 		= $this->input->post('gebruiker_tussenvoegsel');
			$gebruiker_achternaam 			= ucfirst($this->input->post('gebruiker_achternaam'));
			$gebruiker_geslacht	 			= $this->input->post('gebruiker_geslacht');
			$gebruiker_geboortedatum_dag	= $this->input->post('gebruiker_geboortedatum_dag');
			$gebruiker_geboortedatum_maand	= $this->input->post('gebruiker_geboortedatum_maand');
			$gebruiker_geboortedatum_jaar	= $this->input->post('gebruiker_geboortedatum_jaar');
			$gebruiker_adres 				= $this->input->post('gebruiker_adres');
			$gebruiker_postcode 			= $this->input->post('gebruiker_postcode');
			$gebruiker_plaats 				= $this->input->post('gebruiker_plaats');
			$gebruiker_telefoonnummer 		= $this->input->post('gebruiker_telefoonnummer');
			$gebruiker_mobiel 				= $this->input->post('gebruiker_mobiel');
			$gebruiker_emailadres 			= $this->input->post('gebruiker_emailadres');
			
			if(empty($gebruiker_voornaam))
			{
				$gebruiker_voornaam_feedback = 'Graag je voornaam invullen';
				$fouten++;
			}
			
			if(empty($gebruiker_achternaam))
			{
				$gebruiker_achternaam_feedback = 'Graag je achternaam invullen';
				$fouten++;
			}
			
			if(empty($gebruiker_geboortedatum_dag))
			{
				$gebruiker_geboortedatum_feedback = 'Graag de dag invullen';
				$fouten++;
			}
			else
			{
				if(!is_numeric($gebruiker_geboortedatum_dag) || $gebruiker_geboortedatum_dag > 31)
				{
					$gebruiker_geboortedatum_feedback = 'Graag een geldige dag invullen';
					$fouten++;
				}
				else
				{
					if(empty($gebruiker_geboortedatum_maand))
					{
						$gebruiker_geboortedatum_feedback = 'Graag de maand invullen';
						$fouten++;
					}
					else
					{
						if(!is_numeric($gebruiker_geboortedatum_maand) || $gebruiker_geboortedatum_maand > 12)
						{
							$gebruiker_geboortedatum_feedback = 'Graag een geldige maand invullen';
							$fouten++;
						}
						else
						{
							if(empty($gebruiker_geboortedatum_jaar))
							{
								$gebruiker_geboortedatum_feedback = 'Graag het jaartal invullen';
								$fouten++;
							}
							else
							{
								if(!is_numeric($gebruiker_geboortedatum_jaar))
								{
									$gebruiker_geboortedatum_feedback = 'Graag een geldig jaar invullen';
									$fouten++;
								}
								else
								{
									if($gebruiker_geboortedatum_jaar < (date('Y') - 120) || $gebruiker_geboortedatum_jaar > date('Y'))
									{
										$gebruiker_geboortedatum_feedback = 'Graag een realistisch geboortejaar invullen';
										$fouten++;
									}
								}
							}
						}
					}	
				}
			}
			
			if(empty($gebruiker_adres))
			{
				$gebruiker_adres_feedback = 'Graag je adres invullen';
				$fouten++;
			}
			
			if(empty($gebruiker_postcode))
			{
				$gebruiker_postcode_feedback = 'Graag je postcode invullen';
				$fouten++;
			}
			else
			{
				$postcode = str_replace(' ', '', $gebruiker_postcode);
				$postcode_cijfers = substr($postcode, 0, 4);
				$postcode_letters = strtoupper(substr($postcode, -2));
				$gebruiker_postcode = $postcode_cijfers.' '.$postcode_letters;
				
				if(strlen($postcode) == 6)
				{
					if(!is_numeric($postcode_cijfers))
					{
						$gebruiker_postcode_feedback = 'Graag een geldige postcode invullen';
						$fouten++;
					}
					elseif(!preg_match("/^[A-Z]+$/", $postcode_letters))
					{
						$gebruiker_postcode_feedback = 'Graag een geldige postcode invullen';
						$fouten++;
					}
				}
				else
				{
					$gebruiker_postcode_feedback = 'Graag een geldige postcode invullen';
					$fouten++;
				}
			}
			
			if(empty($gebruiker_plaats))
			{
				$gebruiker_plaats_feedback = 'Graag je woonplaats invullen';
				$fouten++;
			}
			
			if(empty($gebruiker_telefoonnummer))
			{
				$gebruiker_telefoonnummer_feedback = 'Graag je telefoonnummer invullen';
				$fouten++;
			}
			else
			{
				$telefoonnummer = preg_replace("/[^0-9]/", '', $gebruiker_telefoonnummer);
				
				if(strlen($telefoonnummer) < 10 || strlen($telefoonnummer) > 11)
				{
					$gebruiker_telefoonnummer_feedback = 'Graag een geldig nummer invullen';
					$fouten++;
				}
			}
			
			if(!empty($gebruiker_mobiel))
			{
				$telefoonnummer = preg_replace("/[^0-9]/", '', $gebruiker_mobiel);
				
				if(strlen($telefoonnummer) < 10 || strlen($telefoonnummer) > 11)
				{
					$gebruiker_mobiel_feedback = 'Graag een geldig nummer invullen';
					$fouten++;
				}
			}
			
			if(empty($gebruiker_emailadres))
			{
				$gebruiker_emailadres_feedback = 'Graag je e-mailadres invullen';
				$fouten++;
			}
			else
			{
				if(!filter_var($gebruiker_emailadres, FILTER_VALIDATE_EMAIL))
				{
					$gebruiker_emailadres_feedback = 'Graag een geldig e-mailadres invullen';
					$fouten++;
				}
			}
			
			if($fouten == 0)
			{
				$data = array(
					'gebruiker_bedrijfsnaam' => $gebruiker_bedrijfsnaam,
					'gebruiker_naam' => str_replace('  ', ' ', $gebruiker_voornaam.' '.$gebruiker_tussenvoegsel.' '.$gebruiker_achternaam),
					'gebruiker_voornaam' => $gebruiker_voornaam,
					'gebruiker_tussenvoegsel' => $gebruiker_tussenvoegsel,
					'gebruiker_achternaam' => $gebruiker_achternaam,
					'gebruiker_geslacht' => $gebruiker_geslacht,
					'gebruiker_geboortedatum' => $gebruiker_geboortedatum_jaar.'-'.$gebruiker_geboortedatum_maand.'-'.$gebruiker_geboortedatum_dag,
					'gebruiker_adres' => $gebruiker_adres,
					'gebruiker_postcode' => $gebruiker_postcode,
					'gebruiker_plaats' => $gebruiker_plaats,
					'gebruiker_telefoonnummer' => $gebruiker_telefoonnummer,
					'gebruiker_mobiel' => $gebruiker_mobiel,
					'gebruiker_emailadres' => $gebruiker_emailadres
				);
				
				$update = $this->gebruikers_model->updateDeelnemer($this->session->userdata('beheerder_ID'), $data);
				
				if($update) redirect('cms/profiel');
				else $feedback = 'Je gegevens zijn niet gewijzigd. Heb je iets aangepast?';
			}
		}
		else
		{
			$gebruiker = $this->gebruikers_model->getGebruikerByID($this->session->userdata('beheerder_ID'));
		
			$gebruiker_bedrijfsnaam 		= $gebruiker->gebruiker_bedrijfsnaam;
			$gebruiker_voornaam 			= $gebruiker->gebruiker_voornaam;
			$gebruiker_tussenvoegsel 		= $gebruiker->gebruiker_tussenvoegsel;
			$gebruiker_achternaam 			= $gebruiker->gebruiker_achternaam;
			$gebruiker_geslacht	 			= $gebruiker->gebruiker_geslacht;
			$gebruiker_geboortedatum		= explode('-', $gebruiker->gebruiker_geboortedatum);
			$gebruiker_geboortedatum_dag	= $gebruiker_geboortedatum[2];
			$gebruiker_geboortedatum_maand	= $gebruiker_geboortedatum[1];
			$gebruiker_geboortedatum_jaar	= $gebruiker_geboortedatum[0];
			$gebruiker_adres 				= $gebruiker->gebruiker_adres;
			$gebruiker_postcode 			= $gebruiker->gebruiker_postcode;
			$gebruiker_plaats 				= $gebruiker->gebruiker_plaats;
			$gebruiker_telefoonnummer 		= $gebruiker->gebruiker_telefoonnummer;
			$gebruiker_mobiel 				= $gebruiker->gebruiker_mobiel;
			$gebruiker_emailadres 			= $gebruiker->gebruiker_emailadres;
		}
		
		
		// PAGINE TONEN
		
		$this->data['feedback'] = $feedback;
		
		$this->data['gebruiker_bedrijfsnaam'] 			= $gebruiker_bedrijfsnaam;
		$this->data['gebruiker_voornaam'] 				= $gebruiker_voornaam;
		$this->data['gebruiker_tussenvoegsel'] 			= $gebruiker_tussenvoegsel;
		$this->data['gebruiker_achternaam'] 			= $gebruiker_achternaam;
		$this->data['gebruiker_geslacht']	 			= $gebruiker_geslacht;
		$this->data['gebruiker_geboortedatum_dag']		= $gebruiker_geboortedatum_dag;
		$this->data['gebruiker_geboortedatum_maand']	= $gebruiker_geboortedatum_maand;
		$this->data['gebruiker_geboortedatum_jaar']		= $gebruiker_geboortedatum_jaar;
		$this->data['gebruiker_adres'] 					= $gebruiker_adres;
		$this->data['gebruiker_postcode'] 				= $gebruiker_postcode;
		$this->data['gebruiker_plaats'] 				= $gebruiker_plaats;
		$this->data['gebruiker_telefoonnummer'] 		= $gebruiker_telefoonnummer;
		$this->data['gebruiker_mobiel'] 				= $gebruiker_mobiel;
		$this->data['gebruiker_emailadres'] 			= $gebruiker_emailadres;
		
		$this->data['gebruiker_bedrijfsnaam_feedback'] 		= $gebruiker_bedrijfsnaam_feedback;
		$this->data['gebruiker_voornaam_feedback'] 			= $gebruiker_voornaam_feedback;
		$this->data['gebruiker_tussenvoegsel_feedback'] 	= $gebruiker_tussenvoegsel_feedback;
		$this->data['gebruiker_achternaam_feedback'] 		= $gebruiker_achternaam_feedback;
		$this->data['gebruiker_geslacht_feedback'] 			= $gebruiker_geslacht_feedback;
		$this->data['gebruiker_geboortedatum_feedback'] 	= $gebruiker_geboortedatum_feedback;
		$this->data['gebruiker_adres_feedback'] 			= $gebruiker_adres_feedback;
		$this->data['gebruiker_postcode_feedback'] 			= $gebruiker_postcode_feedback;
		$this->data['gebruiker_plaats_feedback'] 			= $gebruiker_plaats_feedback;
		$this->data['gebruiker_telefoonnummer_feedback'] 	= $gebruiker_telefoonnummer_feedback;
		$this->data['gebruiker_mobiel_feedback'] 			= $gebruiker_mobiel_feedback;
		$this->data['gebruiker_emailadres_feedback'] 		= $gebruiker_emailadres_feedback;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/profiel_wijzigen_gegevens';
		$this->load->view('cms/template', $pagina);
	}
	
	private function _wachtwoord_wijzigen()
	{
		$this->load->model('gebruikers_model');
		
		$fouten = 0;
		$feedback = '';
		
		$wachtwoord_oud = '';
		$wachtwoord_nieuw = '';
		$wachtwoord_herhaal = '';
		
		
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$wachtwoord_oud 		= $this->input->post('wachtwoord_oud');
			$wachtwoord_nieuw 		= $this->input->post('wachtwoord_nieuw');
			$wachtwoord_herhaal 	= $this->input->post('wachtwoord_herhaal');
			
			if(empty($wachtwoord_oud))
			{
				$feedback = 'Graag je oude wachtwoord invullen';
				$fouten++;
			}
			else
			{
				$gebruiker = $this->gebruikers_model->getGebruikerByID($this->session->userdata('beheerder_ID'));
				$gebruiker_wachtwoord = $gebruiker->gebruiker_wachtwoord;
				
				if(sha1($wachtwoord_oud) != $gebruiker_wachtwoord)
				{
					$feedback = 'Het oude wachtwoord is niet goed';
					$fouten++;
				}
				else
				{
					if(empty($wachtwoord_nieuw))
					{
						$feedback = 'Graag een nieuw wachtwoord invullen';
						$fouten++;
					}
					elseif(empty($wachtwoord_herhaal))
					{
						$feedback = 'Graag het nieuwe wachtwoord herhalen';
						$fouten++;
					}
					elseif($wachtwoord_nieuw != $wachtwoord_herhaal)
					{
						$feedback = 'De nieuwe wachtwoorden komen niet overeen';
						$fouten++;
					}
					else
					{
						// Nieuwe wachtwoord opslaan
						
						$data = array('gebruiker_wachtwoord' => sha1($wachtwoord_nieuw));
						
						$updaten = $this->gebruikers_model->updateDeelnemer($this->session->userdata('beheerder_ID'), $data);
						
						if($updaten) redirect('cms/profiel');
						else $feedback = 'Het wachtwoord is niet gewijzigd. Heb je hem veranderd?';
					}
				}
			}
		}
		
		
		// PAGINE TONEN
		
		$this->data['feedback'] 			= $feedback;
		$this->data['wachtwoord_oud'] 		= $wachtwoord_oud;
		$this->data['wachtwoord_nieuw'] 	= $wachtwoord_nieuw;
		$this->data['wachtwoord_herhaal'] 	= $wachtwoord_herhaal;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/profiel_wijzigen_wachtwoord';
		$this->load->view('cms/template', $pagina);
	}
	
	private function _instellingen_wijzigen()
	{
		$this->load->model('gebruikers_model');
		
		$feedback = '';
		$instelling_anoniem = '';
		$instelling_email_updates = '';
		
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$instelling_anoniem 		= $this->input->post('instelling_anoniem');
			$instelling_email_updates 	= $this->input->post('instelling_email_updates');
			
			$data = array('gebruiker_instelling_anoniem' => $instelling_anoniem, 'gebruiker_instelling_email_updates' => $instelling_email_updates);
			
			$updaten = $this->gebruikers_model->updateDeelnemer($this->session->userdata('beheerder_ID'), $data);
			
			if($updaten) redirect('cms/profiel');
			else $feedback = 'De instellingen zijn niet gewijzigd. Heb je ze veranderd?';
		}
		else
		{
			$gebruiker = $this->gebruikers_model->getGebruikerByID($this->session->userdata('beheerder_ID'));
			
			$instelling_anoniem 		= $gebruiker->gebruiker_instelling_anoniem;
			$instelling_email_updates 	= $gebruiker->gebruiker_instelling_email_updates;
		}
		
		// PAGINA TONEN
		
		$this->data['feedback'] 					= $feedback;
		$this->data['instelling_anoniem'] 			= $instelling_anoniem;
		$this->data['instelling_email_updates'] 	= $instelling_email_updates;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/profiel_wijzigen_instellingen';
		$this->load->view('cms/template', $pagina);
	}
}