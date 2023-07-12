<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Afspraken extends CI_Controller
{
	private $data = array();
	
	public function __construct()
	{
		parent::__construct();
		
		// Rechten controleren en aantal nieuwe items ophalen
		
		$this->load->library('algemeen');
		$this->algemeen->cms();
		if($this->session->userdata('beheerder_rechten') == 'contentmanager') redirect('cms/rechten');
	}
	
	
	
	/* ============= */
	/* = OVERZICHT = */
	/* ============= */
	
	public function index()
	{
		$this->load->model('aanmeldingen_model');
		
		
		$aanmeldingen_afspraken = $this->aanmeldingen_model->getAanmeldingenAfspraken();
		$this->data['aanmeldingen_afspraken'] = $aanmeldingen_afspraken;
		
		$afspraken = $this->aanmeldingen_model->getAfspraken();
		$this->data['afspraken'] = $afspraken;
		
		$afspraken_geweest = $this->aanmeldingen_model->getAfsprakenGeweest();
		$this->data['afspraken_geweest'] = $afspraken_geweest;
		
		
		// PAGINA TONEN
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/afspraken';
		$this->load->view('cms/template', $pagina);
	}
	
	
	
	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */
	
	public function detail($item_ID = null)
	{
		$this->load->model('aanmeldingen_model');
		
		if($item_ID == null) redirect('cms/afspraken');
		
		$item_datum_dag 		= '';
		$item_datum_maand 		= '';
		$item_datum_jaar 		= '';
		$item_tijd_uren			= '';
		$item_tijd_minuten		= '';
		$item_eindtijd_uren		= '';
		$item_eindtijd_minuten	= '';
		$item_voldoende			= '';
		$item_code				= '';
		
		$item_datum_feedback 		= '';
		$item_tijd_feedback 		= '';
		$item_eindtijd_feedback 	= '';
		$item_voldoende_feedback	= '';
		$item_code_feedback 		= '';
		
		// Gegevens afspraak ophalen
		
		$afspraak = $this->aanmeldingen_model->getAanmeldingAfspraakByID($item_ID);
		if($afspraak == null) redirect('cms/afspraken');
		
		
		// FORMULIER VERZONDEN
		
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$fouten 				= 0;
			$item_datum_dag 		= trim($_POST['item_datum_dag']);
			$item_datum_maand 		= trim($_POST['item_datum_maand']);
			$item_datum_jaar 		= trim($_POST['item_datum_jaar']);
			$item_tijd_uren 		= trim($_POST['item_tijd_uren']);
			$item_tijd_minuten 		= trim($_POST['item_tijd_minuten']);
			$item_eindtijd_uren 	= trim($_POST['item_eindtijd_uren']);
			$item_eindtijd_minuten 	= trim($_POST['item_eindtijd_minuten']);
			$item_voldoende			= trim($_POST['item_voldoende']);
			$item_code				= trim($_POST['item_code']);
			
			if(empty($item_datum_dag) || empty($item_datum_maand) || empty($item_datum_jaar))
			{
				$fouten++;
				$item_datum_feedback = 'Graag invullen';
			}
			
			if(empty($item_tijd_uren) || empty($item_tijd_minuten))
			{
				$fouten++;
				$item_tijd_feedback = 'Graag invullen';
			}
			
			if(empty($item_eindtijd_uren) || empty($item_eindtijd_minuten))
			{
				$fouten++;
				$item_eindtijd_feedback = 'Graag invullen';
			}
			
			if(empty($item_code))
			{
				$fouten++;
				$item_code_feedback = 'Graag invullen';
			}
			
			if($fouten == 0)
			{
				// UPDATEN
				
				$data = array(
					'aanmelding_afspraak' => $item_datum_jaar.'-'.$item_datum_maand.'-'.$item_datum_dag.' '.$item_tijd_uren.':'.$item_tijd_minuten.':00', 
					'aanmelding_afspraak_eindtijd' => $item_eindtijd_uren.':'.$item_eindtijd_minuten.':00', 
					'aanmelding_voldoende' => $item_voldoende, 
					'aanmelding_code' => $item_code);
				
				$q = $this->aanmeldingen_model->updateAanmeldingAfspraak($item_ID, $data);
				
				if($q)
				{
					redirect('cms/deelnemers/'.$afspraak->gebruiker_ID);
				}
				else
				{
					echo 'Item wijzigen mislukt. Probeer het nog eens.';
				}
			}
		}
		else
		{
			if(!empty($afspraak->aanmelding_afspraak))
			{
				$item_afspraak 			= explode(' ', $afspraak->aanmelding_afspraak);
				$item_datum				= explode('-', $item_afspraak[0]);
				$item_tijd				= explode(':', $item_afspraak[1]);
				$item_datum_dag 		= $item_datum[2];
				$item_datum_maand 		= $item_datum[1];
				$item_datum_jaar 		= $item_datum[0];
				$item_tijd_uren			= $item_tijd[0];
				$item_tijd_minuten		= $item_tijd[1];
				
				if(!empty($afspraak->aanmelding_afspraak_eindtijd))
				{
					$item_eindtijd 			= explode(':', $afspraak->aanmelding_afspraak_eindtijd);
					$item_eindtijd_uren		= $item_eindtijd[0];
					$item_eindtijd_minuten	= $item_eindtijd[1];
				}
			}
			
			$item_voldoende = $afspraak->aanmelding_voldoende;
			$item_code = $afspraak->aanmelding_code;
		}
		
		
		// PAGINA TONEN
		
		$this->data['afspraak'] = $afspraak;
		
		$this->data['item_ID'] 					= $item_ID;
		$this->data['item_datum_dag'] 			= $item_datum_dag;
		$this->data['item_datum_maand'] 		= $item_datum_maand;
		$this->data['item_datum_jaar'] 			= $item_datum_jaar;
		$this->data['item_tijd_uren'] 			= $item_tijd_uren;
		$this->data['item_tijd_minuten'] 		= $item_tijd_minuten;
		$this->data['item_eindtijd_uren'] 		= $item_eindtijd_uren;
		$this->data['item_eindtijd_minuten'] 	= $item_eindtijd_minuten;
		$this->data['item_voldoende'] 			= $item_voldoende;
		$this->data['item_code'] 				= $item_code;
		
		$this->data['item_datum_feedback'] 		= $item_datum_feedback;
		$this->data['item_tijd_feedback'] 		= $item_tijd_feedback;
		$this->data['item_eindtijd_feedback'] 	= $item_eindtijd_feedback;
		$this->data['item_voldoende_feedback'] 	= $item_voldoende_feedback;
		$this->data['item_code_feedback'] 		= $item_code_feedback;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/afspraken_wijzigen';
		$this->load->view('cms/template', $pagina);
	}
	
	
	
	/* ========================= */
	/* = TOEVOEGEN EN WIJZIGEN = */
	/* ========================= */
	/*
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
		$item_beschrijving 				= '';
		$item_instelling_anoniem 		= '';
		$item_instelling_email_updates 	= '';
		
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
		$item_beschrijving_feedback 				= '';
		$item_instelling_anoniem_feedback 			= '';
		$item_instelling_email_updates_feedback 	= '';
		
		
		
		// FORMULIER VERZONDEN
		
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$fouten = 0;
			
			$item_bedrijfsnaam 			= trim($_POST['item_bedrijfsnaam']);
			$item_voornaam 				= trim($_POST['item_voornaam']);
			$item_tussenvoegsel 		= trim($_POST['item_tussenvoegsel']);
			$item_achternaam 			= trim($_POST['item_achternaam']);
			$item_geboortedatum_dag 	= trim($_POST['item_geboortedatum_dag']);
			$item_geboortedatum_maand 	= trim($_POST['item_geboortedatum_maand']);
			$item_geboortedatum_jaar 	= trim($_POST['item_geboortedatum_jaar']);
			$item_adres 				= trim($_POST['item_adres']);
			$item_postcode_cijfers 		= trim($_POST['item_postcode_cijfers']);
			$item_postcode_letters 		= trim($_POST['item_postcode_letters']);
			$item_plaats 				= trim($_POST['item_plaats']);
			$item_telefoonnummer 		= trim($_POST['item_telefoonnummer']);
			$item_mobiel 				= trim($_POST['item_mobiel']);
			$item_emailadres 			= trim($_POST['item_emailadres']);
			$item_beschrijving 			= trim($_POST['item_beschrijving']);
			
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
			
			if(!isset($_POST['item_geslacht']))
			{
				$fouten++;
				$item_geslacht_feedback = 'Graag selecteren';
			}
			else
			{
				$item_geslacht = trim($_POST['item_geslacht']);
			}
			
			if(empty($item_geboortedatum_dag) || empty($item_geboortedatum_maand) || empty($item_geboortedatum_jaar))
			{
				$fouten++;
				$item_geboortedatum_feedback = 'Graag volledig invullen';
			}
			else
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
			
			if(empty($item_adres))
			{
				$fouten++;
				$item_adres_feedback = 'Graag invullen';
			}
			
			if(empty($item_postcode_cijfers) || empty($item_postcode_letters))
			{
				$fouten++;
				$item_postcode_feedback = 'Graag invullen';
			}
			
			if(empty($item_plaats))
			{
				$fouten++;
				$item_plaats_feedback = 'Graag invullen';
			}
			
			if(empty($item_telefoonnummer) && empty($item_mobiel))
			{
				$fouten++;
				$item_telefoonnummer_feedback = 'Graag invullen';
				$item_mobiel_feedback = 'Graag invullen';
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
			}
			
			if(!isset($_POST['item_instelling_anoniem']))
			{
				$fouten++;
				$item_instelling_anoniem_feedback = 'Graag selecteren';
			}
			else
			{
				$item_instelling_anoniem = trim($_POST['item_instelling_anoniem']);
			}
			
			if(!isset($_POST['item_instelling_email_updates']))
			{
				$fouten++;
				$item_instelling_email_updates_feedback = 'Graag selecteren';
			}
			else
			{
				$item_instelling_email_updates = trim($_POST['item_instelling_email_updates']);
			}
			
			if($fouten == 0)
			{
				// TOEVOEGEN / UPDATEN
				
				$data = array(
					'gebruiker_rechten' => 'deelnemer',
					'gebruiker_bedrijfsnaam' => $item_bedrijfsnaam,
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
					'gebruiker_beschrijving' => $item_beschrijving,
					'gebruiker_instelling_anoniem' => $item_instelling_anoniem,
					'gebruiker_instelling_email_updates' => $item_instelling_email_updates
				);
				
				if($actie == 'toevoegen')
				{
					$item_wachtwoord = 'localhost';
					$data['gebruiker_wachtwoord'] = sha1($item_wachtwoord);
					$q = $this->gebruikers_model->insertDeelnemer($data);
				}
				else
				{
					$q = $this->gebruikers_model->updateDeelnemer($item_ID, $data);
				}
				
				if($q)
				{
					if($actie == 'toevoegen') redirect('cms/deelnemers');
					else redirect('cms/deelnemers/'.$item_ID);
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
				$item_beschrijving 				= $item->gebruiker_beschrijving;
				$item_instelling_anoniem 		= $item->gebruiker_instelling_anoniem;
				$item_instelling_email_updates 	= $item->gebruiker_instelling_email_updates;
			}
		}
		
		
		// PAGINA TONEN
		
		$this->data['actie'] = $actie;
		
		$this->data['item_ID'] 							= $item_ID;
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
		$this->data['item_beschrijving'] 				= $item_beschrijving;
		$this->data['item_instelling_anoniem'] 			= $item_instelling_anoniem;
		$this->data['item_instelling_email_updates'] 	= $item_instelling_email_updates;
		
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
		$this->data['item_beschrijving_feedback'] 				= $item_beschrijving_feedback;
		$this->data['item_instelling_anoniem_feedback'] 		= $item_instelling_anoniem_feedback;
		$this->data['item_instelling_email_updates_feedback'] 	= $item_instelling_email_updates_feedback;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/deelnemers_wijzigen';
		$this->load->view('cms/template', $pagina);
	}
	
	
	
	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */
	/*
	public function verwijderen($item_ID = null, $bevestiging = null)
	{
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
	*/
}