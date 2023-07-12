<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Producten extends CursistenController
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

		$this->load->model('paginas_model');
		$this->load->model('workshops_model');
		$this->load->model('lessen_model');
		$this->load->model('kandidaat_model');
		$this->load->model('producten_model');

		// Producten van gevolgde workshops ophalen

        if($this->session->userdata('gebruiker_rechten') != "kandidaat") {
            if($this->session->userdata('gebruiker_rechten') != 'test') {
                $producten_temp = $this->workshops_model->getWorkshopsGevolgdProductenByGebruikerID($this->session->userdata('gebruiker_ID'));
                $temp = array();
                $producten = array();
                $na_workshop_producten = array();

                if (sizeof($producten_temp) > 0) {
                    foreach ($producten_temp as $product) {
                        if ($product->wanneer_beschikbaar == 'altijd') {
                            $temp[] = $product;
                        } else {
                            $na_workshop_producten[] = $product;
                        }
                    }
                }

                if (sizeof($temp) > 0) {
                    foreach ($temp as $item) {
                        $bool = false;

                        foreach ($producten as $product) {
                            if ($item->product_ID == $product->product_ID) {
                                $bool = true;
                            }
                        }

                        if ($bool == false) {
                            $producten[] = $item;
                        }
                    }
                }

                if (sizeof($na_workshop_producten) > 0) {
                    foreach ($na_workshop_producten as $item) {
                        $bool = false;

                        foreach ($producten as $product) {
                            if ($item->product_ID == $product->product_ID) {
                                $bool = true;
                            }
                        }

                        if ($bool == false) {
                            $groep_lessen = $this->lessen_model->getGroepLessenByGebruikerIDenWorkshopID($this->session->userdata('gebruiker_ID'), $item->workshop_ID);
                            $indi_lessen = $this->lessen_model->getIndividueelLessenByGebruikerIDenWorkshopID($this->session->userdata('gebruiker_ID'), $item->workshop_ID);

                            $lessen = array_merge($groep_lessen, $indi_lessen);
                            $laatste_les = '';

                            foreach ($lessen as $les) {
                                if (date('d-m-Y H:i:s', strtotime($les->les_datum)) > date('d-m-Y H:i:s', strtotime($laatste_les))) {
                                    $item->laatste_les = $les->les_datum;
                                }
                            }

                            $producten[] = $item;
                        }
                    }
                }
            } else {
                $producten = $this->producten_model->getProducten();
            }
        } else {
            $producten = $this->workshops_model->getKandidaatProducten();
        }

		// Redirect als er geen producten gekocht kunnen worden

		if(sizeof($producten) == 0) redirect('cursistenmodule');
		
		// Pagina tekst ophalen

		$content = $this->paginas_model->getPaginaByID(8);

		// Pagina tonen

		$this->data['content'] = $content;
		$this->data['producten'] = $producten;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cursistenmodule/producten';

		$this->load->view('cursistenmodule/template', $pagina);
	}
}
