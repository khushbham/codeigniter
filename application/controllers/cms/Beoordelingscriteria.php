<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beoordelingscriteria extends CI_Controller
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

	public function index()
	{
		$this->load->model('lessen_model');
		$beoordelingscriteria = $this->lessen_model->getBeoordelingscriteria();
		$beoordelingscriteriaVWS = $this->lessen_model->getBeoordelingscriteriaVWS();

		// PAGINA TONEN

		$this->data['beoordelingscriteria'] = $beoordelingscriteria;
		$this->data['beoordelingscriteriaVWS'] = $beoordelingscriteriaVWS;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/beoordelingscriteria';
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
		if($item_ID == null) redirect('cms/beoordelingscriteria');
		$this->_toevoegen_wijzigen('wijzigen', $item_ID);
	}

	private function _toevoegen_wijzigen($actie, $item_ID = null)
	{
		$this->load->model('lessen_model');

		$item_naam = '';


		// FORMULIER VERZONDEN

		if(isset($_POST['item_naam']))
		{
			$fouten 			= 0;
			$item_naam 			= trim($_POST['item_naam']);

			if(empty($item_naam))
			{
				$fouten++;
			}

			if($fouten == 0)
			{
				// TOEVOEGEN / UPDATEN
				$data = array(
					'beoordelingscriteria_naam' => $item_naam,
				);

				if($actie == 'toevoegen')
				{
					$query = $this->lessen_model->insertBeoordelingscriteria($data);
					if($query) redirect('cms/beoordelingscriteria');
				}
				else
				{
					$query = $this->lessen_model->updateBeoordelingscriteria($item_ID, $data);
					if($query) redirect('cms/beoordelingscriteria');
				}
			}
		}

		if($actie == 'wijzigen')
		{
			$item = $this->lessen_model->getBeoordelingscriteriaByID($item_ID);

			$item_naam = $item[0]->beoordelingscriteria_naam;
		}

		// PAGINA TONEN

		$this->data['actie'] = $actie;

		$this->data['item_ID'] 				= $item_ID;
		$this->data['item_naam'] 			= $item_naam;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/beoordelingscriteria_wijzigen';
		$this->load->view('cms/template', $pagina);
	}



	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */

	public function verwijderen($item_ID = null, $bevestiging = null)
	{
		if($item_ID == null) redirect('cms/beoordelingscriteria');

		$this->load->model('lessen_model');
		$item = $this->lessen_model->getBeoordelingscriteriaByID($item_ID);
		if($item == null) redirect('cms/beoordelingscriteria');
		$this->data['item'] = $item[0];

		// ITEM VERWIJDEREN

		if($bevestiging == 'ja')
		{
            $q = $this->lessen_model->deleteBeoordelingscriteria($item_ID);
			if($q) redirect('cms/beoordelingscriteria');
			else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
		}

		// PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/beoordelingscriteria_verwijderen';
		$this->load->view('cms/template', $pagina);
	}
}