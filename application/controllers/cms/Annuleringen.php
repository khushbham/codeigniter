<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Annuleringen extends CI_Controller
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
        $this->load->model('annuleringen_model');

        $workshops = $this->annuleringen_model->getOverzicht();

        // PAGINA TONEN

        $this->data['workshops'] = $workshops;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/annuleringen';
        $this->load->view('cms/template', $pagina);
    }

    /* ========================= */
    /* = TOEVOEGEN EN WIJZIGEN = */
    /* ========================= */

    public function wijzigen($workshop_ID = null)
    {
        $this->load->model('annuleringen_model');
        $this->load->model('workshops_model');

        $item_percentage      = '0';
        $item_actief          = 'Nee';

        if($workshop_ID != null) {
            $annulering = $this->annuleringen_model->getAnnuleringByWorkshopID($workshop_ID);
            if(!empty($annulering)) { $actie = 'wijzigen'; } else { $actie ='toevoegen'; 
                $annulering = $this->workshops_model->getWorkshopByID($workshop_ID); }
        }

        // FORMULIER VERZONDEN

        if(isset($_POST['item_percentage']))
        {
            $fouten 				= 0;
            $item_percentage        = trim($_POST['item_percentage']);
            $item_actief            = trim($_POST['item_actief']);

            if(empty($item_percentage))
            {
                $fouten++;
            }

            if($fouten == 0)
            {
                // TOEVOEGEN / UPDATEN
                if($item_percentage === '0' || $item_percentage === '') { $item_percentage = null; }

                $data = array(
                    'annulering_percentage' => $item_percentage,
                    'annulering_actief' => $item_actief,
                    'workshop_ID' => $workshop_ID
                );

                if($actie == 'toevoegen') $q = $this->annuleringen_model->insertAnnulering($data);
                else {
                    $q = $this->annuleringen_model->updateAnnulering($workshop_ID, $data);

                    redirect('cms/annuleringen');
                }
            }
        }

        if($actie == 'wijzigen')
        {
            $item = $this->annuleringen_model->getAnnuleringByWorkshopID($workshop_ID);
            if($item == null) redirect('cms/annuleringen');

            $item_percentage    = $item->annulering_percentage;
            $item_actief        = $item->annulering_actief;
        }

        // PAGINA TONEN

        $this->data['actie'] = $actie;
        $this->data['annulering'] = $annulering;
        $this->data['item_percentage'] = $item_percentage;
        $this->data['item_actief']  = $item_actief;
        $this->data['workshop_ID'] = $workshop_ID;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/annuleringen_wijzigen';
        $this->load->view('cms/template', $pagina);
    }

    /* =============== */
    /* = VERWIJDEREN = */
    /* =============== */

    public function verwijderen($item_ID = null, $bevestiging = null)
    {
        if($item_ID == null) redirect('cms/annuleringen');

        $this->load->model('kortingscodes_model');
        $item = $this->kortingscodes_model->getKortingscodeByID($item_ID);
        if($item == null) redirect('cms/annuleringen');
        $this->data['item'] = $item;


        // ITEM VERWIJDEREN

        if($bevestiging == 'ja')
        {
            $q = $this->kortingscodes_model->deleteKortingscode($item_ID);
            $r = $this->kortingscodes_model->deleteKortingConnecties($item_ID);
            if($q) redirect('cms/annuleringen');
            else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
        }

        // PAGINA TONEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/annuleringen_verwijderen';
        $this->load->view('cms/template', $pagina);
    }

    public function kortingscodes_verwijderen() {
        if(!empty($_POST['geselecteerde_kortingscodes'])) {
            foreach ($_POST['geselecteerde_kortingscodes'] as $item_ID) {
                if ($item_ID == null) redirect('cms/annuleringen');

                $this->load->model('kortingscodes_model');
                $item = $this->kortingscodes_model->getKortingscodeByID($item_ID);
                if ($item == null) redirect('cms/annuleringen');
                $this->data['item'] = $item;

                $this->kortingscodes_model->deleteKortingscode($item_ID);
                $this->kortingscodes_model->deleteKortingConnecties($item_ID);
            }
        }
        redirect('cms/annuleringen');
    }

    public function upselling_verwijderen($item_ID, $bevestiging = null) {
        if($item_ID == null) redirect('cms/annuleringen');

        $this->load->model('kortingscodes_model');
        $item = $this->kortingscodes_model->getUpsellingByID($item_ID);
        if($item == null) redirect('cms/annuleringen');
        $this->data['item'] = $item;


        // ITEM VERWIJDEREN

        if($bevestiging == 'ja')
        {
            $q = $this->kortingscodes_model->deleteUpselling($item_ID);
            $r = $this->kortingscodes_model->deleteUpsellingConnecties($item_ID);
            if($q) redirect('cms/annuleringen');
            else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
        }


        // PAGINA TONEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/upselling_verwijderen';
        $this->load->view('cms/template', $pagina);
    }

    public function refund($payment_ID) {
        require_once '../../vendor/autoload.php';
        require_once '../config.php';
        try {
            $refund = \Paynl\Refund::get($payment_ID);
            echo json_encode($refund->getData(), JSON_PRETTY_PRINT);
        } catch (\Paynl\Error\Error $e) {
            echo $e->getMessage();
        }
    }
}