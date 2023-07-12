<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gegevens extends CI_Controller
{
	private $data = array();

	public function __construct()
	{
		parent::__construct();

		// Rechten controleren en aantal nieuwe items ophalen

		$this->load->library('algemeen');
		$this->algemeen->cms();
		if($this->session->userdata('beheerder_rechten') != 'admin') redirect('cms/rechten');
	}



	/* ============= */
	/* = OVERZICHT = */
	/* ============= */

	public function index()
	{
        $this->load->model('gebruikers_model');
        $demo = $this->gebruikers_model->getDemoGebruiker();
        $this->data['demo'] = $demo[0];

		$this->load->model('gegevens_model');
		$gegevens = $this->gegevens_model->getGegevens();
		$this->data['gegevens'] = $gegevens;

        $this->load->model('betaalmethodes_model');
        $betaal_methodes = $this->betaalmethodes_model->getMethodes();
        $this->data['betaal_methodes'] = $betaal_methodes;
		// PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/gegevens';
		$this->load->view('cms/template', $pagina);
	}



	/* ============ */
	/* = WIJZIGEN = */
	/* ============ */

	public function wijzigen()
	{
		$this->load->model('gegevens_model');
		$gegevens = $this->gegevens_model->getGegevens();
		$this->data['gegevens'] = $gegevens;

        $this->load->model('betaalmethodes_model');
        $betaal_methodes = $this->betaalmethodes_model->getMethodes();
        $this->data['betaal_methodes'] = $betaal_methodes;

		$feedback = '';

		if(isset($_POST['gegevens']) || isset($_POST['betaalmethodes']))
		{
			$fouten = 0;
            $betaal_methodes = (empty($_POST['betaalmethodes'])) ? null : $_POST['betaalmethodes'];
            $gegevens = (empty($_POST['gegevens'])) ? null : $_POST['gegevens'];;

			for($i = 1; $i <= sizeof($gegevens); $i++)
			{
				$gegeven = $gegevens[$i-1];

				if(empty($gegeven)) $fouten++;
			}

			if($fouten == 0)
			{
                if(!empty($gegevens)) {
                    for($i = 1; $i <= sizeof($gegevens); $i++)
                    {
                        $gegeven = $gegevens[$i-1];
                        $this->gegevens_model->updateGegeven($i, array('gegeven_waarde' => $gegeven));
                    }
                }

                if(!empty($betaal_methodes)) {
                    for($i = 1; $i <= sizeof($betaal_methodes); $i++)
                    {
                        $betaal_methode = $betaal_methodes[$i-1];
                        $this->betaalmethodes_model->updateMethodes($i, array('percentage' => $betaal_methode));
                    }
                }

				redirect('cms/gegevens');
			}
			else
			{
				$feedback = 'Alle velden zijn verplicht';
			}
		}

		$this->data['feedback'] = $feedback;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/gegevens_wijzigen';
		$this->load->view('cms/template', $pagina);
	}

    /* ========================= */
    /* = TOEVOEGEN EN WIJZIGEN = */
    /* ========================= */

    public function demo_wijzigen($item_ID = null)
    {
        if ($item_ID != null ) {
            $this->demo_toevoegen_wijzigen('wijzigen', $item_ID);
        } else {
            $this->demo_toevoegen_wijzigen('toevoegen');
        }
    }

    private function demo_toevoegen_wijzigen($actie, $item_ID = null)
    {
        $this->load->model('gebruikers_model');

        $item_status					= 'actief';
        $item_markering					= 'nee';
        $item_bedrijfsnaam 				= 'localhost';
        $item_voornaam 					= '';
        $item_tussenvoegsel 			= '';
        $item_achternaam 				= '';
        $item_geslacht 					= 'man';
        $item_geboortedatum_dag 		= '';
        $item_geboortedatum_maand 		= '';
        $item_geboortedatum_jaar 		= '';
        $item_adres 					= '';
        $item_postcode_cijfers 			= '';
        $item_postcode_letters 			= '';
        $item_plaats 					= '';
        $item_telefoonnummer 			= '';
        $item_mobiel 					= '';
        $item_emailadres 				= '';
        $item_notities 					= '';
        $item_instelling_anoniem 		= 'ja';
        $item_instelling_email_updates 	= 'nee';
        $item_wachtwoord                = '';

        $item_status_feedback						= '';
        $item_markering_feedback					= '';
        $item_bedrijfsnaam_feedback 				= '';
        $item_voornaam_feedback 					= '';
        $item_tussenvoegsel_feedback 				= '';
        $item_achternaam_feedback 					= '';
        $item_geslacht_feedback 					= '';
        $item_geboortedatum_feedback 				= '';
        $item_adres_feedback 						= '';
        $item_postcode_feedback 					= '';
        $item_plaats_feedback 						= '';
        $item_telefoonnummer_feedback 				= '';
        $item_mobiel_feedback 						= '';
        $item_emailadres_feedback 					= '';



        // FORMULIER VERZONDEN

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $fouten = 0;

            $item_bedrijfsnaam 				= trim($_POST['item_bedrijfsnaam']);
            $item_voornaam 					= ucfirst(trim($_POST['item_voornaam']));
            $item_tussenvoegsel 			= trim($_POST['item_tussenvoegsel']);
            $item_achternaam 				= ucfirst(trim($_POST['item_achternaam']));
            $item_geboortedatum_dag 		= trim($_POST['item_geboortedatum_dag']);
            $item_geboortedatum_maand 		= trim($_POST['item_geboortedatum_maand']);
            $item_geboortedatum_jaar 		= trim($_POST['item_geboortedatum_jaar']);
            $item_adres 					= trim($_POST['item_adres']);
            $item_postcode_cijfers 			= trim($_POST['item_postcode_cijfers']);
            $item_postcode_letters 			= trim($_POST['item_postcode_letters']);
            $item_plaats 					= trim($_POST['item_plaats']);
            $item_telefoonnummer 			= trim($_POST['item_telefoonnummer']);
            $item_mobiel 					= trim($_POST['item_mobiel']);
            $item_emailadres 				= trim($_POST['item_emailadres']);

            // Verplicht
            if(empty($item_voornaam))
            {
                $fouten++;
                $item_voornaam_feedback = 'Graag invullen';
            }

            if(empty($item_achternaam))
            {
                $fouten++;
                $item_achternaam_feedback = 'Graag invullen';
            }

            if(empty($item_emailadres))
            {
                $fouten++;
                $item_emailadres_feedback = 'Graag invullen';
            }
            else
            {
                if(!filter_var($item_emailadres, FILTER_VALIDATE_EMAIL))
                {
                    $fouten++;
                    $item_emailadres_feedback = 'Graag een geldig e-mailadres';
                }
                elseif($actie == 'toevoegen')
                {
                    // Controleren of e-mailadres al gebruikt wordt

                    $gebruiker = $this->gebruikers_model->checkEmailadres($item_emailadres);

                    if($gebruiker != null)
                    {
                        $fouten++;
                        $item_emailadres_feedback = 'E-mailadres al in gebruik';
                    }
                }
            }

            if($fouten == 0)
            {
                // TOEVOEGEN / UPDATEN

                $data = array(
                    'gebruiker_rechten' => 'demo',
                    'gebruiker_status' => $item_status,
                    'gebruiker_markering' => $item_markering,
                    'gebruiker_bedrijfsnaam' => $item_bedrijfsnaam,
                    'gebruiker_naam' => str_replace('  ', ' ', $item_voornaam.' '.$item_tussenvoegsel.' '.$item_achternaam),
                    'gebruiker_voornaam' => $item_voornaam,
                    'gebruiker_tussenvoegsel' => $item_tussenvoegsel,
                    'gebruiker_achternaam' => $item_achternaam,
                    'gebruiker_geslacht' => $item_geslacht,
                    'gebruiker_geboortedatum' => $item_geboortedatum_jaar.'-'.$item_geboortedatum_maand.'-'.$item_geboortedatum_dag,
                    'gebruiker_adres' => $item_adres,
                    'gebruiker_postcode' => $item_postcode_cijfers.' '.$item_postcode_letters,
                    'gebruiker_plaats' => $item_plaats,
                    'gebruiker_telefoonnummer' => $item_telefoonnummer,
                    'gebruiker_mobiel' => $item_mobiel,
                    'gebruiker_emailadres' => $item_emailadres,
                    'gebruiker_wachtwoord' => 'localhost',
                    'gebruiker_notities' => $item_notities,
                    'gebruiker_instelling_anoniem' => $item_instelling_anoniem,
                    'gebruiker_instelling_email_updates' => $item_instelling_email_updates
                );

                if($actie == 'toevoegen')
                {
                    $data['gebruiker_wachtwoord'] = sha1($item_wachtwoord);
                    $q = $this->gebruikers_model->insertDemo($data);
                }
                else
                {
                    $q = $this->gebruikers_model->updateDemo($item_ID, $data);
                }

                if($q)
                {
                    redirect('cms/gegevens/');
                }
                else
                {
                    echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
                }
            }
        }
        else
        {
            if($actie == 'wijzigen')
            {
                $item = $this->gebruikers_model->getGebruikerByID($item_ID);
                if($item == null) redirect('cms/deelnemers');

                $item_bedrijfsnaam 				= $item->gebruiker_bedrijfsnaam;
                $item_voornaam 					= $item->gebruiker_voornaam;
                $item_tussenvoegsel 			= $item->gebruiker_tussenvoegsel;
                $item_achternaam 				= $item->gebruiker_achternaam;
                $item_geslacht 					= $item->gebruiker_geslacht;
                $item_geboortedatum 			= explode('-', $item->gebruiker_geboortedatum);
                $item_geboortedatum_dag 		= $item_geboortedatum[2];
                $item_geboortedatum_maand 		= $item_geboortedatum[1];
                $item_geboortedatum_jaar 		= $item_geboortedatum[0];
                $item_adres 					= $item->gebruiker_adres;

                $item_postcode 					= str_replace(' ', '', $item->gebruiker_postcode);

                if(strlen($item_postcode) == 6)
                {
                    $item_postcode_cijfers = substr($item_postcode, 0, 4);
                    $item_postcode_letters = strtoupper(substr($item_postcode, 4, 2));
                }
                else
                {
                    $item_postcode_cijfers = '';
                    $item_postcode_letters = '';
                }


                $item_plaats 					= $item->gebruiker_plaats;
                $item_telefoonnummer 			= $item->gebruiker_telefoonnummer;
                $item_mobiel 					= $item->gebruiker_mobiel;
                $item_emailadres 				= $item->gebruiker_emailadres;
            }
        }


        // PAGINA TONEN

        $this->data['actie'] = $actie;

        $this->data['item_ID'] 							= $item_ID;
        $this->data['item_bedrijfsnaam'] 				= $item_bedrijfsnaam;
        $this->data['item_voornaam'] 					= $item_voornaam;
        $this->data['item_tussenvoegsel'] 				= $item_tussenvoegsel;
        $this->data['item_achternaam'] 					= $item_achternaam;
        $this->data['item_geslacht'] 					= $item_geslacht;
        $this->data['item_geboortedatum_dag'] 			= $item_geboortedatum_dag;
        $this->data['item_geboortedatum_maand'] 		= $item_geboortedatum_maand;
        $this->data['item_geboortedatum_jaar'] 			= $item_geboortedatum_jaar;
        $this->data['item_adres'] 						= $item_adres;
        $this->data['item_postcode_cijfers'] 			= $item_postcode_cijfers;
        $this->data['item_postcode_letters'] 			= $item_postcode_letters;
        $this->data['item_plaats'] 						= $item_plaats;
        $this->data['item_telefoonnummer'] 				= $item_telefoonnummer;
        $this->data['item_mobiel'] 						= $item_mobiel;
        $this->data['item_emailadres'] 					= $item_emailadres;

        $this->data['item_bedrijfsnaam_feedback'] 				= $item_bedrijfsnaam_feedback;
        $this->data['item_voornaam_feedback'] 					= $item_voornaam_feedback;
        $this->data['item_tussenvoegsel_feedback'] 				= $item_tussenvoegsel_feedback;
        $this->data['item_achternaam_feedback'] 				= $item_achternaam_feedback;
        $this->data['item_geslacht_feedback'] 					= $item_geslacht_feedback;
        $this->data['item_geboortedatum_feedback'] 				= $item_geboortedatum_feedback;
        $this->data['item_adres_feedback'] 						= $item_adres_feedback;
        $this->data['item_postcode_feedback'] 					= $item_postcode_feedback;
        $this->data['item_plaats_feedback'] 					= $item_plaats_feedback;
        $this->data['item_telefoonnummer_feedback'] 			= $item_telefoonnummer_feedback;
        $this->data['item_mobiel_feedback'] 					= $item_mobiel_feedback;
        $this->data['item_emailadres_feedback'] 				= $item_emailadres_feedback;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/demo_account_wijzigen';
        $this->load->view('cms/template', $pagina);
    }
}