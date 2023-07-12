<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uitnodigingen extends CI_Controller
{
	private $data = array();

	public function __construct()
	{
		parent::__construct();

		// Rechten controleren en aantal nieuwe items ophalen

		$this->load->library('algemeen');
		$this->algemeen->cms();
        if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'support') { redirect('cms/rechten'); }
	}


	/* ========= */
	/* = INDEX = */
	/* ========= */

	public function index()
	{
		//////////////////
		// MODELS LADEN //
		//////////////////

		$this->load->model('uitnodigingen_model');

		/////////////////////////
		// UITNODIGINGEN LADEN //
		/////////////////////////

		$uitnodigingen = $this->uitnodigingen_model->getUitnodigingen();
		$this->data['uitnodigingen'] = $uitnodigingen;

		$links = $this->uitnodigingen_model->getLinks();
        $this->data['links'] = $links;

		//////////////////
		// PAGINA TONEN //
		//////////////////

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/uitnodigingen';
		$this->load->view('cms/template', $pagina);
	}


	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */

	public function detail($item_ID = null)
	{
		if($item_ID == null) redirect('cms/uitnodigingen');

		$this->load->model('uitnodigingen_model');

		$uitnodiging = $this->uitnodigingen_model->getUitnodigingByID($item_ID);

		if($uitnodiging == null) redirect('cms/uitnodigingen');

		////////////////
		// VARIABELEN //
		////////////////

		$this->data['uitnodiging'] = $uitnodiging;

		//////////////////
		// PAGINA TONEN //
		//////////////////

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/uitnodigingen_uitnodiging';

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
		if($item_ID == null) redirect('cms/uitnodigingen');
		$this->_toevoegen_wijzigen('wijzigen', $item_ID);
	}

	private function _toevoegen_wijzigen($actie, $item_ID = null)
	{
		//////////////////
		// MODELS LADEN //
		//////////////////

		$this->load->model('lessen_model');
		$this->load->model('workshops_model');
		$this->load->model('uitnodigingen_model');

		////////////////
		// VARIABELEN //
		////////////////

		$lessen 		= $this->lessen_model->getLessen();
		$workshops 		= $this->workshops_model->getWorkshopsStandaardCMS();
		$specialties 	= $this->workshops_model->getWorkshopsSpecialtiesCMS();

		$item_workshop 			= '';
		$item_onderwerp 		= '';
		$item_tekst 			= '';
		$item_nales				= '';
		$item_nales_dagen 		= '';

		$item_workshop_feedback 			= '';
		$item_onderwerp_feedback 			= '';
		$item_tekst_feedback 				= '';
		$item_nales_feedback				= '';
		$item_nales_dagen_feedback 			= '';

		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$fouten = 0;

			$item_workshop			= trim($_POST['item_workshop']);
			$item_onderwerp 		= trim($_POST['item_onderwerp']);
			$item_tekst 			= trim($_POST['item_tekst']);
			$item_nales 			= trim($_POST['item_nales']);
			$item_nales_dagen 		= trim($_POST['item_nales_dagen']);

			// Verplicht

			if(empty($item_workshop)) {
				$fouten++;
				$item_workshop_feedback = 'Graag selecteren';
			}

			if(empty($item_onderwerp))
			{
				$fouten++;
				$item_onderwerp_feedback = 'Graag invullen';
			}

			if(empty($item_tekst))
			{
				$fouten++;
				$item_tekst_feedback = 'Graag invullen';
			}

			if(empty($item_nales)) {
				$fouten++;
				$item_nales_feedback = 'Graag selecteren';
			}

			if(!isset($item_nales_dagen) || !ctype_digit($item_nales_dagen)) {
				$fouten++;
				$item_nales_dagen_feedback = 'Graag invullen';
			}

			if($fouten == 0)
			{
				// TOEVOEGEN / UPDATEN

				$data = array(
					'uitnodiging_onderwerp' => $item_onderwerp,
					'uitnodiging_tekst' => $item_tekst,
					'uitnodiging_dagen_na_afloop' => $item_nales_dagen,
					'workshop_ID' => $item_workshop,
					'les_ID' => $item_nales
				);

				// Data toevoegen/updaten afhankelijk van het type

				if($actie == 'toevoegen')
				{
					$q = $this->uitnodigingen_model->insertUitnodiging($data);
				}
				else
				{
					$q = $this->uitnodigingen_model->updateUitnodiging($item_ID, $data);
				}

				if($q)
				{
					if($actie == 'toevoegen')
					{
						redirect('cms/uitnodigingen');
					}
					else
					{
						redirect('cms/uitnodigingen/'.$item_ID);
					}
				}
				else
				{
					echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
				}
			}
		}
		else
		{
			if($actie == 'wijzigen')
			{
				$item = $this->uitnodigingen_model->getUitnodigingById($item_ID);

				if($item == null) redirect('cms/uitnodigingen');

				$item_workshop 			= $item->workshop_ID;
				$item_nales				= $item->les_ID;
				$item_onderwerp 		= $item->uitnodiging_onderwerp;
				$item_tekst 			= $item->uitnodiging_tekst;
				$item_nales_dagen 		= $item->uitnodiging_dagen_na_afloop;
			}
		}

		//////////////////
		// PAGINA TONEN //
		//////////////////

		$this->data['workshops_standaard']	 = $workshops;
		$this->data['workshops_specialties'] = $specialties;
		$this->data['lessen'] 				 = $lessen;

		$this->data['item_ID'] 				 = $item_ID;
		$this->data['item_workshop'] 		 = $item_workshop;
		$this->data['item_onderwerp'] 		 = $item_onderwerp;
		$this->data['item_tekst'] 			 = $item_tekst;
		$this->data['item_nales_dagen'] 	 = $item_nales_dagen;
		$this->data['item_nales'] 			 = $item_nales;

		$this->data['item_workshop_feedback'] 		= $item_workshop_feedback;
		$this->data['item_onderwerp_feedback'] 		= $item_onderwerp_feedback;
		$this->data['item_tekst_feedback'] 			= $item_tekst_feedback;
		$this->data['item_nales_feedback']			= $item_nales_feedback;
		$this->data['item_nales_dagen_feedback'] 	= $item_nales_dagen_feedback;

		$this->data['actie'] = $actie;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/uitnodigingen_wijzigen';

		$this->load->view('cms/template', $pagina);
	}

    public function link_toevoegen()
    {
        $this->_link_toevoegen_wijzigen('toevoegen');
    }

    public function link_wijzigen($item_ID = null)
    {
        if($item_ID == null) redirect('cms/uitnodigingen');
        $this->_link_toevoegen_wijzigen('wijzigen', $item_ID);
    }

    private function _link_toevoegen_wijzigen($actie, $item_ID = null)
    {
        //////////////////
        // MODELS LADEN //
        //////////////////

        $this->load->model('groepen_model');
        $this->load->model('workshops_model');
        $this->load->model('uitnodigingen_model');

        ////////////////
        // VARIABELEN //
        ////////////////

        $groepen 		= $this->groepen_model->getGroepen();
        $workshops 		= $this->workshops_model->getWorkshops();

        $item_workshop 			            = '';
		$item_groep      		            = '';
		$item_limiet						= null;

        $item_workshop_feedback 			= '';
        $item_groep_feedback     			= '';

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $fouten = 0;

            $item_workshop			= trim($_POST['filter_workshop']);
			$item_groep     		= trim($_POST['filter_groep']);

			if(is_numeric($_POST['item_limiet'])) {
				$item_limiet = trim($_POST['item_limiet']);
			}

            // Verplicht

            if(empty($item_workshop)) {
                $fouten++;
                $item_workshop_feedback = 'Graag selecteren';
            }

            $workshop = $this->workshops_model->getWorkshopByID($item_workshop);

            if(!empty($workshop)) {
                if (in_array($workshop->workshop_type, array('groep', 'online')) && empty($item_groep)) {
                    $fouten++;
                    $item_workshop_feedback = 'Kies een groep';
                }
            }

            if($fouten == 0)
            {
                // TOEVOEGEN / UPDATEN
                $item_code = substr(str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);
                $item_link = base_url("aanmelden/workshop/".$workshop->workshop_url."/".$item_code);

                $data = array(
                    'workshop_ID' => $item_workshop,
                    'groep_ID' => $item_groep,
                    'link_code' => $item_code,
					'link_link' => $item_link,
					'uitnodiging_limiet' => $item_limiet
                );

                // Data toevoegen/updaten afhankelijk van het type

				if($actie == 'toevoegen')
                {
                    $q = $this->uitnodigingen_model->insertUitnodigingsLink($data);
                }
                else
                {
                    $q = $this->uitnodigingen_model->updateUitnodigingsLink($item_ID, $data);
                }

                if($q)
                {
                    redirect('cms/uitnodigingen');
                }
                else
                {
                    echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
                }
            }
        }
        else
        {
            if($actie == 'wijzigen')
            {
                $item = $this->uitnodigingen_model->getLinkByID($item_ID);

                if($item == null) redirect('cms/uitnodigingen');

                $item_workshop 			= $item->workshop_ID;
				$item_groep				= $item->groep_ID;
				$item_limiet			= $item->uitnodiging_limiet;
            }
        }

        //////////////////
        // PAGINA TONEN //
        //////////////////

        $this->data['workshops']	 = $workshops;
        $this->data['groepen']	     = $groepen;

        $this->data['item_ID'] 				 = $item_ID;
        $this->data['item_workshop'] 		 = $item_workshop;
		$this->data['item_groep'] 		     = $item_groep;
		$this->data['item_limiet']			 = $item_limiet;

        $this->data['item_workshop_feedback'] 	= $item_workshop_feedback;
        $this->data['item_groep_feedback'] 		= $item_groep_feedback;

        $this->data['actie'] = $actie;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/link_wijzigen';

        $this->load->view('cms/template', $pagina);
    }


	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */

	public function verwijderen($item_ID = null, $bevestiging = null)
	{
		if($item_ID == null) redirect('cms/uitnodigingen');

		$this->load->model('uitnodigingen_model');
		$item = $this->uitnodigingen_model->getUitnodigingById($item_ID);
		if($item == null) redirect('cms/uitnodigingen');
		$this->data['item'] = $item;

		// ITEM VERWIJDEREN

		if($bevestiging == 'ja')
		{
			$q = $this->uitnodigingen_model->deleteUitnodiging($item_ID);
			if($q) redirect('cms/uitnodigingen');
			else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
		}

		// PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/uitnodigingen_verwijderen';
		$this->load->view('cms/template', $pagina);
	}

    public function link_verwijderen($item_ID = null, $bevestiging = null)
    {
        if($item_ID == null) redirect('cms/uitnodigingen');

        $this->load->model('uitnodigingen_model');
        $item = $this->uitnodigingen_model->getLinkByID($item_ID);
        if($item == null) redirect('cms/uitnodigingen');
        $this->data['item'] = $item;

        // ITEM VERWIJDEREN

        if($bevestiging == 'ja')
        {
            $q = $this->uitnodigingen_model->deleteLink($item_ID);
            if($q) redirect('cms/uitnodigingen');
            else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
        }

        // PAGINA TONEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/link_verwijderen';
        $this->load->view('cms/template', $pagina);
    }
}