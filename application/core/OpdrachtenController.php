<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OpdrachtenController extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		// Rechten controleren
		if((!$this->session->userdata('beheerder_ID')) && !$this->session->userdata('gebruiker_ID')) redirect('');

		// Models laden
		$this->load->model('berichten_model');
		$this->load->model('opdrachten_model');

		// Gebruiker ID ophalen uit de sessie
		$gebruiker_ID = $this->session->userdata('gebruiker_ID');;

		// Aantal nieuwe berichten opslaan in de sessie
		$nieuwe_berichten = $this->berichten_model->getAantalNieuweBerichtenByGebruikerID($gebruiker_ID);
		$this->session->set_userdata('nieuwe_berichten', $nieuwe_berichten);

		// Libraries laden
		$this->load->library('algemeen');

		// Gegevens voor de footer
		$this->data['gegevens'] = $this->algemeen->gegevens();
	}
}
