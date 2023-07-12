<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notities extends CI_Controller
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

    public function index()
    {
        $this->load->model('notities_model');
        $deelnemers = $this->notities_model->getGebruikersMetNotities();
        $this->data['deelnemers'] = $deelnemers;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/notities';
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
        if($item_ID == null) redirect('cms/notities');
        $this->_toevoegen_wijzigen('wijzigen', $item_ID);
    }

    private function _toevoegen_wijzigen($actie, $item_ID = null)
    {
        $this->load->model('notities_model');

        $lege_deelnemers = '';
        $deelnemer       = '';

        if($actie == 'toevoegen') {
            $lege_deelnemers = $this->notities_model->getGebruikersZonderNotities();
        } else {
            $deelnemer = $this->notities_model->getGebruikerByID($item_ID);
        }

        // FORMULIER VERZONDEN

        if(isset($_POST['item_notitie']))
        {
            $item_notitie = trim($_POST['item_notitie']);

            // TOEVOEGEN / UPDATEN

            $data = array(
                'gebruiker_notities' => $item_notitie,
                'gebruiker_notitie_verbergen' => 0
            );

            if($actie == 'wijzigen') {
                $q = $this->notities_model->updateNotitie($item_ID, $data);
            } else {
                if(isset($_POST['item_deelnemer'])) {
                    $q = $this->notities_model->updateNotitie($_POST['item_deelnemer'], $data);
                }
            }

            if($q)
            {
                redirect('cms/notities');
            }
            else
            {
                echo 'Notitie wijzigen mislukt. Probeer het nog eens.';
            }
        }

        // PAGINA TONEN

        $this->data['item'] = $deelnemer;
        $this->data['lege_deelnemers'] = $lege_deelnemers;
        $this->data['actie'] = $actie;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/notities_wijzigen';
        $this->load->view('cms/template', $pagina);
    }

    public function verwijderen() {
        $this->load->model('notities_model');

        $deelnemers = $this->notities_model->getGebruikersMetNotities();

        if(isset($_POST['geselecteerde_notities'])) {
            foreach ($_POST['geselecteerde_notities'] as $deelnemer_ID) {
                // NOTITIE LEGEN
                $data = array(
                    'gebruiker_notitie_verbergen' => '1',
                );

                $q = $this->notities_model->updateNotitie($deelnemer_ID, $data);
            }

            if ($q) {
                redirect('cms/notities');
            } else {
                echo 'Notitie verwijderen mislukt. Probeer het nog eens.';
            }
        }

        $this->data['deelnemers'] = $deelnemers;
        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/notities';
        $this->load->view('cms/template', $pagina);
    }
}