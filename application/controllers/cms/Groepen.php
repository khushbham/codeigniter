<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Groepen extends CI_Controller
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
		$this->pagina();
	}

	public function pagina($p = 1, $a = 1, $ac = 1, $archief = 0, $groepen = 0, $filter = null, $aanmelden = 0)
	{
		$this->load->model('groepen_model');

		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if(isset($_POST['filter_locatie'])) $filter 			= $_POST['filter_locatie'];
		}


		$locaties = $this->groepen_model->getLocaties();
		$aantal_items = $this->groepen_model->getGroepenAantal();
		$aantal_items_actief = $this->groepen_model->getGroepenActiefAantal();
        $aantal_items_archief = $this->groepen_model->getGroepenArchiefAantal();
		$per_pagina = 20;
		$aantal_paginas = ceil($aantal_items / $per_pagina);
		$aantal_paginas_archief = ceil($aantal_items_archief / $per_pagina);
		$aantal_paginas_actief = ceil($aantal_items_actief / $per_pagina);
		$huidige_pagina = $p;
		$huidige_pagina_archief = $a;
		$huidige_pagina_actief = $ac;
		$groepen_actief = $this->groepen_model->getGroepenActief($per_pagina, $huidige_pagina_actief);
        $alle_groepen = $this->groepen_model->getGroepen($per_pagina, $huidige_pagina);
		$groepen_archief = $this->groepen_model->getGroepenArchief($per_pagina, $huidige_pagina_archief);
		$groepen_aanmelden = $this->groepen_model->getGroepenAanmelden();

		if(sizeof($groepen_aanmelden)) {
            foreach ($groepen_aanmelden as $groep) {
                if (!empty($groep)) {
					$groep->plekken_over = sizeof($this->groepen_model->getGroepDeelnemers($groep->groep_ID));
					$groep->plekken_over = $groep->workshop_capaciteit - $groep->plekken_over;
                } else {
                    $groep->plekken_over = 0;
                }
            }
        }

		if (!empty($filter)) {
			$alle_groepen = $this->groepen_model->getGroepen();
			$groepen_actief = $this->groepen_model->getGroepenActief();
			$groepen_archief = $this->groepen_model->getGroepenArchief();

			$groepen_archief = $this->filter($groepen_archief, $filter);
			$alle_groepen = $this->filter($alle_groepen, $filter);
			$groepen_actief = $this->filter($groepen_actief, $filter);
			$groepen_aanmelden = $this->filter($groepen_aanmelden, $filter);

			$aantal_paginas = 0;
			$aantal_paginas_archief = 0;
			$aantal_paginas_actief = 0;
		}

		// Controleren of de paginanummer te hoog is
		if($p > 1 && sizeof($alle_groepen) == 0) redirect('cms/groepen');

		// PAGINA TONEN
		$this->data['filter']				= $filter;
		$this->data['locaties'] 			= $locaties;
		$this->data['groepen_actief']		= $groepen_actief;
		$this->data['alle_groepen'] 		= $alle_groepen;
		$this->data['groepen_archief'] 		= $groepen_archief;
		$this->data['aantal_paginas'] 		= $aantal_paginas;
		$this->data['aantal_paginas_archief'] = $aantal_paginas_archief;
		$this->data['aantal_paginas_actief'] = $aantal_paginas_actief;
		$this->data['huidige_pagina']		= $huidige_pagina;
		$this->data['huidige_pagina_archief']	= $huidige_pagina_archief;
		$this->data['huidige_pagina_actief']	= $huidige_pagina_actief;
		$this->data['archief']	            = $archief;
		$this->data['groepen']				= $groepen;
		$this->data['aanmelden']			= $aanmelden;
		$this->data['groepen_aanmelden']	= $groepen_aanmelden;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/groepen';
		$this->load->view('cms/template', $pagina);
	}

	private function filter($groepen, $filter) {
		$sorted_groepen = array();

		foreach ($groepen as $key => $groep) {
			$this->load->model('groepen_model');
			$lessen = $this->groepen_model->getGroepLocatie($groep->groep_ID);
			if(!empty($lessen)) {
				$array = array();

				foreach($lessen as $les) {
					if($les->les_locatie_ID != null) {
						array_push($array, $les->les_locatie_ID);
					}
				}

				$values = array_count_values($array);
				arsort($values);
				$popular = array_slice(array_keys($values), 0, 1, true);

				if ($popular[0] == $filter) {
					array_push($sorted_groepen ,$groepen[$key]);
				}
			}
		}
		return $sorted_groepen;
	}



	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */

	public function detail($item_ID = null)
	{
		if($item_ID == null) redirect('cms/groepen');

		$les_active = 0;

		$this->load->model('groepen_model');
		$this->load->model('gebruikers_model');
		$this->load->model('media_model');
		$this->load->model('docenten_model');
		$item = $this->groepen_model->getGroepByID($item_ID);
		if($item == null) redirect('cms/groepen');
		$this->data['item'] = $item;

		$this->load->model('lessen_model');
		$lessen = $this->lessen_model->getLessenByWorkshopID($item->workshop_ID);
        $extra_lessen = $this->lessen_model->getExtraLessenByWorkshopIDandGroepID($item->workshop_ID, $item_ID);

        if(!empty($extra_lessen)) {
            $lessen = array_merge((array) $lessen, (array) $extra_lessen);
		}

		if(isset($lessen) && sizeof($lessen) > 0)
		{
			for($i = 1; $i <= sizeof($lessen); $i++)
			{
				$les = $lessen[$i-1];

				$groep_les = $this->lessen_model->getGroepLesByLesID($les->les_ID, $item_ID);

				if(!empty($groep_les->groep_les_datum)) {
				$tijd_vandaag = time();
				$datum_les = $groep_les->groep_les_datum;
				$tijd_les = strtotime($datum_les);

					if($groep_les->groep_les_datum != "0000-00-00 00:00:00") {
						if($tijd_les <= $tijd_vandaag) $les_active = $i;
					}
				}
			}
		}

		$this->data['lessen'] = $lessen;

        $alle_deelnemers = $this->gebruikers_model->getDeelnemers();

		$deelnemers = $this->groepen_model->getGroepDeelnemers($item_ID);
		$this->data['deelnemers'] = $deelnemers;
		// GET THE LOAN PAID AMOUNT 
		$paidDetail = [];
		foreach ($deelnemers as $gebruiker_ids) {
			$gebruikerData = $this->groepen_model->getpaidStatus($gebruiker_ids->gebruiker_ID);
			if($gebruikerData != ""){
				if(!in_array($gebruiker_ids->gebruiker_ID, $paidDetail)){
					array_push($paidDetail , $gebruikerData);
				}
			}
		}
		$pagina['paid'] = $paidDetail;
		if (!empty($deelnemers)) {
            foreach($deelnemers as $deelnemer) {
                foreach($alle_deelnemers as $key=>$cursist) {
                    if($deelnemer->gebruiker_ID == $cursist->gebruiker_ID) {
                        unset($alle_deelnemers[$key]);
                    }
                }
            }
        }

        if(!empty($deelnemers)) {
            foreach($deelnemers as $deelnemer) {
                    $beoordeling = $this->lessen_model->getAVGGebruikerBeoordeling($deelnemer->gebruiker_ID, $item->workshop_ID);

                    if($beoordeling) {
                        $deelnemer->gebruiker_beoordeling = $beoordeling[0]->gebruiker_beoordeling;
                    } else {
                        $deelnemer->gebruiker_beoordeling = 0;
                    }
            }
		}

		if (!empty($deelnemers)) {
			foreach($deelnemers as $deelnemer) {
				$deelnemer->profiel_foto = $this->media_model->getMediaProfielByGebruikerID($deelnemer->gebruiker_ID);
			}
		}

		$alle_groepen = $this->groepen_model->getGroepen();
		$this->data['groepen'] = $alle_groepen;
		$this->data['alle_deelnemers'] = $alle_deelnemers;
		$this->data['les_active'] = $les_active;

		// PAGINA LADEN
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/groepen_groep';
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
		if($item_ID == null) redirect('cms/groepen');
		$this->_toevoegen_wijzigen('wijzigen', $item_ID);
	}

	private function _toevoegen_wijzigen($actie, $item_ID = null)
	{
		$this->load->model('groepen_model');
		$this->load->model('aanmeldingen_model');

		$item_naam 				= '';
		$item_titel 			= '';
		$item_workshop	 		= '';
		$item_aanmelden			= 'nee';
		$item_datum_dag			= '';
		$item_datum_maand		= '';
		$item_datum_jaar		= '';
		$item_actief_datum_dag	= '';
		$item_actief_datum_maand= '';
		$item_actief_datum_jaar	= '';
		$item_archief_datum_dag	= '';
		$item_archief_datum_maand= '';
		$item_archief_datum_jaar= '';
		$item_notities 			= '';
		$item_downloadlinkmail 	= '';
		$item_min_gebruikers	= '';
		$item_drempelwaarde_versturen = '';
		$item_geautomatiseerde_mails = 1;
		$item_feedback_mail = 1;

		$item_naam_feedback 		= '';
		$item_titel_feedback 		= '';
		$item_workshop_feedback 	= '';
		$item_actief_datum_feedback = '';
		$item_archief_datum_feedback = '';
		$item_datum_feedback 		= '';
		$item_notities_feedback 	= '';


		// FORMULIER VERZONDEN

		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$fouten 			= 0;

			$item_naam 			= trim($_POST['item_naam']);
			$item_titel 		= trim($_POST['item_titel']);
			$item_workshop 		= trim($_POST['item_workshop']);
			$item_datum_dag 	= trim($_POST['item_datum_dag']);
			$item_datum_maand 	= trim($_POST['item_datum_maand']);
			$item_datum_jaar 	= trim($_POST['item_datum_jaar']);
			$item_archief_datum_dag 	= trim($_POST['item_archief_datum_dag']);
			$item_archief_datum_maand 	= trim($_POST['item_archief_datum_maand']);
			$item_archief_datum_jaar 	= trim($_POST['item_archief_datum_jaar']);
			$item_actief_datum_dag 		= trim($_POST['item_actief_datum_dag']);
			$item_actief_datum_maand 	= trim($_POST['item_actief_datum_maand']);
			$item_actief_datum_jaar 	= trim($_POST['item_actief_datum_jaar']);
			$item_notities 				= trim($_POST['item_notities']);
			$item_min_gebruikers 		= trim($_POST['item_min_gebruikers']);
			$item_drempelwaarde_versturen 	= trim($_POST['item_drempelwaarde_versturen']);
			$item_geautomatiseerde_mails	= trim($_POST['item_geautomatiseerde_mails']);
			$item_feedback_mail	= trim($_POST['item_feedback_mail']);

			if(empty($item_naam))
			{
				$fouten++;
				$item_naam_feedback = 'Graag invullen';
			}

            if(empty($item_titel))
            {
                $fouten++;
                $item_titel_feedback = 'Graag invullen';
            }

			if(empty($item_workshop))
			{
				$fouten++;
				$item_workshop_feedback = 'Graag selecteren';
			}

			if(isset($_POST['item_aanmelden']))
			{
				$item_aanmelden = $_POST['item_aanmelden'];
			}

			if(empty($item_datum_dag) || empty($item_datum_maand) || empty($item_datum_jaar))
			{
				$fouten++;
				$item_datum_feedback = 'Graag invullen';
			}

			if(empty($item_actief_datum_dag) || empty($item_actief_datum_maand) || empty($item_actief_datum_jaar))
			{
				$fouten++;
				$item_actief_datum_feedback = 'Graag invullen';
			}

			if(empty($item_archief_datum_dag) || empty($item_archief_datum_maand) || empty($item_archief_datum_jaar))
			{
				$fouten++;
				$item_archief_datum_feedback = 'Graag invullen';
			}

			if($fouten == 0)
			{
				// TOEVOEGEN / UPDATEN

				$archief_datum = $item_archief_datum_jaar.'-'.$item_archief_datum_maand.'-'.$item_archief_datum_dag;
				$actief_datum = $item_actief_datum_jaar.'-'.$item_actief_datum_maand.'-'.$item_actief_datum_dag;

				if($actie == "wijzigen") {
					if($item_datum_jaar.'-'.$item_datum_maand.'-'.$item_datum_dag != '0000-00-00') {
						if($item_actief_datum_jaar.'-'.$item_actief_datum_maand.'-'.$item_actief_datum_dag == '0000-00-00') {
							$actief_datum = new DateTime($item_datum_jaar.'-'.$item_datum_maand.'-'.$item_datum_dag);
							$actief_datum->add(DateInterval::createfromdatestring('-14 days'));
							$actief_datum = $actief_datum->format('Y-m-d');
						}

						if($item_archief_datum_jaar.'-'.$item_archief_datum_maand.'-'.$item_archief_datum_dag == '0000-00-00') {
							$archief_datum = new DateTime($item_datum_jaar.'-'.$item_datum_maand.'-'.$item_datum_dag);
							$archief_datum->add(DateInterval::createfromdatestring('6 months'));
							$archief_datum = $archief_datum->format('Y-m-d');
						}
					}
				}

				$data = array(
					'groep_naam' => $item_naam,
					'groep_titel' => $item_titel,
					'groep_aanmelden' => $item_aanmelden,
					'groep_startdatum' => $item_datum_jaar.'-'.$item_datum_maand.'-'.$item_datum_dag,
					'groep_actief_datum' => $actief_datum,
					'groep_archief_datum' => $archief_datum,
					'groep_notities' => $item_notities,
					'workshop_ID' => $item_workshop,
					'groep_drempelwaarde_versturen' => $item_drempelwaarde_versturen,
					'groep_min_gebruikers' => $item_min_gebruikers,
					'groep_geautomatiseerde_mails' => $item_geautomatiseerde_mails,
					'groep_feedback_mail' => $item_feedback_mail
				);

				if($actie == 'toevoegen') $q = $this->groepen_model->insertGroep($data);
				else $q = $this->groepen_model->updateGroep($item_ID, $data);

				if($q)
				{
					if($actie == 'toevoegen') {
						redirect('cms/groepen');
					} else {
						$aanmeldingen = $this->groepen_model->getGroepDeelnemers($item_ID);

						$aanmelding_data = array(
							'workshop_ID' => $item_workshop
						);

						foreach($aanmeldingen as $aanmelding) {
							$query = $this->aanmeldingen_model->updateAanmeldingByID($aanmelding->aanmelding_ID, $aanmelding_data);
						}

						redirect('cms/groepen/'.$item_ID);
					}
				}
				else
				{
					redirect('cms/groepen');
				}
			}
		}
		else
		{
			if($actie == 'wijzigen')
			{
				$item = $this->groepen_model->getGroepByID($item_ID);
				if($item == null) redirect('cms/groepen');

				$item_naam 			= $item->groep_naam;
				$item_titel 		= $item->groep_titel;
				$item_aanmelden	 	= $item->groep_aanmelden;
				$item_datum 		= $item->groep_startdatum;
				$item_actief_datum 	= $item->groep_actief_datum;
				$item_archief_datum = $item->groep_archief_datum;
				$item_notities 		= $item->groep_notities;
				$item_workshop 		= $item->workshop_ID;
				$item_downloadlinkmail = $item->groep_downloadlinkmail;
				$item_drempelwaarde_versturen = $item->groep_drempelwaarde_versturen;
				$item_min_gebruikers = $item->groep_min_gebruikers;
				$item_geautomatiseerde_mails = $item->groep_geautomatiseerde_mails;
				$item_feedback_mail = $item->groep_feedback_mail;

				$datum = explode('-', $item_datum);
				$actief_datum = explode('-', $item_actief_datum);
				$archief_datum = explode('-', $item_archief_datum);

				$item_datum_dag 		= $datum[2];
				$item_datum_maand 		= $datum[1];
				$item_datum_jaar 		= $datum[0];

				$item_actief_datum_dag 		= $actief_datum[2];
				$item_actief_datum_maand 	= $actief_datum[1];
				$item_actief_datum_jaar 	= $actief_datum[0];

				$item_archief_datum_dag 	= $archief_datum[2];
				$item_archief_datum_maand 	= $archief_datum[1];
				$item_archief_datum_jaar 	= $archief_datum[0];
			}
			else
			{
				$item_datum_dag 		= date('d');
				$item_datum_maand 		= date('m');
				$item_datum_jaar 		= date('Y');

				$item_actief_datum_dag 	= "00";
				$item_actief_datum_maand = "00";
				$item_actief_datum_jaar = "0000";

				$item_archief_datum_dag = "00";
				$item_archief_datum_maand = "00";
				$item_archief_datum_jaar = "0000";

				$item_workshop = $item_ID;
			}
		}

		// Workshop ophalen

		$this->load->model('workshops_model');
		$workshops = $this->workshops_model->getGroepWorkshops();
		$this->data['workshops'] = $workshops;


		// PAGINA TONEN

		$this->data['actie'] = $actie;

		$this->data['item_ID'] 				= $item_ID;
		$this->data['item_naam'] 			= $item_naam;
		$this->data['item_titel'] 			= $item_titel;
		$this->data['item_aanmelden'] 		= $item_aanmelden;
		$this->data['item_datum_dag'] 		= $item_datum_dag;
		$this->data['item_datum_maand'] 	= $item_datum_maand;
		$this->data['item_datum_jaar'] 		= $item_datum_jaar;
		$this->data['item_actief_datum_dag'] 	= $item_actief_datum_dag;
		$this->data['item_actief_datum_maand'] 	= $item_actief_datum_maand;
		$this->data['item_actief_datum_jaar'] 	= $item_actief_datum_jaar;
		$this->data['item_archief_datum_dag'] 	= $item_archief_datum_dag;
		$this->data['item_archief_datum_maand'] = $item_archief_datum_maand;
		$this->data['item_archief_datum_jaar'] 	= $item_archief_datum_jaar;
		$this->data['item_notities'] 		= $item_notities;
		$this->data['item_workshop'] 		= $item_workshop;
		$this->data['item_downloadlinkmail'] = $item_downloadlinkmail;
		$this->data['item_drempelwaarde_versturen'] = $item_drempelwaarde_versturen;
		$this->data['item_min_gebruikers']	= $item_min_gebruikers;
		$this->data['item_geautomatiseerde_mails'] = $item_geautomatiseerde_mails;
		$this->data['item_feedback_mail'] 	= $item_feedback_mail;

		$this->data['item_naam_feedback'] 			= $item_naam_feedback;
		$this->data['item_titel_feedback'] 			= $item_titel_feedback;
		$this->data['item_notities_feedback'] 		= $item_notities_feedback;
		$this->data['item_datum_feedback'] 			= $item_datum_feedback;
		$this->data['item_archief_datum_feedback'] 	= $item_archief_datum_feedback;
		$this->data['item_actief_datum_feedback'] 	= $item_actief_datum_feedback;
		$this->data['item_workshop_feedback'] 		= $item_workshop_feedback;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/groepen_wijzigen';
		$this->load->view('cms/template', $pagina);
	}

	public function downloadlink_mail($item_ID = null) {
        $item_email = '';
        $media = '';
        $actie = 'groepen';

        $this->load->model('groepen_model');
        $this->load->model('media_model');
        $groep = $this->groepen_model->getGroepByID($item_ID);
        $downloadlink_media = $this->media_model->getMediaByContentID('downloadlinkmail', $item_ID);

        if (!empty($groep->groep_downloadlinkmail)) {
            $item_email = $groep->groep_downloadlinkmail;
        }

        if (!empty($downloadlink_media)) {
            $media = $downloadlink_media;
        }

        // form submitted
        if(isset($_POST['item_email'])) {
                $item_email = trim($_POST['item_email']);

                $data = array(
                    'groep_downloadlinkmail' => $item_email
                );

                $q = $this->groepen_model->updateGroep($item_ID, $data);

                $this->media_model->verwijderConnecties('downloadlinkmail', $item_ID);

                $media_IDs = explode(',', $_POST['item_media']);

                for ($i = 0; $i < sizeof($media_IDs); $i++) {
                    if ($media_IDs[$i] > 0) {
                        $connectie = array('media_ID' => $media_IDs[$i], 'media_positie' => $i, 'content_type' => 'downloadlinkmail', 'content_ID' => $item_ID);
                        $this->media_model->insertMediaConnectie($connectie);
                    }
                }
                redirect('cms/groepen/wijzigen/' . $item_ID);
        }

        $this->data['groep_naam']                            	 = $groep->groep_naam;
        $this->data['actie']                                     = $actie;
        $this->data['item_ID']                                   = $item_ID;
        $this->data['media']                                     = $media;
        $this->data['item_email']                                = $item_email;

        // PAGINA TONEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/downloadmail_wijzigen';
        $this->load->view('cms/template', $pagina);
	}

	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */

	public function verwijderen($item_ID = null, $bevestiging = null)
	{
        if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'support') { redirect('cms/rechten'); }
		if($item_ID == null) redirect('cms/groepen');

		$this->load->model('groepen_model');
		$item = $this->groepen_model->getGroepByID($item_ID);
		if($item == null) redirect('cms/groepen');
		$this->data['item'] = $item;

		// Aantal aanmeldingen voor de groep ophalen

		$this->load->model('aanmeldingen_model');
		$aanmeldingen = $this->aanmeldingen_model->getAanmeldingenByGroepID($item_ID);
		$this->data['aanmeldingen'] = $aanmeldingen;


		// ITEM VERWIJDEREN

		if($bevestiging == 'ja')
		{
			// Verwijder lessen van groep

			$this->groepen_model->deleteLessenByGroepID($item_ID);

			// Verwijder groep

			$q = $this->groepen_model->deleteGroep($item_ID);
			if($q) redirect('cms/groepen');
			else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
		}


		// PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/groepen_verwijderen';
		$this->load->view('cms/template', $pagina);
	}

	public function archiveren($item_ID = null, $pagina = 1, $pagina_archief = 1, $pagina_actief = 1)
    {
        if($item_ID == null) redirect('cms/groepen');
        $this->load->model('groepen_model');

        $data = array(
            'groep_archiveren' => '1',
			'groep_archief_datum' => date('Y-m-d'),
        );

        $query = $this->groepen_model->GroepArchiverenByID($item_ID, $data);
		$archief = 1;
		$groepen = 0;

        $this->pagina($pagina, $pagina_archief, $pagina_actief, $archief, $groepen);
    }

    public function terugzetten($item_ID = null, $pagina = 1, $pagina_archief = 1, $pagina_actief = 1)
    {
        if($item_ID == null) redirect('cms/groepen');
        $this->load->model('groepen_model');

        $data = array(
            'groep_archiveren' => '0',
			'groep_archief_datum' => NULL,
        );

        $query = $this->groepen_model->GroepArchiverenByID($item_ID, $data);
		$archief = 0;
		$groepen = 1;

        $this->pagina($pagina , $pagina_archief, $pagina_actief, $archief, $groepen);
    }

    public function exporteren($item_ID = null)
    {
        if($item_ID == null) redirect('cms/groepen');

        $this->load->model('groepen_model');
        $item = $this->groepen_model->getGroepByID($item_ID);
        if($item == null) redirect('cms/groepen');
        $this->data['item'] = $item;

        // Aantal aanmeldingen voor de groep ophalen

        $this->load->model('aanmeldingen_model');
        $aanmeldingen = $this->aanmeldingen_model->getAanmeldingenByGroepID($item_ID);
        $this->data['aanmeldingen'] = $aanmeldingen;

        require(dirname(dirname(__FILE__)) . '/cms/Csv.php');

        // Retrieve data to export as CSV
        $body_items = array();
        $this->load->model('groepen_model');
        $cursisten = $this->groepen_model->getGroepDeelnemers($item_ID);

        foreach ($cursisten as $cursist) {
                $body_items[] = array($cursist->gebruiker_voornaam, $cursist->gebruiker_tussenvoegsel, $cursist->gebruiker_achternaam, $cursist->gebruiker_geslacht,
                    $cursist->gebruiker_emailadres, $cursist->gebruiker_telefoonnummer, $cursist->gebruiker_mobiel);
        }

        $csv = new CSV();
        $csv->set_header_items(array(('Voornaam'), ('Tussenvoegsel'), ('Achternaam'),('Gender'), ('E-mail'), ('Telefoonnummer'),('Mobiel')));
        $csv->set_body_items($body_items);
        $csv->output_as_download($item->groep_naam.'.csv'); // prompts the user to download the file as 'export.csv' by default

        // PAGINA TONEN

        $pagina['data'] = $this->data;
        redirect('cms/groepen/'.$item_ID);
    }

    public function deelnemer_toevoegen($item_ID = null) {
        if($item_ID == null) redirect('cms/groepen');

        $query = '';

        $this->load->model('aanmeldingen_model');
        $this->load->model('gebruikers_model');
        $this->load->model('groepen_model');
        $this->load->model('workshops_model');

        if(!empty($_POST['item_deelnemer'])) {
            $deelnemer_ID = trim($_POST['item_deelnemer']);

            $cursist = $this->gebruikers_model->getGebruikerByID($deelnemer_ID);
            $groep_ID = $this->groepen_model->getGroepByID($item_ID);

            $data = array(
                'aanmelding_type' => 'workshop',
                'aanmelding_datum' => date("Y-m-d H:i:s"),
                'aanmelding_betaald_datum' => date("Y-m-d H:i:s"),
                'aanmelding_betaald_bedrag' => 0,
                'gebruiker_ID' => $cursist->gebruiker_ID,
                'workshop_ID' => $groep_ID->workshop_ID,
                'groep_ID' => $item_ID
            );

            $aanmelding_ID = $this->aanmeldingen_model->insertAanmelding($data);

            if (!empty($aanmelding_ID)) {
                // is het een kanidaat?
                $workshops = $this->workshops_model->getWorkshopsByGebruikerID($deelnemer_ID);
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
                $this->gebruikers_model->updateGebruiker($deelnemer_ID, array('gebruiker_rechten' => $rechten));
            }
		}

        if(!$query) {
            echo 'er ging iets mis.';
            redirect('cms/groepen/'.$item_ID);
        } else {
            redirect('cms/groepen/'.$item_ID);
        }
    }

    public function gebruiker_beoordelingen($item_ID = null, $workshop_ID = null) {
        if($item_ID == null || $workshop_ID == null) {
            redirect('cms/groepen');
        }

        $this->load->model('gebruikers_model');
        $this->load->model('lessen_model');
        $this->load->model('workshops_model');

        $workshop = $this->workshops_model->getWorkshopByID($workshop_ID);
        $gebruiker = $this->gebruikers_model->getGebruikerByID($item_ID);
        $beoordelingen = $this->lessen_model->getGebruikerBeoordelingByID($item_ID, $workshop_ID);

        if($workshop == null || $gebruiker == null) {
            redirect('cms/groepen');
        }

        $this->data['beoordelingen'] = $beoordelingen;
        $this->data['gebruiker'] = $gebruiker;
        $this->data['workshop'] = $workshop;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/gebruiker_beoordelingen';
        $this->load->view('cms/template', $pagina);
	}

	public function groep_migreren($huidige_groep_ID) {
		$this->load->model('aanmeldingen_model');
		$this->load->model('groepen_model');
		$this->load->model('gebruikers_model');

		$groep_ID = $_POST['item_groep'];
		$huidige_groep = $this->groepen_model->getGroepByID($huidige_groep_ID);
		$huidige_workshop_ID = $huidige_groep->workshop_ID;

		$migratie_groep = $this->groepen_model->getGroepByID($groep_ID);
		$migratie_workshop_ID = $migratie_groep->workshop_ID;

		$deelnemers = $this->groepen_model->getGroepDeelnemers($groep_ID);

		foreach($deelnemers as $deelnemer) {

			$data = array(
				'groep_ID' => $huidige_groep_ID,
				'workshop_ID' => $huidige_workshop_ID
			);

			$this->aanmeldingen_model->updateAanmeldingByGebruikerWorkshop($deelnemer->gebruiker_ID, $groep_ID, $migratie_workshop_ID, $data);
		}


		$this->load->model('lessen_model');
        $this->load->model('gebruikers_model');
        $this->load->model('media_model');
        $this->load->model('groepen_model');
        $this->load->model('berichten_model');

        if (sizeof($deelnemers)) {
            foreach ($deelnemers as $this->gebruiker) {
                $les_individueel = array();
				$les_groep = array();

                $this->lessen = $this->lessen_model->getGroepLessenByGebruikerIDenWorkshopID($this->gebruiker->gebruiker_ID, $migratie_workshop_ID);

                if (!empty($this->lessen)) {
                    foreach ($this->lessen as $this->les_IDs) {
						$this->les = $this->lessen_model->getLesByID($this->les_IDs->les_ID);
						$groep_les_datum = new DateTime($this->les_IDs->les_datum);
                        if (!empty($this->les)) {
                                if (!empty($this->les->les_voorbereidingsmail)) {
                                    $vandaag = new DateTime();

                                    if ($groep_les_datum->format('Y/m/d') <= $vandaag->format('Y/m/d')) {
                                        $media = array();

                                        $voorbereidingsmail_media = $this->media_model->getMediaByContentID('voorbereidingsmail', $this->les->les_ID);

                                        if ($this->les_IDs->groep_ID) {
                                            $item_tekst = $this->ReplaceTags($this->gebruiker->gebruiker_ID, $this->les->les_voorbereidingsmail, $this->les_IDs->groep_ID);
                                        } else {
                                            $item_tekst = $this->ReplaceTags($this->gebruiker->gebruiker_ID, $this->les->les_voorbereidingsmail);
                                        }

                                        /////////////////////
                                        // BERICHT OPSLAAN //
                                        /////////////////////
                                        $bericht = array(
                                            'bericht_onderwerp' => $this->les->les_titel,
                                            'bericht_tekst' => $item_tekst,
                                            'bericht_datum' => date('Y-m-d H:i:s'),
                                            'bericht_afzender_ID' => 1610,
                                            'bericht_afzender_type' => 'deelnemer',
                                            'bericht_ontvanger_ID' => $this->gebruiker->gebruiker_ID,
                                            'bericht_no_reply' => 1
                                        );

                                        $verzonden = $this->berichten_model->verzendBericht($bericht);

                                        if (!empty($voorbereidingsmail_media)) {
                                            foreach($voorbereidingsmail_media as $item) {
                                                $connectie = array('media_ID' => $item->media_ID, 'media_positie' => $item->media_positie, 'content_type' => 'bericht', 'content_ID' => $verzonden);
                                                $this->media_model->insertMediaConnectie($connectie);
                                            }
                                        }

                                        if($this->gebruiker->gebruiker_instelling_email_updates == 'ja') {
                                            $this->_verstuur_email($this->les->les_titel, 1610, $this->gebruiker->gebruiker_ID);
                                        }
                                    }
                                }

                        }
                }
            }
        }
	}
		redirect("cms/groepen/detail/" . $huidige_groep_ID);
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
    //    $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
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
