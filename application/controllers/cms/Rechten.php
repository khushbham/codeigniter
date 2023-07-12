<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rechten extends CI_Controller
{
	private $data = array();
	
	public function __construct()
	{
		parent::__construct();
		
		// Rechten controleren en aantal nieuwe items ophalen
		
		$this->load->library('algemeen');
		$this->algemeen->cms();
	}
	
	public function index()
	{
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/rechten';
		$this->load->view('cms/template', $pagina);
	}
}