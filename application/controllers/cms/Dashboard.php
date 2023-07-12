<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
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
		/////////////////
		// Load models //
		/////////////////
		
		$this->load->model('gebruikers_model');
		$this->load->model('huiswerk_model');
		$this->load->model('aanmeldingen_model');
		$this->load->model('bestellingen_model');
		$this->load->model('groepen_model');
		
		///////////////
		// Goedendag //
		///////////////
		
		$uren = date('H');
		if($uren >= 0 && $uren < 12) $goedendag = 'Goedemorgen';
		elseif($uren >= 12 && $uren < 18) $goedendag = 'Goedemiddag';
		else $goedendag = 'Goedenavond';
		$this->data['goedendag'] = $goedendag;
		
		$this->load->model('blogs_model');
        $blogs = $this->blogs_model->getBlogs(5, 1);
		$this->data['blogs'] = $blogs;

		/////////////////////////
		// Ingestuurd huiswerk //
		/////////////////////////
		if ($this->session->userdata('beheerder_rechten') != 'docent') {
            $ingestuurd_huiswerk = $this->huiswerk_model->getResultatenOnbekend();
            $this->data['ingestuurd_huiswerk'] = $ingestuurd_huiswerk;


            ////////////////////
            // Afspraak maken //
            ////////////////////

            $afspraak_maken = $this->aanmeldingen_model->getAanmeldingenAfspraken();
            $this->data['afspraak_maken'] = $afspraak_maken;


            ////////////////////////
            // Geplande afspraken //
            ////////////////////////

            $geplande_afspraken = $this->aanmeldingen_model->getAfspraken();
            $this->data['geplande_afspraken'] = $geplande_afspraken;


            //////////////////
            // Aanmeldingen //
            //////////////////

            $aanmeldingen = $this->aanmeldingen_model->getAanmeldingen(10);
            $this->data['aanmeldingen'] = $aanmeldingen;


            //////////////////
            // Bestellingen //
            //////////////////

            $bestellingen_verzenden = $this->bestellingen_model->getBestellingenVerzenden();
            $this->data['bestellingen_verzenden'] = $bestellingen_verzenden;


            /////////////////////
            // Recent ingelogd //
            /////////////////////

            $recent_ingelogd = $this->gebruikers_model->getGebruikersRecentIngelogd('deelnemer', 10);
            $this->data['recent_ingelogd'] = $recent_ingelogd;
        } else {
            $docent = $this->session->userdata('beheerder_ID');
            $ingestuurd_huiswerk = $this->huiswerk_model->getResultatenOnbekendDocent($docent);
            $this->data['ingestuurd_huiswerk'] = $ingestuurd_huiswerk;
		}

		$lessen = $this->groepen_model->getGroepenLessenByDocentGebruikerID($this->session->userdata('beheerder_ID'));
		$this->data['lessen'] = $lessen;

		//////////////////
		// Pagina tonen //
		//////////////////
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/dashboard';
		$this->load->view('cms/template', $pagina);
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
		
		redirect('cursistenmodule/lessen');
	}
}