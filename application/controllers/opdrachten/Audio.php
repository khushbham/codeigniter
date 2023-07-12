<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Audio extends OpdrachtenController
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
		$this->load->view('opdrachten/audio');
	}
}