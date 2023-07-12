<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wachtwoord extends CI_Controller
{
	private $data = array();
	
	public function __construct()
	{
		parent::__construct();
		
		// Inloggen
		
		$this->load->library('algemeen');
		$this->algemeen->inloggen();
		$this->data['gegevens'] = $this->algemeen->gegevens();

		$gegevens = $this->algemeen->gegevens();
			
		if(!empty($gegevens)) {
			foreach($gegevens as $gegeven) {
				if($gegeven->gegeven_naam == 'onderhoud publieke site') {
					if ($gegeven->gegeven_waarde == 'ja') {
						redirect('onderhoud');
					}
				}
			}
		}
	}
	
	public function index()
	{
		$gebruiker_emailadres = '';
		$gebruiker_emailadres_feedback = '';
		
		if(isset($_POST['gebruiker_emailadres']))
		{
			$gebruiker_emailadres = trim($this->input->post('gebruiker_emailadres'));
			
			if(!empty($gebruiker_emailadres))
			{
				if(filter_var($gebruiker_emailadres, FILTER_VALIDATE_EMAIL))
				{
					$this->load->model('gebruikers_model');
					$gebruiker = $this->gebruikers_model->checkEmailadres($gebruiker_emailadres);
					
					if($gebruiker != null)
					{
						if(!$this->session->userdata('wachtwoord_gebruiker'))
						{
							// Nieuw wachtwoord genereren
							
							$gebruiker_wachtwoord = substr(str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
							
							// Nieuw wachtwoord opslaan
							
							$data = array('gebruiker_wachtwoord' => sha1($gebruiker_wachtwoord));
							
							$updaten = $this->gebruikers_model->updateGebruiker($gebruiker->gebruiker_ID, $data);
							
							if($updaten)
							{
								$this->session->set_userdata('wachtwoord_gebruiker', $gebruiker_wachtwoord);
							}	
						}
						else
						{
							$gebruiker_wachtwoord = $this->session->userdata('wachtwoord_gebruiker');
						}
						
						if(!$this->session->userdata('wachtwoord_verzonden'))
						{
							$this->_email_wachtwoord($gebruiker, $gebruiker_wachtwoord);
						}
					}
					else
					{
						$gebruiker_emailadres_feedback = 'Dit e-mailadres is niet bij ons bekend';
					}
				}
				else
				{
					$gebruiker_emailadres_feedback = 'Graag een geldig e-mailadres invullen';
				}
			}
			else
			{
				$gebruiker_emailadres_feedback = 'Graag invullen';
			}
		}
		
		$this->data['gebruiker_emailadres'] = $gebruiker_emailadres;
		$this->data['gebruiker_emailadres_feedback'] = $gebruiker_emailadres_feedback;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'wachtwoord';
		$this->load->view('template', $pagina);
	}
	
	private function _email_wachtwoord($gebruiker, $wachtwoord)
	{
		// GEBRUIKER E-MAILEN
		
		$email_titel = '';
		$email_tekst = '';
		$email_bericht = '';
		
		$email_van_emailadres = 'info@localhost';
		$email_van_naam = 'localhost';
		$email_aan_emailadres = $gebruiker->gebruiker_emailadres;
		$email_aan_naam = $gebruiker->gebruiker_naam;
		
		// BERICHT OPSTELLEN
		
		$email_titel = 'Nieuw wachtwoord';
		$email_tekst = 'Er is een nieuw wachtwoord voor je aangemaakt:<br />'.$wachtwoord;
			
		$email_bericht = '
			<h1>'.$email_titel.'</h1>
			<p>Beste '.$gebruiker->gebruiker_voornaam.',</p>
			<p>'.$email_tekst.'</p>
			<p>Groeten,<br />
			localhost</p>';
		
		
		// E-MAIL
		
		$email = array(
			'html' => $email_bericht,
			'subject' => $email_titel,
		    'from_email' => $email_van_emailadres,
		    'from_name' => $email_van_naam,
		    'to' => array(
		    	array(
		    		'email' => $email_aan_emailadres,
		    		'name' => $email_aan_naam,
		    		'type' => 'to'
				)
			),
		    'headers' => array('Reply-To' => $email_van_emailadres),
		    'track_opens' => true,
		    'track_clicks' => true,
		    'auto_text' => true
		);
		
		
		// E-MAIL VERZENDEN VIA MANDRILL
		
		$this->load->helper('mandrill');
		// $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test		
		$mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
		
		$feedback = $mandrill->messages->send($email);
		
		if($feedback[0]['status'] == 'sent')
		{
			$this->session->set_userdata('wachtwoord_verzonden', true);
		}
		else
		{
			echo 'Er kon geen e-mail worden verzonden';
		}
	}
}