<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Producten extends CI_Controller
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
		$this->load->model('producten_model');
		$producten = $this->producten_model->getProducten();


		// PAGINA TONEN

		$this->data['producten'] = $producten;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/producten';
		$this->load->view('cms/template', $pagina);
	}



	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */

	public function detail($item_ID = null)
	{
		if($item_ID == null) redirect('cms/producten');

		$this->load->model('producten_model');
		$this->load->model('media_model');

		$item = $this->producten_model->getProductByID($item_ID);
		if($item == null) redirect('cms/producten');

		$media = $this->media_model->getMediaByMediaID($item->media_ID);


		// PAGINA TONEN

		$this->data['item'] = $item;
		$this->data['media'] = $media;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/producten_product';
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
		if($item_ID == null) redirect('cms/producten');
		$this->_toevoegen_wijzigen('wijzigen', $item_ID);
	}

	private function _toevoegen_wijzigen($actie, $item_ID = null)
	{
		$this->load->model('producten_model');
		$this->load->model('media_model');

		$item_naam 				= '';
		$item_beschrijving 		= '';
		$item_prijs 			= '';
		$item_huur				= '';
		$item_borg				= '';
		$item_beschrijving_huur = '';
		$item_prijs_naderhand	= '';
		$item_beschikbaar 		= '';
		$media 					= '';

		$item_naam_feedback 			= '';
		$item_beschrijving_feedback 	= '';
		$item_prijs_feedback	 		= '';
		$item_beschikbaar_feedback	 		= '';


		// FORMULIER VERZONDEN

		if(isset($_POST['item_naam']))
		{
			$fouten 				= 0;
			$item_naam 				= trim($_POST['item_naam']);
			$item_beschrijving 		= trim($_POST['item_beschrijving']);
			$item_prijs		 		= trim($_POST['item_prijs']);
			$item_huur		 		= trim($_POST['item_huur']);
			$item_borg		 		= trim($_POST['item_borg']);
			$item_prijs_naderhand	= trim($_POST['item_prijs_naderhand']);
			$item_media		 		= trim($_POST['item_media']);
			$item_beschrijving_huur = trim($_POST['item_beschrijving_huur']);

			if(isset($_POST['item_beschikbaar'])) $item_beschikbaar = trim($_POST['item_beschikbaar']);

			if(empty($item_naam))
			{
				$fouten++;
				$item_naam_feedback = 'Graag invullen';
			}

			if(empty($item_beschrijving))
			{
				$fouten++;
				$item_beschrijving_feedback = 'Graag invullen';
			}

			if(empty($item_prijs))
			{
				$fouten++;
				$item_prijs_feedback = 'Graag invullen';
			}

			if(empty($item_beschikbaar))
			{
				$fouten++;
				$item_beschikbaar_feedback = 'Graag selecteren';
			}

			if($fouten == 0)
			{
				// TOEVOEGEN / UPDATEN

				$data = array(
					'product_naam' => $item_naam,
					'product_beschrijving' => $item_beschrijving,
					'product_prijs' => $item_prijs,
					'product_beschikbaar' => $item_beschikbaar,
					'media_ID' => str_replace(',', '', $item_media),
					'product_huur' => $item_huur,
					'product_borg' => $item_borg,
					'product_prijs_naderhand' => $item_prijs_naderhand,
					'product_beschrijving_huur' => $item_beschrijving_huur
				);

				if($actie == 'toevoegen') $q = $this->producten_model->insertProduct($data);
				else $q = $this->producten_model->updateProduct($item_ID, $data);

				if($q)
				{
					if($actie == 'toevoegen') redirect('cms/producten');
					else redirect('cms/producten/'.$item_ID);
				}
				else
				{
					echo 'Product toevoegen / wijzigen mislukt. Probeer het nog eens.';
				}
			}
		}

		if($actie == 'wijzigen')
		{
			$item = $this->producten_model->getProductByID($item_ID);
			if($item == null) redirect('cms/producten');

			$item_naam 					= $item->product_naam;
			$item_beschrijving 			= $item->product_beschrijving;
			$item_prijs 				= $item->product_prijs;
			$item_beschikbaar			= $item->product_beschikbaar;
			$item_huur					= $item->product_huur;
			$item_borg					= $item->product_borg;
			$item_prijs_naderhand		= $item->product_prijs_naderhand;
			$item_beschrijving_huur		= $item->product_beschrijving_huur;

			// MEDIA OPHALEN

			$media = $this->media_model->getMediaByMediaID($item->media_ID);
		}


		// PAGINA TONEN

		$this->data['actie'] = $actie;

		$this->data['item_ID'] 					= $item_ID;
		$this->data['item_naam'] 				= $item_naam;
		$this->data['item_beschrijving'] 		= $item_beschrijving;
		$this->data['item_prijs'] 				= $item_prijs;
		$this->data['item_huur'] 				= $item_huur;
		$this->data['item_borg'] 				= $item_borg;
		$this->data['item_prijs_naderhand'] 	= $item_prijs_naderhand;
		$this->data['item_beschrijving_huur'] 	= $item_beschrijving_huur;
		$this->data['item_beschikbaar'] 		= $item_beschikbaar;
		$this->data['media'] 					= $media;

		$this->data['item_naam_feedback'] 			= $item_naam_feedback;
		$this->data['item_beschrijving_feedback'] 	= $item_beschrijving_feedback;
		$this->data['item_prijs_feedback'] 			= $item_prijs_feedback;
		$this->data['item_beschikbaar_feedback'] 	= $item_beschikbaar_feedback;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/producten_wijzigen';
		$this->load->view('cms/template', $pagina);
	}



	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */

	public function verwijderen($item_ID = null, $bevestiging = null)
	{
		if($item_ID == null) redirect('cms/producten');

		$this->load->model('producten_model');
		$item = $this->producten_model->getProductByID($item_ID);
		if($item == null) redirect('cms/producten');
		$this->data['item'] = $item;


		$workshops = $this->producten_model->getWorkshopsByProductID($item_ID);
		$this->data['workshops'] = $workshops;

		$bestellingen = $this->producten_model->getBestellingenByProductID($item_ID);
		$this->data['bestellingen'] = $bestellingen;


		// ITEM VERWIJDEREN

		if($bevestiging == 'ja')
		{
			$q = $this->producten_model->deleteProductByID($item_ID);
			if($q) redirect('cms/producten');
			else echo 'Het product kon niet worden verwijderd. Probeer het nog eens.';
		}


		// PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/producten_verwijderen';
		$this->load->view('cms/template', $pagina);
	}
}
