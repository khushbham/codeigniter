<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Onderhoud extends CI_Controller
{
	private $data = array();
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('algemeen');
		$this->algemeen->inloggen();
		$this->data['gegevens'] = $this->algemeen->gegevens();
	}
	
	public function index()
	{
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'onderhoud';
		$this->load->view('template', $pagina);
	}
}