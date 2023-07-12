<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CursistenController extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		// Rechten controleren
		if(!$this->session->userdata('gebruiker_rechten')) redirect('');

		// Models laden
		$this->load->model('berichten_model');
		$this->load->model('workshops_model');
		$this->load->model('kennismakingsworkshop_model');

		// Gebruiker ID ophalen uit de sessie
		$gebruiker_ID = $this->session->userdata('gebruiker_ID');;

		// Aantal nieuwe berichten opslaan in de sessie
		$nieuwe_berichten = $this->berichten_model->getAantalNieuweBerichtenByGebruikerID($gebruiker_ID);
		$this->session->set_userdata('nieuwe_berichten', $nieuwe_berichten);

		// Aantal producten dat gekocht kan worden opslaan in de sessie
		$aantal_producten = count((array) $this->workshops_model->getWorkshopsGevolgdProductenAantalByGebruikerID($gebruiker_ID));
		$this->session->set_userdata('ws_gevolgd_producten_aantal', $aantal_producten);

		// Libraries laden
		$this->load->library('algemeen');

		// Gegevens voor de footer
		$this->data['gegevens'] = $this->algemeen->gegevens();
	}
}
