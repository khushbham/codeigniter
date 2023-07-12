<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends CI_Controller
{
    private $data = array();

    public function __construct()
    {
        parent::__construct();

        // Rechten controleren en aantal nieuwe items ophalen

        $this->load->library('algemeen');
        $this->algemeen->cms();
        if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'contentmanager') redirect('cms/rechten');
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
        $this->load->model('blogs_model');
        $aantal_items = $this->blogs_model->getBlogsAantal();
        $per_pagina = 10;
        $aantal_paginas = ceil($aantal_items / $per_pagina);
        $huidige_pagina = $p;
        $blogs = $this->blogs_model->getBlogs($per_pagina, $huidige_pagina);
        $uitgelichte_blogs = $this->blogs_model->getBlogsUitgelicht();

        // Controleren of de paginanummer te hoog is
        if($p > 1 && sizeof($blogs) == 0) redirect('cms/blog');


        // PAGINA TONEN

        $this->data['blogs'] 			    = $blogs;
        $this->data['aantal_paginas'] 		= $aantal_paginas;
        $this->data['huidige_pagina']		= $huidige_pagina;
        $this->data['uitgelichte_blogs']    = $uitgelichte_blogs;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/blog';
        $this->load->view('cms/template', $pagina);
    }



    /* ============ */
    /* = BEKIJKEN = */
    /* ============ */

    public function detail($item_ID = null)
    {
        if($item_ID == null) redirect('cms/blog');

        $this->load->model('blogs_model');
        $this->load->model('media_model');

        $item = $this->blogs_model->getBlogByID($item_ID);
        if($item == null) redirect('cms/blog');

        $media = $this->media_model->getMediaByMediaID($item->media_ID);
        $meta_media = $this->media_model->getMediaByMediaID($item->meta_media_ID);


        // PAGINA LADEN

        $this->data['item'] = $item;
        $this->data['media'] = $media;
        $this->data['meta_media'] = $meta_media;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/blog_info';
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
        if($item_ID == null) redirect('cms/nieuws');
        $this->_toevoegen_wijzigen('wijzigen', $item_ID);
    }

    private function _toevoegen_wijzigen($actie, $item_ID = null)
    {
        $this->load->model('blogs_model');
        $this->load->model('media_model');

        $item_url 				= '';
        $item_titel 			= '';
        $item_bericht 			= '';
        $item_deelnemer		 	= '';
        $item_gepubliceerd 		= '';
        $item_uitgelicht        = '';
        $item_datum_dag			= '';
		$item_datum_maand		= '';
		$item_datum_jaar		= '';
        $media					= '';
        $media_tonen			= 'ja';
        $media_link				= '';
        $item_meta_title		= '';
        $item_meta_description	= '';
        $item_datum             = '';
        $meta_media             = '';

        $item_url_feedback 				= '';
        $item_titel_feedback 			= '';
        $item_bericht_feedback 			= '';
        $item_deelnemer_feedback 		= '';
        $item_gepubliceerd_feedback 	= '';
        $item_meta_title_feedback		= '';
        $item_meta_description_feedback	= '';
        $item_datum_feedback 		    = '';


        // FORMULIER VERZONDEN

        if(isset($_POST['item_titel']))
        {
            $fouten 				= 0;
            $item_url 				= trim($_POST['item_url']);
            $item_titel 			= trim($_POST['item_titel']);
            $item_bericht 			= trim($_POST['item_bericht']);
            $item_datum_dag 	    = trim($_POST['item_datum_dag']);
			$item_datum_maand 	    = trim($_POST['item_datum_maand']);
			$item_datum_jaar 	    = trim($_POST['item_datum_jaar']);
            $item_deelnemer 		= trim($_POST['item_deelnemer']);
            $item_media		 		= trim($_POST['item_media']);
            $media_ID		 		= isset($_POST['media_ID']) ? trim($_POST['media_ID']) : '';
            if(!empty($media_link)) {
                $media_link	= trim($_POST['media_link']);
            } else {
                $media_link = '';
            }
            $item_meta_title		= trim($_POST['item_meta_title']);
            $item_meta_description	= trim($_POST['item_meta_description']);
            $item_meta_media        = trim($_POST['item_media_uitgelicht']);

            if(isset($_POST['item_gepubliceerd'])) $item_gepubliceerd = $_POST['item_gepubliceerd'];
            if(isset($_POST['item_uitgelicht'])) $item_uitgelicht = $_POST['item_uitgelicht'];
            if(isset($_POST['media_tonen'])) $media_tonen = $_POST['media_tonen'];

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

            if(empty($item_bericht))
            {
                $fouten++;
                $item_bericht_feedback = 'Graag invullen';
            }

            if(empty($item_deelnemer))
            {
                $fouten++;
                $item_deelnemer_feedback = 'Graag invullen';
            }

            if(empty($item_gepubliceerd))
            {
                $fouten++;
                $item_gepubliceerd_feedback = 'Graag selecteren';
            }

            if(empty($item_datum_dag) || empty($item_datum_maand) || empty($item_datum_jaar))
			{
				$fouten++;
				$item_datum_feedback = 'Graag invullen';
			}

            if($fouten == 0)
            {
                // TOEVOEGEN / UPDATEN

                $data = array(
                    'blog_url' => $item_url,
                    'blog_titel' => $item_titel,
                    'blog_bericht' => $item_bericht,
                    'blog_deelnemer' => $item_deelnemer,
                    'blog_gepubliceerd' => $item_gepubliceerd,
                    'blog_uitgelicht' => $item_uitgelicht,
                    'blog_publicatiedatum' => $item_datum_jaar.'-'.$item_datum_maand.'-'.$item_datum_dag,
                    'media_ID' => str_replace(',', '', $item_media),
                    'meta_media_ID' => str_replace(',', '', $item_meta_media),
                    'media_tonen' => $media_tonen,
                    'media_link' => $media_link,
                    'meta_title' => $item_meta_title,
                    'meta_description' => $item_meta_description
                );

                if ($actie == 'toevoegen') {
                    $data['blog_datum'] = date("Y-m-d H:i:s");
                }

                if($actie == 'toevoegen') $q = $this->blogs_model->insertBlog($data);
                else $q = $this->blogs_model->updateBlog($item_ID, $data);

                if($q)
                {
                    if($actie == 'toevoegen') redirect('cms/blog');
                    else redirect('cms/blog/'.$item_ID);
                }
                else
                {
                    echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
                }
            }
        }

        if($actie == 'wijzigen')
        {
            $item = $this->blogs_model->getBlogByID($item_ID);
            if($item == null) redirect('cms/blog');

            $item_url 				= $item->blog_url;
            $item_titel 			= $item->blog_titel;
            $item_bericht 			= $item->blog_bericht;
            $item_deelnemer 		= $item->blog_deelnemer;
            $item_gepubliceerd 		= $item->blog_gepubliceerd;
            $item_uitgelicht        = $item->blog_uitgelicht;
            $item_publicatiedatum   = $item->blog_publicatiedatum;
            $item_datum 		    = $item->blog_datum;
            $item_meta_title		= $item->meta_title;
            $item_meta_description	= $item->meta_description;

            // MEDIA OPHALEN
            $datum = explode('-', $item_publicatiedatum);

            $item_datum_dag 		= $datum[2];
			$item_datum_maand 		= $datum[1];
			$item_datum_jaar 		= $datum[0];

            $media 					= $this->media_model->getMediaByMediaID($item->media_ID);
            $meta_media             = $this->media_model->getMediaByMediaID($item->meta_media_ID);
            $media_tonen		 	= $item->media_tonen;
            $media_link				= $item->media_link;
        }

        // PAGINA TONEN

        $this->data['actie'] = $actie;

        $this->data['item_ID'] 					= $item_ID;
        $this->data['item_url'] 				= $item_url;
        $this->data['item_titel'] 				= $item_titel;
        $this->data['item_datum'] 				= $item_datum;
        $this->data['item_bericht'] 			= $item_bericht;
        $this->data['item_deelnemer'] 			= $item_deelnemer;
        $this->data['item_datum_dag'] 		    = $item_datum_dag;
		$this->data['item_datum_maand'] 	    = $item_datum_maand;
		$this->data['item_datum_jaar'] 		    = $item_datum_jaar;
        $this->data['item_gepubliceerd'] 		= $item_gepubliceerd;
        $this->data['item_uitgelicht']          = $item_uitgelicht;
        $this->data['item_meta_title'] 			= $item_meta_title;
        $this->data['item_meta_description'] 	= $item_meta_description;
        $this->data['meta_media']               = $meta_media;

        $this->data['media']			 	= $media;
        $this->data['media_tonen'] 			= $media_tonen;
        $this->data['media_link'] 			= $media_link;

        $this->data['item_url_feedback'] 				= $item_url_feedback;
        $this->data['item_titel_feedback'] 				= $item_titel_feedback;
        $this->data['item_bericht_feedback'] 			= $item_bericht_feedback;
        $this->data['item_datum_feedback'] 			    = $item_datum_feedback;
        $this->data['item_deelnemer_feedback']	 		= $item_deelnemer_feedback;
        $this->data['item_gepubliceerd_feedback'] 		= $item_gepubliceerd_feedback;
        $this->data['item_meta_title_feedback'] 		= $item_meta_title_feedback;
        $this->data['item_meta_description_feedback'] 	= $item_meta_description_feedback;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/blog_wijzigen';
        $this->load->view('cms/template', $pagina);
    }



    /* =============== */
    /* = VERWIJDEREN = */
    /* =============== */

    public function verwijderen($item_ID = null, $bevestiging = null)
    {
        if($item_ID == null) redirect('cms/blog');

        $this->load->model('blogs_model');
        $item = $this->blogs_model->getBlogByID($item_ID);
        if($item == null) redirect('cms/blog');
        $this->data['item'] = $item;


        // ITEM VERWIJDEREN

        if($bevestiging == 'ja')
        {
            $q = $this->blogs_model->deleteBlog($item_ID);
            if($q) redirect('cms/blog');
            else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
        }


        // PAGINA TONEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/blog_verwijderen';
        $this->load->view('cms/template', $pagina);
    }
}