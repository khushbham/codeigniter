<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Berichten extends CursistenController
{
	public function __construct()
	{
		parent::__construct();

		$this->load->helper('tijdstip_helper');
	}



	/* ============= */
	/* = OVERZICHT = */
	/* ============= */

	public function index($item_ID = null)
	{
		if($item_ID == null) $this->_overzicht();
		else $this->_bericht($item_ID);
	}

	public function _overzicht()
	{
		///////////////////////
		// BERICHTEN OPHALEN //
		///////////////////////

		$this->load->model('berichten_model');
		$berichten = $this->berichten_model->getBerichtenByGebruikerID($this->session->userdata('gebruiker_ID'));
        $verzonden_berichten = $this->berichten_model->getVerzondenBerichtenByGebruikerID($this->session->userdata('gebruiker_ID'));

        $this->data['berichten'] = $berichten;
        $this->data['verzonden_berichten'] = $verzonden_berichten;
        $this->data['verzonden'] = false;



		//////////////////
		// PAGINA TONEN //
		//////////////////

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cursistenmodule/berichten';
		$this->load->view('cursistenmodule/template', $pagina);
	}



	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */

	public function bericht($item_ID = null)
	{
		/////////////////////
		// BERICHT OPHALEN //
		/////////////////////

		$user_id =  $this->session->userdata('gebruiker_ID');
		// die;
		if($item_ID == null) redirect('cursistenmodule/berichten');
		$this->load->model('berichten_model');
		$this->load->model('media_model');
		$bericht = $this->berichten_model->getBerichtByID($item_ID);
		$sender_id = $bericht->bericht_afzender_ID;
		$receiver_id = $bericht->bericht_ontvanger_ID;
		if($receiver_id == $user_id || $sender_id == $user_id){
			$bericht_media = $this->media_model->getMediaByContentID('bericht', $item_ID);
			$this->data['bericht'] = $bericht;
			$this->data['bericht_media'] = $bericht_media;
		}else{
			redirect('/cursistenmodule/berichten');
		}

		///////////////////////////
		// NIEUW BERICHT UPDATEN //
		///////////////////////////

		if($bericht->bericht_nieuw == 'ja')
		{
            if($bericht->bericht_afzender_ID != $this->session->userdata('gebruiker_ID')) {
                // Nieuw bericht niet meer als nieuw opslaan

                $update = array('bericht_nieuw' => 'nee');
                $this->berichten_model->updateBerichtByID($item_ID, $update);


                // Aantal nieuwe berichten in sessie verminderen

                $nieuwe_berichten = $this->session->userdata('nieuwe_berichten') - 1;
                $this->session->set_userdata('nieuwe_berichten', $nieuwe_berichten);
            }
		}



		//////////////////
		// PAGINA TONEN //
		//////////////////

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cursistenmodule/berichten_bericht';
		$this->load->view('cursistenmodule/template', $pagina);
	}



	/* ========================= */
	/* = NIEUW EN BEANTWOORDEN = */
	/* ========================= */

	public function nieuw()
	{
		$this->_opstellen();
	}

	public function beantwoorden($item_ID = null)
	{
		if($item_ID == null) redirect('cursistenmodule/berichten');
		$this->_opstellen($item_ID);
	}

	private function _opstellen($item_ID = null)
	{
		//////////////////
		// MODELS LADEN //
		//////////////////

		$this->load->model('berichten_model');
		$this->load->model('gebruikers_model');
		$this->load->model('docenten_model');
		$this->load->model('media_model');



		////////////////
		// VARIABELEN //
		////////////////

		$media 					= '';
        $media_uitgelicht		= '';
		$item_ontvanger 		= '';
		$item_ontvangers		= '';
		$bericht_media 		    = '';
		$item_onderwerp 		= '';
		$item_tekst 			= '';
		$item_beantwoorden 		= false;
		$item_verzonden			= false;
		$docenten = $this->docenten_model->getDocenten();
        $beheerders = $this->gebruikers_model->getBeheerders();


		// Nieuw bericht sturen of een bericht beantwoorden?

		if($item_ID == null)
		{
			///////////////////
			// NIEUW BERICHT //
			///////////////////

			// Medecursisten ophalen

			$medecursisten = $this->gebruikers_model->getMedecursisten();

            $this->load->model('aanmeldingen_model');
            $verlopen_deelnemers = $this->aanmeldingen_model->getAanmeldingenVerlopen();

            if(!empty($verlopen_deelnemers)) {
                if (!empty($medecursisten)) {
                    foreach ($verlopen_deelnemers as $verlopen_deelnemer) {
                        foreach ($medecursisten as $key => $cursist) {
                            if ($verlopen_deelnemer->gebruiker_ID == $cursist->gebruiker_ID) {
                                unset($medecursisten[$key]);
                            }
                        }
                    }
                }
            }

			$this->data['medecursisten'] = $medecursisten;
			$this->data['bericht_media'] = $bericht_media;
		}
		else
		{
			//////////////////////////
			// BERICHT BEANTWOORDEN //
			//////////////////////////

			$item_beantwoorden = true;


			// Bericht ophalen

			$bericht = $this->berichten_model->getBerichtByID($item_ID);
			$this->data['bericht'] = $bericht;
            $bericht_media = $this->media_model->getMediaByContentID('bericht', $item_ID);
            $this->data['bericht_media'] = $bericht_media;

			if(!$bericht->bericht_no_reply && $bericht->bericht_ontvanger_ID == $this->session->userdata('gebruiker_ID')) {
                    // Informatie bericht initialiseren

                    $item_ontvanger = $bericht->bericht_afzender_ID;

                    if (substr($bericht->bericht_onderwerp, 0, 4) == 'Re: ') {
                        $item_onderwerp = $bericht->bericht_onderwerp;
                    } else {
                        $item_onderwerp = 'Re: ' . $bericht->bericht_onderwerp;
                    }

                    $item_tekst = "\n\n\n--- Verzonden op " . date('d-m-y H:i:s', strtotime($bericht->bericht_datum)) . " uur door " . $bericht->gebruiker_naam . "\n\n" . $bericht->bericht_tekst;

            } else {
			    redirect('cursistenmodule/berichten');
            }
		}

            if (isset($_POST['item_tekst'])) {
                if($this->session->userdata('demo') == true) {
                    redirect('cursistenmodule/berichten');
                } else {
                // Ontvanger ophalen bij nieuw bericht

                if (!$item_beantwoorden) {
                    $item_ontvanger = $_POST['item_ontvanger'];
                }

        if(!empty($_POST['item_ontvanger']) || !empty($item_ontvanger)) {

		if (sizeof($_POST['item_ontvanger']) > 0) {
			$verzonden_naar = array();

			foreach ($_POST['item_ontvanger'] as $item_ontvanger) {

				$item_ontvanger_type = 'deelnemer';

                $item_afzender = $this->session->userdata('gebruiker_ID');
                $item_onderwerp = $_POST['item_onderwerp'];
				$bericht_no_reply = (isset($_POST['bericht_no_reply'])) ? 1 : 0;
                $item_tekst = $_POST['item_tekst'];

                // Controleren of alle velden zijn ingevuld

                if (!empty($item_ontvanger) && !empty($item_onderwerp) && !empty($item_tekst)) {
					// Controleren aan wie het bericht moet worden verzonden

						/////////////////////////
						// BERICHT AAN CURSIST //
						/////////////////////////

						if(!in_array($item_ontvanger, $verzonden_naar)) {
                        $item_tekst = $this->ReplaceTags($item_ontvanger, $item_tekst);

                        $bericht = array(
                            'bericht_onderwerp' => $item_onderwerp,
                            'bericht_tekst' => $item_tekst,
                            'bericht_datum' => date('Y-m-d H:i:s'),
                            'bericht_afzender_ID' => $item_afzender,
								'bericht_afzender_type' => 'docent',
								'bericht_ontvanger_ID' => $item_ontvanger,
								'bericht_no_reply' => $bericht_no_reply);

                        $verzonden = $this->berichten_model->verzendBericht($bericht);

                    if(isset($_POST['item_media'])) {
                        $media_IDs = explode(',', $_POST['item_media']);

                        for ($i = 0; $i < sizeof($media_IDs); $i++) {
                            if ($media_IDs[$i] > 0) {
                                $connectie = array('media_ID' => $media_IDs[$i], 'media_positie' => $i, 'content_type' => 'bericht', 'content_ID' => $verzonden);
                                $this->media_model->insertMediaConnectie($connectie);
                            }
                        }
                    }

                    // Controleren of het bericht is opgeslagen

                    if ($verzonden > 0) {
                        $item_verzonden = true;


                        // Bericht beantwoord updaten

                        if ($item_beantwoorden) {
                            $this->berichten_model->updateBerichtByID($item_ID, array('bericht_beantwoord' => 'ja'));
                        }


                        // Verzonden bericht ophalen

                        $bericht = $this->berichten_model->getBerichtByID($verzonden);
                        $this->data['bericht'] = $bericht;


                        // Ontvanger ophalen

                        $ontvanger = $this->gebruikers_model->getGebruikerByID($bericht->bericht_ontvanger_ID);
                        $this->data['ontvanger'] = $ontvanger;


                        // E-mail update sturen naar ontvanger

                        if (!isset($_SESSION['bericht_onderwerp']) || !isset($_SESSION['bericht_tekst']) || $item_onderwerp != $_SESSION['bericht_onderwerp'] || $item_tekst != $_SESSION['bericht_tekst']) {
                            // Controleren of de ontvanger e-mail updates wilt ontvangen

                            if ($ontvanger->gebruiker_instelling_email_updates == 'ja') {
										$this->_verstuur_email($item_onderwerp, $item_afzender, $item_ontvanger);
										array_push($verzonden_naar, $item_ontvanger);
                            }
                        }


                        // Bericht opslaan in sessie

                        $_SESSION['bericht_onderwerp'] = $item_onderwerp;
                        $_SESSION['bericht_tekst'] = $item_tekst;
                        $_SESSION['bericht_ID'] = $verzonden;
                    } else {
                        $item_verzonden = false;

                        echo 'Je bericht kon niet worden verzonden. Probeer het nog eens.';
                    }
                }
            }
        }
			}
		}
	}
}


		//////////////////
		// PAGINA TONEN //
		//////////////////

        $this->data['media'] 				= $media;
        $this->data['media_uitgelicht']		= $media_uitgelicht;
        $this->data['beheerders']           = $beheerders;
        $this->data['docenten']             = $docenten;
		$this->data['item_ID'] 				= $item_ID;
		$this->data['item_ontvanger'] 		= $item_ontvanger;
		$this->data['item_onderwerp'] 		= $item_onderwerp;
		$this->data['item_tekst'] 			= $item_tekst;
		$this->data['item_beantwoorden'] 	= $item_beantwoorden;
		$this->data['item_verzonden'] 		= $item_verzonden;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cursistenmodule/berichten_nieuw';
		$this->load->view('cursistenmodule/template', $pagina);
	}



	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */

	public function verwijderen($item_ID = null, $actie = null)
	{
		// Bericht ophalen

		if($item_ID == null) redirect('cursistenmodule/berichten');
		$this->load->model('berichten_model');
		$bericht = $this->berichten_model->getBerichtByID($item_ID);
		$this->data['bericht'] = $bericht;


		// Controleren of het bericht bestaat en of hij voor de gebruiker bestemd is
        if($bericht == null || $bericht->bericht_ontvanger_ID != $this->session->userdata('gebruiker_ID') && $bericht->bericht_afzender_ID != $this->session->userdata('gebruiker_ID')) redirect('cursistenmodule/berichten');


		/////////////////////////
		// BERICHT VERWIJDEREN //
		/////////////////////////

		if($actie == 'ja')
		{
            $this->afzender = $this->berichten_model->getBerichtByandZenderID($item_ID, $this->session->userdata('gebruiker_ID'));
            $this->ontvanger = $this->berichten_model->getBerichtByandOntvangerID($item_ID, $this->session->userdata('gebruiker_ID'));

            if(!empty($this->afzender) || !empty($this->ontvanger)) {
                if (!empty($this->afzender)) {
                    $update = array('bericht_verwijderd_afzender' => 1);
                } elseif (!empty($this->ontvanger)) {
                    $update = array('bericht_verwijderd_ontvanger' => 1);
                } else {
                    echo 'Item kon niet worden verwijderd. Probeer het nog eens.';
                }

                if (!empty($update)) {
                    $this->berichten_model->updateBerichtByID($item_ID, $update);
                    $verwijderen = $this->berichten_model->getBerichtVerwijderen($item_ID);
                    $bericht_naar_zichzelf = $this->berichten_model->getBerichtVerwijderenZichzelf($item_ID, $this->session->userdata('beheerder_ID'));

                    if (!empty($verwijderen)) {
                        $this->berichten_model->verwijderBerichtByID($item_ID);
                    }

                    if(!empty($bericht_naar_zichzelf)) {
                        $this->berichten_model->verwijderBerichtByID($item_ID);
                    }

                    redirect('cursistenmodule/berichten');
                }
            } else {
                $this->berichten_model->verwijderBerichtByID($item_ID);
                redirect('cursistenmodule/berichten');
            }
		}



		//////////////////
		// PAGINA TONEN //
		//////////////////

		$this->data['item_ID'] = $item_ID;
		$this->data['pagina'] = $actie;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cursistenmodule/berichten_verwijderen';
		$this->load->view('cursistenmodule/template', $pagina);
	}



	/////////////////////////////
	// E-MAIL UPDATE VERZENDEN //
	/////////////////////////////

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
		// $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
		$mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');

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

    function ReplaceTags($gebruiker_ID, $email_tekst) {
        $this->load->model('gebruikers_model');

        $deelnemer = $this->gebruikers_model->getGebruikerByID($gebruiker_ID);

        $email_tekst = str_replace('[voornaam]', $deelnemer->gebruiker_voornaam, $email_tekst);
        $email_tekst = str_replace('[achternaam]', $deelnemer->gebruiker_achternaam, $email_tekst);

        return  $email_tekst;
    }
}
