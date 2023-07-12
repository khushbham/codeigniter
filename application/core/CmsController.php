<?php

class CmsController extends MY_Controller
{
	public function __construct($check_beheerder_rechten = false)
	{
		parent::__construct();

		// Libraries laden
		$this->load->library('algemeen');

		// Aantal nieuwe items ophalen
		$this->algemeen->cms();
		
		// Rechten controleren
		if($check_beheerder_rechten && $this->session->userdata('beheerder_rechten') != 'admin')
		{
			redirect('cms/rechten');
		}
	}
}
