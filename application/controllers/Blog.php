<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends CI_Controller
{
    private $data = array();

    public function __construct()
    {
        parent::__construct();

        // Inloggen

        $this->load->library('algemeen');
        $this->algemeen->inloggen();
        $this->data['gegevens'] = $this->algemeen->gegevens();

        $gegevens = $this->algemeen->gegevens();
			
		if(!empty($gegevens)) {
			foreach($gegevens as $gegeven) {
				if($gegeven->gegeven_naam == 'onderhoud publieke site') {
					if ($gegeven->gegeven_waarde == 'ja') {
						redirect('onderhoud');
					}
				}
			}
		}
    }

    public function index()
    {
        $this->pagina();
    }

    public function pagina($p = 1)
    {
        $this->load->model('blogs_model');
        $aantal_items = $this->blogs_model->getblogsGepubliceerdAantal();
        $per_pagina = 5;
        $aantal_paginas = ceil($aantal_items / $per_pagina);
        $huidige_pagina = $p;
        $blogs = $this->blogs_model->getblogsGepubliceerd($per_pagina, $huidige_pagina);
        $uitgelichte_blogs = $this->blogs_model->getBlogsUitgelichtGepubliceerd();

        // PAGINA TONEN

        $this->data['blogs'] 			    = $blogs;
        $this->data['uitgelichte_blogs']    = $uitgelichte_blogs;
        $this->data['aantal_paginas'] 		= $aantal_paginas;
        $this->data['huidige_pagina']		= $huidige_pagina;

        $this->data['meta_title'] = 'Blog - localhost';
        $this->data['meta_description'] = '';

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'blog';
        $this->load->view('template', $pagina);
    }

    public function blog_info($url = null)
    {
        $this->load->model('blogs_model');
        $this->load->model('media_model');
        $blog = $this->blogs_model->getblogByURL($url);
        $this->data['blog'] = $blog;
        if(!$blog) redirect('blog');


        // PAGINA TONEN

        $this->data['og_type'] = 'blog';
        $this->data['meta_title'] = $blog->blog_titel.' - blog - localhost';
        $this->data['meta_description'] = filter_var(substr($blog->blog_bericht, 0, 310), FILTER_SANITIZE_STRIPPED);

        if(!empty($blog->meta_title)) $this->data['meta_title'] = $blog->meta_title;
        if(!empty($blog->meta_description)) $this->data['meta_description'] = $blog->meta_description;

        $meta_media = $this->media_model->getMediaByMediaID($blog->meta_media_ID);
        if(sizeof($meta_media) > 0) $this->data['og_image'] = base_url('/media/afbeeldingen/origineel/'.$meta_media[0]->media_src);

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'blog_info';
        $this->load->view('template', $pagina);
    }
}