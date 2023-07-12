<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kortingscodes extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Rechten controleren en aantal nieuwe items ophalen

        $this->load->library('algemeen');
        $this->algemeen->cms();
        if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'support') { redirect('cms/rechten'); }
    }



    /* ============= */
    /* = OVERZICHT = */
    /* ============= */

    public function index()
    {
        $this->load->model('kortingscodes_model');
        $this->load->model('kandidaat_model');

        $kandidaat_workshops = $this->kandidaat_model->getKandidaatWorkshops();
        $kandidaat_producten = $this->kandidaat_model->getKandidaatProducten();
        $kortingscodes = $this->kortingscodes_model->getKortingscodesCMS();
        $upselling = $this->kortingscodes_model->getUpselling();

        if (!empty($kortingscodes)) {
            foreach ($kortingscodes as $kortingscode) {
                $kortingscode->item_aantal = count($this->kortingscodes_model->getKortingConnectiesByID($kortingscode->kortingscode_ID));
            }
        }


        // PAGINA TONEN

        $this->data['kortingscodes'] 	= $kortingscodes;
        $this->data['upselling'] 	= $upselling;
        $this->data['kandidaat_workshops'] 	= $kandidaat_workshops;
        $this->data['kandidaat_producten'] 	= $kandidaat_producten;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/kortingscodes';
        $this->load->view('cms/template', $pagina);
    }

    /* ============ */
    /* = BEKIJKEN = */
    /* ============ */

    public function detail($item_ID = null)
    {
        $this->load->model('kortingscodes_model');
        $this->load->model('workshops_model');
        $this->load->model('producten_model');
        $this->load->model('kennismakingsworkshop_model');

        if($item_ID == null) redirect('cms/kortingscodes');

        // Gegevens kortingscode ophalen

        $kortingscode = $this->kortingscodes_model->getKortingscodeByID($item_ID);
        if($kortingscode == null) redirect('cms/kortingscodes');

        // kortingscode connecties ophalen

        $items = $this->kortingscodes_model->getKortingConnectiesByID($item_ID);
        $workshops = array();
        $producten = array();
        $kennismakingsworkshops = array();
        $volledige_workshops = array();
        $volledige_producten = array();
        $volledige_kennismakingsworkshops = array();

        foreach($items as $item) {
            if ($item->workshop_ID) {
                array_push($workshops, $item->workshop_ID);
            }

            if ($item->product_ID) {
                array_push($producten, $item->product_ID);
            }

            if ($item->kennismakingsworkshop_ID) {
                array_push($kennismakingsworkshops, $item->kennismakingsworkshop_ID);
            }
        }

        if ($workshops > 0) {
            foreach ($workshops as $workshop_ID) {
                $workshop = $this->workshops_model->getWorkshopByID($workshop_ID);

                array_push($volledige_workshops, $workshop);
            }
            $this->data['workshops'] = $volledige_workshops;
        }

        if ($producten > 0) {
            foreach ($producten as $producten_ID) {
                $product = $this->producten_model->getProductByID($producten_ID);

                array_push($volledige_producten, $product);
            }
            $this->data['producten'] = $volledige_producten;
        }

        if ($kennismakingsworkshops > 0) {
            foreach ($kennismakingsworkshops as $kennismakingsworkshop_ID) {
                $kennismakingsworkshop = $this->kennismakingsworkshop_model->getKennismakingsworkshopByID($kennismakingsworkshop_ID);

                array_push($volledige_kennismakingsworkshops, $kennismakingsworkshop);
            }
            $this->data['kennismakingsworkshops'] = $volledige_kennismakingsworkshops;
        }

        // PAGINA TONEN
        $this->data['kortingscode'] = $kortingscode;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/kortingscodes_kortingscode';
        $this->load->view('cms/template', $pagina);
    }

    public function upselling_detail($item_ID = null)
    {
        $this->load->model('kortingscodes_model');
        $this->load->model('workshops_model');
        $this->load->model('producten_model');

        if($item_ID == null) redirect('cms/kortingscodes');

        // Gegevens ophalen

        $upselling = $this->kortingscodes_model->getUpsellingByID($item_ID);
        if($upselling == null) redirect('cms/kortingscodes');

        // connecties ophalen

        $items = $this->kortingscodes_model->getUpsellingConnectiesByIDs($item_ID);
        $producten = array();
        $volledige_producten = array();

        foreach($items as $item) {
            if ($item->product_ID) {
                array_push($producten, $item->product_ID);
            }
        }

        if ($producten > 0) {
            foreach ($producten as $producten_ID) {
                $product = $this->producten_model->getProductByID($producten_ID);

                $connectie = $this->kortingscodes_model->getUpsellingConnectieByIDs($upselling->upselling_ID, $producten_ID);

                if (!empty($connectie)) {
                    $product->upselling_prijs = $connectie[0]->upselling_prijs;
                }

                array_push($volledige_producten, $product);
            }
            $this->data['producten'] = $volledige_producten;
        }

        // PAGINA TONEN
        $this->data['upselling'] = $upselling;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/upselling_detail';
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
        if($item_ID == null) redirect('cms/kortingscodes');
        $this->_toevoegen_wijzigen('wijzigen', $item_ID);
    }

    private function _toevoegen_wijzigen($actie, $item_ID = null)
    {
        $this->load->model('kortingscodes_model');
        $this->load->model('kennismakingsworkshop_model');
        $this->load->model('workshops_model');
        $this->load->model('producten_model');

        $item_kortingscode      = '';
        $item_percentage        = '';
        $item_vast_bedrag       = '';
        $item_limiet            = '';
        $item_datum_dag			= '';
        $item_datum_maand		= '';
        $item_datum_jaar		= '';
        $item_einddatum_dag		= '';
        $item_einddatum_maand	= '';
        $item_einddatum_jaar	= '';
        $item_in3         = '';

        $item_kortingscode_feedback = '';
        $item_percentage_feedback   = '';
        $item_tijd_feedback         = '';
        $item_vast_bedrag_feedback  = '';
        $item_datum_feedback        = '';
        $item_einddatum_feedback    = '';

        $kennismakingsworkshops = $this->kennismakingsworkshop_model->getKennismakingsworkshops();
        $workshops = $this->kortingscodes_model->getWorkshops();
        $producten = $this->producten_model->getProducten();

        // FORMULIER VERZONDEN

        if(isset($_POST['item_kortingscode']))
        {
            $fouten 				= 0;
            $item_kortingscode      = trim($_POST['item_kortingscode']);
            $item_percentage        = trim($_POST['item_percentage']);
            $item_vast_bedrag       = trim($_POST['item_vast_bedrag']);
            $item_limiet            = trim($_POST['item_limiet']);
            $item_datum_dag			= trim($_POST['item_datum_dag']);
            $item_datum_maand		= trim($_POST['item_datum_maand']);
            $item_datum_jaar		= trim($_POST['item_datum_jaar']);
            $item_einddatum_dag		= trim($_POST['item_einddatum_dag']);
            $item_einddatum_maand	= trim($_POST['item_einddatum_maand']);
            $item_einddatum_jaar	= trim($_POST['item_einddatum_jaar']);
            $item_in3         = trim($_POST['item_in3']);

            if(empty($item_datum_dag) || empty($item_datum_maand) || empty($item_datum_jaar))
            {
                $fouten++;
                $item_datum_feedback = 'Graag invullen';
            }

            if(empty($item_kortingscode))
            {
                $fouten++;
                $item_kortingscode_feedback = 'Graag invullen';
            }

            if ($actie == 'toevoegen') {
                $kortingscode_bestaat = $this->kortingscodes_model->getKortingscodesByCode($item_kortingscode);

                if(!empty($kortingscode_bestaat))
                {
                    $fouten++;
                    $item_kortingscode_feedback = 'Kortingscode bestaat al';
                }

            }

            if(empty($item_percentage) && empty($item_vast_bedrag))
            {
                $fouten++;
                $item_percentage_feedback = 'Graag een percentage of een vast bedrag invullen.';
                $item_vast_bedrag_feedback = 'Graag een percentage of een vast bedrag invullen.';
            }

            if($fouten == 0)
            {
                // TOEVOEGEN / UPDATEN
                if($item_percentage === '0' || $item_percentage === '') { $item_percentage = null; }
                if($item_limiet === '0' || $item_limiet === '') { $item_limiet = null; }
                //if($item_einddatum_jaar === '00' || $item_datum_maand === '00' || $item_datum_dag === '00' || $item_einddatum_jaar === '' || $item_datum_maand === '' || $item_datum_dag === '' ) { $item_limiet = null; }

                $data = array(
                    'kortingscode' => $item_kortingscode,
                    'kortingscode_percentage' => $item_percentage,
                    'kortingscode_vast_bedrag' => $item_vast_bedrag,
                    'kortingscode_startdatum' => $item_datum_jaar.'-'.$item_datum_maand.'-'.$item_datum_dag.' 00:00:00',
                    'kortingscode_einddatum' =>  $item_einddatum_jaar.'-'.$item_einddatum_maand.'-'.$item_einddatum_dag.' 00:00:00',
                    'kortingscode_limiet' => $item_limiet,
                    'kortingscode_in3' => $item_in3
                );

                if($actie == 'toevoegen') $q = $this->kortingscodes_model->insertKortingscode($data);
                else {
                    $q = $this->kortingscodes_model->deleteKortingConnecties($item_ID, $data);
                    $r = $this->kortingscodes_model->deleteKortingscode($item_ID, $data);
                    $q = $this->kortingscodes_model->insertKortingscode($data);

                    $geselecteerde_kennismakingsworkshops = isset($_POST['geselecteerde_kennismakingsworkshops']) ? $_POST['geselecteerde_kennismakingsworkshops'] : null;
                    $geselecteerde_workshops = isset($_POST['geselecteerde_workshops']) ? $_POST['geselecteerde_workshops'] : null;
                    $geselecteerde_producten = isset($_POST['geselecteerde_producten']) ? $_POST['geselecteerde_producten'] : null;
                    $kortingscode_ID = $q;

                    if(!empty($geselecteerde_kennismakingsworkshops)) {
                        foreach ($geselecteerde_kennismakingsworkshops as $kennismakingsworkshop_ID) {
                            $data = array(
                                'kortingscode_ID' => $kortingscode_ID,
                                'workshop_ID' => '',
                                'product_ID' => '',
                                'kennismakingsworkshop_ID' => $kennismakingsworkshop_ID,
                            );
                            $this->kortingscodes_model->koppelKortingscodes($data);
                        }
                    }

                    if(!empty($geselecteerde_workshops)) {
                        foreach ($geselecteerde_workshops as $geselecteerde_workshop_ID) {
                            $data = array(
                                'kortingscode_ID' => $kortingscode_ID,
                                'workshop_ID' => $geselecteerde_workshop_ID,
                                'product_ID' => '',
                                'kennismakingsworkshop_ID' => '',
                            );
                            $this->kortingscodes_model->koppelKortingscodes($data);
                        }
                    }

                    if(!empty($geselecteerde_producten)) {
                        foreach ($geselecteerde_producten as $geselecteerde_product_ID) {
                            $data = array(
                                'kortingscode_ID' => $kortingscode_ID,
                                'workshop_ID' => '',
                                'product_ID' => $geselecteerde_product_ID,
                                'kennismakingsworkshop_ID' => '',
                            );
                            $this->kortingscodes_model->koppelKortingscodes($data);
                        }
                    }

                    redirect('cms/kortingscodes');
                }

                if($q)
                {
                    if($actie == 'toevoegen') {
                        $geselecteerde_kennismakingsworkshops = isset($_POST['geselecteerde_kennismakingsworkshops']) ? $_POST['geselecteerde_kennismakingsworkshops'] : null;
                        $geselecteerde_workshops = isset($_POST['geselecteerde_workshops']) ? $_POST['geselecteerde_workshops'] : null;
                        $geselecteerde_producten = isset($_POST['geselecteerde_producten']) ? $_POST['geselecteerde_producten'] : null;
                        $kortingscode_ID = $q;

                        if(!empty($geselecteerde_kennismakingsworkshops)) {
                            foreach ($geselecteerde_kennismakingsworkshops as $kennismakingsworkshop_ID) {
                                $data = array(
                                    'kortingscode_ID' => $kortingscode_ID,
                                    'workshop_ID' => '',
                                    'product_ID' => '',
                                    'kennismakingsworkshop_ID' => $kennismakingsworkshop_ID,
                                );
                                $this->kortingscodes_model->koppelKortingscodes($data);
                            }
                        }

                        if(!empty($geselecteerde_workshops)) {
                            foreach ($geselecteerde_workshops as $geselecteerde_workshop_ID) {
                                $data = array(
                                    'kortingscode_ID' => $kortingscode_ID,
                                    'workshop_ID' => $geselecteerde_workshop_ID,
                                    'product_ID' => '',
                                    'kennismakingsworkshop_ID' => '',
                                );
                                $this->kortingscodes_model->koppelKortingscodes($data);
                            }
                        }

                        if(!empty($geselecteerde_producten)) {
                            foreach ($geselecteerde_producten as $geselecteerde_product_ID) {
                                $data = array(
                                    'kortingscode_ID' => $kortingscode_ID,
                                    'workshop_ID' => '',
                                    'product_ID' => $geselecteerde_product_ID,
                                    'kennismakingsworkshop_ID' => '',
                                );
                                $this->kortingscodes_model->koppelKortingscodes($data);
                            }
                        }

                        redirect('cms/kortingscodes');
                    } else {
                        redirect('cms/kortingscodes/');
                    }
                }
                else
                {
                    echo 'kortingscode '.$actie.' mislukt. Probeer het nog eens.';
                }
            }
        }

        if($actie == 'wijzigen')
        {
            $item = $this->kortingscodes_model->getKortingscodeByID($item_ID);
            if($item == null) redirect('cms/kortingscodes');

            $item_kortingscode = $item->kortingscode;
            $item_percentage   = $item->kortingscode_percentage;
            $item_vast_bedrag  = $item->kortingscode_vast_bedrag;
            $item_limiet       = $item->kortingscode_limiet;
            $item_datum 	   = $item->kortingscode_startdatum;
            $item_einddatum    = $item->kortingscode_einddatum;
            $item_in3    = $item->kortingscode_in3;

            $datum_tijd 			= explode(' ', $item_datum);
            $einddatum_tijd 		= explode(' ', $item_einddatum);
            $datum 					= explode('-', $datum_tijd[0]);
            $einddatum 				= explode('-', $einddatum_tijd[0]);

            $item_einddatum_dag     = $einddatum[2];
            $item_einddatum_maand   = $einddatum[1];
            $item_einddatum_jaar    = $einddatum[0];
            $item_datum_dag 		= $datum[2];
            $item_datum_maand 		= $datum[1];
            $item_datum_jaar 		= $datum[0];

            $connecties = $this->kortingscodes_model->getKortingConnectiesByID($item_ID);

            if (!empty($connecties)) {
                $this->data['connecties'] = $connecties;
            }

        } else {
            $item_datum_dag 		= date('d');
            $item_datum_maand 		= date('m');
            $item_datum_jaar 		= date('Y');
        }

        // PAGINA TONEN

        $this->data['actie'] = $actie;
        $this->data['item_ID'] = $item_ID;

        $this->data['item_kortingscode'] 		= $item_kortingscode;
        $this->data['item_percentage'] 			= $item_percentage;
        $this->data['item_vast_bedrag'] 		= $item_vast_bedrag;
        $this->data['item_limiet'] 			    = $item_limiet;
        $this->data['item_datum_dag'] 			= $item_datum_dag;
        $this->data['item_datum_maand'] 		= $item_datum_maand;
        $this->data['item_datum_jaar'] 			= $item_datum_jaar;
        $this->data['item_einddatum_dag'] 		= $item_einddatum_dag;
        $this->data['item_einddatum_maand'] 	= $item_einddatum_maand;
        $this->data['item_einddatum_jaar'] 		= $item_einddatum_jaar;
        $this->data['item_in3'] 		    = $item_in3;

        $this->data['item_kortingscode_feedback'] 	= $item_kortingscode_feedback;
        $this->data['item_percentage_feedback']	    = $item_percentage_feedback;
        $this->data['item_tijd_feedback']	 	    = $item_tijd_feedback;
        $this->data['item_vast_bedrag_feedback']	= $item_vast_bedrag_feedback;
        $this->data['item_datum_feedback']	 	    = $item_datum_feedback;
        $this->data['item_einddatum_feedback']	 	= $item_einddatum_feedback;
        $this->data['kennismakingsworkshops']       = $kennismakingsworkshops;
        $this->data['workshops']                    = $workshops;
        $this->data['producten']                    = $producten;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/kortingscodes_wijzigen';
        $this->load->view('cms/template', $pagina);
    }

    public function upselling_toevoegen()
    {
        $this->_upselling_toevoegen_wijzigen('toevoegen');
    }

    public function upselling_wijzigen($item_ID = null)
    {
        if($item_ID == null) redirect('cms/kortingscodes');
        $this->_upselling_toevoegen_wijzigen('wijzigen', $item_ID);
    }

    private function _upselling_toevoegen_wijzigen($actie, $item_ID = null)
    {
        $this->load->model('kortingscodes_model');
        $this->load->model('workshops_model');
        $this->load->model('producten_model');

        $workshop_ID = '';
        $upselling = '';
        $workshops = $this->kortingscodes_model->getWorkshops();
        $producten = $this->producten_model->getProducten();

        // FORMULIER VERZONDEN

        if (isset($_POST['kortingen'])) {
            $fouten = 0;
            $item_workshop = $_POST['item_workshop'];
            $kortingen = $_POST['kortingen'];

            $this->kortingscodes_model->deleteUpsellingConnecties($item_ID);

            if($actie == 'toevoegen') {
                $upselling_data = array(
                    'workshop_ID' => $item_workshop
                );

                $item_ID = $this->kortingscodes_model->insertUpselling($upselling_data);
            }

            foreach ($kortingen as $item) {
                $data = array(
                    'upselling_ID' => $item_ID,
                    'product_ID' => $item['product_ID'],
                    'upselling_prijs' => $item['prijs']
                );

                if($actie == 'wijzigen') {
                    $upselling_data = array(
                        'workshop_ID' => $item_workshop
                    );

                    $this->kortingscodes_model->updateUpselling($item_ID, $upselling_data);
                }

                $q = $this->kortingscodes_model->insertUpsellingConnectie($data);
            }
            redirect('cms/kortingscodes');
        }

        if($actie == 'wijzigen') {
            $upselling = $this->kortingscodes_model->getUpsellingByID($item_ID);
            $upselling = $upselling->workshop_ID;

            foreach($producten as $product) {
                $connectie = $this->kortingscodes_model->getUpsellingConnectieByIDs($item_ID, $product->product_ID);

                if (!empty($connectie)) {
                    $product->upselling_prijs = $connectie[0]->upselling_prijs;
                }
            }
        }

        // PAGINA TONEN

        $this->data['actie'] = $actie;
        $this->data['item_ID'] = $item_ID;
        $this->data['workshop_ID'] = $upselling;

        $this->data['workshops'] = $workshops;
        $this->data['producten'] = $producten;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/upselling_wijzigen';
        $this->load->view('cms/template', $pagina);
    }

    /* =============== */
    /* = VERWIJDEREN = */
    /* =============== */

    public function verwijderen($item_ID = null, $bevestiging = null)
    {
        if($item_ID == null) redirect('cms/kortingscodes');

        $this->load->model('kortingscodes_model');
        $item = $this->kortingscodes_model->getKortingscodeByID($item_ID);
        if($item == null) redirect('cms/kortingscodes');
        $this->data['item'] = $item;


        // ITEM VERWIJDEREN

        if($bevestiging == 'ja')
        {
            $q = $this->kortingscodes_model->deleteKortingscode($item_ID);
            $r = $this->kortingscodes_model->deleteKortingConnecties($item_ID);
            if($q) redirect('cms/kortingscodes');
            else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
        }


        // PAGINA TONEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/kortingscodes_verwijderen';
        $this->load->view('cms/template', $pagina);
    }

    public function kortingscodes_verwijderen() {
        if(!empty($_POST['geselecteerde_kortingscodes'])) {
            foreach ($_POST['geselecteerde_kortingscodes'] as $item_ID) {
                if ($item_ID == null) redirect('cms/kortingscodes');

                $this->load->model('kortingscodes_model');
                $item = $this->kortingscodes_model->getKortingscodeByID($item_ID);
                if ($item == null) redirect('cms/kortingscodes');
                $this->data['item'] = $item;

                $this->kortingscodes_model->deleteKortingscode($item_ID);
                $this->kortingscodes_model->deleteKortingConnecties($item_ID);
            }
        }
        redirect('cms/kortingscodes');
    }

    public function upselling_verwijderen($item_ID, $bevestiging = null) {
        if($item_ID == null) redirect('cms/kortingscodes');

        $this->load->model('kortingscodes_model');
        $item = $this->kortingscodes_model->getUpsellingByID($item_ID);
        if($item == null) redirect('cms/kortingscodes');
        $this->data['item'] = $item;


        // ITEM VERWIJDEREN

        if($bevestiging == 'ja')
        {
            $q = $this->kortingscodes_model->deleteUpselling($item_ID);
            $r = $this->kortingscodes_model->deleteUpsellingConnecties($item_ID);
            if($q) redirect('cms/kortingscodes');
            else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
        }


        // PAGINA TONEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/upselling_verwijderen';
        $this->load->view('cms/template', $pagina);
    }
}