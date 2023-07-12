<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	private $data = array();

	public function __construct()
	{
		parent::__construct();

		// Rechten controleren en aantal nieuwe items ophalen

		$this->load->library('algemeen');
		$this->algemeen->opdrachten();

		$this->data['gegevens'] = $this->algemeen->gegevens();

		$gegevens = $this->algemeen->gegevens();
	}

	public function index()
	{
		/////////////////
		// Load models //
		/////////////////

		$this->load->model('gebruikers_model');
		$this->load->model('uploads_model');
		$this->load->model('aanmeldingen_model');
		$this->load->model('opdrachten_model');

		///////////////
		// Goedendag //
		///////////////

		$uren = date('H');
		if($uren >= 0 && $uren < 12) $goedendag = 'Goedemorgen';
		elseif($uren >= 12 && $uren < 18) $goedendag = 'Goedemiddag';
		else $goedendag = 'Goedenavond';
		$this->data['goedendag'] = $goedendag;

		///////////////////////////
		// Ingestuurde opdrachten //
		///////////////////////////
		if ($this->session->userdata('beheerder_rechten') == 'admin') {
            $ingestuurde_opdrachten = $this->opdrachten_model->getVoltooideOpdrachten();
            $this->data['ingestuurde_opdrachten'] = $ingestuurde_opdrachten;


            /////////////////////
            // Recent ingelogd //
            /////////////////////

            $recent_ingelogd = $this->gebruikers_model->getGebruikersRecentIngelogd('deelnemer', 10);
            $this->data['recent_ingelogd'] = $recent_ingelogd;
		}

		///////////////////////////
		// Voltooide opdrachten //
		///////////////////////////

            $voltooide_opdrachten = $this->opdrachten_model->getVoltooideOpdrachtenByGebruikerID($this->session->userdata('gebruiker_ID'));
            $this->data['voltooide_opdrachten'] = $voltooide_opdrachten;


		//////////////////
		// Pagina tonen //
		//////////////////

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'opdrachten/dashboard';
		$this->load->view('opdrachten/template', $pagina);
	}

	public function inloggen()
	{
		$this->load->model('gebruikers_model');
		$gebruiker = $this->gebruikers_model->getGebruikerByID(4293);
		if($gebruiker == null) redirect('cms/deelnemers');

		$userdata = array(
			'gebruiker_ID' => $gebruiker->gebruiker_ID,
			'gebruiker_rechten' => $gebruiker->gebruiker_rechten,
			'gebruiker_voornaam' => $gebruiker->gebruiker_voornaam,
			'gebruiker_naam' => $gebruiker->gebruiker_voornaam.' '.$gebruiker->gebruiker_tussenvoegsel.' '.$gebruiker->gebruiker_achternaam,
			'gebruiker_profiel_foto' => $gebruiker->gebruiker_profiel_foto
		);

		$this->session->set_userdata($userdata);

		redirect('opdrachten');
	}
}