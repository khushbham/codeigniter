<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aanwezigheid extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('algemeen');
        $this->algemeen->cms();
        if($this->session->userdata('beheerder_rechten') == 'contentmanager') redirect('cms/rechten');
    }

    public function detail($item_ID) {
        if($item_ID == null) redirect('cms/');

        $this->load->model('aanwezigheid_model');
        $this->load->model('groepen_model');
        $this->load->model('gebruikers_model');
        $this->load->model('lessen_model');
        $this->load->model('media_model');

        if(!empty($_POST['aanwezigheid_opslaan'])) {
            foreach($_POST['aanwezigheid'] as $gebruiker_ID => $aanwezigheid) {
                $opgeslagen_aanwezigheid = $this->aanwezigheid_model->getAanwezigheidByGroeplesIDGebruikerID($item_ID, $gebruiker_ID);

                if (!empty($opgeslagen_aanwezigheid)) {
                    $this->aanwezigheid_model->deleteAanwezigheid($item_ID, $gebruiker_ID);
                }

                if($aanwezigheid != 'ja') {
                    $data = array(
                        'gebruiker_ID' => $gebruiker_ID,
                        'les_ID' => $item_ID,
                        'aanwezigheid_aanwezig' => $aanwezigheid
                    );

                    // update les
                    $q = $this->aanwezigheid_model->insertAanwezigheid($data);
                }
            }

            if(!empty($_POST['uitgenodigd'])) {
                foreach($_POST['uitgenodigd'] as $gebruiker_ID => $uitgenodigd) {
                        $data = array(
                            'gebruiker_uitgenodigd_vervolg' => $uitgenodigd
                        );

                        // update gebruiker
                        $q = $this->gebruikers_model->updateDeelnemer($gebruiker_ID, $data);
                }
            }
        }

        $groep_les = $this->lessen_model->getGroepLesByID($item_ID);

        $niet_aanwezig = $this->aanwezigheid_model->getAanwezigheidByGroepLesID($groep_les->groep_les_ID);

        $cursisten = $this->groepen_model->getGroepDeelnemers($groep_les->groep_ID);
        $deelnemers = $cursisten;

		$groep = $this->groepen_model->getGroepByID($groep_les->groep_ID);

		$les = $this->lessen_model->getLesByID($groep_les->les_ID);

        if(sizeof($cursisten) > 0) {
            foreach($cursisten as $cursist) {
                $deelnemer_groepen = $this->gebruikers_model->getDeelnemersGroepnonArchief($cursist->gebruiker_ID);
                
                $cursist->groepen = "";

                if(!empty($deelnemer_groepen)) {
                    foreach($deelnemer_groepen as $deelnemer_groep) {
                        $cursist->groepen .=  " - " . $deelnemer_groep->groep_naam;
                    }
                }

                if(sizeof($niet_aanwezig) > 0) {
                    foreach ($niet_aanwezig as $uitzondering) {
                        if ($cursist->gebruiker_ID == $uitzondering->gebruiker_ID) {
                            $cursist->aanwezig = 'nee';
                            break;
                        } else {
                            $cursist->aanwezig = '';
                        }
                    }
                } else {
                    foreach ($cursisten as $cursist) {
                        $cursist->aanwezig = '';
                    }
                }
            }
        }

        if(!empty($cursisten)) {
            foreach($cursisten as $cursist) {
                $beoordeling = $this->lessen_model->getGebruikerBeoordeling($cursist->gebruiker_ID, $groep_les->les_ID);

                if($beoordeling) {
                    $cursist->gebruiker_beoordeling = $beoordeling[0]->gebruiker_beoordeling;
                } else {
                    $cursist->gebruiker_beoordeling = 0;
                }
            }
        }
		
		if(sizeof($deelnemers) > 0) {
			foreach($deelnemers as $cursist) {
				$cursist->profiel_foto = $this->media_model->getMediaProfielByGebruikerID($cursist->gebruiker_ID);

				if(!empty($cursist->profiel_foto)) {
					$cursist->profiel_foto = base_url('media/uploads/') . $cursist->profiel_foto->media_src;
				} else {
					$cursist->profiel_foto = "";
				}

				if(sizeof($niet_aanwezig) > 0) {
					foreach ($niet_aanwezig as $uitzondering) {
						if ($cursist->gebruiker_ID == $uitzondering->gebruiker_ID) {
							$cursist->aanwezig = 'nee';
							break;
						} else {
							$cursist->aanwezig = 'ja';
						}
					}
				} else {
					foreach ($cursisten as $cursist) {
							$cursist->aanwezig = 'ja';
					}
				}
			}
        }

		$this->data['deelnemers'] = $deelnemers;
		$this->data['cursisten'] = $cursisten;
        $this->data['groep']	= $groep;
        $this->data['groep_les'] = $groep_les;
        $this->data['les']     = $les;

        // PAGINA TONEN

        $this->data['cursisten'] = $cursisten;
        $this->data['groep_les'] = $groep_les;
        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/aanwezigheid_detail';
        $this->load->view('cms/template', $pagina);
    }

    public function exporteren($item_ID)
    {
        if($item_ID == null) redirect('cms/');

        $this->load->model('aanwezigheid_model');
        $this->load->model('groepen_model');
        $this->load->model('lessen_model');

        $groep_les = $this->lessen_model->getGroepLesByID($item_ID);

        $niet_aanwezig = $this->aanwezigheid_model->getAanwezigheidByGroepLesID($groep_les->groep_les_ID);

        $cursisten = $this->groepen_model->getGroepDeelnemers($groep_les->groep_ID);

        if(sizeof($cursisten) > 0) {
            foreach($cursisten as $cursist) {
                if(sizeof($niet_aanwezig) > 0) {
                    foreach ($niet_aanwezig as $uitzondering) {
                        if ($cursist->gebruiker_ID == $uitzondering->gebruiker_ID) {
                            $cursist->aanwezig = 'nee';
                        } else {
                            $cursist->aanwezig = 'ja';
                        }
                    }
                } else {
                    $cursist->aanwezig = 'ja';
                }
            }
        }

        require(dirname(dirname(__FILE__)) . '/cms/Csv.php');

        foreach ($cursisten as $cursist) {
            $body_items[] = array($cursist->gebruiker_voornaam, $cursist->gebruiker_tussenvoegsel, $cursist->gebruiker_achternaam,
                $cursist->gebruiker_emailadres, $cursist->gebruiker_telefoonnummer, $cursist->gebruiker_mobiel, $cursist->aanwezig);
        }

        $csv = new CSV();
        $csv->set_header_items(array(('Voornaam'), ('Tussenvoegsel'), ('Achternaam'), ('E-mail'), ('Telefoonnummer'), ('Mobiel') ,('Aanwezig')));
        $csv->set_body_items($body_items);
        $csv->output_as_download('Export-Aanwezigheid.csv'); // prompts the user to download the file as 'export.csv' by default

        // PAGINA TONEN

        $pagina['data'] = $this->data;
        redirect('cms/groepen/');
    }
}