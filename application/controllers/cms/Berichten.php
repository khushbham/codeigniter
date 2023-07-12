<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Berichten extends CI_Controller
{
	private $data = array();
	
	public function __construct()
	{
		parent::__construct();
		
		// Rechten controleren en aantal nieuwe items ophalen
		
		$this->load->library('algemeen');
		$this->algemeen->cms();
		
        $this->load->helper('tijdstip_helper');
        if($this->session->userdata('beheerder_rechten') == 'contentmanager') redirect('cms/rechten');
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
		$berichten = $this->berichten_model->getBerichtenByGebruikerID($this->session->userdata('beheerder_ID'));
        $verzonden_berichten = $this->berichten_model->getVerzondenBerichtenByGebruikerID($this->session->userdata('beheerder_ID'));
        $templates = $this->berichten_model->getTemplates();

        $this->data['templates'] = $templates;
		$this->data['berichten'] = $berichten;
		$this->data['verzonden_berichten'] = $verzonden_berichten;
        $this->data['verzonden'] = false;
		
		
		//////////////////
		// PAGINA TONEN //
		//////////////////
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/berichten';
		$this->load->view('cms/template', $pagina);
	}
	
	
	
	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */
	
	public function bericht($item_ID = null)
	{
		/////////////////////
		// BERICHT OPHALEN //
		/////////////////////
		
		if($item_ID == null) redirect('cms/berichten');
		
		$this->load->model('berichten_model');
		$this->load->model('media_model');
		$bericht = $this->berichten_model->getBerichtByID($item_ID);
		$this->data['bericht'] = $bericht;
        $bericht_media = $this->media_model->getMediaByContentID('bericht', $item_ID);
        $this->data['bericht_media'] = $bericht_media;
		
		
		
		///////////////////////////
		// NIEUW BERICHT UPDATEN //
		///////////////////////////
		
		if($bericht->bericht_nieuw == 'ja')
		{
			// Nieuw bericht niet meer als nieuw opslaan
			if($bericht->bericht_afzender_ID != $this->session->userdata('beheerder_ID')) {
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
		$pagina['pagina'] = 'cms/berichten_bericht';
		$this->load->view('cms/template', $pagina);
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
		if($item_ID == null) redirect('cms/berichten');
		$this->_opstellen($item_ID);
	}
	
    public function doorsturen($item_ID = null)
	{
		if($item_ID == null) redirect('cms/berichten');
		$this->_opstellen($item_ID, true);
	}

	private function _opstellen($item_ID = null, $doorsturen = false)
	{
		//////////////////
		// MODELS LADEN //
		//////////////////

		$this->load->model('berichten_model');
		$this->load->model('gebruikers_model');
		$this->load->model('groepen_model');
        $this->load->model('docenten_model');
        $this->load->model('media_model');



		////////////////
		// VARIABELEN //
		////////////////
		
		$item_ontvanger_type	= 'deelnemer';
		$item_ontvanger 		= '';
        $bericht_media          = '';
		$item_ontvangers		= '';
		$item_groep				= '';
		$item_onderwerp 		= '';
		$item_tekst 			= '';
		$item_beantwoorden 		= false;
		$item_verzonden			= false;
		$bericht_no_reply       = 0;
        $berichten_aan          = 'ja';
        $templates              = $this->berichten_model->getTemplates();
        $docenten               = $this->docenten_model->getDocenten();

        if($this->session->userdata('beheerder_rechten') == 'docent') {
            $docent = $this->docenten_model->getDocentByGebruikerID($this->session->userdata('beheerder_ID'));
            $berichten_aan = $docent->docent_berichten_aan;
        }

		// Nieuw bericht sturen of een bericht beantwoorden?
		
		if($item_ID == null && !$doorsturen)
		{
			///////////////////
			// NIEUW BERICHT //
			///////////////////

			// Controleren of een deelnemerslijst is verzonden

			if(isset($_POST['deelnemerslijst']))
			{
				// Deelnemerslijst ophalen

				$item_ontvanger_type = 'deelnemerslijst';
				$item_ontvanger = $_POST['deelnemerslijst'];
				$item_ontvangers = explode(",", $item_ontvanger);
			}
			else
			{
                if($this->session->userdata('beheerder_rechten') != 'deelnemer') {
                    $beheerders = $this->gebruikers_model->getBeheerders();
                    $this->data['beheerders'] = $beheerders;
                }

                if ($berichten_aan != 'nee') {
                    // Cursisten ophalen
                    $groepen_archief_deelnemers = $this->gebruikers_model->getDeelnemersArachiefGroep();
                    $groepen_deelnemers = $this->gebruikers_model->getDeelnemersGroep();
                    $cursisten = $this->gebruikers_model->getDeelnemers();

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

                    if (!empty($groepen_archief_deelnemers) && !empty($groepen_deelnemers)) {
                        foreach ($groepen_archief_deelnemers as $deelnemer) {
                            $dubbel_bool = false;

                            foreach ($dubbele as $dubbel_ID) {
                                if ($deelnemer->gebruiker_ID == $dubbel_ID) {
                                    $dubbel_bool = true;
                                }
                            }

                            if ($dubbel_bool == false) {
                                array_push($temp_array, $deelnemer);
                            }
                        }
                    }

                    foreach ($cursisten as $key => $cursist) {
                        $bool = false;
                        foreach ($temp_array as $tmp) {
                            if ($tmp->gebruiker_ID == $cursist->gebruiker_ID) {
                                $bool = true;
                            }
                        }

                        if ($bool == true) {
                            unset($cursisten[$key]);
                        }
                    }

                    $this->load->model('aanmeldingen_model');
                    $verlopen_deelnemers = $this->aanmeldingen_model->getAanmeldingenVerlopen();

                    if(!empty($verlopen_deelnemers)) {
                        if (!empty($cursisten)) {
                            foreach ($verlopen_deelnemers as $verlopen_deelnemer) {
                                foreach ($cursisten as $key => $cursist) {
                                    if ($verlopen_deelnemer->gebruiker_ID == $cursist->gebruiker_ID) {
                                        unset($cursisten[$key]);
                                    }
                                }
                            }
                        }
                    }

                    $this->data['cursisten'] = $cursisten;

                    // Groepen ophalen

                    $groepen = $this->groepen_model->getGroepenMetCursistenBerichtenLijst();
                    $this->data['groepen'] = $groepen;
                }
			}
		} else if ($doorsturen && $item_ID != null) {
            if(isset($_POST['deelnemerslijst']))
            {
                $item_ontvanger_type = 'deelnemerslijst';
                $item_ontvanger = $_POST['deelnemerslijst'];
                $item_ontvangers = explode(",", $item_ontvanger);
            }
            else
            {
                if($this->session->userdata('beheerder_rechten') != 'deelnemer') {
                    $beheerders = $this->gebruikers_model->getBeheerders();
                    $this->data['beheerders'] = $beheerders;
                }

                if ($berichten_aan != 'nee') {
                    $groepen_archief_deelnemers = $this->gebruikers_model->getDeelnemersArachiefGroep();
                    $groepen_deelnemers = $this->gebruikers_model->getDeelnemersGroep();
                    $cursisten = $this->gebruikers_model->getDeelnemers();

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

                    if (!empty($groepen_archief_deelnemers) && !empty($groepen_deelnemers)) {
                        foreach ($groepen_archief_deelnemers as $deelnemer) {
                            $dubbel_bool = false;

                            foreach ($dubbele as $dubbel_ID) {
                                if ($deelnemer->gebruiker_ID == $dubbel_ID) {
                                    $dubbel_bool = true;
                                }
                            }

                            if ($dubbel_bool == false) {
                                array_push($temp_array, $deelnemer);
                            }
                        }
                    }

                    foreach ($cursisten as $key => $cursist) {
                        $bool = false;
                        foreach ($temp_array as $tmp) {
                            if ($tmp->gebruiker_ID == $cursist->gebruiker_ID) {
                                $bool = true;
                            }
                        }

                        if ($bool == true) {
                            unset($cursisten[$key]);
                        }
                    }

                    $this->load->model('aanmeldingen_model');
                    $verlopen_deelnemers = $this->aanmeldingen_model->getAanmeldingenVerlopen();

                    if(!empty($verlopen_deelnemers)) {
                        if (!empty($cursisten)) {
                            foreach ($verlopen_deelnemers as $verlopen_deelnemer) {
                                foreach ($cursisten as $key => $cursist) {
                                    if ($verlopen_deelnemer->gebruiker_ID == $cursist->gebruiker_ID) {
                                        unset($cursisten[$key]);
                                    }
                                }
                            }
                        }
                    }

                    $this->data['cursisten'] = $cursisten;
                    $groepen = $this->groepen_model->getGroepenMetCursistenBerichtenLijst();
                    $this->data['groepen'] = $groepen;

                    $bericht = $this->berichten_model->getBerichtByID($item_ID);
                    $this->data['bericht'] = $bericht;
                    $bericht_media = $this->media_model->getMediaByContentID('bericht', $item_ID);
                    $this->data['bericht_media'] = $bericht_media;
        
        
                    // Controleren of het bericht voor de huidige gebruiker bedoeld is
        
                    if($bericht->bericht_ontvanger_ID == $this->session->userdata('beheerder_ID'))
                    {
                        if(substr($bericht->bericht_onderwerp, 0, 4) == 'FW: ')
                        {
                            $item_onderwerp = $bericht->bericht_onderwerp;
                        }
                        else
                        {
                            $item_onderwerp = 'FW: '.$bericht->bericht_onderwerp;
                        }
        
                        $item_tekst = "\n\n\n--- Verzonden op ".date('d-m-y H:i:s', strtotime($bericht->bericht_datum))." uur door ".$bericht->gebruiker_naam."\n\n".$bericht->bericht_tekst;
                    }
                }
            }
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


			// Controleren of het bericht voor de huidige gebruiker bedoeld is

			if($bericht->bericht_ontvanger_ID == $this->session->userdata('beheerder_ID'))
			{
				// Informatie bericht initialiseren

				$item_ontvanger = $bericht->bericht_afzender_ID;

				if(substr($bericht->bericht_onderwerp, 0, 4) == 'Re: ')
				{
					$item_onderwerp = $bericht->bericht_onderwerp;
				}
				else
				{
					$item_onderwerp = 'Re: '.$bericht->bericht_onderwerp;
				}

				$item_tekst = "\n\n\n--- Verzonden op ".date('d-m-y H:i:s', strtotime($bericht->bericht_datum))." uur door ".$bericht->gebruiker_naam."\n\n".$bericht->bericht_tekst;
			}
		}

		/////////////////////////
		// FORMULIER VALIDEREN //
		/////////////////////////

		if(isset($_POST['item_tekst'])) {
            // Ontvanger ophalen bij nieuw bericht
            $item_ontvanger_type = isset($_POST['item_ontvanger_type']) ? $_POST['item_ontvanger_type'] : '';

        if(!empty($_POST['item_ontvanger']) || !empty($item_ontvanger)) {

            if(!empty($item_ontvanger)) {
                $_POST['item_ontvanger'] = array($item_ontvanger);
            }

            if (sizeof($_POST['item_ontvanger']) > 0 && $item_ontvanger_type != 'deelnemerslijst') {
                $verzonden_naar = array();

                foreach ($_POST['item_ontvanger'] as $item_ontvanger) {

                    $item_ontvanger_type = 'deelnemer';

                    if (!$item_beantwoorden) {
                        // Controleren of er een groepsbericht verstuurd moet worden
                        if (substr($item_ontvanger, 0, 5) == 'groep') {
                            $item_ontvanger_type = 'groep';
                            $item_ontvanger = str_replace('groep-', '', $item_ontvanger);
                        }
                    }

                    $item_afzender = $this->session->userdata('beheerder_ID');
                    $item_onderwerp = $_POST['item_onderwerp'];
                    $bericht_no_reply = (isset($_POST['bericht_no_reply'])) ? 1 : 0;
                    $item_tekst = $_POST['item_tekst'];

                    // Controleren of alle velden zijn ingevuld

                    if (!empty($item_ontvanger) && !empty($item_onderwerp) && !empty($item_tekst)) {
                        // Controleren aan wie het bericht moet worden verzonden

                        if ($item_ontvanger_type == 'deelnemer') {
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
                        } elseif ($item_ontvanger_type == 'groep') {
                            ///////////////////////
                            // BERICHT AAN GROEP //
                            ///////////////////////

                            // Groep ophalen

                            $item_groep = $this->groepen_model->getGroepByID($item_ontvanger);


                            // Deelnemers van groep ophalen

                            $deelnemers = $this->groepen_model->getGroepDeelnemers($item_ontvanger);


                            // Controleren of er deelnemers zijn aangemeld

                            if (sizeof($deelnemers) > 0) {
                                // Bericht verzenden aan deelnemers

                                foreach ($deelnemers as $deelnemer) {
                                    // Bericht opslaan
                                    if (!in_array($deelnemer->gebruiker_ID, $verzonden_naar)) {
                                        $item_text = $this->ReplaceTags($deelnemer->gebruiker_ID, $item_tekst, $item_ontvanger);

                                        $bericht = array(
                                            'bericht_onderwerp' => $item_onderwerp,
                                            'bericht_tekst' => $item_text,
                                            'bericht_datum' => date('Y-m-d H:i:s'),
                                            'bericht_afzender_ID' => $item_afzender,
                                            'bericht_afzender_type' => 'docent',
                                            'bericht_ontvanger_ID' => $deelnemer->gebruiker_ID,
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

                                            // E-mail update sturen naar ontvanger

                                            if ($deelnemer->gebruiker_instelling_email_updates == 'ja') {
                                                $this->_verstuur_email($item_onderwerp, $item_afzender, $deelnemer->gebruiker_ID);
                                                array_push($verzonden_naar, $deelnemer->gebruiker_ID);
                                            }
                                        } else {
                                            $item_verzonden = false;
                                            echo 'Je bericht kon niet worden verzonden. Probeer het nog eens.';
                                        }
                                    }
                                }
                            } else {
                                echo 'Geen deelnemers binnen deze groep. Selecteer een andere groep.';
                            }
                        }
                    }
                }
            } elseif ($item_ontvanger_type == 'deelnemerslijst') {
                //////////////////////////////////////
                // BERICHT AAN LIJST VAN DEELNEMERS //
                //////////////////////////////////////

                $item_ontvanger = $_POST['item_ontvanger'];

                if (!$item_beantwoorden) {
                    // Ontvanger ID ophalen
                    if (isset($_POST['item_ontvanger_type']) && $_POST['item_ontvanger_type'] == 'deelnemerslijst') {
                        $item_ontvanger_type = 'deelnemerslijst';
                        $item_ontvangers = explode(",", $item_ontvanger);
                    }
                }

                $item_afzender = $this->session->userdata('beheerder_ID');
                $item_onderwerp = $_POST['item_onderwerp'];
                $bericht_no_reply = (isset($_POST['bericht_no_reply'])) ? 1 : 0;
                $item_tekst = $_POST['item_tekst'];

                // Deelnemers ophalen

                $deelnemers = $this->gebruikers_model->getDeelnemersByIDArray($item_ontvangers);


                // Controleren of er deelnemers zijn gevonden

                if (sizeof($deelnemers) > 0) {
                    // Bericht verzenden aan deelnemers

                    foreach ($deelnemers as $deelnemer) {
                        // Bericht opslaan
                        $item_text = $this->ReplaceTags($deelnemer->gebruiker_ID, $item_tekst);

                        $bericht = array(
                            'bericht_onderwerp' => $item_onderwerp,
                            'bericht_tekst' => $item_text,
                            'bericht_datum' => date('Y-m-d H:i:s'),
                            'bericht_afzender_ID' => $item_afzender,
                            'bericht_afzender_type' => 'docent',
                            'bericht_ontvanger_ID' => $deelnemer->gebruiker_ID,
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

                            // E-mail update sturen naar ontvanger

                            if ($deelnemer->gebruiker_instelling_email_updates == 'ja') {
                                $this->_verstuur_email($item_onderwerp, $item_afzender, $deelnemer->gebruiker_ID);
                            }
                        } else {
                            $item_verzonden = false;

                            echo 'Je bericht kon niet worden verzonden. Probeer het nog eens.';
                        }
                    }
                } else {
                    echo 'Geen deelnemers binnen deze selectie.';
                }
            }
        }
        }


		
		//////////////////
		// PAGINA TONEN //
		//////////////////

        $this->data['docenten']             = $docenten;
        $this->data['bericht_media']        = $bericht_media;
        $this->data['templates']            = $templates;
		$this->data['item_ID'] 				= $item_ID;
		$this->data['item_ontvanger_type'] 	= $item_ontvanger_type;
		$this->data['item_ontvanger'] 		= $item_ontvanger;
		$this->data['item_ontvangers'] 		= $item_ontvangers;
		$this->data['item_groep'] 			= $item_groep;
		$this->data['item_onderwerp'] 		= $item_onderwerp;
		$this->data['item_tekst'] 			= $item_tekst;
		$this->data['item_beantwoorden'] 	= $item_beantwoorden;
		$this->data['item_verzonden'] 		= $item_verzonden;
		$this->data['bericht_no_reply'] 	= $bericht_no_reply;
        $this->data['item_doorsturen']      = $doorsturen;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/berichten_nieuw';
		$this->load->view('cms/template', $pagina);
	}

    /* =============== */
    /* = VERWIJDEREN = */
    /* =============== */

    public function template_verwijderen($item_ID = null, $actie = null)
    {
        // Bericht ophalen

        if($item_ID == null) redirect('cms/berichten');
        $this->load->model('berichten_model');
        $template = $this->berichten_model->getTemplatebyID($item_ID);

        $this->data['item'] = $template;

        /////////////////////////
        // BERICHT VERWIJDEREN //
        /////////////////////////

        if($actie == 'ja')
        {
            $q = $this->berichten_model->deleteTemplate($item_ID);

            if($q) {
                redirect('cms/berichten');
            } else {
                echo 'Item kon niet worden verwijderd. Probeer het nog eens.';
            }
        }

        //////////////////
        // PAGINA TONEN //
        //////////////////

        $this->data['item_ID'] = $item_ID;
        $this->data['pagina'] = $actie;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/template_verwijderen';
        $this->load->view('cms/template', $pagina);
    }

	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */
	
	public function verwijderen($item_ID = null, $actie = null)
	{
		// Bericht ophalen
		
		if($item_ID == null) redirect('cms/berichten');
		$this->load->model('berichten_model');
		$bericht = $this->berichten_model->getBerichtByID($item_ID);
		$this->data['bericht'] = $bericht;
		
		
		// Controleren of het bericht bestaat en of hij voor de gebruiker bestemd is
		
		if($bericht == null || $bericht->bericht_ontvanger_ID != $this->session->userdata('beheerder_ID') && $bericht->bericht_afzender_ID != $this->session->userdata('beheerder_ID')) redirect('cms/berichten');
		
		
		
		/////////////////////////
		// BERICHT VERWIJDEREN //
		/////////////////////////
		
		if($actie == 'ja')
		{
            $this->afzender = $this->berichten_model->getBerichtByandZenderID($item_ID, $this->session->userdata('beheerder_ID'));
            $this->ontvanger = $this->berichten_model->getBerichtByandOntvangerID($item_ID, $this->session->userdata('beheerder_ID'));

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

                    redirect('cms/berichten');
                }
            } else {
                $this->berichten_model->verwijderBerichtByID($item_ID);
                redirect('cms/berichten');
            }
		}
		
		
		
		//////////////////
		// PAGINA TONEN //
		//////////////////
		
		$this->data['item_ID'] = $item_ID;
		$this->data['pagina'] = $actie;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/berichten_verwijderen';
		$this->load->view('cms/template', $pagina);
	}
	
	
	public function verwijder_meerdere($actie = null)
	{
		// Bericht ophalen

		if(empty($_POST['geselecteerde_berichten'])) redirect('cms/berichten');
		$this->load->model('berichten_model');

		// Haal alle geselecteerde berichten op

		$bericht = $this->berichten_model->getBerichtWhereIDIn($_POST['geselecteerde_berichten']);
		
		// Controleren of het bericht bestaat en of hij voor de gebruiker bestemd is

		foreach ($bericht as $ber)
		{
			if($ber == null || $ber->bericht_ontvanger_ID != $this->session->userdata('beheerder_ID') && $ber->bericht_afzender_ID != $this->session->userdata('beheerder_ID'))
			{
				redirect('cms/berichten');
			}
		}

		$this->data['bericht'] = $bericht;

		/////////////////////////
		// BERICHT VERWIJDEREN //
		/////////////////////////

		if($actie == 'ja')
		{
            foreach($bericht as $ber) {
                $this->afzender = $this->berichten_model->getBerichtByandZenderID($ber->bericht_ID, $this->session->userdata('beheerder_ID'));
                $this->ontvanger = $this->berichten_model->getBerichtByandOntvangerID($ber->bericht_ID, $this->session->userdata('beheerder_ID'));

                if (!empty($this->afzender) || !empty($this->ontvanger)) {
                    if (!empty($this->afzender)) {
                        $update = array('bericht_verwijderd_afzender' => 1);
                    } elseif (!empty($this->ontvanger)) {
                        $update = array('bericht_verwijderd_ontvanger' => 1);
                    } else {
                        echo 'Item kon niet worden verwijderd. Probeer het nog eens.';
                    }

                    if (!empty($update)) {
                        $this->berichten_model->updateBerichtByID($ber->bericht_ID, $update);
                        $verwijderen = $this->berichten_model->getBerichtVerwijderen($ber->bericht_ID);
                        $bericht_naar_zichzelf = $this->berichten_model->getBerichtVerwijderenZichzelf($ber->bericht_ID, $this->session->userdata('beheerder_ID'));

                        if (!empty($verwijderen)) {
                            $this->berichten_model->verwijderBerichtByID($ber->bericht_ID);
                        }

                        if (!empty($bericht_naar_zichzelf)) {
                            $this->berichten_model->verwijderBerichtByID($ber->bericht_ID);
                        }
                    }
                } else {
                    $this->berichten_model->verwijderBerichtByID($ber->bericht_ID);
                }
            }

            redirect('cms/berichten');
		}


		//////////////////
		// PAGINA TONEN //
		//////////////////

		$this->data['pagina'] = $actie;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/berichten_verwijderen';
		$this->load->view('cms/template', $pagina);
	}
	
	//////////////
	// E-MAILEN //
	//////////////
	
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