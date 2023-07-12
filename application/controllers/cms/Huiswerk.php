<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Huiswerk extends CI_Controller
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
		$this->load->model('huiswerk_model');
        if ($this->session->userdata('beheerder_rechten') != 'docent' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker') {
            $resultaten = $this->huiswerk_model->getResultatenOnbekend();
            $this->data['resultaten'] = $resultaten;

            $beoordelingen = $this->huiswerk_model->getResultatenBeoordeeld();
            $this->data['beoordelingen'] = $beoordelingen;
        } else {
            $docent_ID = $this->session->userdata('beheerder_ID');

            $resultaten = $this->huiswerk_model->getResultatenOnbekendDocent($docent_ID);
            $this->data['resultaten'] = $resultaten;

            $beoordelingen = $this->huiswerk_model->getResultatenBeoordeeldDocent($docent_ID);
            $this->data['beoordelingen'] = $beoordelingen;
        }

		// PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/huiswerk';
		$this->load->view('cms/template', $pagina);
	}



	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */

	public function beoordelen($item_ID = null)
	{
		if($item_ID == null) redirect('cms/huiswerk');

		$this->load->model('huiswerk_model');
		$this->load->model('docenten_model');
		$this->load->model('lessen_model');
		$resultaat = $this->huiswerk_model->getResultaatByID($item_ID);
		if($resultaat == null) redirect('cms/huiswerk');
		$this->data['resultaat'] = $resultaat;

		$huiswerk = $this->huiswerk_model->getHuiswerk($resultaat->gebruiker_ID, $resultaat->les_ID);

		$opnieuw = $this->huiswerk_model->getHuiswerkOpnieuw($resultaat->gebruiker_ID, $resultaat->les_ID);


        if($resultaat->docent_ID) {
            $docent = $this->docenten_model->getDocentByGebruikerID($resultaat->docent_ID);
            $this->data['docent'] 	= $docent;
        }

        $beoordelingscriteria = $this->lessen_model->getBeoordelingscriteria();
        $this->data['beoordelingscriteria'] = $beoordelingscriteria;

        if($resultaat->workshop_niveau == 5) {
            $beoordelingscriteriaVWS = $this->lessen_model->getBeoordelingscriteriaVWS();
            $this->data['beoordelingscriteriaVWS'] = $beoordelingscriteriaVWS;
        }

        if(!empty($huiswerk)) {
            foreach($huiswerk as $item) {
                $beoordelingscriteria_resultaat = $this->lessen_model->getBeoordelingscriteriaAndHuiswerk($item->huiswerk_ID);
                $item->resultaten = $beoordelingscriteria_resultaat;
            }
        }

        if(!empty($opnieuw)) {
            foreach($opnieuw as $item) {
                $beoordelingscriteria_resultaat = $this->lessen_model->getBeoordelingscriteriaAndHuiswerk($item->huiswerk_ID);
                $item->resultaten = $beoordelingscriteria_resultaat;
            }
        }

        $this->data['huiswerk'] = $huiswerk;
		$this->data['opnieuw'] = $opnieuw;

        $les = $this->lessen_model->getLesByID($resultaat->les_ID);

		$item_voldoende			= '';
		$item_feedback_src		= '';
		$item_feedback_tekst	= '';
		$item_notities			= '';
        $item_opnieuw			= '';
        $item_criteria_voldoende = '';

		$item_voldoende_feedback		= '';
		$item_feedback_src_feedback		= '';
		$item_feedback_tekst_feedback	= '';
		$item_opnieuw_feedback			= '';


		// FORMULIER VERZONDEN

		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if(isset($_POST['item_voldoende']))
			{
				$fouten = 0;
                $docent = $this->docenten_model->getDocentByGebruikerID($this->session->userdata('beheerder_ID'));

                if(!isset($docent->docent_ID)) {
                    $docent_ID = null;
                } else {
                    $docent_ID = $docent->docent_ID;
                }

				$opnieuw_insturen		= trim($_POST['opnieuw_insturen']);
				$item_voldoende 		= trim($_POST['item_voldoende']);
				$item_feedback_tekst	= trim($_POST['item_feedback_tekst']);
				$item_notities			= trim($_POST['item_notities']);
				$item_opnieuw			= trim($_POST['item_opnieuw']);
                $nagekeken_door		    = $docent_ID;

                $item_criteria_voldoende = $_POST['item_criteria_voldoende'];

				if(($item_voldoende == 'nee' && $item_opnieuw == '' && $opnieuw_insturen != 'ja'))
				{
					$fouten++;
					$item_opnieuw_feedback = 'Graag een aantal invullen';
				}

                if ($item_voldoende == 'nee' && ($_POST['item_opnieuw'] > $les->les_huiswerk_aantal || !is_numeric($item_opnieuw) ||  $_POST['item_opnieuw'] < 0))
                {
                    $fouten++;
                    $item_opnieuw_feedback = 'Vul een geldig getal in';
                }

				if($fouten == 0) {
                    if ($_POST['audio_option'] == 'audio_src') {
                        if ($_FILES['item_feedback_src']['error'] > 0) {
                            switch ($_FILES['item_feedback_src']['error']) {
                                case 1:
                                    $item_feedback_src_feedback = 'Het bestand is te groot';
                                    break;

                                case 2:
                                    $item_feedback_src_feedback = 'Het bestand is te groot';
                                    break;

                                case 3:
                                    $item_feedback_src_feedback = 'Het bestand is niet goed geupload';
                                    break;

                                case 4:
                                    $item_feedback_src_feedback = 'Graag een bestand selecteren';
                                    break;

                                case 6:
                                    $item_feedback_src_feedback = 'Geen tijdelijke folder';
                                    break;

                                case 7:
                                    $item_feedback_src_feedback = 'Kon bestand niet uploaden';
                                    break;
                            }
                        }

                            $bestand_types = array('audio/mp3');
                            $bestand_extensies = array('mp3');
                            $bestand_naam = $_FILES['item_feedback_src']['name'];
                            $bestand_type = $_FILES['item_feedback_src']['type'];
                            $bestand_grootte = $_FILES['item_feedback_src']['size'];
                            $bestand_tijdelijke_naam = $_FILES['item_feedback_src']['tmp_name'];

                            $bestand_type_extensie = explode('.', $bestand_naam);
                            $bestand_type_extensie = strtolower(end($bestand_type_extensie));

                            if (in_array($bestand_type_extensie, $bestand_extensies) || empty($_FILES['item_feedback_src']['name'])) {
                                if ($bestand_grootte < 10000000) // 10 MB
                                {
                                    if ($item_voldoende == 'ja') $beoordeling = 'voldoende';
                                    else $beoordeling = 'onvoldoende';

                                    $bestandsnaam = $resultaat->gebruiker_ID . '-' . $resultaat->workshop_ID . '-' . $resultaat->les_ID . '-' . date('Ymd-His') . '-' . $beoordeling . '.' . $bestand_type_extensie;

                                    if (move_uploaded_file($bestand_tijdelijke_naam, './media/huiswerk/' . $bestandsnaam)) {
                                        if ($opnieuw_insturen != 'ja') {
                                            // Eerste beoordeling

                                            $data = array(
                                                'resultaat_beoordelen' => 'nee',
                                                'resultaat_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                                'resultaat_voldoende' => $item_voldoende,
                                                'resultaat_opnieuw' => $item_opnieuw,
                                                'resultaat_feedback_src' => $bestandsnaam,
                                                'resultaat_feedback_tekst' => $item_feedback_tekst,
                                                'resultaat_notities' => $item_notities,
                                                'docent_ID' => $nagekeken_door
                                            );
                                        } else {
                                            // Tweede beoordeling

                                            $data = array(
                                                'resultaat_beoordelen' => 'nee',
                                                'resultaat_opnieuw_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                                'resultaat_opnieuw_voldoende' => $item_voldoende,
                                                'resultaat_opnieuw_feedback_src' => $bestandsnaam,
                                                'resultaat_opnieuw_feedback_tekst' => $item_feedback_tekst,
                                                'resultaat_notities' => $item_notities,
                                                'docent_ID' => $nagekeken_door

                                            );
                                        }

                                        // UPDATEN

                                        $updaten = $this->huiswerk_model->updateResultaat($resultaat->gebruiker_ID, $resultaat->les_ID, $data);

                                        if ($updaten) {
                                            if(!empty($item_criteria_voldoende)) {
                                                foreach($item_criteria_voldoende as $key => $huiswerk) {
                                                    foreach($huiswerk as $key_h => $criteria) {
                                                            $data_beoordeling = array(
                                                                'beoordelingscriteria_ID' => $key,
                                                                'huiswerk_ID' => $key_h,
                                                                'beoordelingscriteria_resultaat' => $criteria
                                                            );


                                                            $q = $this->lessen_model->insertBeoordelingscriteriaResultaat($data_beoordeling);
                                                    }
                                                }
                                            }

                                            if ($resultaat->gebruiker_instelling_email_updates == 'ja') {
                                                // E-MAIL OPSTELLEN

                                                $email_van_emailadres = 'info@localhost';
                                                $email_van_naam = 'localhost';
                                                $email_aan_emailadres = $resultaat->gebruiker_emailadres;
                                                $email_aan_naam = $resultaat->gebruiker_naam;


                                                // BERICHT OPSTELLEN

                                                $email_onderwerp = 'Je opdracht voor de les ' . $resultaat->les_titel . ' is beoordeeld';
                                                $email_tekst = '<p>Je hebt een beoordeling ontvangen voor jouw opdracht voor de les "' . $resultaat->les_titel . '" van de volgende workshop: "' . $resultaat->workshop_titel . '". Ga naar de <a href="https://localhost" title="Bezoek de website van localhost" target="_blank">Cursistenmodule</a> om de feedback van jouw docent te bekijken.</p>';

                                                $email_bericht = '
												<h1>' . $email_onderwerp . '</h1>
												<p>Beste ' . $resultaat->gebruiker_voornaam . ',</p>
												' . $email_tekst . '
												<p>Met vriendelijke groet,</p>
												<p>localhost</p>';


                                                // E-MAIL

                                                $email = array(
                                                    'html' => $email_bericht,
                                                    'subject' => $email_onderwerp,
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
                                                $mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
                                                // $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
                                                $feedback = $mandrill->messages->send($email);

                                                if ($feedback[0]['status'] == 'sent') {
                                                    // echo 'E-mail verzonden';
                                                } else {
                                                    // echo 'Er kon geen e-mail worden verzonden';
                                                }
                                            }

                                            // REDIRECT
                                            redirect('cms/huiswerk/beoordelen/' . $resultaat->resultaat_ID);
                                        } else {
                                            echo 'Item wijzigen mislukt. Probeer het nog eens.';
                                        }
                                    } else {
                                        if ($opnieuw_insturen != 'ja') {
                                            // Eerste beoordeling

                                            $data = array(
                                                'resultaat_beoordelen' => 'nee',
                                                'resultaat_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                                'resultaat_voldoende' => $item_voldoende,
                                                'resultaat_opnieuw' => $item_opnieuw,
                                                'resultaat_feedback_tekst' => $item_feedback_tekst,
                                                'resultaat_notities' => $item_notities,
                                                'docent_ID' => $nagekeken_door
                                            );
                                        } else {
                                            // Tweede beoordeling

                                            $data = array(
                                                'resultaat_beoordelen' => 'nee',
                                                'resultaat_opnieuw_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                                'resultaat_opnieuw_voldoende' => $item_voldoende,
                                                'resultaat_opnieuw_feedback_tekst' => $item_feedback_tekst,
                                                'resultaat_notities' => $item_notities,
                                                'docent_ID' => $nagekeken_door

                                            );
                                        }

                                        // UPDATEN

                                        $updaten = $this->huiswerk_model->updateResultaat($resultaat->gebruiker_ID, $resultaat->les_ID, $data);

                                        if ($updaten) {
                                            if(!empty($item_criteria_voldoende)) {
                                                foreach($item_criteria_voldoende as $key => $huiswerk) {
                                                    foreach($huiswerk as $key_h => $criteria) {
                                                            $data_beoordeling = array(
                                                                'beoordelingscriteria_ID' => $key,
                                                                'huiswerk_ID' => $key_h,
                                                                'beoordelingscriteria_resultaat' => $criteria
                                                            );


                                                            $q = $this->lessen_model->insertBeoordelingscriteriaResultaat($data_beoordeling);
                                                    }
                                                }
                                            }

                                            if ($resultaat->gebruiker_instelling_email_updates == 'ja') {
                                                // E-MAIL OPSTELLEN

                                                $email_van_emailadres = 'info@localhost';
                                                $email_van_naam = 'localhost';
                                                $email_aan_emailadres = $resultaat->gebruiker_emailadres;
                                                $email_aan_naam = $resultaat->gebruiker_naam;


                                                // BERICHT OPSTELLEN

                                                $email_onderwerp = 'Je opdracht voor de les ' . $resultaat->les_titel . ' is beoordeeld';
                                                $email_tekst = '<p>Je hebt een beoordeling ontvangen voor jouw opdracht voor de les "' . $resultaat->les_titel . '" van de volgende workshop: "' . $resultaat->workshop_titel . '". Ga naar de <a href="https://localhost" title="Bezoek de website van localhost" target="_blank">Cursistenmodule</a> om de feedback van jouw docent te bekijken.</p>';

                                                $email_bericht = '
                                                <h1>' . $email_onderwerp . '</h1>
                                                <p>Beste ' . $resultaat->gebruiker_voornaam . ',</p>
                                                ' . $email_tekst . '
                                                <p>Met vriendelijke groet,</p>
                                                <p>localhost</p>';


                                                // E-MAIL

                                                $email = array(
                                                    'html' => $email_bericht,
                                                    'subject' => $email_onderwerp,
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
                                                $mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
                                                // $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
                                                $feedback = $mandrill->messages->send($email);

                                                if ($feedback[0]['status'] == 'sent') {
                                                    // echo 'E-mail verzonden';
                                                } else {
                                                    // echo 'Er kon geen e-mail worden verzonden';
                                                }
                                            }

                                            // REDIRECT
                                            redirect('cms/huiswerk/beoordelen/' . $resultaat->resultaat_ID);
                                        } else {
                                            echo 'Item wijzigen mislukt. Probeer het nog eens.';
                                        }
                                    }
                                } else {
                                    $item_feedback_src_feedback = 'De MP3 is te groot (maximaal 10 MB)';
                                }
                            } else {
                                if ($opnieuw_insturen != 'ja') {
                                    // Eerste beoordeling

                                    $data = array(
                                        'resultaat_beoordelen' => 'nee',
                                        'resultaat_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                        'resultaat_voldoende' => $item_voldoende,
                                        'resultaat_opnieuw' => $item_opnieuw,
                                        'resultaat_feedback_tekst' => $item_feedback_tekst,
                                        'resultaat_notities' => $item_notities,
                                        'docent_ID' => $nagekeken_door
                                    );
                                } else {
                                    // Tweede beoordeling

                                    $data = array(
                                        'resultaat_beoordelen' => 'nee',
                                        'resultaat_opnieuw_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                        'resultaat_opnieuw_voldoende' => $item_voldoende,
                                        'resultaat_opnieuw_feedback_tekst' => $item_feedback_tekst,
                                        'resultaat_notities' => $item_notities,
                                        'docent_ID' => $nagekeken_door

                                    );
                                }

                                // UPDATEN

                                $updaten = $this->huiswerk_model->updateResultaat($resultaat->gebruiker_ID, $resultaat->les_ID, $data);

                                if ($updaten) {
                                    if(!empty($item_criteria_voldoende)) {
                                        foreach($item_criteria_voldoende as $key => $huiswerk) {
                                            foreach($huiswerk as $key_h => $criteria) {
                                                    $data_beoordeling = array(
                                                        'beoordelingscriteria_ID' => $key,
                                                        'huiswerk_ID' => $key_h,
                                                        'beoordelingscriteria_resultaat' => $criteria
                                                    );


                                                    $q = $this->lessen_model->insertBeoordelingscriteriaResultaat($data_beoordeling);
                                            }
                                        }
                                    }

                                    if ($resultaat->gebruiker_instelling_email_updates == 'ja') {
                                        // E-MAIL OPSTELLEN

                                        $email_van_emailadres = 'info@localhost';
                                        $email_van_naam = 'localhost';
                                        $email_aan_emailadres = $resultaat->gebruiker_emailadres;
                                        $email_aan_naam = $resultaat->gebruiker_naam;


                                        // BERICHT OPSTELLEN

                                        $email_onderwerp = 'Je opdracht voor de les ' . $resultaat->les_titel . ' is beoordeeld';
                                        $email_tekst = '<p>Je hebt een beoordeling ontvangen voor jouw opdracht voor de les "' . $resultaat->les_titel . '" van de volgende workshop: "' . $resultaat->workshop_titel . '". Ga naar de <a href="https://localhost" title="Bezoek de website van localhost" target="_blank">Cursistenmodule</a> om de feedback van jouw docent te bekijken.</p>';

                                        $email_bericht = '
                                        <h1>' . $email_onderwerp . '</h1>
                                        <p>Beste ' . $resultaat->gebruiker_voornaam . ',</p>
                                        ' . $email_tekst . '
                                        <p>Met vriendelijke groet,</p>
                                        <p>localhost</p>';


                                        // E-MAIL

                                        $email = array(
                                            'html' => $email_bericht,
                                            'subject' => $email_onderwerp,
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
                                        $mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
                                        // $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
                                        $feedback = $mandrill->messages->send($email);

                                        if ($feedback[0]['status'] == 'sent') {
                                            // echo 'E-mail verzonden';
                                        } else {
                                            // echo 'Er kon geen e-mail worden verzonden';
                                        }
                                    }

                                    // REDIRECT
                                    redirect('cms/huiswerk/beoordelen/' . $resultaat->resultaat_ID);
                                } else {
                                    echo 'Item wijzigen mislukt. Probeer het nog eens.';
                                }
                            }
                        } else {
                            if(!empty($_POST['upload'])) {
                                if ($item_voldoende == 'ja') $beoordeling = 'voldoende';
                                else $beoordeling = 'onvoldoende';

                                $bestandsnaam = $resultaat->gebruiker_ID . '-' . $resultaat->workshop_ID . '-' . $resultaat->les_ID . '-' . date('Ymd-His') . '-' . $beoordeling . '.mp3';
                                if (file_exists('./media/huiswerk/' . $_POST['upload'] . '-' . date('Ymd') . '.mp3')) {
                                    if (rename('./media/huiswerk/' . $_POST['upload'] . '-' . date('Ymd') . '.mp3', './media/huiswerk/' . $bestandsnaam)) {
                                        if ($opnieuw_insturen != 'ja') {
                                            // Eerste beoordeling

                                            $data = array(
                                                'resultaat_beoordelen' => 'nee',
                                                'resultaat_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                                'resultaat_voldoende' => $item_voldoende,
                                                'resultaat_opnieuw' => $item_opnieuw,
                                                'resultaat_feedback_src' => $bestandsnaam,
                                                'resultaat_feedback_tekst' => $item_feedback_tekst,
                                                'resultaat_notities' => $item_notities,
                                                'docent_ID' => $nagekeken_door
                                            );
                                        } else {
                                            // Tweede beoordeling
                                            $data = array(
                                                'resultaat_beoordelen' => 'nee',
                                                'resultaat_opnieuw_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                                'resultaat_opnieuw_voldoende' => $item_voldoende,
                                                'resultaat_opnieuw_feedback_src' => $bestandsnaam,
                                                'resultaat_opnieuw_feedback_tekst' => $item_feedback_tekst,
                                                'resultaat_notities' => $item_notities,
                                                'docent_ID' => $nagekeken_door

                                            );
                                        }

                                        // UPDATEN

                                        $updaten = $this->huiswerk_model->updateResultaat($resultaat->gebruiker_ID, $resultaat->les_ID, $data);

                                        if ($updaten) {
                                                if(!empty($item_criteria_voldoende)) {
                                                    foreach($item_criteria_voldoende as $key => $huiswerk) {
                                                        foreach($huiswerk as $key_h => $criteria) {
                                                                $data_beoordeling = array(
                                                                    'beoordelingscriteria_ID' => $key,
                                                                    'huiswerk_ID' => $key_h,
                                                                    'beoordelingscriteria_resultaat' => $criteria
                                                                );


                                                                $q = $this->lessen_model->insertBeoordelingscriteriaResultaat($data_beoordeling);
                                                        }
                                                    }
                                                }

                                            if ($resultaat->gebruiker_instelling_email_updates == 'ja') {
                                                // E-MAIL OPSTELLEN

                                                $email_van_emailadres = 'info@localhost';
                                                $email_van_naam = 'localhost';
                                                $email_aan_emailadres = $resultaat->gebruiker_emailadres;
                                                $email_aan_naam = $resultaat->gebruiker_naam;


                                                // BERICHT OPSTELLEN

                                                $email_onderwerp = 'Je opdracht voor de les ' . $resultaat->les_titel . ' is beoordeeld';
                                                $email_tekst = '<p>Je hebt een beoordeling ontvangen voor jouw opdracht voor de les "' . $resultaat->les_titel . '" van de volgende workshop: "' . $resultaat->workshop_titel . '". Ga naar de <a href="https://localhost" title="Bezoek de website van localhost" target="_blank">Cursistenmodule</a> om de feedback van jouw docent te bekijken.</p>';
                                                $email_bericht = '
                                            <h1>' . $email_onderwerp . '</h1>
                                            <p>Beste ' . $resultaat->gebruiker_voornaam . ',</p>
                                            ' . $email_tekst . '
                                            <p>Met vriendelijke groet,</p>
                                            <p>localhost</p>';


                                            // E-MAIL

                                            $email = array(
                                                'html' => $email_bericht,
                                                'subject' => $email_onderwerp,
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
                                            $mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
                                            // $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
                                            $feedback = $mandrill->messages->send($email);

                                            if ($feedback[0]['status'] == 'sent') {
                                                // echo 'E-mail verzonden';
                                            } else {
                                                // echo 'Er kon geen e-mail worden verzonden';
                                            }
                                                    }

                                            // REDIRECT

                                            redirect('cms/huiswerk/beoordelen/' . $resultaat->resultaat_ID);
                                        } else {
                                            echo 'Item wijzigen mislukt. Probeer het nog eens.';
                                        }
                                    }
                                } else {
                                    $item_feedback_src_feedback = 'Het bestand is niet geüpload';
                                }
                            } else {
                                if(empty($_POST['upload'])) {
                                if ($opnieuw_insturen != 'ja') {
                                    // Eerste beoordeling

                                    $data = array(
                                        'resultaat_beoordelen' => 'nee',
                                        'resultaat_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                        'resultaat_voldoende' => $item_voldoende,
                                        'resultaat_opnieuw' => $item_opnieuw,
                                        'resultaat_feedback_src' => $bestandsnaam,
                                        'resultaat_feedback_tekst' => $item_feedback_tekst,
                                        'resultaat_notities' => $item_notities,
                                        'docent_ID' => $nagekeken_door
                                    );
                                } else {
                                    // Tweede beoordeling

                                    $data = array(
                                        'resultaat_beoordelen' => 'nee',
                                        'resultaat_opnieuw_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                        'resultaat_opnieuw_voldoende' => $item_voldoende,
                                        'resultaat_opnieuw_feedback_src' => $bestandsnaam,
                                        'resultaat_opnieuw_feedback_tekst' => $item_feedback_tekst,
                                        'resultaat_notities' => $item_notities,
                                        'docent_ID' => $nagekeken_door

                                    );
                                }

                                 // UPDATEN

                                 $updaten = $this->huiswerk_model->updateResultaat($resultaat->gebruiker_ID, $resultaat->les_ID, $data);

                                 if ($updaten) {
                                     if(!empty($item_criteria_voldoende)) {
                                         foreach($item_criteria_voldoende as $key => $huiswerk) {
                                             foreach($huiswerk as $key_h => $criteria) {
                                                     $data_beoordeling = array(
                                                         'beoordelingscriteria_ID' => $key,
                                                         'huiswerk_ID' => $key_h,
                                                         'beoordelingscriteria_resultaat' => $criteria
                                                     );


                                                     $q = $this->lessen_model->insertBeoordelingscriteriaResultaat($data_beoordeling);
                                             }
                                         }
                                     }

                                     if ($resultaat->gebruiker_instelling_email_updates == 'ja') {
                                         // E-MAIL OPSTELLEN

                                         $email_van_emailadres = 'info@localhost';
                                         $email_van_naam = 'localhost';
                                         $email_aan_emailadres = $resultaat->gebruiker_emailadres;
                                         $email_aan_naam = $resultaat->gebruiker_naam;


                                         // BERICHT OPSTELLEN

                                         $email_onderwerp = 'Je opdracht voor de les ' . $resultaat->les_titel . ' is beoordeeld';
                                         $email_tekst = '<p>Je hebt een beoordeling ontvangen voor jouw opdracht voor de les "' . $resultaat->les_titel . '" van de volgende workshop: "' . $resultaat->workshop_titel . '". Ga naar de <a href="https://localhost" title="Bezoek de website van localhost" target="_blank">Cursistenmodule</a> om de feedback van jouw docent te bekijken.</p>';

                                         $email_bericht = '
                                         <h1>' . $email_onderwerp . '</h1>
                                         <p>Beste ' . $resultaat->gebruiker_voornaam . ',</p>
                                         ' . $email_tekst . '
                                         <p>Met vriendelijke groet,</p>
                                         <p>localhost</p>';


                                         // E-MAIL

                                         $email = array(
                                             'html' => $email_bericht,
                                             'subject' => $email_onderwerp,
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
                                         $mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
                                        //  $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
                                         $feedback = $mandrill->messages->send($email);

                                         if ($feedback[0]['status'] == 'sent') {
                                             // echo 'E-mail verzonden';
                                         } else {
                                             // echo 'Er kon geen e-mail worden verzonden';
                                         }
                                     }

                                     // REDIRECT
                                     redirect('cms/huiswerk/beoordelen/' . $resultaat->resultaat_ID);
                                 } else {
                                     echo 'Item wijzigen mislukt. Probeer het nog eens.';
                                 }
                             }
                            }
                        }
                }
			} else {
                $item_voldoende_feedback = 'Selecteer een optie';
            }
		}


		// PAGINA TONEN

		$this->data['item_voldoende'] 			= $item_voldoende;
		$this->data['item_feedback_src'] 		= $item_feedback_src;
		$this->data['item_feedback_tekst'] 		= $item_feedback_tekst;
		$this->data['item_notities'] 			= $item_notities;
		$this->data['item_opnieuw'] 			= $item_opnieuw;

		$this->data['item_voldoende_feedback'] 			= $item_voldoende_feedback;
		$this->data['item_feedback_src_feedback'] 		= $item_feedback_src_feedback;
		$this->data['item_feedback_tekst_feedback'] 	= $item_feedback_tekst_feedback;
		$this->data['item_opnieuw_feedback'] 			= $item_opnieuw_feedback;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/huiswerk_beoordelen';
		$this->load->view('cms/template', $pagina);
	}

public function edit_review($item_ID = null)
    {
        if($item_ID == null) redirect('cms/huiswerk');

        $this->load->model('huiswerk_model');
        $this->load->model('docenten_model');
        $this->load->model('lessen_model');
        $resultaat = $this->huiswerk_model->getResultaatByID($item_ID);

        if($resultaat == null) redirect('cms/huiswerk');
        $this->data['resultaat'] = $resultaat;

        $huiswerk = $this->huiswerk_model->getHuiswerk($resultaat->gebruiker_ID, $resultaat->les_ID);

        $opnieuw = $this->huiswerk_model->getHuiswerkOpnieuw($resultaat->gebruiker_ID, $resultaat->les_ID);


        if($resultaat->docent_ID) {
            $docent = $this->docenten_model->getDocentByGebruikerID($resultaat->docent_ID);
            $this->data['docent']   = $docent;
        }

        $beoordelingscriteria = $this->lessen_model->getBeoordelingscriteria();
        $this->data['beoordelingscriteria'] = $beoordelingscriteria;

        if(!empty($huiswerk)) {
            foreach($huiswerk as $item) {
                $beoordelingscriteria_resultaat = $this->lessen_model->getBeoordelingscriteriaAndHuiswerk($item->huiswerk_ID);
                $item->resultaten = $beoordelingscriteria_resultaat;
            }
        }

        if(!empty($opnieuw)) {
            foreach($opnieuw as $item) {
                $beoordelingscriteria_resultaat = $this->lessen_model->getBeoordelingscriteriaAndHuiswerk($item->huiswerk_ID);
                $item->resultaten = $beoordelingscriteria_resultaat;
            }
        }

        $this->data['huiswerk'] = $huiswerk;
        $this->data['opnieuw'] = $opnieuw;

        $les = $this->lessen_model->getLesByID($resultaat->les_ID);

        $item_voldoende         = $resultaat->resultaat_opnieuw_voldoende;
        $item_feedback_src      = '';
        $item_feedback_tekst    = $resultaat->resultaat_feedback_tekst;
        if(!empty($resultaat->item_notities)) {
            $item_notities          = $resultaat->item_notities;
        } else {
            $item_notities          = '';
        }
        $item_opnieuw           = '';
        $item_criteria_voldoende = '';

        $item_voldoende_feedback        = '';
        $item_feedback_src_feedback     = '';
        $item_feedback_tekst_feedback   = '';
        $item_opnieuw_feedback          = '';

        if($this->input->post('update_review'))
        {

            if(isset($_POST['item_voldoende']))
            {
                $fouten = 0;
                $docent = $this->docenten_model->getDocentByGebruikerID($this->session->userdata('beheerder_ID'));

                if(!isset($docent->docent_ID)) {
                    $docent_ID = null;
                } else {
                    $docent_ID = $docent->docent_ID;
                }

                $opnieuw_insturen       = trim($_POST['opnieuw_insturen']);
                $item_voldoende         = trim($_POST['item_voldoende']);
                $item_feedback_tekst    = trim($_POST['item_feedback_tekst']);
                $item_opnieuw_feedback_tekst = trim($_POST['resultaat_opnieuw_feedback_tekst']);


                $item_notities          = trim($_POST['item_notities']);
                $item_opnieuw           = trim($_POST['item_opnieuw']);
                $nagekeken_door         = $docent_ID;

                $item_criteria_voldoende = $_POST['item_criteria_voldoende'];

                if(($item_voldoende == 'nee' && $item_opnieuw == '' && $opnieuw_insturen != 'ja'))
                {
                    $fouten++;
                    $item_opnieuw_feedback = 'Graag een aantal invullen';
                }

                if ($item_voldoende == 'nee' && ($_POST['item_opnieuw'] > $les->les_huiswerk_aantal || !is_numeric($item_opnieuw) ||  $_POST['item_opnieuw'] < 0))
                {
                    $fouten++;
                    $item_opnieuw_feedback = 'Vul een geldig getal in';
                }

                if($fouten == 0) {
                    if ($_POST['audio_option'] == 'audio_src') {
                        if ($_FILES['item_feedback_src']['error'] > 0) {
                            switch ($_FILES['item_feedback_src']['error']) {
                                case 1:
                                    $item_feedback_src_feedback = 'Het bestand is te groot';
                                    break;

                                case 2:
                                    $item_feedback_src_feedback = 'Het bestand is te groot';
                                    break;

                                case 3:
                                    $item_feedback_src_feedback = 'Het bestand is niet goed geupload';
                                    break;

                                case 4:
                                    $item_feedback_src_feedback = 'Graag een bestand selecteren';
                                    break;

                                case 6:
                                    $item_feedback_src_feedback = 'Geen tijdelijke folder';
                                    break;

                                case 7:
                                    $item_feedback_src_feedback = 'Kon bestand niet uploaden';
                                    break;
                            }
                        }

                            $bestand_types = array('audio/mp3');
                            $bestand_extensies = array('mp3');
                            $bestand_naam = $_FILES['item_feedback_src']['name'];
                            $bestand_type = $_FILES['item_feedback_src']['type'];
                            $bestand_grootte = $_FILES['item_feedback_src']['size'];
                            $bestand_tijdelijke_naam = $_FILES['item_feedback_src']['tmp_name'];

                            $bestand_type_extensie = explode('.', $bestand_naam);
                            $bestand_type_extensie = strtolower(end($bestand_type_extensie));

                            if (in_array($bestand_type_extensie, $bestand_extensies) || empty($_FILES['item_feedback_src']['name'])) {
                                if ($bestand_grootte < 10000000) // 10 MB
                                {
                                    if ($item_voldoende == 'ja') $beoordeling = 'voldoende';
                                    else $beoordeling = 'onvoldoende';

                                    $bestandsnaam = $resultaat->gebruiker_ID . '-' . $resultaat->workshop_ID . '-' . $resultaat->les_ID . '-' . date('Ymd-His') . '-' . $beoordeling . '.' . $bestand_type_extensie;

                                    if (move_uploaded_file($bestand_tijdelijke_naam, './media/huiswerk/' . $bestandsnaam)) {
                                        if ($opnieuw_insturen != 'ja') {
                                            // Eerste beoordeling

                                            $data = array(
                                                'resultaat_beoordelen' => 'nee',
                                                'resultaat_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                                'resultaat_voldoende' => $item_voldoende,
                                                'resultaat_opnieuw' => $item_opnieuw,
                                                'resultaat_feedback_src' => $bestandsnaam,
                                                'resultaat_feedback_tekst' => $item_feedback_tekst,
                                                'resultaat_notities' => $item_notities,
                                                'docent_ID' => $nagekeken_door
                                            );
                                        } else {
                                            // Tweede beoordeling

                                            $data = array(
                                                'resultaat_beoordelen' => 'nee',
                                                'resultaat_opnieuw_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                                'resultaat_opnieuw_voldoende' => $item_voldoende,
                                                'resultaat_opnieuw_feedback_src' => $bestandsnaam,
                                                'resultaat_opnieuw_feedback_tekst' => $item_feedback_tekst,
                                                'resultaat_notities' => $item_notities,
                                                'docent_ID' => $nagekeken_door

                                            );
                                        }

                                        // UPDATEN

                                        $updaten = $this->huiswerk_model->updateResultaat($resultaat->gebruiker_ID, $resultaat->les_ID, $data);

                                        if ($updaten) {
                                            if(!empty($item_criteria_voldoende)) {
                                                foreach($item_criteria_voldoende as $key => $huiswerk) {
                                                    foreach($item_criteria_voldoende as $key => $huiswerk) {
                                                        foreach($huiswerk as $key_h => $criteria) {
                                                            $this->lessen_model->deleteBeoordelingscriteriaResultaat_new($key_h);
                                                        }
                                                    }
                                                    foreach($huiswerk as $key_h => $criteria) {
                                                            $data_beoordeling = array(
                                                                'beoordelingscriteria_ID' => $key,
                                                                'huiswerk_ID' => $key_h,
                                                                'beoordelingscriteria_resultaat' => $criteria
                                                            );


                                                            $q = $this->lessen_model->insertBeoordelingscriteriaResultaat($data_beoordeling);
                                                    }
                                                }
                                            }

                                            if ($resultaat->email_notification_homework_feedback == 'ja') {
                                                // E-MAIL OPSTELLEN

                                                $email_van_emailadres = 'info@localhost';
                                                $email_van_naam = 'localhost';
                                                $email_aan_emailadres = $resultaat->gebruiker_emailadres;
                                                $email_aan_naam = $resultaat->gebruiker_naam;


                                                // BERICHT OPSTELLEN

                                                $email_onderwerp = 'Beoordeling voor les ' . $resultaat->les_titel;
                                                $email_tekst = '<p>Je hebt een beoordeling ontvangen voor jouw huiswerkinzending voor de les "' . $resultaat->les_titel . '" van de volgende workshop: "' . $resultaat->workshop_titel . '". Ga naar de <a href="https://localhost" title="Bezoek de website van localhost" target="_blank">Cursistenmodule</a> om de feedback van jouw docent te beluisteren.</p>';

                                                $email_bericht = '
                                                <h1>' . $email_onderwerp . '</h1>
                                                <p>Beste ' . $resultaat->gebruiker_voornaam . ',</p>
                                                ' . $email_tekst . '
                                                <p>Met vriendelijke groet,</p>
                                                <p>localhost</p>';


                                                // E-MAIL

                                                $email = array(
                                                    'html' => $email_bericht,
                                                    'subject' => $email_onderwerp,
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
                                                $mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
                                                // $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
                                                $feedback = $mandrill->messages->send($email);

                                                if ($feedback[0]['status'] == 'sent') {
                                                    // echo 'E-mail verzonden';
                                                } else {
                                                    // echo 'Er kon geen e-mail worden verzonden';
                                                }
                                            }

                                            // REDIRECT
                                            redirect('cms/huiswerk/beoordelen/' . $resultaat->resultaat_ID);
                                        } else {
                                            echo 'Item wijzigen mislukt. Probeer het nog eens.';
                                        }
                                    } else {
                                        if ($opnieuw_insturen != 'ja') {
                                            // Eerste beoordeling

                                            $data = array(
                                                'resultaat_beoordelen' => 'nee',
                                                'resultaat_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                                'resultaat_voldoende' => $item_voldoende,
                                                'resultaat_opnieuw' => $item_opnieuw,
                                                'resultaat_feedback_tekst' => $item_feedback_tekst,
                                                'resultaat_notities' => $item_notities,
                                                'docent_ID' => $nagekeken_door
                                            );
                                        } else {
                                            // Tweede beoordeling

                                            $data = array(
                                                'resultaat_beoordelen' => 'nee',
                                                'resultaat_opnieuw_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                                'resultaat_opnieuw_voldoende' => $item_voldoende,
                                                'resultaat_opnieuw_feedback_tekst' => $item_feedback_tekst,
                                                'resultaat_notities' => $item_notities,
                                                'docent_ID' => $nagekeken_door

                                            );
                                        }

                                        // UPDATEN

                                        $updaten = $this->huiswerk_model->updateResultaat($resultaat->gebruiker_ID, $resultaat->les_ID, $data);

                                        if ($updaten) {
                                            if(!empty($item_criteria_voldoende)) {
                                                foreach($item_criteria_voldoende as $key => $huiswerk) {
                                                    foreach($huiswerk as $key_h => $criteria) {
                                                        $this->lessen_model->deleteBeoordelingscriteriaResultaat_new($key_h);
                                                    }
                                                }
                                                foreach($item_criteria_voldoende as $key => $huiswerk) {
                                                    foreach($huiswerk as $key_h => $criteria) {
                                                            $data_beoordeling = array(
                                                                'beoordelingscriteria_ID' => $key,
                                                                'huiswerk_ID' => $key_h,
                                                                'beoordelingscriteria_resultaat' => $criteria
                                                            );


                                                            $q = $this->lessen_model->insertBeoordelingscriteriaResultaat($data_beoordeling);
                                                    }
                                                }
                                            }

                                            if ($resultaat->email_notification_homework_feedback == 'ja') {
                                                // E-MAIL OPSTELLEN

                                                $email_van_emailadres = 'info@localhost';
                                                $email_van_naam = 'localhost';
                                                $email_aan_emailadres = $resultaat->gebruiker_emailadres;
                                                $email_aan_naam = $resultaat->gebruiker_naam;


                                                // BERICHT OPSTELLEN

                                                $email_onderwerp = 'Beoordeling voor les ' . $resultaat->les_titel;
                                                $email_tekst = '<p>Je hebt een beoordeling ontvangen voor jouw huiswerkinzending voor de les "' . $resultaat->les_titel . '" van de volgende workshop: "' . $resultaat->workshop_titel . '". Ga naar de <a href="https://localhost" title="Bezoek de website van localhost" target="_blank">Cursistenmodule</a> om de feedback van jouw docent te beluisteren.</p>';

                                                $email_bericht = '
                                                <h1>' . $email_onderwerp . '</h1>
                                                <p>Beste ' . $resultaat->gebruiker_voornaam . ',</p>
                                                ' . $email_tekst . '
                                                <p>Met vriendelijke groet,</p>
                                                <p>localhost</p>';


                                                // E-MAIL

                                                $email = array(
                                                    'html' => $email_bericht,
                                                    'subject' => $email_onderwerp,
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
                                                $mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
                                                // $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
                                                $feedback = $mandrill->messages->send($email);

                                                if ($feedback[0]['status'] == 'sent') {
                                                    // echo 'E-mail verzonden';
                                                } else {
                                                    // echo 'Er kon geen e-mail worden verzonden';
                                                }
                                            }

                                            // REDIRECT
                                            redirect('cms/huiswerk/beoordelen/' . $resultaat->resultaat_ID);
                                        } else {
                                            echo 'Item wijzigen mislukt. Probeer het nog eens.';
                                        }
                                    }
                                } else {
                                    $item_feedback_src_feedback = 'De MP3 is te groot (maximaal 10 MB)';
                                }
                            } else {
                                if ($opnieuw_insturen != 'ja') {
                                    // Eerste beoordeling

                                    $data = array(
                                        'resultaat_beoordelen' => 'nee',
                                        'resultaat_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                        'resultaat_voldoende' => $item_voldoende,
                                        'resultaat_opnieuw' => $item_opnieuw,
                                        'resultaat_feedback_tekst' => $item_feedback_tekst,
                                        'resultaat_notities' => $item_notities,
                                        'docent_ID' => $nagekeken_door
                                    );
                                } else {
                                    // Tweede beoordeling

                                    $data = array(
                                        'resultaat_beoordelen' => 'nee',
                                        'resultaat_opnieuw_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                        'resultaat_opnieuw_voldoende' => $item_voldoende,
                                        'resultaat_opnieuw_feedback_tekst' => $item_feedback_tekst,
                                        'resultaat_notities' => $item_notities,
                                        'docent_ID' => $nagekeken_door

                                    );
                                }

                                // UPDATEN

                                $updaten = $this->huiswerk_model->updateResultaat($resultaat->gebruiker_ID, $resultaat->les_ID, $data);

                                if ($updaten) {
                                    if(!empty($item_criteria_voldoende)) {
                                        foreach($item_criteria_voldoende as $key => $huiswerk) {
                                            foreach($huiswerk as $key_h => $criteria) {
                                                $this->lessen_model->deleteBeoordelingscriteriaResultaat_new($key_h);
                                            }
                                        }
                                        foreach($item_criteria_voldoende as $key => $huiswerk) {
                                            foreach($huiswerk as $key_h => $criteria) {
                                                    $data_beoordeling = array(
                                                        'beoordelingscriteria_ID' => $key,
                                                        'huiswerk_ID' => $key_h,
                                                        'beoordelingscriteria_resultaat' => $criteria
                                                    );


                                                    $q = $this->lessen_model->insertBeoordelingscriteriaResultaat($data_beoordeling);
                                            }
                                        }
                                    }

                                    if ($resultaat->email_notification_homework_feedback == 'ja') {
                                        // E-MAIL OPSTELLEN

                                        $email_van_emailadres = 'info@localhost';
                                        $email_van_naam = 'localhost';
                                        $email_aan_emailadres = $resultaat->gebruiker_emailadres;
                                        $email_aan_naam = $resultaat->gebruiker_naam;


                                        // BERICHT OPSTELLEN

                                        $email_onderwerp = 'Beoordeling voor les ' . $resultaat->les_titel;
                                        $email_tekst = '<p>Je hebt een beoordeling ontvangen voor jouw huiswerkinzending voor de les "' . $resultaat->les_titel . '" van de volgende workshop: "' . $resultaat->workshop_titel . '". Ga naar de <a href="https://localhost" title="Bezoek de website van localhost" target="_blank">Cursistenmodule</a> om de feedback van jouw docent te beluisteren.</p>';

                                        $email_bericht = '
                                        <h1>' . $email_onderwerp . '</h1>
                                        <p>Beste ' . $resultaat->gebruiker_voornaam . ',</p>
                                        ' . $email_tekst . '
                                        <p>Met vriendelijke groet,</p>
                                        <p>localhost</p>';


                                        // E-MAIL

                                        $email = array(
                                            'html' => $email_bericht,
                                            'subject' => $email_onderwerp,
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
                                        $mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
                                        // $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
                                        $feedback = $mandrill->messages->send($email);

                                        if ($feedback[0]['status'] == 'sent') {
                                            // echo 'E-mail verzonden';
                                        } else {
                                            // echo 'Er kon geen e-mail worden verzonden';
                                        }
                                    }

                                    // REDIRECT
                                    redirect('cms/huiswerk/beoordelen/' . $resultaat->resultaat_ID);
                                } else {
                                    echo 'Item wijzigen mislukt. Probeer het nog eens.';
                                }
                            }
                        } else {
                            if(!empty($_POST['upload'])) {
                                if ($item_voldoende == 'ja') $beoordeling = 'voldoende';
                                else $beoordeling = 'onvoldoende';

                                $bestandsnaam = $resultaat->gebruiker_ID . '-' . $resultaat->workshop_ID . '-' . $resultaat->les_ID . '-' . date('Ymd-His') . '-' . $beoordeling . '.mp3';
                                if (file_exists('./media/huiswerk/' . $_POST['upload'] . '-' . date('Ymd') . '.mp3')) {
                                    if (rename('./media/huiswerk/' . $_POST['upload'] . '-' . date('Ymd') . '.mp3', './media/huiswerk/' . $bestandsnaam)) {
                                        if ($opnieuw_insturen != 'ja') {
                                            // Eerste beoordeling

                                            $data = array(
                                                'resultaat_beoordelen' => 'nee',
                                                'resultaat_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                                'resultaat_voldoende' => $item_voldoende,
                                                'resultaat_opnieuw' => $item_opnieuw,
                                                'resultaat_feedback_src' => $bestandsnaam,
                                                'resultaat_feedback_tekst' => $item_feedback_tekst,
                                                'resultaat_notities' => $item_notities,
                                                'docent_ID' => $nagekeken_door
                                            );
                                        } else {
                                            // Tweede beoordeling
                                            $data = array(
                                                'resultaat_beoordelen' => 'nee',
                                                'resultaat_opnieuw_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                                'resultaat_opnieuw_voldoende' => $item_voldoende,
                                                'resultaat_opnieuw_feedback_src' => $bestandsnaam,
                                                'resultaat_opnieuw_feedback_tekst' => $item_feedback_tekst,
                                                'resultaat_notities' => $item_notities,
                                                'docent_ID' => $nagekeken_door

                                            );
                                        }

                                        // UPDATEN

                                        $updaten = $this->huiswerk_model->updateResultaat($resultaat->gebruiker_ID, $resultaat->les_ID, $data);

                                        if ($updaten) {
                                                if(!empty($item_criteria_voldoende)) {

                                                    foreach($item_criteria_voldoende as $key => $huiswerk) {
                                                        foreach($huiswerk as $key_h => $criteria) {
                                                            $this->lessen_model->deleteBeoordelingscriteriaResultaat_new($key_h);
                                                        }
                                                    }

                                                    foreach($item_criteria_voldoende as $key => $huiswerk) {
                                                        foreach($huiswerk as $key_h => $criteria) {
                                                                $data_beoordeling = array(
                                                                    'beoordelingscriteria_ID' => $key,
                                                                    'huiswerk_ID' => $key_h,
                                                                    'beoordelingscriteria_resultaat' => $criteria
                                                                );


                                                                $q = $this->lessen_model->insertBeoordelingscriteriaResultaat($data_beoordeling);
                                                        }
                                                    }
                                                }

                                            if ($resultaat->email_notification_homework_feedback == 'ja') {
                                                // E-MAIL OPSTELLEN

                                                $email_van_emailadres = 'info@localhost';
                                                $email_van_naam = 'localhost';
                                                $email_aan_emailadres = $resultaat->gebruiker_emailadres;
                                                $email_aan_naam = $resultaat->gebruiker_naam;


                                                // BERICHT OPSTELLEN

                                                $email_onderwerp = 'Beoordeling voor les ' . $resultaat->les_titel;
                                                $email_tekst = '<p>Je hebt een beoordeling ontvangen voor jouw huiswerkinzending voor de les "' . $resultaat->les_titel . '" van de volgende workshop: "' . $resultaat->workshop_titel . '". Ga naar de <a href="https://localhost" title="Bezoek de website van localhost" target="_blank">Cursistenmodule</a> om de feedback van jouw docent te beluisteren.</p>';
                                                $email_bericht = '
                                            <h1>' . $email_onderwerp . '</h1>
                                            <p>Beste ' . $resultaat->gebruiker_voornaam . ',</p>
                                            ' . $email_tekst . '
                                            <p>Met vriendelijke groet,</p>
                                            <p>localhost</p>';


                                            // E-MAIL

                                            $email = array(
                                                'html' => $email_bericht,
                                                'subject' => $email_onderwerp,
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
                                            $mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
                                            // $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
                                            $feedback = $mandrill->messages->send($email);

                                            if ($feedback[0]['status'] == 'sent') {
                                                // echo 'E-mail verzonden';
                                            } else {
                                                // echo 'Er kon geen e-mail worden verzonden';
                                            }
                                                    }

                                            // REDIRECT

                                            redirect('cms/huiswerk/beoordelen/' . $resultaat->resultaat_ID);
                                        } else {
                                            echo 'Item wijzigen mislukt. Probeer het nog eens.';
                                        }
                                    }
                                } else {
                                    $item_feedback_src_feedback = 'Het bestand is niet geüpload';
                                }
                            } else {
                                if(empty($_POST['upload'])) {
                                if ($opnieuw_insturen != 'ja') {
                                    // Eerste beoordeling

                                    $data = array(
                                        'resultaat_beoordelen' => 'nee',
                                        'resultaat_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                        'resultaat_voldoende' => $item_voldoende,
                                        'resultaat_opnieuw' => $item_opnieuw,
                                        'resultaat_feedback_src' => $bestandsnaam,
                                        'resultaat_feedback_tekst' => $item_feedback_tekst,
                                        'resultaat_opnieuw_feedback_tekst' => $item_opnieuw_feedback_tekst,
                                        'resultaat_notities' => $item_notities,
                                        'docent_ID' => $nagekeken_door
                                    );
                                } else {
                                    // Tweede beoordeling

                                    // $data = array(
                                    //     'resultaat_beoordelen' => 'nee',
                                    //     'resultaat_opnieuw_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                    //     'resultaat_opnieuw_voldoende' => $item_voldoende,
                                    //     'resultaat_feedback_src' => $bestandsnaam,
                                    //     'resultaat_feedback_tekst' => $item_feedback_tekst,
                                    //     'resultaat_notities' => $item_notities,
                                    //     'docent_ID' => $nagekeken_door

                                    // );
                                    $data = array(
                                        'resultaat_beoordelen' => 'nee',
                                        'resultaat_opnieuw_beoordeeld_datum' => date('Y-m-d H:i:s'),
                                        'resultaat_voldoende' => $item_voldoende,
                                        'resultaat_opnieuw_voldoende' => $item_voldoende,
                                        'resultaat_feedback_src' => $bestandsnaam,
                                        'resultaat_feedback_tekst' => $item_feedback_tekst,
                                        'resultaat_opnieuw_feedback_tekst' => $item_opnieuw_feedback_tekst,
                                        'resultaat_notities' => $item_notities,
                                        'docent_ID' => $nagekeken_door

                                    );
                                }

                                 // UPDATEN
                                 $updaten = $this->huiswerk_model->updateResultaat($resultaat->gebruiker_ID, $resultaat->les_ID, $data);

                                 if ($updaten) {
                                     if(!empty($item_criteria_voldoende)) {
                                        foreach($item_criteria_voldoende as $key => $huiswerk) {
                                            foreach($huiswerk as $key_h => $criteria) {
                                                $this->lessen_model->deleteBeoordelingscriteriaResultaat_new($key_h);
                                            }
                                        }
                                         foreach($item_criteria_voldoende as $key => $huiswerk) {
                                            // die();
                                             foreach($huiswerk as $key_h => $criteria) {
                                                 // print_r($key);
                                                     $data_beoordeling = array(
                                                         'beoordelingscriteria_ID' => $key,
                                                         'huiswerk_ID' => $key_h,
                                                         'beoordelingscriteria_resultaat' => $criteria
                                                     );


                                                     $q = $this->lessen_model->insertBeoordelingscriteriaResultaat($data_beoordeling);
                                             }
                                         }

                                     }

                                     if ($resultaat->email_notification_homework_feedback == 'ja') {
                                         // E-MAIL OPSTELLEN

                                         $email_van_emailadres = 'info@localhost';
                                         $email_van_naam = 'localhost';
                                         $email_aan_emailadres = $resultaat->gebruiker_emailadres;
                                         $email_aan_naam = $resultaat->gebruiker_naam;


                                         // BERICHT OPSTELLEN

                                         $email_onderwerp = 'Beoordeling voor les ' . $resultaat->les_titel;
                                         $email_tekst = '<p>Je hebt een beoordeling ontvangen voor jouw huiswerkinzending voor de les "' . $resultaat->les_titel . '" van de volgende workshop: "' . $resultaat->workshop_titel . '". Ga naar de <a href="https://localhost" title="Bezoek de website van localhost" target="_blank">Cursistenmodule</a> om de feedback van jouw docent te beluisteren.</p>';

                                         $email_bericht = '
                                         <h1>' . $email_onderwerp . '</h1>
                                         <p>Beste ' . $resultaat->gebruiker_voornaam . ',</p>
                                         ' . $email_tekst . '
                                         <p>Met vriendelijke groet,</p>
                                         <p>localhost</p>';


                                         // E-MAIL

                                         $email = array(
                                             'html' => $email_bericht,
                                             'subject' => $email_onderwerp,
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
                                         $mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
                                        //  $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
                                         $feedback = $mandrill->messages->send($email);

                                         if ($feedback[0]['status'] == 'sent') {
                                             // echo 'E-mail verzonden';
                                         } else {
                                             // echo 'Er kon geen e-mail worden verzonden';
                                         }
                                     }

                                     //REDIRECT
                                     redirect('cms/huiswerk/beoordelen/' . $resultaat->resultaat_ID);
                                 } else {
                                     echo 'Item wijzigen mislukt. Probeer het nog eens.';
                                 }
                             }
                            }
                        }
                }
            } else {
                $item_voldoende_feedback = 'Selecteer een optie';
            }
        }

        $this->data['item_voldoende']           = $item_voldoende;
        $this->data['item_feedback_src']        = $item_feedback_src;
        $this->data['item_feedback_tekst']      = $item_feedback_tekst;
        $this->data['item_notities']            = $item_notities;
        $this->data['item_opnieuw']             = $item_opnieuw;

        $this->data['item_voldoende_feedback']          = $item_voldoende_feedback;
        $this->data['item_feedback_src_feedback']       = $item_feedback_src_feedback;
        $this->data['item_feedback_tekst_feedback']     = $item_feedback_tekst_feedback;
        $this->data['item_opnieuw_feedback']            = $item_opnieuw_feedback;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/edit_review';
        $this->load->view('cms/template', $pagina);

    }


	/* ========================= */
	/* = TOEVOEGEN EN WIJZIGEN = */
	/* ========================= */



	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */

	public function verwijderen($item_ID = null, $bevestiging = null)
	{
		if($item_ID == null) redirect('cms/huiswerk');

		$this->load->model('huiswerk_model');
		$item = $this->huiswerk_model->getResultaatByID($item_ID);
		if($item == null) redirect('cms/huiswerk');
		$this->data['item'] = $item;


		// ITEM VERWIJDEREN

		if($bevestiging == 'ja')
		{
			$q = $this->huiswerk_model->deleteResultaat($item_ID);
			if($q) redirect('cms/huiswerk');
			else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
		}


		// PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/huiswerk_verwijderen';
		$this->load->view('cms/template', $pagina);
	}

	public function verwijder_item($huiswerk_ID = null, $item_ID = null, $bevestiging = null) {
        if($item_ID == null) redirect('cms/huiswerk');
        if($huiswerk_ID == null) redirect('cms/huiswerk');;
        $this->load->model('huiswerk_model');
        $item = $this->huiswerk_model->getResultaatByID($item_ID);
        $huiswerk = $this->huiswerk_model->getHuiswerkByID($huiswerk_ID);
        if($item == null) redirect('cms/huiswerk');
        $this->data['item'] = $item;
        $this->data['huiswerk'] = $huiswerk;

        // ITEM VERWIJDEREN

        if($bevestiging == 'ja')
        {
            $huiswerk_resultaat = $this->huiswerk_model->getHuiswerk($item->gebruiker_ID, $item->les_ID);

            if(sizeof($huiswerk_resultaat) > 1) {
                $q = $this->huiswerk_model->deleteHuiswerk($huiswerk_ID, $item_ID);
            } else {
                $q = $this->huiswerk_model->deleteResultaat($item_ID);
            }

            if($q) {
                $this->_verstuur_email($item);
                redirect('cms/huiswerk');
            }
            else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
        }

        // PAGINA TONEN
        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/huiswerk_mp3_verwijderen';
        $this->load->view('cms/template', $pagina);
    }

    private function _verstuur_email($resultaat)
    {
        $this->load->model('gebruikers_model');
        if ($resultaat->gebruiker_instelling_email_updates == 'ja') {
            // E-MAIL OPSTELLEN

            $email_van_emailadres = 'info@localhost';
            $email_van_naam = 'localhost';
            $email_aan_emailadres = $resultaat->gebruiker_emailadres;
            $email_aan_naam = $resultaat->gebruiker_naam;

            // BERICHT OPSTELLEN

            $email_onderwerp = 'Huiswerk verwijderd voor les ' . $resultaat->les_titel;
            $email_tekst = '<p>Een van jouw huiswerk opdrachten voor de les "' . $resultaat->les_titel . '" van de volgende workshop: "' . $resultaat->workshop_titel . '" is verwijderd. Ga naar de <a href="https://localhost" title="Bezoek de website van localhost" target="_blank">Cursistenmodule</a> om de opnieuw je huiswerk in te leveren.</p>';
            $email_bericht = '
                                            <h1>' . $email_onderwerp . '</h1>
                                            <p>Beste ' . $resultaat->gebruiker_voornaam . ',</p>
                                            ' . $email_tekst . '
                                            <p>Met vriendelijke groet,</p>
                                            <p>localhost</p>';


            // E-MAIL

            $email = array(
                'html' => $email_bericht,
                'subject' => $email_onderwerp,
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
                //   $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
            $feedback = $mandrill->messages->send($email);

            if ($feedback[0]['status'] == 'sent') {
                // echo 'E-mail verzonden';
            } else {
                // echo 'Er kon geen e-mail worden verzonden';
            }
        }
    }
}
