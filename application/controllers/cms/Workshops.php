<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workshops extends CI_Controller
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

	public function index($archief = false)
	{
		$this->load->model('workshops_model');
		$this->load->model('lessen_model');

		$workshops = $this->workshops_model->getWorkshopsStandaardCMS();
		$specialties = $this->workshops_model->getWorkshopsSpecialtiesCMS();
		$uitgelicht = $this->workshops_model->getWorkshopsUitgelichtCMS();
        $workshops_archief_specialty = $this->workshops_model->getWorkshopsSpecialtiesCMSArchief();
        $workshops_archief = $this->workshops_model->getWorkshopsStandaardCMSArchief();
        $uitgelicht_archief = $this->workshops_model->getWorkshopsUitgelichtCMSArchief();
        $gratis_lessen = $this->lessen_model->getGratisLessen();


        $this->load->model('kennismakingsworkshop_model');
        $kennismakingsworkshops = $this->kennismakingsworkshop_model->getKennismakingsworkshops();

		// PAGINA TONEN

        $this->data['archief']                = $archief;
        $this->data['workshops_archief']      = $workshops_archief;
        $this->data['specialties_archief']    = $workshops_archief_specialty;
        $this->data['uitgelicht_archief']     = $uitgelicht_archief;
		$this->data['workshops'] 		      = $workshops;
        $this->data['kennismakingsworkshops'] = $kennismakingsworkshops;
		$this->data['specialties'] 		      = $specialties;
		$this->data['uitgelicht'] 		      = $uitgelicht;
		$this->data['gratis_lessen'] 		  = $gratis_lessen;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/workshops';
		$this->load->view('cms/template', $pagina);
	}



	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */

	public function detail($item_ID = null)
	{
		if($item_ID == null) redirect('cms/workshops');

		$this->load->model('workshops_model');
		$this->load->model('media_model');
		$this->load->model('gebruikers_model');

		// Workshop ophalen

		$workshop = $this->workshops_model->getWorkshopByID($item_ID);
		if($workshop == null) redirect('cms/workshops');

        $workshop->workshop_beschrijving = $this->ReplaceTags($workshop->workshop_beschrijving);

		// Lessen ophalen

		$lessen = $this->workshops_model->getWorkshopLessenByID($item_ID);

		// Groepen ophalen

		$groepen = $this->workshops_model->getWorkshopGroepenByID($item_ID);

		// Deelnemers ophalen

		$deelnemers = $this->workshops_model->getWorkshopDeelnemersByID($item_ID);

		// Producten ophalen

		$producten = $this->workshops_model->getWorkshopProductenByID($item_ID);

        $alle_deelnemers = $this->gebruikers_model->getDeelnemers();

        if (!empty($deelnemers)) {
            foreach($deelnemers as $deelnemer) {
                foreach($alle_deelnemers as $key=>$cursist) {
                    if($deelnemer->gebruiker_ID == $cursist->gebruiker_ID) {
                        unset($alle_deelnemers[$key]);
                    }
                }
            }
        }

        $this->data['alle_deelnemers'] = $alle_deelnemers;

        // MEDIA OPHALEN

		$media = $this->media_model->getMediaByMediaID($workshop->media_ID);
		$media_uitgelicht = $this->media_model->getMediaByMediaID($workshop->media_uitgelicht_ID);



		// PAGINA TONEN

		$this->data['workshop'] 		= $workshop;
		$this->data['lessen'] 			= $lessen;
		$this->data['groepen'] 			= $groepen;
		$this->data['deelnemers'] 		= $deelnemers;
		$this->data['producten'] 		= $producten;
		$this->data['media'] 			= $media;
		$this->data['media_uitgelicht'] = $media_uitgelicht;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/workshops_workshop';
		$this->load->view('cms/template', $pagina);
	}

	public function lessen_kopieren($item_ID) {
		if($item_ID == null) redirect('cms/workshops');

		$this->load->model('workshops_model');
		$this->load->model('lessen_model');
		$this->load->model('media_model');

		$workshops = $this->workshops_model->getAlleWorkshops();
		$item = $this->workshops_model->getWorkshopByID($item_ID);
		$lessen = $this->lessen_model->getLessenByWorkshopID($item->workshop_ID);


		if(isset($_POST['workshop_ID'])) {
			$workshop_naar_ID = $_POST['workshop_ID'];
			$geselecteerde_lessen = $_POST['geselecteerde_lessen'];

			if(!empty($lessen)) {
				foreach ($lessen as $les) {
					if(!empty($geselecteerde_lessen)) {
						foreach ($geselecteerde_lessen as $geselecteerde_les_ID) {
							if($geselecteerde_les_ID == $les->les_ID) {
								$les_ID = $les->les_ID;
								$media = $this->media_model->getMediaConnectie('les', $les->les_ID);
								$les->les_ID = '';
								$les->workshop_ID = $workshop_naar_ID;
								$insert_les_ID = $this->lessen_model->insertLes($les);

								foreach ($media as $file) {
									$file->media_connectie_ID = '';
									$file->content_ID = $insert_les_ID;
									$this->media_model->insertMediaConnectie($file);
								}

								if (!empty($les->les_voorbereidingsmail)) {
									$voorbereidingsmail_media = $this->media_model->getMediaConnectie('voorbereidingsmail', $les_ID);

									if(!empty($voorbereidingsmail_media)) {
										foreach ($voorbereidingsmail_media as $file) {
											$file->media_connectie_ID = '';
											$file->content_ID = $insert_les_ID;
											$this->media_model->insertMediaConnectie($file);
										}
									}
								}

								if (!empty($les->les_welkomstmail)) {
									$welkomstmail_media = $this->media_model->getMediaConnectie('welkomstmail', $les_ID);

									if(!empty($welkomstmail_media)) {
										foreach ($welkomstmail_media as $file) {
											$file->media_connectie_ID = '';
											$file->content_ID = $insert_les_ID;
											$this->media_model->insertMediaConnectie($file);
										}
									}
								}
							}
						}
					}
				}
				redirect('cms/workshops/' . $workshop_naar_ID);
			}
		}

		$this->data['item'] = $item;
		$this->data['lessen'] = $lessen;
		$this->data['workshops'] = $workshops;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/lessen_kopieren';
		$this->load->view('cms/template', $pagina);
	}

	/* ===================== */
	/* = PRODUCTEN BEHEREN = */
	/* ===================== */

	public function producten($item_ID)
	{
		if($item_ID == null) redirect('cms/workshops');

		$this->load->model('workshops_model');
		$workshop = $this->workshops_model->getWorkshopByID($item_ID);
		if($workshop == null) redirect('cms/workshops');


		// GEKOPPELDE PRODUCTEN UPDATEN

		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			$this->load->model('workshops_model');
			$this->workshops_model->deleteWorkshopProductenByID($item_ID);
			$aantal_gekoppeld = 0;

			if(isset($_POST['producten']))
			{
				$aantal_gekoppeld = sizeof($_POST['producten']);

				foreach($_POST['producten'] as $product_ID) {
                    $this->workshops_model->koppelWorkshopProduct($item_ID, $product_ID);
                    foreach($_POST['product_beschikbaar'] as $id => $key) {
                        if($id == $product_ID) {
                            $data = array(
                                'wanneer_beschikbaar' => $key
                            );

                            $this->workshops_model->updateProductBeschikbaar($item_ID, $product_ID, $data);
                        }
                    }
				}
			}

			$this->workshops_model->updateWorkshop($item_ID, array('workshop_producten' => $aantal_gekoppeld));
			redirect('cms/workshops/'.$item_ID);
		}


		// PRODUCTEN OPHALEN

		$this->load->model('producten_model');
		$producten = $this->producten_model->getProducten();
		$gekoppeld = $this->workshops_model->getWorkshopProductenByID($item_ID);
		$gekoppeld_array = array();

		foreach($gekoppeld as $product) {
            $gekoppeld_array[] = array('id' => $product->product_ID, 'wanneer_beschikbaar' => $product->wanneer_beschikbaar);
        }


		// PAGINA TONEN

		$this->data['workshop'] = $workshop;
		$this->data['producten'] = $producten;
		$this->data['gekoppeld'] = $gekoppeld_array;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/workshops_producten';
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
		if($item_ID == null) redirect('cms/workshops');
		$this->_toevoegen_wijzigen('wijzigen', $item_ID);
	}

	private function _toevoegen_wijzigen($actie, $item_ID = null)
	{
		$this->load->model('workshops_model');
		$this->load->model('media_model');

		$item_in3                         = '';
		$item_cursistenmodule                   = '';
        $item_zichtbaar_publiek                 = '';
        $item_zichtbaar_cursist                 = '';
		$item_type 								= '';
		$item_niveau                            = '';
		$item_specialty 						= '';
		$item_gepubliceerd 						= '';
		$item_uitgelicht 						= '';
		$item_url 								= '';
		$item_titel 							= '';
		$item_ondertitel 						= '';
		$item_afkorting 						= '';
		$item_locatie    						= 'Leiden';
		$item_inleiding 						= '';
		$item_beschrijving 						= '';
		$item_prijs 							= '';
		$item_startdatum_dag					= '';
		$item_startdatum_maand					= '';
		$item_startdatum_jaar					= '';
		$item_frequentie						= '';
		$item_duur 								= '';
		$item_capaciteit 						= '';
		$item_toelatingseisen 					= '';
		$item_inclusief 						= '';
		$item_exclusief 						= '';
		$item_stemtest	 						= '';
		$item_stemtest_code 					= '';
		$item_stemtest_prijs					= '';
		$item_stemtest_tekst					= '';
		$item_stemtest_dagen_korting_na_afloop  = '';
		$item_aanmelden_tekst					= '';
		$media 									= '';
		$media_uitgelicht						= '';
		$item_meta_title						= '';
		$item_meta_description					= '';
        $item_welkomstmail					    = '';
		$item_feedbackmail					    = '';
		$item_herinneringsmail 					= '';
		$item_soort								= '';
		$item_cursistenmodule_tekst				= '';
		$item_grootte_zichtbaar					= '';
		$item_producten_tekst					= '';

		$item_type_feedback 							= '';
        $item_niveau_feedback 							= '';
		$item_specialty_feedback	 					= '';
		$item_gepubliceerd_feedback						= '';
		$item_uitgelicht_feedback						= '';
		$item_url_feedback 								= '';
		$item_titel_feedback 							= '';
		$item_ondertitel_feedback 						= '';
		$item_afkorting_feedback 						= '';
		$item_locatie_feedback   						= '';
		$item_inleiding_feedback 						= '';
		$item_beschrijving_feedback 					= '';
		$item_prijs_feedback 							= '';
		$item_startdatum_feedback						= '';
		$item_frequentie_feedback						= '';
		$item_duur_feedback 							= '';
		$item_capaciteit_feedback 						= '';
		$item_toelatingseisen_feedback 					= '';
		$item_inclusief_feedback 						= '';
		$item_exclusief_feedback 						= '';
		$item_stemtest_feedback		 					= '';
		$item_stemtest_code_feedback 					= '';
		$item_stemtest_prijs_feedback 					= '';
		$item_stemtest_tekst_feedback 					= '';
		$item_stemtest_dagen_korting_na_afloop_feedback = '';
		$item_aanmelden_tekst_feedback					= '';
		$item_meta_title_feedback						= '';
		$item_meta_description_feedback					= '';
        $item_in3_feedback                        		= '';
        $item_cursistenmodule_feedback                  = '';
        $item_zichtbaar_publiek_feedback                = '';
		$item_zichtbaar_cursist_feedback                = '';
		$item_producten_tekst_feedback					= '';

		// FORMULIER VERZONDEN

		if(isset($_POST['item_titel']))
		{
			$fouten 								= 0;
			$item_type 								= trim($_POST['item_type']);
            $item_niveau 							= trim($_POST['item_niveau']);
			$item_url 								= trim($_POST['item_url']);
			$item_titel 							= trim($_POST['item_titel']);
			$item_ondertitel 						= trim($_POST['item_ondertitel']);
			$item_afkorting 						= trim($_POST['item_afkorting']);
			$item_locatie    						= trim($_POST['item_locatie']);
			$item_inleiding 						= trim($_POST['item_inleiding']);
			$item_beschrijving 						= trim($_POST['item_beschrijving']);
			$item_cursistenmodule_tekst				= trim($_POST['item_cursistenmodule_tekst']);
			$item_prijs 							= trim($_POST['item_prijs']);
			$item_startdatum_dag 					= trim($_POST['item_startdatum_dag']);
			$item_startdatum_maand 					= trim($_POST['item_startdatum_maand']);
			$item_startdatum_jaar 					= trim($_POST['item_startdatum_jaar']);
			$item_frequentie 						= trim($_POST['item_frequentie']);
			$item_duur 								= trim($_POST['item_duur']);
			$item_capaciteit 						= trim($_POST['item_capaciteit']);
			$item_toelatingseisen 					= trim($_POST['item_toelatingseisen']);
			$item_inclusief 						= trim($_POST['item_inclusief']);
			$item_exclusief 						= trim($_POST['item_exclusief']);
			$item_stemtest_code	 					= trim($_POST['item_stemtest_code']);
			$item_stemtest_prijs 					= trim($_POST['item_stemtest_prijs']);
			$item_stemtest_tekst 					= trim($_POST['item_stemtest_tekst']);
			$item_stemtest_dagen_korting_na_afloop 	= trim($_POST['item_stemtest_dagen_korting_na_afloop']);
			$item_aanmelden_tekst 					= trim($_POST['item_aanmelden_tekst']);
			$item_media								= trim($_POST['item_media']);
			$item_media_uitgelicht					= trim($_POST['item_media_uitgelicht']);
			$item_meta_title						= trim($_POST['item_meta_title']);
			$item_meta_description					= trim($_POST['item_meta_description']);
            $item_in3					        = trim($_POST['item_in3']);
            $item_cursistenmodule					= trim($_POST['item_cursistenmodule']);
            $item_zichtbaar_publiek					= trim($_POST['item_zichtbaar_publiek']);
			$item_zichtbaar_cursist					= trim($_POST['item_zichtbaar_cursist']);
			$item_soort								= trim($_POST['item_soort']);
			$item_producten_tekst					= trim($_POST['item_producten_tekst']);

			if(isset($_POST['item_specialty'])) $item_specialty = trim($_POST['item_specialty']);
			if(isset($_POST['item_gepubliceerd'])) $item_gepubliceerd = trim($_POST['item_gepubliceerd']);
			if(isset($_POST['item_uitgelicht'])) $item_uitgelicht = trim($_POST['item_uitgelicht']);
			if(isset($_POST['item_grootte_zichtbaar'])) $item_grootte_zichtbaar = trim($_POST['item_grootte_zichtbaar']);
			if(isset($_POST['item_stemtest'])) $item_stemtest = trim($_POST['item_stemtest']);

			if(empty($item_type))
			{
				$fouten++;
				$item_type_feedback = 'Graag selecteren';
			}

			if(empty($item_specialty))
			{
				$fouten++;
				$item_specialty_feedback = 'Graag selecteren';
			}

			if(empty($item_gepubliceerd))
			{
				$fouten++;
				$item_gepubliceerd_feedback = 'Graag selecteren';
			}

			if(empty($item_uitgelicht))
			{
				$fouten++;
				$item_uitgelicht_feedback = 'Graag selecteren';
			}

			if(empty($item_url))
			{
				$fouten++;
				$item_url_feedback = 'Graag invullen';
			}

			if(empty($item_titel))
			{
				$fouten++;
				$item_titel_feedback = 'Graag invullen';
			}

			if(empty($item_ondertitel))
			{
				$fouten++;
				$item_ondertitel_feedback = 'Graag invullen';
			}

			if(empty($item_afkorting))
			{
				$fouten++;
				$item_afkorting_feedback = 'Graag invullen';
			}

			if(empty($item_inleiding))
			{
				$fouten++;
				$item_inleiding_feedback = 'Graag invullen';
			}

			if(empty($item_prijs))
			{
				$fouten++;
				$item_prijs_feedback = 'Graag invullen';
			}

			if(!in_array($item_type, array('groep', 'online')) && empty($item_frequentie))
			{
				$fouten++;
				$item_frequentie_feedback = 'Graag invullen';
			}

			if(empty($item_duur))
			{
				$fouten++;
				$item_duur_feedback = 'Graag invullen';
			}

			if(!is_numeric($item_capaciteit))
			{
				$fouten++;
				$item_capaciteit_feedback = 'Graag invullen';
			}

			if(empty($item_stemtest))
			{
				$fouten++;
				$item_stemtest_feedback = 'Graag selecteren';
			}

			if($item_stemtest == 'ja')
			{
				if(empty($item_stemtest_code))
				{
					$fouten++;
					$item_stemtest_code_feedback = 'Graag invullen';
				}

				if(empty($item_stemtest_prijs))
				{
					$fouten++;
					$item_stemtest_prijs_feedback = 'Graag invullen';
				}

				if(empty($item_stemtest_tekst))
				{
					$fouten++;
					$item_stemtest_tekst_feedback = 'Graag invullen';
				}
			}

			if($fouten == 0)
			{
				// TOEVOEGEN / UPDATEN

				$item_startdatum = '';
				if(!empty($item_startdatum_jaar) && !empty($item_startdatum_maand) && !empty($item_startdatum_dag)) $item_startdatum = $item_startdatum_jaar.'-'.$item_startdatum_maand.'-'.$item_startdatum_dag;

				// if($item_stemtest == 'ja') $stemtest = 1;
				// else $stemtest = 0;

				$data = array(
					'workshop_type' => $item_type,
					'workshop_soort' => $item_soort,
					'workshop_niveau' => $item_niveau,
					'workshop_specialty' => $item_specialty,
					'workshop_gepubliceerd' => $item_gepubliceerd,
					'workshop_uitgelicht' => $item_uitgelicht,
					'workshop_url' => $item_url,
					'workshop_titel' => $item_titel,
					'workshop_ondertitel' => $item_ondertitel,
					'workshop_afkorting' => $item_afkorting,
					'workshop_locatie' => $item_locatie,
					'workshop_inleiding' => $item_inleiding,
					'workshop_beschrijving' => $item_beschrijving,
					'workshop_cursistenmodule_tekst' => $item_cursistenmodule_tekst,
					'workshop_prijs' => $item_prijs,
					'workshop_startdatum' => $item_startdatum,
					'workshop_frequentie' => $item_frequentie,
					'workshop_duur' => $item_duur,
					'workshop_capaciteit' => $item_capaciteit,
					'workshop_toelatingseisen' => $item_toelatingseisen,
					'workshop_inclusief' => $item_inclusief,
					'workshop_exclusief' => $item_exclusief,
					'workshop_stemtest' => $item_stemtest,
					'workshop_stemtest_code' => $item_stemtest_code,
					'workshop_stemtest_prijs' => $item_stemtest_prijs,
					'workshop_stemtest_tekst' => $item_stemtest_tekst,
					'workshop_stemtest_dagen_korting_na_afloop' => $item_stemtest_dagen_korting_na_afloop,
					'workshop_aanmelden_tekst' => $item_aanmelden_tekst,
					'media_ID' => str_replace(',', '', $item_media),
					'media_uitgelicht_ID' => str_replace(',', '', $item_media_uitgelicht),
					'meta_title' => $item_meta_title,
					'meta_description' => $item_meta_description,
					'workshop_in3' => $item_in3,
					'volledige_cursistenmodule' => $item_cursistenmodule,
					'workshop_zichtbaar_publiek' => $item_zichtbaar_publiek,
					'workshop_zichtbaar_cursist' => $item_zichtbaar_cursist,
					'workshop_grootte_zichtbaar' => $item_grootte_zichtbaar,
					'workshop_producten_tekst'	=> $item_producten_tekst
				);

				if($actie == 'toevoegen' && empty($_POST['item_kopie'])) {
                    $q = $this->workshops_model->insertWorkshop($data);
                } else {
					$q = $this->workshops_model->updateWorkshop($item_ID, $data);
                }

                if (!empty($_POST['item_kopie']) && $actie == 'toevoegen') {
                    $this->load->model('lessen_model');
                    $insert_id = $this->workshops_model->insertWorkshopReturnID($data);
                    if (!empty($insert_id)) {
                        $q = true;
                    }

                    $lessen = $this->lessen_model->getLessenByWorkshopID($_POST['item_kopie']);

                    foreach ($lessen as $les) {
                        $media = $this->media_model->getMediaConnectie('les', $les->les_ID);
                        $les->les_ID = '';
                        $les->workshop_ID = $insert_id;
                        $insert_les_ID = $this->lessen_model->insertLes($les);

                        foreach ($media as $file) {
                            $file->media_connectie_ID = '';
                            $file->content_ID = $insert_les_ID;
                            $this->media_model->insertMediaConnectie($file);
                        }
                    }

                    $producten = $this->workshops_model->getWorkshopProductenByID($_POST['item_kopie']);

                    foreach ($producten as $product) {
                        $this->workshops_model->koppelWorkshopProduct($insert_id, $product->product_ID);
                    }
                }

				if($q)
				{
					if($actie == 'toevoegen') redirect('cms/workshops');
					else redirect('cms/workshops/'.$item_ID);
				}
				else
				{
					echo 'Workshop toevoegen / wijzigen mislukt. Probeer het nog eens.';
				}
			}
		}

		if($actie == 'wijzigen')
		{
			$workshop = $this->workshops_model->getWorkshopByID($item_ID);
			if($workshop == null) redirect('cms/workshops');

			$item_type 								= $workshop->workshop_type;
			$item_soort								= $workshop->workshop_soort;
			$item_niveau 							= $workshop->workshop_niveau;
			$item_specialty							= $workshop->workshop_specialty;
			$item_gepubliceerd						= $workshop->workshop_gepubliceerd;
			$item_uitgelicht						= $workshop->workshop_uitgelicht;
			$item_url 								= $workshop->workshop_url;
			$item_titel 							= $workshop->workshop_titel;
			$item_ondertitel 						= $workshop->workshop_ondertitel;
			$item_afkorting 						= $workshop->workshop_afkorting;
			$item_locatie 						    = $workshop->workshop_locatie;
			$item_inleiding 						= $workshop->workshop_inleiding;
			$item_beschrijving 						= $workshop->workshop_beschrijving;
			$item_cursistenmodule_tekst				= $workshop->workshop_cursistenmodule_tekst;
			$item_prijs 							= $workshop->workshop_prijs;
			$item_startdatum 						= $workshop->workshop_startdatum;
			$item_frequentie 						= $workshop->workshop_frequentie;
			$item_duur 								= $workshop->workshop_duur;
			$item_capaciteit 						= $workshop->workshop_capaciteit;
			$item_toelatingseisen 					= $workshop->workshop_toelatingseisen;
			$item_inclusief 						= $workshop->workshop_inclusief;
			$item_exclusief 						= $workshop->workshop_exclusief;
			$item_stemtest	 						= $workshop->workshop_stemtest;
			$item_stemtest_code 					= $workshop->workshop_stemtest_code;
			$item_stemtest_prijs 					= $workshop->workshop_stemtest_prijs;
			$item_stemtest_tekst 					= $workshop->workshop_stemtest_tekst;
			$item_stemtest_dagen_korting_na_afloop 	= $workshop->workshop_stemtest_dagen_korting_na_afloop;
            $item_in3 	                    = $workshop->workshop_in3;
            $item_cursistenmodule	                = $workshop->volledige_cursistenmodule;
            $item_zichtbaar_publiek	                = $workshop->workshop_zichtbaar_publiek;
			$item_zichtbaar_cursist	                = $workshop->workshop_zichtbaar_cursist;
			$item_grootte_zichtbaar					= $workshop->workshop_grootte_zichtbaar;
			$item_producten_tekst					= $workshop->workshop_producten_tekst;

			$item_aanmelden_tekst 			= $workshop->workshop_aanmelden_tekst;
			$item_meta_title				= $workshop->meta_title;
			$item_meta_description			= $workshop->meta_description;
			$item_welkomstmail              = $workshop->workshop_welkomstmail;
			$item_herinneringsmail			= $workshop->workshop_herinneringsmail;
            $item_feedbackmail              = $workshop->workshop_feedbackmail;

			// if($item_stemtest) $item_stemtest = 'ja';
			// else $item_stemtest = 'nee';

			if($item_startdatum != '' && $item_startdatum != '0000-00-00 00:00:00')
			{
				$item_startdatum_explode = explode(' ', $item_startdatum);
				$startdatum_explode = explode('-', $item_startdatum_explode[0]);

				$item_startdatum_dag 	= $startdatum_explode[2];
				$item_startdatum_maand 	= $startdatum_explode[1];
				$item_startdatum_jaar 	= $startdatum_explode[0];
			}

			// MEDIA OPHALEN

			$media = $this->media_model->getMediaByMediaID($workshop->media_ID);
			$media_uitgelicht = $this->media_model->getMediaByMediaID($workshop->media_uitgelicht_ID);
		}

		// PAGINA TONEN

		$this->data['actie'] = $actie;

		$this->data['item_ID'] 									= $item_ID;
		$this->data['item_type'] 								= $item_type;
		$this->data['item_soort']								= $item_soort;
		$this->data['item_niveau'] 							    = $item_niveau;
		$this->data['item_specialty'] 							= $item_specialty;
		$this->data['item_gepubliceerd'] 						= $item_gepubliceerd;
		$this->data['item_uitgelicht'] 							= $item_uitgelicht;
		$this->data['item_url'] 								= $item_url;
		$this->data['item_titel'] 								= $item_titel;
		$this->data['item_ondertitel'] 							= $item_ondertitel;
		$this->data['item_afkorting'] 							= $item_afkorting;
		$this->data['item_locatie'] 							= $item_locatie;
		$this->data['item_inleiding'] 							= $item_inleiding;
		$this->data['item_beschrijving'] 						= $item_beschrijving;
		$this->data['item_cursistenmodule_tekst']				= $item_cursistenmodule_tekst;
		$this->data['item_prijs'] 								= $item_prijs;
		$this->data['item_startdatum_dag']						= $item_startdatum_dag;
		$this->data['item_startdatum_maand']					= $item_startdatum_maand;
		$this->data['item_startdatum_jaar']						= $item_startdatum_jaar;
		$this->data['item_frequentie'] 							= $item_frequentie;
		$this->data['item_duur'] 								= $item_duur;
		$this->data['item_capaciteit'] 							= $item_capaciteit;
		$this->data['item_toelatingseisen'] 					= $item_toelatingseisen;
		$this->data['item_inclusief'] 							= $item_inclusief;
		$this->data['item_exclusief'] 							= $item_exclusief;
		$this->data['item_stemtest'] 							= $item_stemtest;
		$this->data['item_stemtest_code'] 						= $item_stemtest_code;
		$this->data['item_stemtest_prijs'] 						= $item_stemtest_prijs;
		$this->data['item_stemtest_tekst'] 						= $item_stemtest_tekst;
		$this->data['item_stemtest_dagen_korting_na_afloop'] 	= $item_stemtest_dagen_korting_na_afloop;
		$this->data['item_aanmelden_tekst'] 					= $item_aanmelden_tekst;
		$this->data['media'] 									= $media;
		$this->data['media_uitgelicht']							= $media_uitgelicht;
		$this->data['item_meta_title'] 							= $item_meta_title;
		$this->data['item_meta_description'] 					= $item_meta_description;
		$this->data['item_welkomstmail'] 					    = $item_welkomstmail;
		$this->data['item_herinneringsmail']					= $item_herinneringsmail;
		$this->data['item_feedbackmail'] 					    = $item_feedbackmail;
		$this->data['item_in3'] 					        = $item_in3;
		$this->data['item_cursistenmodule'] 					= $item_cursistenmodule;
		$this->data['item_zichtbaar_publiek']			        = $item_zichtbaar_publiek;
		$this->data['item_zichtbaar_cursist'] 					= $item_zichtbaar_cursist;
		$this->data['item_grootte_zichtbaar'] 					= $item_grootte_zichtbaar;
		$this->data['item_producten_tekst']						= $item_producten_tekst;

		$this->data['item_type_feedback'] 					  	  		= $item_type_feedback;
		$this->data['item_niveau_feedback'] 					  	  	= $item_niveau_feedback;
		$this->data['item_specialty_feedback']	 			  	  		= $item_specialty_feedback;
		$this->data['item_gepubliceerd_feedback']	 		  	  		= $item_gepubliceerd_feedback;
		$this->data['item_uitgelicht_feedback']	 			  	  		= $item_uitgelicht_feedback;
		$this->data['item_url_feedback'] 					  	  		= $item_url_feedback;
		$this->data['item_titel_feedback'] 					  	  		= $item_titel_feedback;
		$this->data['item_ondertitel_feedback'] 			  	  		= $item_ondertitel_feedback;
		$this->data['item_afkorting_feedback'] 				  	  		= $item_afkorting_feedback;
		$this->data['item_locatie_feedback'] 				  	  		= $item_locatie_feedback;
		$this->data['item_inleiding_feedback'] 				  	  		= $item_inleiding_feedback;
		$this->data['item_beschrijving_feedback'] 			  	  		= $item_beschrijving_feedback;
		$this->data['item_prijs_feedback'] 					  	  		= $item_prijs_feedback;
		$this->data['item_startdatum_feedback'] 			  	  		= $item_startdatum_feedback;
		$this->data['item_frequentie_feedback'] 			  	  		= $item_frequentie_feedback;
		$this->data['item_duur_feedback'] 					  	  		= $item_duur_feedback;
		$this->data['item_capaciteit_feedback'] 			  	  		= $item_capaciteit_feedback;
		$this->data['item_toelatingseisen_feedback'] 		  	  		= $item_toelatingseisen_feedback;
		$this->data['item_inclusief_feedback'] 				  	  		= $item_inclusief_feedback;
		$this->data['item_exclusief_feedback'] 				  	  		= $item_exclusief_feedback;
		$this->data['item_stemtest_feedback'] 				  	  		= $item_stemtest_feedback;
		$this->data['item_stemtest_code_feedback'] 			  	  		= $item_stemtest_code_feedback;
		$this->data['item_stemtest_prijs_feedback'] 		  	  		= $item_stemtest_prijs_feedback;
		$this->data['item_stemtest_tekst_feedback'] 		  	  		= $item_stemtest_tekst_feedback;
		$this->data['item_stemtest_dagen_korting_na_afloop_feedback'] 	= $item_stemtest_dagen_korting_na_afloop_feedback;
		$this->data['item_aanmelden_tekst_feedback'] 					= $item_aanmelden_tekst_feedback;
		$this->data['item_meta_title_feedback'] 						= $item_meta_title_feedback;
		$this->data['item_meta_description_feedback'] 					= $item_meta_description_feedback;
		$this->data['item_in3_feedback'] 		        				= $item_in3_feedback;
		$this->data['item_cursistenmodule_feedback'] 					= $item_cursistenmodule_feedback;
		$this->data['item_zichtbaar_publiek_feedback'] 					= $item_zichtbaar_publiek_feedback;
		$this->data['item_zichtbaar_cursist_feedback'] 					= $item_zichtbaar_cursist_feedback;
		$this->data['item_producten_tekst_feedback']					= $item_producten_tekst_feedback;

        if(!empty($_POST['item_kopie'])) $this->data['item_kopie'] = $_POST['item_kopie'];

		$pagina['data'] = $this->data;
        $pagina['workshops'] = $this->workshops_model->getWorkshops();
		$pagina['pagina'] = 'cms/workshops_wijzigen';
		$this->load->view('cms/template', $pagina);
	}

	public function welkomstmail($item_ID = null) {
        $item_email = '';
        $media = '';
        $fouten = 0;
        $actie = 'workshops';
        $item_welkomstmail_versturen = '';

        $item_welkomstmail_versturen_feedback = '';

        $this->load->model('workshops_model');
        $this->load->model('media_model');
        $workshop = $this->workshops_model->getWorkshopByID($item_ID);
        $welkomstmail_media = $this->media_model->getMediaByContentID('welkomstmail', $item_ID);

        if (!empty($workshop->workshop_welkomstmail)) {
            $item_email = $workshop->workshop_welkomstmail;
            $item_welkomstmail_versturen = $workshop->welkomstmail_dagen_ervoor_versturen;
        }

        if (!empty($welkomstmail_media)) {
            $media = $welkomstmail_media;
        }

        // form submitted
        if(isset($_POST['item_email'])) {
            if($fouten == 0) {
                $item_email = trim($_POST['item_email']);

                $data = array(
                    'workshop_welkomstmail' => $item_email,
                );

                $q = $this->workshops_model->updateWorkshop($item_ID, $data);

                $this->media_model->verwijderConnecties('welkomstmail', $item_ID);

                $media_IDs = explode(',', $_POST['item_media']);

                for ($i = 0; $i < sizeof($media_IDs); $i++) {
                    if ($media_IDs[$i] > 0) {
                        $connectie = array('media_ID' => $media_IDs[$i], 'media_positie' => $i, 'content_type' => 'welkomstmail', 'content_ID' => $item_ID);
                        $this->media_model->insertMediaConnectie($connectie);
                    }
                }
                redirect('cms/workshops/wijzigen/' . $item_ID);
            }
        }

        $this->data['workshop_titel']                            = $workshop->workshop_titel;
        $this->data['actie']                                     = $actie;
        $this->data['item_ID']                                   = $item_ID;
        $this->data['media']                                     = $media;
        $this->data['item_email']                                = $item_email;

        // PAGINA TONEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/welkomstmail_wijzigen';
        $this->load->view('cms/template', $pagina);
	}

	public function herinneringsmail($item_ID = null) {
        $item_email = '';
        $media = '';
        $fouten = 0;
        $actie = 'workshops';
        $item_herinneringsmail_versturen = '';

        $item_herinneringsmail_versturen_feedback = '';

        $this->load->model('workshops_model');
        $this->load->model('media_model');
        $workshop = $this->workshops_model->getWorkshopByID($item_ID);
        $herinneringsmail_media = $this->media_model->getMediaByContentID('herinneringsmail', $item_ID);

        if (!empty($workshop->workshop_herinneringsmail)) {
            $item_email = $workshop->workshop_herinneringsmail;
            $item_herinneringsmail_versturen = $workshop->herinneringsmail_dagen_ervoor_versturen;
        }

        if (!empty($herinneringsmail_media)) {
            $media = $herinneringsmail_media;
        }

        // form submitted
        if(isset($_POST['item_email'])) {
            if(empty($_POST['item_herinneringsmail_versturen']) || !is_numeric($_POST['item_herinneringsmail_versturen']))
            {
                $fouten++;
                $item_herinneringsmail_versturen_feedback = 'Graag een geldig getal invullen';
            }


            if($fouten == 0) {
                $item_email = trim($_POST['item_email']);
                $item_herinneringsmail_versturen = $_POST['item_herinneringsmail_versturen'];

                $data = array(
                    'workshop_herinneringsmail' => $item_email,
                    'herinneringsmail_dagen_ervoor_versturen' => $item_herinneringsmail_versturen,
                );

                $q = $this->workshops_model->updateWorkshop($item_ID, $data);

                $this->media_model->verwijderConnecties('herinneringsmail', $item_ID);

                $media_IDs = explode(',', $_POST['item_media']);

                for ($i = 0; $i < sizeof($media_IDs); $i++) {
                    if ($media_IDs[$i] > 0) {
                        $connectie = array('media_ID' => $media_IDs[$i], 'media_positie' => $i, 'content_type' => 'herinneringsmail', 'content_ID' => $item_ID);
                        $this->media_model->insertMediaConnectie($connectie);
                    }
                }
                redirect('cms/workshops/wijzigen/' . $item_ID);
            }
		}

        $this->data['workshop_titel']                            = $workshop->workshop_titel;
        $this->data['actie']                                     = $actie;
        $this->data['item_ID']                                   = $item_ID;
        $this->data['media']                                     = $media;
        $this->data['item_email']                                = $item_email;
        $this->data['item_herinneringsmail_versturen']           = $item_herinneringsmail_versturen;
        $this->data['item_herinneringsmail_versturen_feedback']  = $item_herinneringsmail_versturen_feedback;

        // PAGINA TONEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/herinneringsmail_wijzigen';
        $this->load->view('cms/template', $pagina);
    }

    public function feedbackmail($item_ID = null) {
        $item_email                     = '';
        $media                          = '';
        $fouten                         = 0;
        $actie                          = 'workshops';
        $item_feedbackmail_versturen    = '';

        $item_feedbackmail_versturen_feedback   = '';

        $this->load->model('workshops_model');
        $this->load->model('media_model');

        $workshop = $this->workshops_model->getWorkshopByID($item_ID);
        $feedbackmail_media = $this->media_model->getMediaByContentID('feedbackmail', $item_ID);

        if (!empty($workshop->workshop_feedbackmail)) {
            $item_email = $workshop->workshop_feedbackmail;
            $item_feedbackmail_versturen = $workshop->feedbackmail_dagen_erna_versturen;
        }

        if (!empty($feedbackmail_media)) {
            $media = $feedbackmail_media;
        }

        // form submitted
        if(isset($_POST['item_email'])) {
            if(empty($_POST['item_feedbackmail_versturen']) || !is_numeric($_POST['item_feedbackmail_versturen']))
            {
                $fouten++;
                $item_feedbackmail_versturen_feedback = 'Graag een geldig getal invullen';
            }

            if($fouten == 0) {
                $item_email = trim($_POST['item_email']);
                $item_feedbackmail_versturen = $_POST['item_feedbackmail_versturen'];

                $data = array(
                    'workshop_feedbackmail' => $item_email,
                    'feedbackmail_dagen_erna_versturen' => $item_feedbackmail_versturen
                );

                $q = $this->workshops_model->updateWorkshop($item_ID, $data);

                $this->media_model->verwijderConnecties('feedbackmail', $item_ID);

                $media_IDs = explode(',', $_POST['item_media']);

                for ($i = 0; $i < sizeof($media_IDs); $i++) {
                    if ($media_IDs[$i] > 0) {
                        $connectie = array('media_ID' => $media_IDs[$i], 'media_positie' => $i, 'content_type' => 'feedbackmail', 'content_ID' => $item_ID);
                        $this->media_model->insertMediaConnectie($connectie);
                    }
                }
                redirect('cms/workshops/wijzigen/' . $item_ID);
            }
        }

        $this->data['workshop_titel']                            = $workshop->workshop_titel;
        $this->data['actie']                                     = $actie;
        $this->data['item_ID']                                   = $item_ID;
        $this->data['media']                                     = $media;
        $this->data['item_email']                                = $item_email;
        $this->data['item_feedbackmail_versturen']               = $item_feedbackmail_versturen;
        $this->data['item_feedbackmail_versturen_feedback']      = $item_feedbackmail_versturen_feedback;

        // PAGINA TONEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/feedbackmail_wijzigen';
        $this->load->view('cms/template', $pagina);
    }



	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */

	public function verwijderen($item_ID = null, $bevestiging = null)
	{
		if($item_ID == null) redirect('cms/workshops');

		$this->load->model('workshops_model');
		$item = $this->workshops_model->getWorkshopByID($item_ID);
		if($item == null) redirect('cms/workshops');
		$this->data['item'] = $item;

		$this->load->model('aanmeldingen_model');
		$aanmeldingen = $this->aanmeldingen_model->getAanmeldingenByWorkshopID($item_ID);
		$this->data['aanmeldingen'] = $aanmeldingen;


		// ITEM VERWIJDEREN

		if($bevestiging == 'ja')
		{
			$q = $this->workshops_model->deleteWorkshopByID($item_ID);
			if($q) redirect('cms/workshops');
			else echo 'De workshop kon niet worden verwijderd. Probeer het nog eens.';
		}


		// PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/workshops_verwijderen';
		$this->load->view('cms/template', $pagina);
	}

    public function archiveren($item_ID = null)
    {
        if($item_ID == null) redirect('cms/workshops');
        $this->load->model('workshops_model');
        $this->load->model('groepen_model');
        $this->load->model('gebruikers_model');

        $data = array(
            'workshop_archiveren' => 1
        );

        $this->workshops_model->updateWorkshop($item_ID, $data);

        $data = array(
            'groep_archiveren' => 1
        );

        $groepen = $this->workshops_model->getWorkshopGroepenByID($item_ID);

        if(sizeof($groepen) > 0) {
            foreach($groepen as $groep) {
                $this->groepen_model->GroepArchiverenByID($groep->groep_ID, $data);

                $cursisten = $this->groepen_model->getGroepDeelnemers($groep->groep_ID);

                if(sizeof($cursisten) > 0) {
                    foreach($cursisten as $cursist)  {
                         $cursist_groepen = $this->gebruikers_model->getDeelnemersGroepnonArchief($cursist->gebruiker_ID);
                         $indivuduele_workshops = $this->gebruikers_model->GetWorkshopsDeelnemerIndividueel($cursist->gebruiker_ID);

                        if(empty($cursist_groepen) && empty($indivuduele_workshops)) {
                            $this->gebruikers_model->updateGebruiker($cursist->gebruiker_ID, array('gebruiker_status' => 'inactief'));
                        }
                    }
                }
            }
        } else {
            $cursisten = $this->workshops_model->getWorkshopDeelnemersByID($item_ID);

            if(sizeof($cursisten) > 0) {
                foreach($cursisten as $cursist)  {
                    $cursist_groepen = $this->gebruikers_model->getDeelnemersGroepnonArchief($cursist->gebruiker_ID);
                    $indivuduele_workshops = $this->gebruikers_model->GetWorkshopsDeelnemerIndividueel($cursist->gebruiker_ID);

                    if(empty($cursist_groepen) && empty($indivuduele_workshops)) {
                        $this->gebruikers_model->updateGebruiker($cursist->gebruiker_ID, array('gebruiker_status' => 'inactief'));
                    }
                }
            }
        }

        $archief = false;

        $this->index($archief);
    }

    public function dearchiveren($item_ID = null)
    {
        if($item_ID == null) redirect('cms/workshops');
        $this->load->model('workshops_model');
        $this->load->model('groepen_model');
        $this->load->model('gebruikers_model');

        $data = array(
            'workshop_archiveren' => 0
        );

        $this->workshops_model->updateWorkshop($item_ID, $data);

        $data = array(
            'groep_archiveren' => 0
        );

        $groepen = $this->workshops_model->getWorkshopGroepenByIDArchief($item_ID);

        if(sizeof($groepen) > 0) {
            foreach($groepen as $groep) {
                $this->groepen_model->updateGroep($groep->groep_ID, $data);

                $cursisten = $this->groepen_model->getGroepDeelnemers($groep->groep_ID);

                if(sizeof($cursisten) > 0) {
                    foreach($cursisten as $cursist)  {
                        $this->gebruikers_model->updateGebruiker($cursist->gebruiker_ID, array('gebruiker_status' => 'actief'));
                    }
                }
            }
        } else {
            $cursisten = $this->workshops_model->getWorkshopDeelnemersByID($item_ID);

            if(sizeof($cursisten) > 0) {
                foreach($cursisten as $cursist)  {
                    $this->gebruikers_model->updateGebruiker($cursist->gebruiker_ID, array('gebruiker_status' => 'actief'));
                }
            }
        }

        $archief = true;

        $this->index($archief);
    }

    public function deelnemer_toevoegen($item_ID = null) {
        if($item_ID == null) redirect('cms/workshops');

        $query = '';

        $this->load->model('aanmeldingen_model');
        $this->load->model('gebruikers_model');
        $this->load->model('workshops_model');

        if(!empty($_POST['item_deelnemer'])) {
            $deelnemer_ID = trim($_POST['item_deelnemer']);

            $cursist = $this->gebruikers_model->getGebruikerByID($deelnemer_ID);

            $data = array(
                'aanmelding_type' => 'workshop',
                'aanmelding_datum' => date("Y-m-d H:i:s"),
                'aanmelding_betaald_datum' => date("Y-m-d H:i:s"),
                'aanmelding_betaald_bedrag' => 0,
                'gebruiker_ID' => $cursist->gebruiker_ID,
                'workshop_ID' => $item_ID,
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

        if(!$aanmelding_ID) {
            echo 'er ging iets mis.';
            redirect('cms/workshops/'.$item_ID);
        } else {
            redirect('cms/workshops/'.$item_ID);
        }
    }

    function ReplaceTags($tekst) {
        $tekst = str_replace('[TAB-TITEL-BEGIN]', '<ul id="tab"><li><label>', $tekst);
        $tekst = str_replace('[TAB-TITEL-EIND]', '<span class="changeArrow arrow"></span></label>', $tekst);
        $tekst = str_replace('[TAB-BEGIN]', '<div class="content">', $tekst);
        $tekst = str_replace('[TAB-EIND]', '</div></li></ul>', $tekst);

        $tekst = str_replace('[BORDER-BEGIN]', '<div class="border">', $tekst);
        $tekst = str_replace('[BORDER-EIND]', '</div>', $tekst);

        $tekst = str_replace('[BLAUWE-ACHTERGROND-BEGIN]', '<div class="blauwe_achtergrond">', $tekst);
        $tekst = str_replace('[BLAUWE-ACHTERGROND-EIND]', '</div>', $tekst);
        $tekst = str_replace('[VINKJE]', '<img src="'. base_url('assets/images/vinkje.png') .'">', $tekst);

        $tekst = str_replace('[LINK-BEGIN]', '<a class="button button--orange" href="', $tekst);
        $tekst = str_replace('[LINK-EIND]', '">', $tekst);
        $tekst = str_replace('[LINK-TEKST-BEGIN]', '', $tekst);
        $tekst = str_replace('[LINK-TEKST-EIND]', '</a>', $tekst);

        $tekst = str_replace('[BUTTON-BEGIN]', '<a class="button button--orange" href="', $tekst);
        $tekst = str_replace('[BUTTON-EIND]', '">', $tekst);
        $tekst = str_replace('[BUTTON-TEKST-BEGIN]', '', $tekst);
        $tekst = str_replace('[BUTTON-TEKST-EIND]', '</a>', $tekst);

        return  $tekst;
    }
}