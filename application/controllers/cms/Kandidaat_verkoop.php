<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kandidaat_verkoop extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Rechten controleren en aantal nieuwe items ophalen

        $this->load->library('algemeen');
        $this->algemeen->cms();
        if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'support') { redirect('cms/rechten'); }
    }

    /* ========================= */
    /* = TOEVOEGEN EN WIJZIGEN = */
    /* ========================= */

    public function toevoegen()
    {
        $this->_toevoegen_wijzigen('toevoegen');
    }

    public function wijzigen()
    {
        $this->_toevoegen_wijzigen('wijzigen');
    }

    private function _toevoegen_wijzigen($actie)
    {
        $this->load->model('kortingscodes_model');
        $this->load->model('kandidaat_model');
        $this->load->model('workshops_model');
        $this->load->model('producten_model');

        $workshops = $this->kortingscodes_model->getWorkshops();
        $producten = $this->producten_model->getProducten();

        // FORMULIER VERZONDEN

        if(isset($_POST['geselecteerde_workshops']) || isset($_POST['geselecteerde_producten'])) {
            $fouten = 0;

            if ($actie == 'toevoegen') {
                if ($fouten == 0) {
                    $this->kandidaat_model->ClearTableWorkshops();
                    $this->kandidaat_model->ClearTableProducten();

                    if (!empty($_POST['geselecteerde_workshops'])) {
                        $geselecteerde_workshops = $_POST['geselecteerde_workshops'];

                        foreach ($geselecteerde_workshops as $geselecteerde_workshop_ID) {
                            $data = array(
                                'workshop_ID' => $geselecteerde_workshop_ID,
                            );
                            $this->kandidaat_model->insertKandidaatWorkshop($data);
                        }
                    }

                    if (!empty($_POST['geselecteerde_producten'])) {
                        $geselecteerde_producten = $_POST['geselecteerde_producten'];

                        foreach ($geselecteerde_producten as $geselecteerde_product_ID) {
                            $data = array(
                                'product_ID' => $geselecteerde_product_ID,
                            );
                            $this->kandidaat_model->insertKandidaatProduct($data);
                        }
                    }

                    redirect('cms/kortingscodes');
                }
            } else {
                echo 'verkoop opties ' . $actie . ' mislukt. Probeer het nog eens.';
            }
        }

        if($actie == 'wijzigen') {
            if(isset($_POST['geen_checkbox']) && $_POST['geen_checkbox'] == 'on') {
                $this->kandidaat_model->ClearTableWorkshops();
                $this->kandidaat_model->ClearTableProducten();

                redirect('cms/kortingscodes');
            }


            if(isset($_POST['geselecteerde_workshops']) || isset($_POST['geselecteerde_producten'] ))
            {
                $fouten = 0;

                if($fouten == 0) {
                    $this->kandidaat_model->ClearTableWorkshops();
                    $this->kandidaat_model->ClearTableProducten();

                    if(!empty($_POST['geselecteerde_workshops'])) {
                        $geselecteerde_workshops = $_POST['geselecteerde_workshops'];

                        foreach ($geselecteerde_workshops as $geselecteerde_workshop_ID) {
                            $data = array(
                                'workshop_ID' => $geselecteerde_workshop_ID,
                            );
                            $this->kandidaat_model->insertKandidaatWorkshop($data);
                        }
                    }

                    if(!empty($_POST['geselecteerde_producten'])) {
                        $geselecteerde_producten = $_POST['geselecteerde_producten'];

                        foreach ($geselecteerde_producten as $geselecteerde_product_ID) {
                            $data = array(
                                'product_ID' => $geselecteerde_product_ID,
                            );
                            $this->kandidaat_model->insertKandidaatProduct($data);
                        }
                    }
                }
                redirect('cms/kortingscodes');
            }

            $workshop_connecties = $this->kandidaat_model->getKandidaatWorkshops();
            $product_connecties = $this->kandidaat_model->getKandidaatProducten();

            if (!empty($workshop_connecties)) {
                $this->data['workshop_connecties'] = $workshop_connecties;
            }
            if (!empty($product_connecties)) {
                $this->data['product_connecties'] = $product_connecties;
            }
        }

        // PAGINA TONEN

        $this->data['actie'] = $actie;

        $this->data['workshops']                    = $workshops;
        $this->data['producten']                    = $producten;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/kandidaat_verkoop_wijzigen';
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