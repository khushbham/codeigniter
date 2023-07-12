<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resultaten extends CursistenController
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
		// Models laden

        $this->load->model('huiswerk_model');
        $this->load->model('lessen_model');

        $resultaten = '';
        $huiswerk = '';
        $opnieuw = '';

        $resultaten = $this->huiswerk_model->getResultatenByGebruikerIDandWorkshop_ID($this->session->userdata('gebruiker_ID'), $this->session->userdata('workshop_ID'));

        $beoordelingscriteria = $this->lessen_model->getBeoordelingscriteria();
        $this->data['beoordelingscriteria'] = $beoordelingscriteria;

        if(!empty($resultaten)) {
            foreach($resultaten as $resultaat) {
                $huiswerk = $this->huiswerk_model->getHuiswerk($resultaat->gebruiker_ID, $resultaat->les_ID);
                $opnieuw = $this->huiswerk_model->getHuiswerkOpnieuw($resultaat->gebruiker_ID, $resultaat->les_ID);

                if(!empty($huiswerk)) {
                    foreach($huiswerk as $item) {
                        $beoordelingscriteria_resultaat = $this->lessen_model->getBeoordelingscriteriaAndHuiswerk($item->huiswerk_ID);
                        $item->resultaten = $beoordelingscriteria_resultaat;
                    }
                    $resultaat->huiswerk = $huiswerk;
                }

                if(!empty($opnieuw)) {
                    foreach($opnieuw as $item) {
                        $beoordelingscriteria_resultaat = $this->lessen_model->getBeoordelingscriteriaAndHuiswerk($item->huiswerk_ID);
                        $item->resultaten = $beoordelingscriteria_resultaat;
                    }
                    $resultaat->huiswerk_opnieuw = $opnieuw;
                }
            } 
        }

        $this->data['resultaten']  = $resultaten;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cursistenmodule/resultaten';

		$this->load->view('cursistenmodule/template', $pagina);
	}
}
