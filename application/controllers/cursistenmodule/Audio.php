<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Audio extends CursistenController
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
		$this->load->view('cursistenmodule/audio');
	}
}