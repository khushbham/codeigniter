<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vragen extends CursistenController
{
	public function __construct()
	{
		parent::__construct();
	}
	
	
	
	/* ============= */
	/* = OVERZICHT = */
	/* ============= */
	
	public function index()
	{
		$this->load->model('vragen_model');
		$vragen = $this->vragen_model->getVragen('cursistenmodule');
		$this->data['vragen'] = $vragen;
		
		// PAGINA TONEN
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cursistenmodule/vragen';
		$this->load->view('cursistenmodule/template', $pagina);
	}
}