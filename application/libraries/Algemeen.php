<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Algemeen
{
	//////////////
	// INLOGGEN //
	//////////////

    public function inloggen()
    {
		$CI =& get_instance();
		// Variabelen initialiseren

		$inloggen_emailadres 	= '';
		$inloggen_wachtwoord 	= '';
		$inloggen_token 		= '';
		$inloggen_feedback 		= '';



		// Controleren of de gebruiker wil inloggen

		if(isset($_POST['inloggen']))
		{
			// Verzonden gegevens ophalen

			$inloggen_emailadres 	= trim($_POST['inloggen_emailadres']);
			$inloggen_wachtwoord 	= trim($_POST['inloggen_wachtwoord']);

			// Controleren of gegevens gevuld zijn

			if(!empty($inloggen_emailadres) && !empty($inloggen_wachtwoord))
			{
				// Controleren of het e-mailadres geldig is

				if(filter_var($inloggen_emailadres, FILTER_VALIDATE_EMAIL))
				{
					// Gegevens controleren in de database

					$CI->load->model('gebruikers_model');
                    $CI->load->model('workshops_model');
                    $CI->load->model('kennismakingsworkshop_model');

					$gebruiker = $CI->gebruikers_model->checkGebruiker($inloggen_emailadres, sha1($inloggen_wachtwoord));

					if($gebruiker != null)
					{
						unset($_SESSION['inloggen_token']);
						unset($_SESSION['inloggen_emailadres']);
						unset($_SESSION['inloggen_feedback']);

						if($gebruiker->gebruiker_rechten == 'deelnemer' || $gebruiker->gebruiker_rechten == 'kandidaat' || $gebruiker->gebruiker_rechten == 'test')
						{
							$CI->load->model('gegevens_model');
							$gegevens = $CI->gegevens_model->getGegevens();

							$onderhoud_cursistenmodule = false;

							if(!empty($gegevens)) {
								foreach($gegevens as $gegeven) {
									if($gegeven->gegeven_naam == 'onderhoud cursistenmodule') {
										if ($gegeven->gegeven_waarde == 'ja') {
											$onderhoud_cursistenmodule = true;
										}
									}
								}
							}

							if(!$onderhoud_cursistenmodule) {
								$userdata = array(
									'gebruiker_ID' => $gebruiker->gebruiker_ID,
									'gebruiker_geslacht' => $gebruiker->gebruiker_geslacht,
									'gebruiker_geboortedatum' => $gebruiker->gebruiker_geboortedatum,
									'gebruiker_rechten' => $gebruiker->gebruiker_rechten,
									'gebruiker_voornaam' => $gebruiker->gebruiker_voornaam,
									'gebruiker_naam' => $gebruiker->gebruiker_voornaam.' '.$gebruiker->gebruiker_tussenvoegsel.' '.$gebruiker->gebruiker_achternaam,
									'gebruiker_profiel_foto' => $gebruiker->gebruiker_profiel_foto
								);

								$CI->session->set_userdata($userdata);

								$workshops = $CI->workshops_model->getWorkshopsByGebruikerID($gebruiker->gebruiker_ID);
								$kennismakingsworkshops = $CI->kennismakingsworkshop_model->getKennismakingsworkshopsByGebruikerID($gebruiker->gebruiker_ID);
								$vandaag = new DateTime();
								if(!empty($kennismakingsworkshops)) {
								$kennismakingsworkshop_datum = date('Y/m/d', strtotime($kennismakingsworkshops[0]->kennismakingsworkshop_datum));
								}
								if (empty($workshops) && !empty($kennismakingsworkshops)) {
									if ($kennismakingsworkshop_datum > $vandaag->format('Y/m/d')) {
										$CI->session->set_userdata('kennismakingsworkshop_acc', true);
									} else {
										$CI->session->set_userdata('kennismakingsworkshop_acc', false);
									}
								} else {
									$CI->session->set_userdata('kennismakingsworkshop_acc', false);
								}

								redirect('cursistenmodule');
							} else {


								redirect('onderhoud');
							}
						}
						else
						{
                            if ($gebruiker->gebruiker_status != 'inactief' && $gebruiker->gebruiker_rechten != 'demo') {
                                $userdata = array(
                                    'beheerder_ID' => $gebruiker->gebruiker_ID,
                                    'beheerder_rechten' => $gebruiker->gebruiker_rechten,
                                    'beheerder_voornaam' => $gebruiker->gebruiker_voornaam,
                                    'beheerder_naam' => $gebruiker->gebruiker_voornaam . ' ' . $gebruiker->gebruiker_tussenvoegsel . ' ' . $gebruiker->gebruiker_achternaam
                                );

                                $CI->session->set_userdata($userdata);

                                redirect('cms');
                            } else {
                                $CI->load->model('gegevens_model');
                                $gegevens = $CI->gegevens_model->getGegevens();

                                $demo_account = false;

                                if(!empty($gegevens)) {
                                    foreach($gegevens as $gegeven) {
                                        if($gegeven->gegeven_naam == 'Demo account actief') {
                                            if ($gegeven->gegeven_waarde == 'ja') {
                                                $demo_account = true;
                                            }
                                        }
                                    }
                                }

                                if ($gebruiker->gebruiker_rechten == 'demo' && $demo_account == true) {
                                    $userdata = array(
                                        'gebruiker_ID' => $gebruiker->gebruiker_ID,
                                        'gebruiker_rechten' => $gebruiker->gebruiker_rechten,
                                        'gebruiker_voornaam' => $gebruiker->gebruiker_voornaam,
                                        'gebruiker_naam' => $gebruiker->gebruiker_voornaam.' '.$gebruiker->gebruiker_tussenvoegsel.' '.$gebruiker->gebruiker_achternaam,
                                        'demo' => true
                                    );

                                    $CI->session->set_userdata($userdata);

                                    redirect('cursistenmodule');
                                } else {
                                    $inloggen_feedback = 'Geblokkeerd';
                                }
                            }
						}
					}
					else
					{
						$inloggen_feedback = 'Gegevens onbekend';
					}
				}
				else
				{
					$inloggen_feedback = 'Onjuist e-mailadres';
				}
			}
			else
			{
				$inloggen_feedback = 'Graag invullen';
			}
		}


		/*
		// Controleren of de gebruiker wil inloggen

		if(isset($_POST['inloggen']) && isset($_SESSION['inloggen_token']))
		{
			// Controleren of de token hetzelfde is als in de sessie

			if($_POST['inloggen_token'] == $_SESSION['inloggen_token'])
			{
				// Verzonden gegevens ophalen

				$inloggen_emailadres 	= trim($_POST['inloggen_emailadres']);
				$inloggen_wachtwoord 	= trim($_POST['inloggen_wachtwoord']);

				// Controleren of gegevens gevuld zijn

				if(!empty($inloggen_emailadres) && !empty($inloggen_wachtwoord))
				{
					// Controleren of het e-mailadres geldig is

					if(filter_var($inloggen_emailadres, FILTER_VALIDATE_EMAIL))
					{
						// Gegevens controleren in de database

						$CI->load->model('gebruikers_model');

						$gebruiker = $CI->gebruikers_model->checkGebruiker($inloggen_emailadres, sha1($inloggen_wachtwoord));

						if($gebruiker != null)
						{
							unset($_SESSION['inloggen_token']);
							unset($_SESSION['inloggen_emailadres']);
							unset($_SESSION['inloggen_feedback']);

							if($gebruiker->gebruiker_rechten == 'deelnemer')
							{
								$userdata = array(
									'gebruiker_ID' => $gebruiker->gebruiker_ID,
									'gebruiker_rechten' => $gebruiker->gebruiker_rechten,
									'gebruiker_voornaam' => $gebruiker->gebruiker_voornaam,
									'gebruiker_naam' => $gebruiker->gebruiker_voornaam.' '.$gebruiker->gebruiker_tussenvoegsel.' '.$gebruiker->gebruiker_achternaam
								);

								$CI->session->set_userdata($userdata);

								redirect('cursistenmodule');
							}
							else
							{
								$userdata = array(
									'beheerder_ID' => $gebruiker->gebruiker_ID,
									'beheerder_rechten' => $gebruiker->gebruiker_rechten,
									'beheerder_voornaam' => $gebruiker->gebruiker_voornaam,
									'beheerder_naam' => $gebruiker->gebruiker_voornaam.' '.$gebruiker->gebruiker_tussenvoegsel.' '.$gebruiker->gebruiker_achternaam
								);

								$CI->session->set_userdata($userdata);

								redirect('cms');
							}
						}
						else
						{
							$inloggen_feedback = 'Gegevens onbekend';
						}
					}
					else
					{
						$inloggen_feedback = 'Onjuist e-mailadres';
					}
				}
				else
				{
					$inloggen_feedback = 'Graag invullen';
				}
			}
			else
			{
				$inloggen_feedback = 'Token niet geldig';
			}
		}
		*/

		// Token genereren en gegevens opslaan in sessie

		$inloggen_token = md5(uniqid(microtime(), true));

		$_SESSION['inloggen_token'] 		= $inloggen_token;
		$_SESSION['inloggen_emailadres'] 	= $inloggen_emailadres;
		$_SESSION['inloggen_feedback'] 		= $inloggen_feedback;
    }



	//////////////
	// GEGEVENS //
	//////////////

    public function gegevens()
    {
    	$CI =& get_instance();

    	$CI->load->model('gegevens_model');
    	return $CI->gegevens_model->getGegevens();
    }



	/////////
	// CMS //
	/////////

    public function cms()
    {
		$CI =& get_instance();

		// Rechten controleren

		if(!$CI->session->userdata('beheerder_rechten')) redirect('');

		// Models laden

		$CI->load->model('aanmeldingen_model');
		$CI->load->model('bestellingen_model');
		$CI->load->model('huiswerk_model');
		$CI->load->model('berichten_model');
		$CI->load->model('docenten_model');

		// Nieuwe afspraken ophalen

		$nieuwe_afspraken = $CI->aanmeldingen_model->getAantalAfspraken();
		$CI->session->set_userdata('nieuwe_afspraken', $nieuwe_afspraken);

		// Nieuwe bestellingen ophalen

		$nieuwe_bestellingen = $CI->bestellingen_model->getAantalBestellingenVerzenden();
		$CI->session->set_userdata('nieuwe_bestellingen', $nieuwe_bestellingen);

		// Nieuw huiswerk ophalen

        if($CI->session->userdata('beheerder_rechten') == 'admin') {
            $nieuw_huiswerk = $CI->huiswerk_model->getAantalResultatenOnbekend();
            $CI->session->set_userdata('nieuw_huiswerk', $nieuw_huiswerk);
        } else {
            $nieuw_huiswerk = $CI->huiswerk_model->getAantalResultatenOnbekendDocent($CI->session->userdata('beheerder_ID'));
            $CI->session->set_userdata('nieuw_huiswerk', $nieuw_huiswerk);
        }

		// Nieuwe berichten ophalen

		$nieuwe_berichten = $CI->berichten_model->getAantalNieuweBerichtenByGebruikerID($CI->session->userdata('beheerder_ID'));
		$CI->session->set_userdata('nieuwe_berichten', $nieuwe_berichten);
	}



	////////////////
	// OPDRACHTEN //
	////////////////

    public function opdrachten()
    {
		$CI =& get_instance();


		// Models laden

		$CI->load->model('opdrachten_model');
		$CI->load->model('huiswerk_model');
		$CI->load->model('berichten_model');

		// Nieuw huiswerk ophalen

        if($CI->session->userdata('beheerder_rechten') == 'admin') {
            $nieuw_huiswerk = $CI->huiswerk_model->getAantalResultatenOnbekend();
            $CI->session->set_userdata('nieuw_huiswerk', $nieuw_huiswerk);
        }
    }
}