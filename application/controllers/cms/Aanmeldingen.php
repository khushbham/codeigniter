<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aanmeldingen extends CI_Controller
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

		$aanmeldingen_archief = array();
		$aanmeldingen = array();
		$aanmeldingen_temp = $this->aanmeldingen_model->getAanmeldingen();

		foreach($aanmeldingen_temp as $aanmelding) {
            if($aanmelding->aanmelding_archief == 1) {
                array_push($aanmeldingen_archief, $aanmelding);
            } else {
                array_push($aanmeldingen, $aanmelding);
            }
        }

		$this->data['aanmeldingen'] = $aanmeldingen;
		$this->data['aanmeldingen_archief'] = $aanmeldingen_archief;
        $this->data['archief'] = false;

		$aanmeldingen_afgerond = $this->aanmeldingen_model->getAanmeldingenWorkshopsAfgerond();
		$this->data['aanmeldingen_afgerond'] = $aanmeldingen_afgerond;

        $aanmeldingen_verlopen = $this->aanmeldingen_model->getAanmeldingenVerlopen();
        $this->data['aanmeldingen_verlopen'] = $aanmeldingen_verlopen;

		// PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/aanmeldingen';
		$this->load->view('cms/template', $pagina);
	}



	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */

	public function detail($item_ID = null)
	{
		if($item_ID == null) redirect('cms/aanmeldingen');


		$this->load->model('aanmeldingen_model');
		$this->load->model('bestellingen_model');
		$this->load->model('lessen_model');


		// Aanmelding ophalen

		$item = $this->aanmeldingen_model->getAanmeldingByID($item_ID);
		if($item == null) redirect('cms/aanmeldingen');
		$this->data['item'] = $item;


		// Bestelling ophalen (indien aanwezig)

		$bestelling = $this->bestellingen_model->getBestellingByAanmeldingID($item->aanmelding_ID);
		$this->data['bestelling'] = $bestelling;


		// Bestelling producten ophalen (indien aanwezig)

		if($bestelling != null)
		{
			$producten = $this->bestellingen_model->getProductenByBestellingID($bestelling->bestelling_ID);
			$this->data['producten'] = $producten;
		}


		// iDEAL statusupdates

		$statusupdates = array(
			00 => 'Transactie succesvol',
			02 => 'Autorisatielimiet creditcard is overschreden. Neem contact op met het Support Team Rabo OmniKassa.',
			03 => 'Ongeldig contract',
			05 => 'Geweigerd',
			12 => 'Ongeldige transactie. Controleer de velden in het betaalverzoek.',
			14 => 'Ongeldig creditcardnummer, ongeldig card security code, ongeldige card (MasterCard) of ongeldig Card Verification Value (MasterCard of VISA)',
			17 => 'Annulering van de betaling door de gebruiker',
			24 => 'Ongeldige status',
			25 => 'Transactie niet gevonden in de database',
			30 => 'Ongeldig formaat',
			34 => 'Er is een verdenking van fraude',
			40 => 'Handeling niet toegestaan voor de webwinkel',
			60 => 'In afwachting van de statusmelding',
			63 => 'Er is een probleem in de beveiliging geconstateerd. Transactie is beëindigd.',
			75 => 'Het maximaal aantal toegestane pogingen voor invoering creditcardnummer (3) is overschreden.',
			90 => 'Rabo OmniKassa-server tijdelijk niet bereikbaar',
			94 => 'Dubbele transactie',
			97 => 'Tijd overschreden. Transactie is geweigerd.',
			99 => 'Betaalpagina tijdelijk niet bereikbaar'
		);

		$this->data['statusupdates'] = $statusupdates;


		// Lessen ophalen

		if(in_array($item->workshop_type, array('groep', 'online')))
		{
			// Groepslessen ophalen
			$this->data['lessen'] = $this->lessen_model->getLessenByGroepID($item->groep_ID);
		}
		else
		{
			// Individuele lessen ophalen
			$this->data['lessen'] = $this->lessen_model->getLessenByGebruikerID($item->gebruiker_ID, $item->aanmelding_workshop_ID);
		}


		// PAGINA LADEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/aanmeldingen_detail';
		$this->load->view('cms/template', $pagina);
	}



	/* ========================= */
	/* = TOEVOEGEN EN WIJZIGEN = */
	/* ========================= */


	public function toevoegen($workshop_ID = null)
	{
		$this->_toevoegen_wijzigen('toevoegen', $workshop_ID);
	}

	public function wijzigen($item_ID = null)
	{
		if($item_ID == null) redirect('cms/aanmeldingen');
		$this->_toevoegen_wijzigen('wijzigen', $item_ID);
	}

	private function _toevoegen_wijzigen($actie, $item_ID = null)
	{
		$this->load->model('aanmeldingen_model');
		$this->load->model('workshops_model');
		$this->load->model('groepen_model');

		$workshops 					= $this->workshops_model->getAlleWorkshops();
		$groepen 					= $this->groepen_model->getAlleGroepen();
		$workshop_ID				= '';
		$groep_ID					= '';
		$item_betaald 				= 'nee';
		$item_afgerond 				= 'nee';

		$item_betaald_feedback 		= '';
		$item_afgerond_feedback 	= '';

		// FORMULIER VERZONDEN
		if(isset($_POST['workshop_ID']))
		{
			$fouten 				= 0;

			if(isset($_POST['item_betaald']))
			{
				$item_betaald = $_POST['item_betaald'];
			}
			else
			{
				$item_betaald_feedback = 'Graag selecteren';
				$fouten++;
			}

			if(isset($_POST['item_afgerond']))
			{
				$item_afgerond = $_POST['item_afgerond'];
			}
			else
			{
				$item_afgerond_feedback = 'Graag selecteren';
				$fouten++;
			}

			if($fouten == 0)
			{
					$data = array();

					if($item_betaald != $_SESSION['item_betaald'])
					{
						if($item_betaald == 'nee') $data['aanmelding_betaald_datum'] = '0000-00-00 00:00:00';
						else $data['aanmelding_betaald_datum'] = date('Y-m-d H:i:s');
					}

					if($item_afgerond != $_SESSION['item_afgerond'])
					{
						if($item_afgerond == 'nee')
						{
							$data['aanmelding_afgerond'] = 0;
							$data['aanmelding_afgerond_datum'] = '0000-00-00 00:00:00';
						}
						else
						{
							$data['aanmelding_afgerond'] = 1;
							$data['aanmelding_afgerond_datum'] = date('Y-m-d H:i:s');
						}
					}

					if($actie != 'toevoegen') {
						$data['workshop_ID'] = $_POST['workshop_ID'];

						if(isset($_POST['groep_ID'])) {
							$data['groep_ID'] = $_POST['groep_ID'];
						}
					}

					if($actie == 'toevoegen') $q = $this->aanmeldingen_model->insertAanmelding($data);
					else $q = $this->aanmeldingen_model->updateAanmeldingByID($item_ID, $data);

					if($q)
					{
						if($actie == 'toevoegen') redirect('cms/aanmeldingen');
						else redirect('cms/aanmeldingen/'.$item_ID);
					}
					else
					{
						echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
					}
				}
			}

		if($actie == 'wijzigen')
		{
			// AANMELDING OPHALEN

			$item = $this->aanmeldingen_model->getAanmeldingByID($item_ID);
			if($item == null) redirect('cms/aanmeldingen');

			if($item->aanmelding_betaald_datum != '0000-00-00 00:00:00') $item_betaald = 'ja';
			if($item->aanmelding_afgerond) $item_afgerond = 'ja';

			$workshop_ID = $item->workshop_ID;
			$groep_ID = $item->groep_ID;
		}

		// HUIDIGE GEGEVENS OPSLAAN IN SESSIE

		$_SESSION['item_betaald'] 	= $item_betaald;
		$_SESSION['item_afgerond'] 	= $item_afgerond;


		// PAGINA TONEN

		$this->data['actie'] = $actie;

		$this->data['item_ID']		 			= $item_ID;
		$this->data['item_betaald'] 			= $item_betaald;
		$this->data['item_afgerond']			= $item_afgerond;
		$this->data['workshops']		 		= $workshops;
		$this->data['groepen']		 			= $groepen;

		$this->data['workshop_ID']		 		= $workshop_ID;
		$this->data['groep_ID']		 			= $groep_ID;

		$this->data['item_betaald_feedback'] 	= $item_betaald_feedback;
		$this->data['item_afgerond_feedback'] 	= $item_afgerond_feedback;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/aanmeldingen_wijzigen';
		$this->load->view('cms/template', $pagina);
	}



	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */

	public function verwijderen($item_ID = null, $bevestiging = null)
	{
	    if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'support') { redirect('cms/rechten'); }
		echo 'Binnenkort';
	}

	public function niet_betaald($item_ID, $dashboard = false) {
        $this->load->model('aanmeldingen_model');
        $aanmelding = $this->aanmeldingen_model->getAanmeldingByID($item_ID);

        if (!$aanmelding) {
              redirect('cms/aanmeldingen');
		}

        $aanmelding_datum = date('d-m-Y H:i:s', strtotime($aanmelding->aanmelding_datum));

        $link = 'https://localhost/aanmelden/afronden/'.$aanmelding->aanmelding_type.'/'.$aanmelding->workshop_url.'/'.$aanmelding->aanmelding_ID.'/'.$aanmelding->aanmelding_code;

        if($aanmelding->aanmelding_type == 'workshop')
        {
            $email_onderwerp = 'Aanmelding '.$aanmelding->workshop_titel.' afronden';
            $aangemeld_voor = $aanmelding->workshop_titel;
        }
        else if($aanmelding->aanmelding_type == 'kennismakingsworkshop')
        {
            $email_onderwerp = 'Aanmelding '.$aanmelding->kennismakingsworkshop_titel.' afronden';
            $aangemeld_voor = $aanmelding->kennismakingsworkshop_titel;
            $link = 'https://localhost/aanmelden/afronden/'.$aanmelding->aanmelding_type.'/'.date('d-m-Y', strtotime($aanmelding->kennismakingsworkshop_datum)).'/'.$aanmelding->aanmelding_ID.'/'.$aanmelding->aanmelding_code;
        }
        else
        {
            $email_onderwerp = 'Aanmelding '.$aanmelding->aanmelding_type.' '.$aanmelding->workshop_titel.' afronden';
            $aangemeld_voor = $aanmelding->aanmelding_type.' van de '.$aanmelding->workshop_titel;
        }


        $email_bericht  = '<h1>'.$email_onderwerp.'</h1>';
        $email_bericht .= '<p>Beste '.ucfirst($aanmelding->gebruiker_voornaam).',</p>';
        $email_bericht .= '<p>Volgens onze informatie heb je jezelf op '.date('d-m-Y', strtotime($aanmelding->aanmelding_datum)).' om '.date('H.i', strtotime($aanmelding->aanmelding_datum)).' uur geprobeerd aan te melden voor de '.$aangemeld_voor.'. De betaling is alleen nog niet afgerond. <a href="'.$link.'" title="'.$email_onderwerp.'">Klik hier</a> om de betaling alsnog af te ronden.</p>';
        $email_bericht .= '<p>Let op: De link is slechts 24 uur beschikbaar, daarna wordt je aanmelding automatisch uit ons systeem verwijderd.</p>';
        $email_bericht .= '<p>Met vriendelijk groet,</p>';
        $email_bericht .= '<p>localhost</p>';

        $email = array(
            'html' => $email_bericht,
            'subject' => $email_onderwerp,
            'from_email' => 'info@localhost',
            'from_name' => 'localhost',
            'to' => array(
                array(
                    'email' => $aanmelding->gebruiker_emailadres,
                    'name' => $aanmelding->gebruiker_naam,
                    'type' => 'to'
                )
            ),
            //'bcc_address' => 'mark@flitsend-webdesign.nl',
            'headers' => array('Reply-To' => 'info@localhost'),
            'track_opens' => true,
            'track_clicks' => true,
            'auto_text' => true
        );

        $this->load->helper('mandrill');
		// $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
        $this->mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');

        $this->mandrill->messages->send($email);

        // UPDATE AANMELDING

        $data = array('aanmelding_herinnering_datum' => date('Y-m-d H:i:s'));
        $this->aanmeldingen_model->updateAanmeldingByID($aanmelding->aanmelding_ID, $data);

        if($dashboard == true) {
            redirect('cms/dashboard');
        } else {
            redirect('cms/aanmeldingen');
        }

    }

    public function aanmeldingenExporteren() {
        $this->load->model('aanmeldingen_model');
	    $aanmeldingen = $this->aanmeldingen_model->getNietGeexporteerdeAanmeldingen();

	        if(!empty($aanmeldingen)) {
                require(dirname(dirname(__FILE__)) . '/cms/Csv.php');

                foreach ($aanmeldingen as $item) {
                    if(empty($item->gebruiker_bedrijfsnaam)) $item->gebruiker_bedrijfsnaam = " ";
                    if(empty($item->gebruiker_naam)) $item->gebruiker_naam = " ";
                    if(empty($item->gebruiker_adres)) $item->gebruiker_adres = " ";
                    if(empty($item->gebruiker_postcode)) $item->gebruiker_postcode = " ";
                    if(empty($item->gebruiker_plaats)) $item->gebruiker_plaats = " ";
                    if(empty($item->gebruiker_emailadres)) $item->gebruiker_emailadres = " ";

                    $body_items[] = array(
                        str_replace(',', '', $item->gebruiker_bedrijfsnaam),
                        str_replace(',', '',$item->gebruiker_naam),
                        str_replace(',', '',$item->gebruiker_adres),
                        str_replace(',', '',$item->gebruiker_postcode),
                        str_replace(',', '',$item->gebruiker_plaats),
                        str_replace(',', '',$item->gebruiker_emailadres));
                }

                $begin_ID = PHP_INT_MAX;
                $eind_ID = NULL;

                foreach($aanmeldingen as $item) {
                    if($item->aanmelding_ID < $begin_ID) $begin_ID = $item->aanmelding_ID;
                    if($item->aanmelding_ID > $eind_ID) $eind_ID = $item->aanmelding_ID;

                    $data = array(
                        'aanmelding_geexporteerd' => 1
                    );

                    $this->aanmeldingen_model->updateAanmeldingByID($item->aanmelding_ID, $data);
                }

                $export = array(
                    'export_naam' => 'Export-Aanmeldingen-' . date('d-m-Y') . '.csv',
                    'begin_ID' => $begin_ID,
                    'eind_ID' => $eind_ID
                );

                $this->aanmeldingen_model->insertExport($export);

                $csv = new CSV();
                $csv->set_body_items($body_items);
                $csv->output_as_downloadCSV('Export-Aanmeldingen-' . date('d-m-Y') . '.csv', ";"); // prompts the user to download the file as 'export.csv' by default
            }


        $pagina['data'] = $this->data;
        redirect('cms/aanmeldingen/');
    }

    public function exportGeschiedenis() {
        $this->load->model('aanmeldingen_model');
        $exports = $this->aanmeldingen_model->getExportGeschiedenis();

        $this->data['exports'] 	= $exports;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/aanmelding_exports';
        $this->load->view('cms/template', $pagina);
    }

    public function geschiedenisExport($item_ID) {
	    if(empty($item_ID)) redirect('cms/aanmeldingen');

        $this->load->model('aanmeldingen_model');
        $export = $this->aanmeldingen_model->getExportByID($item_ID);

        if(!empty($export)) {
            $aanmeldingen = $this->aanmeldingen_model->getExportAanmeldingen($export->begin_ID, $export->eind_ID);

            require(dirname(dirname(__FILE__)) . '/cms/Csv.php');

            foreach ($aanmeldingen as $item) {
                if (empty($item->gebruiker_bedrijfsnaam)) $item->gebruiker_bedrijfsnaam = " ";
                if (empty($item->gebruiker_naam)) $item->gebruiker_naam = " ";
                if (empty($item->gebruiker_adres)) $item->gebruiker_adres = " ";
                if (empty($item->gebruiker_postcode)) $item->gebruiker_postcode = " ";
                if (empty($item->gebruiker_plaats)) $item->gebruiker_plaats = " ";
                if (empty($item->gebruiker_emailadres)) $item->gebruiker_emailadres = " ";

                $body_items[] = array(
                    str_replace(',', '', $item->gebruiker_bedrijfsnaam),
                    str_replace(',', '', $item->gebruiker_naam),
                    str_replace(',', '', $item->gebruiker_adres),
                    str_replace(',', '', $item->gebruiker_postcode),
                    str_replace(',', '', $item->gebruiker_plaats),
                    str_replace(',', '', $item->gebruiker_emailadres));
            }

            $csv = new CSV();
            $csv->set_body_items($body_items);
            $csv->output_as_downloadCSV($export->export_naam, ';'); // prompts the user to download the file as 'export.csv' by default
        }

        redirect($this->exportGeschiedenis());
    }

    public function exportVerwijderen($item_ID) {
        if(empty($item_ID)) redirect('cms/aanmeldingen');

        $this->load->model('aanmeldingen_model');
        $this->aanmeldingen_model->deleteExport($item_ID);

        redirect('cms/aanmeldingen');
	}

	public function wachtwoord($aanmelding_ID)
	{
		$gebruiker_emailadres = '';
		$this->load->model('aanmeldingen_model');

			$aanmelding = $this->aanmeldingen_model->getAanmeldingByID($aanmelding_ID);
			$gebruiker_emailadres = $aanmelding->gebruiker_emailadres;

			if(!empty($gebruiker_emailadres))
			{
				$this->load->model('gebruikers_model');
				$gebruiker = $this->gebruikers_model->checkEmailadres($gebruiker_emailadres);

				if($gebruiker != null)
				{
					if(!$this->session->userdata('wachtwoord_gebruiker'))
					{
						// Nieuw wachtwoord genereren

						$gebruiker_wachtwoord = substr(str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);

						// Nieuw wachtwoord opslaan

						$data = array('gebruiker_wachtwoord' => sha1($gebruiker_wachtwoord));

						$updaten = $this->gebruikers_model->updateGebruiker($gebruiker->gebruiker_ID, $data);

						if($updaten)
						{
							$this->session->set_userdata('wachtwoord_gebruiker', $gebruiker_wachtwoord);
						}
					}
					else
					{
						$gebruiker_wachtwoord = $this->session->userdata('wachtwoord_gebruiker');
					}

					if(!$this->session->userdata('wachtwoord_verzonden'))
					{
						$this->_email_wachtwoord($gebruiker, $gebruiker_wachtwoord);
					}
				}
			}

			redirect('cms/deelnemers/'. $gebruiker->gebruiker_ID);
	}

	public function user_role()
	{
		$this->load->model('aanmeldingen_model');
		$this->data['permissions'] = $this->aanmeldingen_model->get_permissions();
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/rollen';
        $this->load->view('cms/template', $pagina);
	}

	private function _email_wachtwoord($gebruiker, $wachtwoord)
	{
		// GEBRUIKER E-MAILEN

		$email_titel = '';
		$email_tekst = '';
		$email_bericht = '';

		$email_van_emailadres = 'info@localhost';
		$email_van_naam = 'localhost';
		$email_aan_emailadres = $gebruiker->gebruiker_emailadres;
		$email_aan_naam = $gebruiker->gebruiker_naam;


		// BERICHT OPSTELLEN

		$email_titel = 'Nieuw wachtwoord';
		$email_tekst = 'Er is een nieuw wachtwoord voor je aangemaakt:<br />'.$wachtwoord;

		$email_bericht = '
			<h1>'.$email_titel.'</h1>
			<p>Beste '.$gebruiker->gebruiker_voornaam.',</p>
			<p>'.$email_tekst.'</p>
			<p>Groeten,<br />
			localhost</p>';


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
		    'headers' => array('Reply-To' => $email_van_emailadres),
		    'track_opens' => true,
		    'track_clicks' => true,
		    'auto_text' => true
		);


		// E-MAIL VERZENDEN VIA MANDRILL

		$this->load->helper('mandrill');
		// $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
		$mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');

		$feedback = $mandrill->messages->send($email);

		if($feedback[0]['status'] == 'sent')
		{
			$this->session->set_userdata('wachtwoord_verzonden', true);
		}
		else
		{
			echo 'Er kon geen e-mail worden verzonden';
		}
	}
}
