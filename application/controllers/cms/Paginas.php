<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paginas extends CI_Controller
{
	private $data = array();

	public function __construct()
	{
		parent::__construct();

		// Rechten controleren en aantal nieuwe items ophalen

		$this->load->library('algemeen');
		$this->algemeen->cms();
		// if($this->session->userdata('beheerder_rechten') != 'admin' || $this->session->userdata('gebruiker_ID') != 6164) redirect('cms/rechten');
		if($this->session->userdata('beheerder_rechten') != 'admin') redirect('cms/rechten');
	}



	/* ============= */
	/* = OVERZICHT = */
	/* ============= */

	public function index()
	{
		$this->pagina();
	}

	public function pagina($p = 1)
	{
		$this->load->model('paginas_model');
		$aantal_items = $this->paginas_model->getPaginasAantal();
		$per_pagina = 10;
		$aantal_paginas = ceil($aantal_items / $per_pagina);
		$huidige_pagina = $p;
		$paginas = $this->paginas_model->getPaginas($per_pagina, $huidige_pagina);

		// Controleren of de paginanummer te hoog is
		if($p > 1 && sizeof($paginas) == 0) redirect('cms/paginas');


		// PAGINA TONEN

		$this->data['paginas'] 				= $paginas;
		$this->data['aantal_paginas'] 		= $aantal_paginas;
		$this->data['huidige_pagina']		= $huidige_pagina;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/paginas';
		$this->load->view('cms/template', $pagina);
	}



	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */

	public function detail($item_ID = null)
	{
		if($item_ID == null) redirect('cms/paginas');

		$this->load->model('paginas_model');
		$this->load->model('media_model');

		$item = $this->paginas_model->getpaginaByID($item_ID);
		$item->pagina_tekst = $this->ReplaceTags($item->pagina_tekst);
		if($item == null) redirect('cms/paginas');

		$media = $this->media_model->getMediaByMediaID($item->media_ID);
		$meta_media = $this->media_model->getMediaByMediaID($item->meta_media_ID);


		// PAGINA LADEN

		$this->data['item'] = $item;
		$this->data['media'] = $media;
		$this->data['meta_media'] = $meta_media;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/paginas_pagina';
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
		if($item_ID == null) redirect('cms/paginas');
		$this->_toevoegen_wijzigen('wijzigen', $item_ID);
	}

	private function _toevoegen_wijzigen($actie, $item_ID = null)
	{
		$this->load->model('paginas_model');
		$this->load->model('media_model');

		$item_url 				= '';
		$item_titel 			= '';
		$item_titel_menu 		= '';
		$item_inleiding			= '';
		$item_tekst			 	= '';
		$media 					= '';
		$item_meta_title		= '';
		$item_meta_description	= '';
		$item_video_url			= '';
		$item_chat_url			= '';
		$meta_media       		= '';

		$item_url_feedback 				= '';
		$item_titel_feedback 			= '';
		$item_titel_menu_feedback 		= '';
		$item_inleiding_feedback 		= '';
		$item_tekst_feedback 			= '';
		$item_meta_title_feedback		= '';
		$item_meta_description_feedback	= '';
		$item_video_url_feedback		= '';


		// FORMULIER VERZONDEN

		if(isset($_POST['item_titel']))
		{
			$fouten 				= 0;
			$item_url 				= trim($_POST['item_url']);
			$item_titel 			= trim($_POST['item_titel']);
			$item_titel_menu 		= trim($_POST['item_titel_menu']);
			$item_inleiding 		= trim($_POST['item_inleiding']);
			$item_tekst		 		= trim($_POST['item_tekst']);
			$item_media			 	= trim($_POST['item_media']);
			$item_video_url			= trim($_POST['item_video_url']);
			$item_chat_url			= trim($_POST['item_chat_url']);
			$item_meta_media  		= trim($_POST['item_media_uitgelicht']);

			if(isset($_POST['item_meta_title'])) {
				$item_meta_title		= trim($_POST['item_meta_title']);
			}

			if(isset($_POST['item_meta_description'])) {
				$item_meta_description	= trim($_POST['item_meta_description']);
			}

			if(empty($item_url))
			{
				$fouten++;
				$item_url_feedback = 'Graag invullen';
			}

			if(empty($item_titel))
			{
				$fouten++;
				$item_titel_feedback = 'Graag invullen';
			}

			if(empty($item_titel_menu))
			{
				$fouten++;
				$item_titel_menu_feedback = 'Graag invullen';
			}

			if(empty($item_tekst))
			{
				$fouten++;
				$item_tekst_feedback = 'Graag invullen';
			}

			if($fouten == 0)
			{
				// TOEVOEGEN / UPDATEN

				$data = array(
					'pagina_url' => $item_url,
					'pagina_titel' => $item_titel,
					'pagina_titel_menu' => $item_titel_menu,
					'pagina_inleiding' => $item_inleiding,
					'pagina_tekst' => $item_tekst,
					'media_ID' => str_replace(',', '', $item_media),
					'meta_media_ID' => str_replace(',', '', $item_meta_media),
					'meta_title' => $item_meta_title,
					'meta_description' => $item_meta_description,
					'video_url' => $item_video_url,
					'chat_url' => $item_chat_url
				);

				if($actie == 'toevoegen') $q = $this->paginas_model->insertPagina($data);
				else $q = $this->paginas_model->updatePagina($item_ID, $data);

				if($q)
				{
					if($actie == 'toevoegen') redirect('cms/paginas');
					else redirect('cms/paginas/'.$item_ID);
				}
				else
				{
					echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
				}
			}
		}

		if($actie == 'wijzigen')
		{
			$item = $this->paginas_model->getPaginaByID($item_ID);
			if($item == null) redirect('cms/paginas');

			$item_url 				= $item->pagina_url;
			$item_titel 			= $item->pagina_titel;
			$item_titel_menu 		= $item->pagina_titel_menu;
			$item_inleiding 		= $item->pagina_inleiding;
			$item_tekst		 		= $item->pagina_tekst;
			$item_meta_gewenst		= $item->meta_gewenst;
			$item_meta_title		= $item->meta_title;
			$item_meta_description	= $item->meta_description;
			$item_video_url			= $item->video_url;
			$item_chat_url			= $item->chat_url;

			// MEDIA OPHALEN

			$media 					= $this->media_model->getMediaByMediaID($item->media_ID);
			$meta_media       		= $this->media_model->getMediaByMediaID($item->meta_media_ID);
		}


		// PAGINA TONEN

		$this->data['actie'] = $actie;

		$this->data['item_ID'] 					= $item_ID;
		$this->data['item_url'] 				= $item_url;
		$this->data['item_titel'] 				= $item_titel;
		$this->data['item_titel_menu'] 			= $item_titel_menu;
		$this->data['item_inleiding'] 			= $item_inleiding;
		$this->data['item_tekst'] 				= $item_tekst;
		$this->data['media']	 				= $media;
		$this->data['item_meta_gewenst'] 		= $item_meta_gewenst;
		$this->data['item_meta_title'] 			= $item_meta_title;
		$this->data['item_meta_description'] 	= $item_meta_description;
		$this->data['item_video_url']			= $item_video_url;
		$this->data['item_chat_url']			= $item_chat_url;
		$this->data['meta_media']          		= $meta_media;

		$this->data['item_url_feedback'] 				= $item_url_feedback;
		$this->data['item_titel_feedback'] 				= $item_titel_feedback;
		$this->data['item_titel_menu_feedback'] 		= $item_titel_menu_feedback;
		$this->data['item_inleiding_feedback'] 			= $item_inleiding_feedback;
		$this->data['item_tekst_feedback']	 			= $item_tekst_feedback;
		$this->data['item_meta_title_feedback'] 		= $item_meta_title_feedback;
		$this->data['item_meta_description_feedback'] 	= $item_meta_description_feedback;
		$this->data['item_video_url_feedback']			= $item_video_url_feedback;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/paginas_wijzigen';
		$this->load->view('cms/template', $pagina);
	}

	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */

	/*
	public function verwijderen($item_ID = null, $bevestiging = null)
	{
		if($item_ID == null) redirect('cms/reacties');

		$this->load->model('reacties_model');
		$item = $this->reacties_model->getReactieByID($item_ID);
		if($item == null) redirect('cms/reacties');
		$this->data['item'] = $item;


		// ITEM VERWIJDEREN

		if($bevestiging == 'ja')
		{
			$q = $this->reacties_model->deleteReactie($item_ID);
			if($q) redirect('cms/reacties');
			else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
		}


		// PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/reacties_verwijderen';
		$this->load->view('cms/template', $pagina);
	}
	*/

    function ReplaceTags($tekst) {
        $tekst = str_replace('[TAB-TITEL-BEGIN]', '<ul id="tab"><li><label>', $tekst);
        $tekst = str_replace('[TAB-TITEL-EIND]', '<span class="changeArrow arrow"></span></label>', $tekst);
        $tekst = str_replace('[TAB-BEGIN]', '<div class="content">', $tekst);
        $tekst = str_replace('[TAB-EIND]', '</div></li></ul>', $tekst);

        $tekst = str_replace('[BORDER-BEGIN]', '<div class="border">', $tekst);
        $tekst = str_replace('[BORDER-EIND]', '</div>', $tekst);

        $tekst = str_replace('[BLAUWE-ACHTERGROND-BEGIN]', '<div class="blauwe_achtergrond">', $tekst);
        $tekst = str_replace('[BLAUWE-ACHTERGROND-EIND]', '</div>', $tekst);

        $tekst = str_replace('[VINKJE]', '<img src="'. base_url('assets/images/vinkje.png') .'">', $tekst);

        $tekst = str_replace('[LINK-BEGIN]', '<a class="button button--orange" href="', $tekst);
        $tekst = str_replace('[LINK-EIND]', '">', $tekst);
        $tekst = str_replace('[LINK-TEKST-BEGIN]', '', $tekst);
        $tekst = str_replace('[LINK-TEKST-EIND]', '</a>', $tekst);

        $tekst = str_replace('[BUTTON-BEGIN]', '<a class="button button--orange" href="', $tekst);
        $tekst = str_replace('[BUTTON-EIND]', '">', $tekst);
        $tekst = str_replace('[BUTTON-TEKST-BEGIN]', '', $tekst);
        $tekst = str_replace('[BUTTON-TEKST-EIND]', '</a>', $tekst);
        return  $tekst;
    }
}