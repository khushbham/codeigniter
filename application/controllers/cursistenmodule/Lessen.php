<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lessen extends CursistenController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function audio()
	{
		$this->load->view('cursistenmodule/audio');
	}



	/* ============= */
	/* = OVERZICHT = */
	/* ============= */

	public function index()
	{
        if($this->session->userdata('kennismakingsworkshop_acc') == true || $this->session->userdata('gebruiker_rechten') == 'kandidaat'):
            redirect('cursistenmodule');
        endif;

		$les_active = 0;
		$groep = false;

        $this->load->model('huiswerk_model');
        $this->load->model('docenten_model');

		// Workshops ophalen

		$this->load->model('workshops_model');
		$workshops = $this->workshops_model->getWorkshopsByGebruikerID($this->session->userdata('gebruiker_ID'));
		$this->data['workshops'] = $workshops;

        $this->load->model('kennismakingsworkshop_model');
        $kennismakingsworkshops = $this->kennismakingsworkshop_model->getKennismakingsworkshopsByGebruikerID($this->session->userdata('gebruiker_ID'));
        $this->data['kennismakingsworkshops'] = $kennismakingsworkshops;

		if($this->session->userdata('workshop_ID'))
		{
			// Workshop ophalen

			$workshop = $this->workshops_model->getWorkshopByGebruikerID($this->session->userdata('workshop_ID'), $this->session->userdata('gebruiker_ID'));
			$this->data['workshop'] = $workshop;

			if(in_array($this->session->userdata('workshop_type'), array('groep', 'online')))
			{
				$groep = true;

				// Groepslessen ophalen

                $this->load->model('lessen_model');
                $this->load->model('aanwezigheid_model');

                if($this->session->userdata('workshop_soort') == 'uitgebreid') {
                    $lessen = array();

                    $lessen_datum = $this->lessen_model->getLessenByGroepIDNormaalDatum($this->session->userdata('groep_ID'));
                    $lessen_beschikbaar = $this->lessen_model->getLessenByGroepIDBeschikbaar($this->session->userdata('groep_ID'));
                    $lessen_voorbereidend = $this->lessen_model->getVoorbereidendeLessenByGroepID($this->session->userdata('groep_ID'));


                    $lessen = $lessen_beschikbaar;

                    if(!empty($lessen_voorbereidend)) {
                        foreach($lessen_voorbereidend as $item) {
                            foreach($lessen_datum as $les) {
                                if($item->les_gekoppeld_aan_ID == $les->les_ID) {
                                    $voorbereidende_les_datum = new DateTime();
                                    $groep_les_datum = new DateTime($les->groep_les_datum);
                                    $voorbereidende_les_datum = $groep_les_datum->sub(DateInterval::createFromDateString($item->les_dagen_ervoor_beschikbaar . ' days'));
                                    $item->groep_les_datum = $voorbereidende_les_datum->format('Y-m-d');
                                }
                            }
                        }
                    }

                    $lessen_temp = array_merge($lessen_datum, $lessen_voorbereidend);

                    function cmp($a, $b) {
                        return strcmp($a->groep_les_datum, $b->groep_les_datum);
                    }

                    usort($lessen_temp, "cmp");
                    $lessen = array_merge($lessen, $lessen_temp);
                    $bool = false;
                    $lessen_unique = array();

                    foreach($lessen as $temp) {
                        if(!empty($lessen_unique)) {
                            foreach($lessen_unique as $unique) {
                                if($unique->groep_les_ID == $temp->groep_les_ID) {
                                    $bool = true;
                                }
                            }
                        }

                        if($bool == false) {
                            array_push($lessen_unique, $temp);
                        } else {
                            $bool = false;
                        }
                    }

                    if(!empty($lessen_unique)) {
                        $lessen = $lessen_unique;
                    }

                    $this->data['lessen'] = $lessen;
                } else {
                    $lessen = $this->lessen_model->getLessenByGroepID($this->session->userdata('groep_ID'));
                    $this->data['lessen'] = $lessen;
                }

				// Bepalen bij welke les de groep is

                if(isset($lessen) && sizeof($lessen) > 0)
                {
                    for($i = 1; $i <= sizeof($lessen); $i++)
                    {
                        $les = $lessen[$i-1];

                        $tijd_vandaag = time();
                        $datum_les = $les->groep_les_datum;
                        $tijd_les = strtotime($datum_les);

                        if($tijd_les <= $tijd_vandaag) $les_active = $i;

                        if($groep) {
                            $aanwezigheid = $this->aanwezigheid_model->getAanwezigheidByGroeplesIDGebruikerID($les->groep_les_ID, $this->session->userdata('gebruiker_ID'));

                            if ($aanwezigheid) {
                                $les->les_aanwezigheid = "nee";
                            }
                        }
                    }
                }
			}
			else
			{
				// Individuele lessen ophalen

				$this->load->model('lessen_model');
				$lessen = $this->lessen_model->getLessenByGebruikerID($this->session->userdata('gebruiker_ID'), $this->session->userdata('workshop_ID'));
				$this->data['lessen'] = $lessen;


				// Bepalen bij welke les de deelnemer is

				if(isset($lessen) && sizeof($lessen) > 0)
				{
					for($i = 1; $i <= sizeof($lessen); $i++)
					{
						$les = $lessen[$i-1];

						$tijd_vandaag = time();
						$datum_les = $les->individu_les_datum;
						$tijd_les = strtotime($datum_les);

						if($tijd_les <= $tijd_vandaag) $les_active = $i;
					}
				}
			}
		}


		// PAGINA TONEN

		$this->data['les_active'] = $les_active;
		$this->data['groep'] = $groep;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cursistenmodule/lessen';
		$this->load->view('cursistenmodule/template', $pagina);
    }



	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */

	public function les($item_ID)
    {
        // Workshops ophalen

        $aanwezigheid_feedback = '';
        $aanwezig = true;
        $volgende_les = '';

        $this->load->model('workshops_model');
        $workshops = $this->workshops_model->getWorkshopsByGebruikerID($this->session->userdata('gebruiker_ID'));
        $this->data['workshops'] = $workshops;


        $this->load->model('lessen_model');
        $this->load->model('huiswerk_model');
        $this->load->model('aanwezigheid_model');
        $this->load->model('gebruikers_model');
        $this->load->model('docenten_model');

        if (in_array($this->session->userdata('workshop_type'), array('groep', 'online'))) {
            $groep = true;

            // Groepsles
            // Groepslessen ophalen
            if($this->session->userdata('workshop_soort') == 'uitgebreid') {
                $lessen = array();

                $lessen_datum = $this->lessen_model->getLessenByGroepIDNormaalDatum($this->session->userdata('groep_ID'));
                $lessen_beschikbaar = $this->lessen_model->getLessenByGroepIDBeschikbaar($this->session->userdata('groep_ID'));
                $lessen_voorbereidend = $this->lessen_model->getVoorbereidendeLessenByGroepID($this->session->userdata('groep_ID'));

                $lessen = $lessen_beschikbaar;

                if(!empty($lessen_voorbereidend)) {
                    foreach($lessen_voorbereidend as $item) {
                        foreach($lessen_datum as $les) {
                            if($item->les_gekoppeld_aan_ID == $les->les_ID) {
                                $voorbereidende_les_datum = new DateTime();
                                $groep_les_datum = new DateTime($les->groep_les_datum);
                                $voorbereidende_les_datum = $groep_les_datum->sub(DateInterval::createFromDateString($item->les_dagen_ervoor_beschikbaar . ' days'));
                                $item->groep_les_datum = $voorbereidende_les_datum->format('Y-m-d');
                            }
                        }
                    }
                }

                $lessen_temp = array_merge($lessen_datum, $lessen_voorbereidend);

                function cmp($a, $b) {
                    return strcmp($a->groep_les_datum, $b->groep_les_datum);
                }

                usort($lessen_temp, "cmp");
                $lessen = array_merge($lessen, $lessen_temp);

                $bool = false;
                $lessen_unique = array();

                foreach($lessen as $temp) {
                    if(!empty($lessen_unique)) {
                        foreach($lessen_unique as $unique) {
                            if($unique->groep_les_ID == $temp->groep_les_ID) {
                                $bool = true;
                            }
                        }
                    }

                    if($bool == false) {
                        array_push($lessen_unique, $temp);
                    } else {
                        $bool = false;
                    }
                }

                if(!empty($lessen_unique)) {
                    $lessen = $lessen_unique;
                }

                $this->data['lessen'] = $lessen;


                $huidige_les = $item_ID;
                $length = count($lessen) -1;

                for($i = 0; $i < $length - 1; ++$i) {
                    $les_temp = $lessen[$i];

                    if ($les_temp->groep_les_ID == $huidige_les) {
                        $volgende_les = $lessen[$i+1];
                        break;
                    }
                }
			} else {
				$lessen = $this->lessen_model->getLessenByGroepID($this->session->userdata('groep_ID'));
            }

            $this->data['lessen'] = $lessen;

            $vorige_les = '';
            $beoordeeld = '';

            // Bepalen bij welke les de groep is

            if(isset($lessen) && sizeof($lessen) > 0)
            {
                $active = 0;
                for($i = 0; $i < sizeof($lessen); $i++)
                {
                    $les = $lessen[$i];

                    $datum_vandaag = date('Y-m-d');
                    $datum_les = $les->groep_les_datum;

                    $tijd_vandaag = strtotime($datum_vandaag);
                    $tijd_les = strtotime($datum_les);

                    if($tijd_les <= $tijd_vandaag) $active = $i;
                }

                if(count($lessen) > 1) {
                    if (($active - 1) >= 0) {
                        $vorige_les = $lessen[$active - 1];
                        $beoordeeld = $this->lessen_model->getBeoordeling($this->session->userdata('gebruiker_ID'), $vorige_les->les_ID);
                    }
                }
            }

            if (!empty($vorige_les)) {
                $aanwezigheid_temp = $this->aanwezigheid_model->getAanwezigheidByGroeplesIDGebruikerID($vorige_les->groep_les_ID, $this->session->userdata('gebruiker_ID'));

                if(!empty($aanwezigheid_temp)) {
                    $aanwezig = false;
                }
            }

            // LES OPHALEN (voor huiswerk uploaden!)

            $les = $this->lessen_model->getGroepLesByID($item_ID);

        if ($les == null) redirect('cursistenmodule/lessen');
            // VIDEO HEADER URL BOUWEN
            $les = $this->videoUrlBouwen($les);

            $this->data['les'] = $les;
            $bekeken_les = $les;
            $this->data['vorige_les'] = $vorige_les;
            $this->data['volgende_les'] = $volgende_les;

            $this->data['beoordeeld'] = $beoordeeld;
            $this->data['aanwezig'] = $aanwezig;
        } else {
            $groep = false;
            $vorige_les = '';
            $beoordeeld = '';

            $lessen = $this->lessen_model->getLessenByGebruikerID($this->session->userdata('gebruiker_ID'), $this->session->userdata('workshop_ID'));
            $this->data['lessen'] = $lessen;

            // Individuele les
            if(isset($lessen) && sizeof($lessen) > 0)
            {
                foreach($lessen as $item) {
                    $temp_item = $this->lessen_model->getLesIndividuByID($item->individu_les_ID);

                    $item->les_ID = $temp_item->les_ID;
                }

                $active = 0;
                for($i = 0; $i < sizeof($lessen); $i++)
                {
                    $les = $lessen[$i];

                    $tijd_vandaag = time();
                    $datum_les = $les->individu_les_datum;
                    $tijd_les = strtotime($datum_les);

                    if($tijd_les <= $tijd_vandaag) $active = $i;
                }

                if(count($lessen) > 1) {
                    if(($active - 1) >= 0) {
                        $vorige_les = $lessen[$active - 1];
                        $beoordeeld = $this->lessen_model->getBeoordeling($this->session->userdata('gebruiker_ID'), $vorige_les->les_ID);
                    }
                }
            }

            if($this->session->userdata('workshop_soort') == 'uitgebreid') {
            if (!empty($vorige_les)) {
                $aanwezigheid_temp = $this->aanwezigheid_model->getAanwezigheidByGroeplesIDGebruikerID($vorige_les->groep_les_ID, $this->session->userdata('gebruiker_ID'));

                if(!empty($aanwezigheid_temp)) {
                    $aanwezig = false;
                }
            }
        }

            // VIDEO HEADER URL BOUWEN
            $les = $this->videoUrlBouwen($les);

            $les = $this->lessen_model->getLesIndividuByID($item_ID);
            if ($les == null) redirect('cursistenmodule/lessen');
            $this->data['les'] = $les;
            $bekeken_les = $les;
            $this->data['volgende_les'] = $volgende_les;
            $this->data['vorige_les'] = $vorige_les;
            $this->data['beoordeeld'] = $beoordeeld;
            $this->data['aanwezig'] = $aanwezig;
        }

        // HUISWERK TOEVOEGEN OF INSTUREN

        $feedback = '';
        $huiswerk_titel = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $huiswerk_titel = trim($this->input->post('huiswerk_titel'));
            $huiswerk_opnieuw = trim($this->input->post('huiswerk_opnieuw'));

            if (isset($_POST['insturen'])) {
                // Huiswerk insturen

                if ($huiswerk_opnieuw == 'nee') {
                    // Resultaat toevoegen

                    $data = array(
                        'resultaat_beoordelen' => 'ja',
                        'resultaat_ingestuurd_datum' => date('Y-m-d H:i:s'),
                        'gebruiker_ID' => $this->session->userdata('gebruiker_ID'),
                        'les_ID' => $les->les_ID
                    );

                    $insturen = $this->huiswerk_model->insertResultaat($data);
                } else {
                    // Resultaat updaten

                    $data = array(
                        'resultaat_beoordelen' => 'ja',
                        'resultaat_opnieuw_ingestuurd_datum' => date('Y-m-d H:i:s')
                    );

                    $insturen = $this->huiswerk_model->updateResultaat($this->session->userdata('gebruiker_ID'), $les->les_ID, $data);
                }

                if ($insturen) {
                    // Terug naar les

                    redirect('cursistenmodule/lessen/' . $item_ID);
                }
            } elseif (isset($_POST['uploaden'])) {
                if ($_POST['audio_option'] == 'audio_src') {
                    // Huiswerk uploaden

                    if (empty($huiswerk_titel)) {
                        $feedback = 'Graag een titel invullen';
                    } else {
                        if ($_FILES['huiswerk_bestand']['error'] > 0) {
                            switch ($_FILES['huiswerk_bestand']['error']) {
                                case 1:
                                    $feedback = 'Het bestand is te groot';
                                    break;

                                case 2:
                                    $feedback = 'Het bestand is te groot';
                                    break;

                                case 3:
                                    $feedback = 'Het bestand is niet goed geupload';
                                    break;

                                case 4:
                                    $feedback = 'Graag een bestand selecteren';
                                    break;

                                case 6:
                                    $feedback = 'Geen tijdelijke folder';
                                    break;

                                case 7:
                                    $feedback = 'Kon bestand niet uploaden';
                                    break;
                            }
                        } else {
                            $bestand_types = array('audio/mp3');
                            $bestand_extensies = array('mp3');

                            $bestand_naam = $_FILES['huiswerk_bestand']['name'];
                            $bestand_type = $_FILES['huiswerk_bestand']['type'];
                            $bestand_grootte = $_FILES['huiswerk_bestand']['size'];
                            $bestand_tijdelijke_naam = $_FILES['huiswerk_bestand']['tmp_name'];

                            $bestand_type_extensie = explode('.', $bestand_naam);
                            $bestand_type_extensie = strtolower(end($bestand_type_extensie));

                            if (in_array($bestand_type_extensie, $bestand_extensies)) {
                                if ($bestand_grootte < 10000000) // 10 MB
                                {
                                    $titel = preg_replace("/[^a-z0-9]/", '', strtolower($huiswerk_titel));
                                    $titel = str_replace(' ', '-', $titel);
                                    $bestandsnaam = $this->session->userdata('gebruiker_ID') . '-' . $this->session->userdata('workshop_ID') . '-' . $les->les_ID . '-' . date('Ymd-His') . '-' . $titel . '.' . $bestand_type_extensie;

                                    if (move_uploaded_file($bestand_tijdelijke_naam, './media/huiswerk/' . $bestandsnaam)) {
                                        // Huiswerk opslaan in de database

                                        $data = array(
                                            'huiswerk_opnieuw' => $huiswerk_opnieuw,
                                            'huiswerk_titel' => $huiswerk_titel,
                                            'huiswerk_src' => $bestandsnaam,
                                            'huiswerk_datum' => date('Y-m-d H:i:s'),
                                            'gebruiker_ID' => $this->session->userdata('gebruiker_ID'),
                                            'les_ID' => $les->les_ID
                                        );

                                        $toevoegen = $this->huiswerk_model->insertHuiswerk($data);

                                        if ($toevoegen) {
                                            if ($groep) redirect('cursistenmodule/lessen/' . $les->groep_les_ID);
                                            else redirect('cursistenmodule/lessen/' . $les->individu_les_ID);
                                        } else {
                                            $feedback = 'Huiswerk toevoegen mislukt';
                                        }
                                    } else {
                                        $feedback = 'Het bestand is niet geüpload';
                                    }
                                } else {
                                    $feedback = 'De MP3 is te groot (maximaal 10 MB)';
                                }
                            } else {
                                $feedback = 'Selecteer een MP3 bestand';
                            }
                        }
                    }
                } else {
                    if (empty($huiswerk_titel)) {
                        $feedback = 'Graag een titel invullen';
                    } else {
                        $bestand_naam = $_FILES['huiswerk_bestand']['name'];
                        $bestand_type_extensie = explode('.', $bestand_naam);
                        $bestand_type_extensie = strtolower(end($bestand_type_extensie));

                        $titel = preg_replace("/[^a-z0-9 ]/", '', strtolower($huiswerk_titel));
                        $titel = str_replace(' ', '-', $titel);
                        $bestandsnaam = $this->session->userdata('gebruiker_ID') . '-' . $this->session->userdata('workshop_ID') . '-' . $les->les_ID . '-' . date('Ymd-His') . '-' . $titel . '.mp3';

                        if (file_exists('./media/huiswerk/' . $_POST['upload'] . '-' . date('Ymd') . '.mp3')) {
                            if (rename('./media/huiswerk/' . $_POST['upload'] . '-' . date('Ymd') . '.mp3', './media/huiswerk/' . $bestandsnaam)) {
                                // Huiswerk opslaan in de database

                                $data = array(
                                    'huiswerk_opnieuw' => $huiswerk_opnieuw,
                                    'huiswerk_titel' => $huiswerk_titel,
                                    'huiswerk_src' => $bestandsnaam,
                                    'huiswerk_datum' => date('Y-m-d H:i:s'),
                                    'gebruiker_ID' => $this->session->userdata('gebruiker_ID'),
                                    'les_ID' => $les->les_ID
                                );

                                $toevoegen = $this->huiswerk_model->insertHuiswerk($data);

                                if ($toevoegen) {
                                    if ($groep) redirect('cursistenmodule/lessen/' . $les->groep_les_ID);
                                    else redirect('cursistenmodule/lessen/' . $les->individu_les_ID);
                                } else {
                                    $feedback = 'Huiswerk toevoegen mislukt';
                                }
                            } else {
                                $feedback = 'Het bestand is niet geüpload';
                            }
                        }
                    }
                }
            }
        }

        if (isset($_POST['aanwezigheid'])) {
            if ($_POST['aanwezigheid'] == 'nee') {
                $gebruiker = $this->gebruikers_model->getGebruikerByID($this->session->userdata('gebruiker_ID'));

                if (!$gebruiker) {
                    redirect('cursistenmodule/lessen/');
                }

                $aanwezigheid = $this->aanwezigheid_model->getAanwezigheidByGroeplesIDGebruikerID($item_ID, $this->session->userdata('gebruiker_ID'));

                if (!empty($aanwezigheid)) {
                    $this->aanwezigheid_model->deleteAanwezigheid($item_ID, $this->session->userdata('gebruiker_ID'));
                }

                $data = array(
                    'gebruiker_ID' => $gebruiker->gebruiker_ID,
                    'les_ID' => $item_ID,
                    'aanwezigheid_aanwezig' => $_POST['aanwezigheid']
                );

                // update les
                $q = $this->aanwezigheid_model->insertAanwezigheid($data);

                if($q) {
                    $aanwezigheid_feedback = 'Aanwezigheid aangepast';
                } else {
                    $aanwezigheid_feedback = 'Er ging iets mis. Probeer het opnieuw.';
                }
            } else {
                $aanwezigheid = $this->aanwezigheid_model->getAanwezigheidByGroeplesIDGebruikerID($item_ID, $this->session->userdata('gebruiker_ID'));
                $q = '';

                if (!empty($aanwezigheid)) {
                    $q = $this->aanwezigheid_model->deleteAanwezigheid($item_ID, $this->session->userdata('gebruiker_ID'));
                }

                if($q) {
                    $aanwezigheid_feedback = 'Aanwezigheid aangepast';
                }
            }
        }

		// MEDIA OPHALEN

		$this->load->model('media_model');
		$media = $this->media_model->getMediaByContentID('les', $les->les_ID);

        $media_nieuw = $this->media_model->getMediaNieuwByContentID('les', $les->les_ID);

        if(!empty($media_nieuw)) {
            if(!empty($les->groep_les_datum)) {
                if ($les->groep_les_datum >= $media_nieuw[0]->media_ingang) {
                    $media = $media_nieuw;
                }
            } else {
                if ($les->individu_les_datum >= $media_nieuw[0]->media_ingang) {
                    $media = $media_nieuw;
                }
            }
        }

        $placeholder = $this->media_model->getMediaByContentID('placeholder', $les->les_ID);
        $this->data['placeholder'] = $placeholder;
		$this->data['media'] = $media;


		// HUISWERK OPHALEN

        $huiswerk = $this->huiswerk_model->getHuiswerk($this->session->userdata('gebruiker_ID'), $les->les_ID);

        if(!empty($huiswerk)) {
            foreach($huiswerk as $item) {
                $beoordelingscriteria_resultaat = $this->lessen_model->getBeoordelingscriteriaAndHuiswerk($item->huiswerk_ID);
                $item->resultaten = $beoordelingscriteria_resultaat;
            }
        }

		$this->data['huiswerk'] = $huiswerk;

        $opnieuw = $this->huiswerk_model->getHuiswerkOpnieuw($this->session->userdata('gebruiker_ID'), $les->les_ID);


        if(!empty($opnieuw)) {
            foreach($opnieuw as $item) {
                $beoordelingscriteria_resultaat = $this->lessen_model->getBeoordelingscriteriaAndHuiswerk($item->huiswerk_ID);
                $item->resultaten = $beoordelingscriteria_resultaat;
            }
        }

		$this->data['opnieuw'] = $opnieuw;

        $this->load->model('gegevens_model');
        $gegevens = $this->gegevens_model->getGegevensHuiswerk();
        $huiswerk_aan = $gegevens[0]->gegeven_waarde;
        $this->data['huiswerk_aan'] = $huiswerk_aan;

        $gegevens_temp = $this->gegevens_model->getGegevensHuiswerkTekst();
        $gegevens_tekst = $gegevens_temp[0]->gegeven_waarde;
        $this->data['gegevens_tekst'] = $gegevens_tekst;

        $beoordeling_les = 0;
        $beoordeling = $this->lessen_model->getBeoordeling($this->session->userdata('gebruiker_ID'), $les->les_ID);

        if(!empty($beoordeling[0]->les_beoordeling)) {
            $beoordeling_les = $beoordeling[0]->les_beoordeling;
        }
        $this->data['beoordeling'] = $beoordeling_les;

		// RESULTATEN OPHALEN

		$resultaat = $this->huiswerk_model->getResultaat($this->session->userdata('gebruiker_ID'), $les->les_ID);
		$this->data['resultaat'] = $resultaat;

		// AANWEZIGHEID OPHALEN
        $aanwezigheid = $this->aanwezigheid_model->getAanwezigheidByGroeplesIDGebruikerID($item_ID, $this->session->userdata('gebruiker_ID'));
        $this->data['aanwezigheid'] = $aanwezigheid;
        $this->data['aanwezigheid_feedback'] = $aanwezigheid_feedback;

		if(!empty($bekeken_les)) {
			if(!empty($bekeken_les->les_ID)) {
				$bekekenles = array(
					'les_ID' => $bekeken_les->les_ID,
					'gebruiker_ID' => $this->session->userdata('gebruiker_ID'),
					'workshop_ID' => $bekeken_les->workshop_ID
				);

				$les_bekeken = $this->lessen_model->getLesBekeken($this->session->userdata('gebruiker_ID'), $bekeken_les->les_ID);

				if(empty($les_bekeken)) {
					$this->lessen_model->insertLesBekeken($bekekenles);
				}
			}
		}

        // PAGINA TONEN

		$this->data['groep'] = $groep;
		$this->data['feedback'] = $feedback;
		$this->data['huiswerk_titel'] = $huiswerk_titel;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cursistenmodule/lessen_les';
		$this->load->view('cursistenmodule/template', $pagina);
    }

    private function videoUrlBouwen($les) {
        $video_urls = array();

        if(!empty($les->les_video_url)) {
            $aantal_url = explode(';', $les->les_video_url);
                if($les->les_video_type == "vimeo_standaard" || $les->les_video_type == "vimeo") {
                foreach($aantal_url as $item) {
                    $url = explode('/', $item);
                    if($url[3] == "event") {
                        array_push($video_urls ,$url[4]);
                    } else {
                        array_push($video_urls ,$url[3]);
                    }
                }
                } else {
                    array_push($video_urls , $les->les_video_url);
                }
        }

        $les->les_video_url = $video_urls;
        return $les;
    }
}
