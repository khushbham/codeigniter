<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vragen extends CI_Controller
{
	private $data = array();
	
	public function __construct()
	{
		parent::__construct();
		
		// Rechten controleren en aantal nieuwe items ophalen
		
		$this->load->library('algemeen');
		$this->algemeen->cms();
		if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'docent') redirect('cms/rechten');
	}
	
	
	
	/* ============= */
	/* = OVERZICHT = */
	/* ============= */
	
	public function index()
	{
        if($this->session->userdata('beheerder_rechten') != 'admin') redirect('cms/rechten');
		$this->load->model('vragen_model');
		$vragen_website = $this->vragen_model->getVragen('website');
		$vragen_cursistenmodule = $this->vragen_model->getVragen('cursistenmodule');
		
		
		// PAGINA TONEN
		
		$this->data['vragen_website'] 				= $vragen_website;
		$this->data['vragen_cursistenmodule'] 		= $vragen_cursistenmodule;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/vragen';
		$this->load->view('cms/template', $pagina);
	}
	
	
	
	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */
	
	public function detail($item_ID = null)
	{
        if($this->session->userdata('beheerder_rechten') != 'admin') redirect('cms/rechten');

		if($item_ID == null) redirect('cms/vragen');
		
		$this->load->model('vragen_model');
		$this->load->model('media_model');
		
		$item = $this->vragen_model->getVraagByID($item_ID);
		if($item == null) redirect('cms/vragen');
		
		$media = $this->media_model->getMediaByMediaID($item->media_ID);
		
		
		// PAGINA LADEN
		
		$this->data['item'] = $item;
		$this->data['media'] = $media;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/vragen_vraag';
		$this->load->view('cms/template', $pagina);
	}

    public function vragen_docent()
    {
        $this->load->model('vragen_model');
        $vragen = $this->vragen_model->getVragen('cursistenmodule');
        $this->data['vragen'] = $vragen;

        // PAGINA TONEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/vragen_docent';
        $this->load->view('cms/template', $pagina);
    }
	
	
	
	/* ========================= */
	/* = TOEVOEGEN EN WIJZIGEN = */
	/* ========================= */
	
	public function toevoegen()
	{
        if($this->session->userdata('beheerder_rechten') != 'admin') redirect('cms/rechten');
		$this->_toevoegen_wijzigen('toevoegen');
	}
	
	public function wijzigen($item_ID = null)
	{
        if($this->session->userdata('beheerder_rechten') != 'admin') redirect('cms/rechten');
		if($item_ID == null) redirect('cms/vragen');
		$this->_toevoegen_wijzigen('wijzigen', $item_ID);
	}
	
	private function _toevoegen_wijzigen($actie, $item_ID = null)
	{
        if($this->session->userdata('beheerder_rechten') != 'admin') redirect('cms/rechten');

		$this->load->model('vragen_model');
		$this->load->model('media_model');
		
		$item_titel 			= '';
		$item_antwoord		 	= '';
		$item_type	 			= '';
		$item_gepubliceerd 		= '';
		$media					= '';
		
		$item_titel_feedback 			= '';
		$item_antwoord_feedback 		= '';
		$item_type_feedback 			= '';
		$item_gepubliceerd_feedback 	= '';
		
		
		// FORMULIER VERZONDEN
		
		if(isset($_POST['item_titel']))
		{
			$fouten 			= 0;
			$item_titel 		= trim($_POST['item_titel']);
			$item_antwoord 		= trim($_POST['item_antwoord']);
			$item_media			= trim($_POST['item_media']);
			
			if(isset($_POST['item_type'])) $item_type = $_POST['item_type'];
			if(isset($_POST['item_gepubliceerd'])) $item_gepubliceerd = $_POST['item_gepubliceerd'];
			
			if(empty($item_titel))
			{
				$fouten++;
				$item_titel_feedback = 'Graag invullen';
			}
			
			if(empty($item_antwoord))
			{
				$fouten++;
				$item_antwoord_feedback = 'Graag invullen';
			}
			
			if(empty($item_type))
			{
				$fouten++;
				$item_type_feedback = 'Graag selecteren';
			}
			
			if(empty($item_gepubliceerd))
			{
				$fouten++;
				$item_gepubliceerd_feedback = 'Graag selecteren';
			}
			
			if($fouten == 0)
			{
				// TOEVOEGEN / UPDATEN
				
				$data = array(
					'vraag_titel' => $item_titel,
					'vraag_antwoord' => $item_antwoord,
					'vraag_type' => $item_type,
					'vraag_gepubliceerd' => $item_gepubliceerd,
					'media_ID' => str_replace(',', '', $item_media)
				);
				
				if($actie == 'toevoegen') $q = $this->vragen_model->insertVraag($data);
				else $q = $this->vragen_model->updateVraag($item_ID, $data);
				
				if($q)
				{
					if($actie == 'toevoegen') redirect('cms/vragen');
					else redirect('cms/vragen/'.$item_ID);
				}
				else
				{
					echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
				}
			}
		}
		
		if($actie == 'wijzigen')
		{
			$item = $this->vragen_model->getVraagByID($item_ID);
			if($item == null) redirect('cms/vragen');
			
			$item_titel 			= $item->vraag_titel;
			$item_antwoord 			= $item->vraag_antwoord;
			$item_type 				= $item->vraag_type;
			$item_gepubliceerd 		= $item->vraag_gepubliceerd;
			
			// MEDIA OPHALEN
			
			$media = $this->media_model->getMediaByMediaID($item->media_ID);
		}
		
		
		// PAGINA TONEN
		
		$this->data['actie'] = $actie;
		
		$this->data['item_ID'] 				= $item_ID;
		$this->data['item_titel'] 			= $item_titel;
		$this->data['item_antwoord'] 		= $item_antwoord;
		$this->data['item_type'] 			= $item_type;
		$this->data['item_gepubliceerd'] 	= $item_gepubliceerd;
		$this->data['media']			 	= $media;
		
		$this->data['item_titel_feedback'] 				= $item_titel_feedback;
		$this->data['item_antwoord_feedback']	 		= $item_antwoord_feedback;
		$this->data['item_type_feedback'] 				= $item_type_feedback;
		$this->data['item_gepubliceerd_feedback'] 		= $item_gepubliceerd_feedback;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/vragen_wijzigen';
		$this->load->view('cms/template', $pagina);
	}
	
	
	
	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */
	
	public function verwijderen($item_ID = null, $bevestiging = null)
	{
        if($this->session->userdata('beheerder_rechten') != 'admin') redirect('cms/rechten');

		if($item_ID == null) redirect('cms/vragen');
		
		$this->load->model('vragen_model');
		$item = $this->vragen_model->getVraagByID($item_ID);
		if($item == null) redirect('cms/vragen');
		$this->data['item'] = $item;
		
		
		// ITEM VERWIJDEREN
		
		if($bevestiging == 'ja')
		{
			$q = $this->vragen_model->deleteVraag($item_ID);
			if($q) redirect('cms/vragen');
			else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
		}
		
		
		// PAGINA TONEN
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/vragen_verwijderen';
		$this->load->view('cms/template', $pagina);
	}
}