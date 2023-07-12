<?php

class SiteController extends MY_Controller
{
	public function __construct($inloggen = true)
	{
		parent::__construct();

		if($inloggen)
		{
			// Libraries laden
			$this->load->library('algemeen');

			// Inloggen afhandelen indien het formulier bovenaan de pagina verzonden wordt
			$this->algemeen->inloggen();

			// Gegevens voor de footer
			$this->data['gegevens'] = $this->algemeen->gegevens();
		}
	}
}
