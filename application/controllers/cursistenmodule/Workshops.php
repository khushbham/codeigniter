<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workshops extends CursistenController
{
	public function __construct()
	{
		parent::__construct();
	}

	/* ============= */
	/* = OVERZICHT = */
	/* ============= */

	public function index()
	{
        if($this->session->userdata('kennismakingsworkshop_acc') == true):
            redirect('cursistenmodule');
        endif;

		// Workshops ophalen

        $this->load->model('workshops_model');

        if($this->session->userdata('gebruiker_rechten') == "dummy") {
            $workshops = $this->workshops_model->getWorkshopsDummy();
        } else {
            $workshops = $this->workshops_model->getWorkshopsByGebruikerID($this->session->userdata('gebruiker_ID'));
        }
		$this->data['workshops'] = $workshops;

        $workshop_niveau = 0;

        if(!empty($workshops)) {
            foreach ($workshops as $workshop) {
                if ($workshop->workshop_niveau > $workshop_niveau) {
                    $workshop_niveau = $workshop->workshop_niveau;
                }
            }
        }

		if($this->session->userdata('gebruiker_rechten') != "kandidaat") {
            $aanbevolen = $this->workshops_model->getWorkshopsNietGevolgd($this->session->userdata('gebruiker_ID'));
        } else {
            $aanbevolen = $this->workshops_model->getKandidaatWorkshopsNietGevolgd($this->session->userdata('gebruiker_ID'));
        }

        $basisworkshop = false;
        $vervolgworkshop = false;
        $bootcamp = false;
        $specialty = false;
        $intro = false;

        if (sizeof($workshops) > 0) {
            foreach ($workshops as $workshop) {
                if ($workshop->workshop_ID == 9 || $workshop->workshop_niveau == 5) {
                    $vervolgworkshop = true;
                }
                if($workshop->workshop_ID == 47) {
                    $intro = true;
                }
                if ($workshop->workshop_ID == 41 || $workshop->workshop_ID == 37 || $workshop->workshop_ID == 33 || $workshop->workshop_ID == 53 || $workshop->workshop_ID == 57 || $workshop->workshop_ID == 59 || $workshop->workshop_ID == 61) {
                    $basisworkshop = true;
                }
                if ($workshop->workshop_ID == 35 || $workshop->workshop_ID == 39 || $workshop->workshop_ID == 55 || $workshop->workshop_ID == 63 || $workshop->workshop_ID == 67 || $workshop->workshop_ID == 69) {
                    $bootcamp = true;
                }
                if($workshop->workshop_specialty == "ja") {
                    $specialty = true;
                }
            }
        }

        if (!empty($aanbevolen)) {
            foreach ($aanbevolen as $workshop) {
                $this->load->model('groepen_model');
                $groepen = $this->groepen_model->getGroepenAanmeldenByWorkshopID($workshop->workshop_ID);

                if (!empty($groepen)) {
                    $plekken_over = sizeof($this->groepen_model->getGroepDeelnemers($groepen[0]->groep_ID));
                    $workshop->plekken_over = $workshop->workshop_capaciteit - $plekken_over;
                } else {
                    $workshop->plekken_over = 0;
                }
            }
        }

        if(!empty($aanbevolen)) {
            if ($vervolgworkshop == true) {
                foreach($aanbevolen as $key => $item) {
                    if($item->workshop_ID == 41 || $item->workshop_ID == 37 || $item->workshop_ID == 33 || $item->workshop_ID == 39 || $item->workshop_ID == 35 || $item->workshop_ID == 53 || $item->workshop_ID == 55 || $item->workshop_ID == 57 || $item->workshop_ID == 59 || $item->workshop_ID == 61 || $item->workshop_ID == 63 || $item->workshop_ID == 67 || $item->workshop_ID == 69) {
                        unset($aanbevolen[$key]);
                    }
                }
            }

            if ($basisworkshop == true) {
                foreach($aanbevolen as $key => $item) {
                    if($item->workshop_ID == 41 || $item->workshop_ID == 37 || $item->workshop_ID == 33 || $item->workshop_ID == 39 || $item->workshop_ID == 35 || $item->workshop_ID == 53 || $item->workshop_ID == 55 || $item->workshop_ID == 57 || $item->workshop_ID == 59 || $item->workshop_ID == 61 || $item->workshop_ID == 63 || $item->workshop_ID == 67 || $item->workshop_ID == 69) {
                        unset($aanbevolen[$key]);
                    }
                }
            }

            if ($bootcamp == true) {
                foreach($aanbevolen as $key => $item) {
                    if($item->workshop_ID == 41 || $item->workshop_ID == 37 || $item->workshop_ID == 33 || $item->workshop_ID == 39 || $item->workshop_ID == 35 || $item->workshop_ID == 53 || $item->workshop_ID == 55 || $item->workshop_ID == 57 || $item->workshop_ID == 59 || $item->workshop_ID == 61 || $item->workshop_ID == 63 || $item->workshop_ID == 67 || $item->workshop_ID == 69) {
                        unset($aanbevolen[$key]);
                    }
                }
            }

            foreach($aanbevolen as $key => $item) {
                if(($bootcamp == false && $basisworkshop == false) && ($item->workshop_ID == 9 || $item->workshop_ID == 71)) {
                    unset($aanbevolen[$key]);
                }
            }
        }

        $this->data['workshop_niveau']       = $workshop_niveau;
        $this->data['bootcamp']              = $bootcamp;
        $this->data['basisworkshop']         = $basisworkshop;
        $this->data['vervolgworkshop']       = $vervolgworkshop;
        $this->data['aanbevolen']            = $aanbevolen;

		// PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cursistenmodule/workshops';
		$this->load->view('cursistenmodule/template', $pagina);
	}
}