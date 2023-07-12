<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nietgevonden extends CI_Controller
{
	private $data = array();
	
	public function __construct()
	{
		parent::__construct();
		
		// Inloggen
		
		$this->load->library('algemeen');
		$this->algemeen->inloggen();
		$this->data['gegevens'] = $this->algemeen->gegevens();
	}
	
	public function index()
	{
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'nietgevonden';
		$this->load->view('template', $pagina);
	}
}