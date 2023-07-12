<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bestellen extends CursistenController
{
    // DATA
    private $producten;
    private $systeem_kortingen; // kortingen uitgereikt door het systeem, bijv. aanmelden binnen een X aantal dagen na een kennismakingsworkshop

	// VELDEN
	
	private $bestellen_ID					= '';
	private $bestellen_bedrijfsnaam 		= '';
	private $bestellen_voornaam 			= '';
	private $bestellen_tussenvoegsel 		= '';
	private $bestellen_achternaam 			= '';
	private $bestellen_geslacht 			= '';
	private $bestellen_geboortedatum_dag 	= '';
	private $bestellen_geboortedatum_maand 	= '';
	private $bestellen_geboortedatum_jaar 	= '';
	private $bestellen_adres 				= '';
	private $bestellen_postcode 			= '';
	private $bestellen_plaats 				= '';
	private $bestellen_telefoon 			= '';
	private $bestellen_mobiel 				= '';
	private $bestellen_emailadres 			= '';
    private $bestellen_kortingscode 		= '';
    private $bestellen_kortingscode_feedback= '';

	private $bestellen_producten			= '';
	private $bestellen_afleveren_adres 		= '';
	private $bestellen_afleveren_postcode 	= '';
	private $bestellen_afleveren_plaats 	= '';
	private $in3_aan                  = 0;
	
	
	// FEEDBACK
			
	private $bestellen_afleveren_adres_feedback 		= '';
	private $bestellen_afleveren_postcode_feedback 		= '';
	private $bestellen_afleveren_plaats_feedback 		= '';



	public function __construct()
	{
		parent::__construct();
        require_once(APPPATH.'libraries/vendor/autoload.php');

    //    Paynl\Config::setApiToken('d9b947ac5e523c975f3e727988467fd01080d954');
    //    Paynl\Config::setServiceId('SL-2635-1031');

        Paynl\Config::setApiToken('2719c9cefc49c2699e193afa61db758aca8c504d');
		Paynl\Config::setServiceId('SL-2836-8790');

        if ($this->session->userdata('demo') == true) {
            redirect('cursistenmodule/producten');
        }
	}
	
	
	
	///////////////
	// BESTELLEN //
	///////////////
	
	
	public function index()
	{
        $this->session->unset_userdata('kortingscode_connecties');
        $this->session->unset_userdata('kortingscode');

		if(!isset($_POST['bestellen_formulier']))
		{			
			// Stap terug gedaan? Ingevulde gegevens ophalen uit sessie
			if($this->session->userdata('bestellen_voornaam')) $this->_gegevens_formulier_ophalen();
		}

		if(isset($_POST['producten']))
		{
			$this->bestellen_producten = $this->input->post('producten');

			$this->session->set_userdata(array(
				'bestellen_producten' => $this->bestellen_producten
			));
		} else {
			$bestellen_producten = $this->session->userdata('bestellen_producten');
			if(empty($bestellen_producten))
			{
				redirect('cursistenmodule/producten');
			}
		}

		// Controleren of het formulier verzonden is

		$fouten = 0;
        $this->load->model('producten_model');
        $this->load->model('kortingscodes_model');

		if(isset($_POST['bestellen_afleveren_adres']))
		{
			$this->bestellen_afleveren_adres 		= trim($this->input->post('bestellen_afleveren_adres'));
			$this->bestellen_afleveren_postcode	 	= trim($this->input->post('bestellen_afleveren_postcode'));
			$this->bestellen_afleveren_plaats 		= trim($this->input->post('bestellen_afleveren_plaats'));

			if(!empty($this->bestellen_afleveren_adres) || !empty($this->bestellen_afleveren_postcode) || !empty($this->bestellen_afleveren_plaats))
			{
				// Adres

				if(empty($this->bestellen_afleveren_adres))
				{
					$this->bestellen_afleveren_adres_feedback = 'Graag een adres invullen';
					$fouten++;
				}

				// Postcode

				if(empty($this->bestellen_afleveren_postcode))
				{
					$this->bestellen_afleveren_postcode_feedback = 'Graag een postcode invullen';
					$fouten++;
				}
				else
				{
					$postcode = str_replace(' ', '', $this->bestellen_afleveren_postcode);
					$postcode_cijfers = substr($postcode, 0, 4);
					$postcode_letters = strtoupper(substr($postcode, -2));

					if(strlen($postcode) == 6)
					{
						if(!is_numeric($postcode_cijfers))
						{
							$this->bestellen_afleveren_postcode_feedback = 'Graag een geldige postcode invullen';
							$fouten++;
						}
						elseif(!preg_match("/^[A-Z]+$/", $postcode_letters))
						{
							$this->bestellen_afleveren_postcode_feedback = 'Graag een geldige postcode invullen';
							$fouten++;
						}
					}
					else
					{
						$this->bestellen_afleveren_postcode_feedback = 'Graag een geldige postcode invullen';
						$fouten++;
					}
				}

				// Plaats

				if(empty($this->bestellen_afleveren_plaats))
				{
					$this->bestellen_afleveren_plaats_feedback = 'Graag een plaats invullen';
					$fouten++;
				}
			}

            // Kortingscode
            if(isset($_POST['bestellen_kortingscode']))
            {
                $this->bestellen_kortingscode = $_POST['bestellen_kortingscode'];

                if(!empty($this->bestellen_kortingscode))
                {
                    $kortingscode = $this->kortingscodes_model->getKortingscodesByCode($this->bestellen_kortingscode);

                    if (!empty($kortingscode)) {
                        if (!empty($kortingscode->kortingscode_percentage) || !empty($kortingscode->kortingscode_vast_bedrag)) {
                            $connecties = $this->kortingscodes_model->getKortingConnectiesByID($kortingscode->kortingscode_ID);
                        }
                    }

                    $now = date('Y-m-d');

                    if(empty($connecties) || $kortingscode->kortingscode_limiet === '0' || (empty($kortingscode->kortingscode_percentage) && empty($kortingscode->kortingscode_vast_bedrag)) || $now < $kortingscode->kortingscode_startdatum || ($now > $kortingscode->kortingscode_einddatum && $kortingscode->kortingscode_einddatum != '0000-00-00'))
                    {
                        $this->bestellen_kortingscode_feedback = 'Graag een geldige code invullen';
                        $fouten++;
                    } else {
                        if (!empty($kortingscode->kortingscode_limiet)) {
                            $limiet = $kortingscode->kortingscode_limiet - 1;

                            $data = array(
                                'kortingscode_limiet' => $limiet,
                            );
                            $this->kortingscodes_model->updateKortingscodesLimiet($kortingscode->kortingscode_ID, $data);
                        }

                        $this->session->set_userdata('kortingscode_connecties', $connecties);
                        $this->session->set_userdata('kortingscode', $kortingscode);

                    }
                }
            }

            if($fouten == 0)
			{
				$userdata = array(
					'bestellen_afleveren_adres' => $this->bestellen_afleveren_adres,
					'bestellen_afleveren_postcode' => $this->bestellen_afleveren_postcode,
					'bestellen_afleveren_plaats' => $this->bestellen_afleveren_plaats
				);

				$this->session->set_userdata($userdata);

				redirect('cursistenmodule/bestellen/bevestigen');
			}
		}
		else
		{
			// Stap terug gedaan? Ingevulde gegevens ophalen uit sessie
			if($this->session->userdata('bestellen_voornaam')) $this->_gegevens_formulier_ophalen();
		}


		// PAGINA TEKST OPHALEN

		$this->load->model('paginas_model');
		$content = $this->paginas_model->getPaginaByID(7);
		$this->data['content'] = $content;

        $this->data['bestellen_kortingscode'] 			        = $this->bestellen_kortingscode;
		$this->data['bestellen_producten'] 						= $this->bestellen_producten;
		$this->data['bestellen_afleveren_adres'] 				= $this->bestellen_afleveren_adres;
		$this->data['bestellen_afleveren_postcode'] 			= $this->bestellen_afleveren_postcode;
		$this->data['bestellen_afleveren_plaats'] 				= $this->bestellen_afleveren_plaats;
		$this->data['bestellen_afleveren_adres_feedback'] 		= $this->bestellen_afleveren_adres_feedback;
        $this->data['bestellen_kortingscode_feedback'] 			= $this->bestellen_kortingscode_feedback;
		$this->data['bestellen_afleveren_postcode_feedback'] 	= $this->bestellen_afleveren_postcode_feedback;
		$this->data['bestellen_afleveren_plaats_feedback'] 		= $this->bestellen_afleveren_plaats_feedback;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cursistenmodule/bestellen';
		$this->load->view('cursistenmodule/template', $pagina);
	}
	
	
	////////////////////
	// KORT FORMULIER //
	////////////////////
		
	
	private function _gegevens_gebruiker_opslaan($gebruiker)
	{
		// GEGEVENS GEBRUIKER OPHALEN EN OPSLAAN
		
		$bestellen_geboortedatum = explode('-', $gebruiker->gebruiker_geboortedatum);
		
		$this->bestellen_bedrijfsnaam = $gebruiker->gebruiker_bedrijfsnaam;
		$this->bestellen_voornaam = $gebruiker->gebruiker_voornaam;
		$this->bestellen_tussenvoegsel = $gebruiker->gebruiker_tussenvoegsel;
		$this->bestellen_achternaam = $gebruiker->gebruiker_achternaam;
		$this->bestellen_geslacht = $gebruiker->gebruiker_geslacht;
		$this->bestellen_geboortedatum_dag = $bestellen_geboortedatum[2];
		$this->bestellen_geboortedatum_maand = $bestellen_geboortedatum[1];
		$this->bestellen_geboortedatum_jaar = $bestellen_geboortedatum[0];
		$this->bestellen_adres = $gebruiker->gebruiker_adres;
		$this->bestellen_postcode = $gebruiker->gebruiker_postcode;
		$this->bestellen_plaats = $gebruiker->gebruiker_plaats;
		$this->bestellen_telefoon = $gebruiker->gebruiker_telefoonnummer;
		$this->bestellen_mobiel = $gebruiker->gebruiker_mobiel;
		$this->bestellen_emailadres = $gebruiker->gebruiker_emailadres;
		
		$this->_gegevens_formulier_opslaan();
	}
	
	
	////////////////
	// BEVESTIGEN //
	////////////////
	
	public function bevestigen()
	{
		// MODELS LADEN

		$this->load->model('gebruikers_model');
		$this->load->model('bestellingen_model');
		$this->load->model('producten_model');
        $this->load->model('kortingscodes_model');

		// GEGEVENS OPHALEN UIT SESSIE

		$this->_gegevens_formulier_ophalen();
		
		// GEGEVENS GEBRUIKER OPHALEN

		$gebruiker = $this->gebruikers_model->getGebruikerByID($this->session->userdata('gebruiker_ID'));

		// GEGEVENS OPSLAAN

		if($gebruiker != null)
		{
			$this->_gegevens_gebruiker_opslaan($gebruiker);
		} 
		else
		{
			redirect('cursistenmodule/producten');
		}

		// KOSTEN
		
		$bestellen_bedrag = 0;

        // Korting

        if(!empty($this->bestellen_kortingscode))
        {
            $this->load->model('kortingscodes_model');

            $kortingscode = $this->kortingscodes_model->getKortingscodesByCode($this->bestellen_kortingscode);
            if (!empty($kortingscode)) {
                $connecties = $this->kortingscodes_model->getKortingConnectiesByID($kortingscode->kortingscode_ID);
            }

            if(empty($connecties))
            {
                $this->bestellen_kortingscode_feedback = 'Graag een geldige code invullen';

                $this->data['bestellen_kort_kortingscode_feedback'] = 'graag een geldige kortingscode invullen';

                $pagina['data'] = $this->data;
                $pagina['pagina'] = 'cursistenmodule/bestellen';
                $this->load->view('cursistenmodule/template', $pagina);
            } else {
                $this->session->set_userdata('kortingscode_connecties', $connecties);
                $this->session->set_userdata('kortingscode', $kortingscode);
            }
        }

		// Producten => Products
		if(!empty($this->bestellen_producten) && sizeof($this->bestellen_producten) > 0)
		{
			foreach($this->bestellen_producten as $product_ID)
			{
				$product = $this->producten_model->getProductByID($product_ID);
				if($product->product_prijs_naderhand != 0){
					$product_amount = $product->product_prijs_naderhand;
				}else{
					$product_amount =  $product->product_prijs;
				}
				$bestellen_bedrag += $product_amount;
			}
		} 
		else 
		{		
			redirect('cursistenmodule/producten');
		}
		
		
		/////////////
		// BETALEN //
		/////////////
		
		// BESTELLING TOEVOEGEN, UPDATEN OF VERWIJDEREN
		
		$bestelling = $this->bestellingen_model->getBestellingLosByID($this->bestellen_ID);
		
		if($this->bestellen_producten != '' && sizeof($this->bestellen_producten) > 0)
		{
			// Afleveradres initialeren
			
			if(!empty($this->bestellen_afleveren_adres))
			{
				$bestellen_afleveren_adres 		= $this->bestellen_afleveren_adres;
				$bestellen_afleveren_postcode 	= $this->bestellen_afleveren_postcode;
				$bestellen_afleveren_plaats 	= $this->bestellen_afleveren_plaats;
			}
			else
			{
				$bestellen_afleveren_adres 		= $this->bestellen_adres;
				$bestellen_afleveren_postcode 	= $this->bestellen_postcode;
				$bestellen_afleveren_plaats 	= $this->bestellen_plaats;
			}
			
			$data = array(
				'bestelling_adres' 		=> $bestellen_afleveren_adres,
				'bestelling_postcode' 	=> $bestellen_afleveren_postcode,
				'bestelling_plaats' 	=> $bestellen_afleveren_plaats,
				'aanmelding_ID' 		=> null
			);
			
			if($bestelling == null)
			{
				if($this->session->userdata('gebruiker_rechten') != 'test') {
					// Nieuwe bestelling toevoegen
					
                    $this->bestellen_ID = $this->bestellingen_model->insertBestelling($data);
					
                    // Bestelling "los" toevoegen
                    $bestelling_los_data = array(
						'bestelling_datum' => date('Y-m-d- H:i:s'),
                        'bestelling_betaald_bedrag' => $bestellen_bedrag,
                        'bestelling_ID' => $this->bestellen_ID,
                        'gebruiker_ID' => $gebruiker->gebruiker_ID,
                    );
					
                    $this->bestellingen_model->insertBestellingLos($bestelling_los_data);
                }
			}
			else
			{
				if($this->session->userdata('gebruiker_rechten') != 'test') {
					// Bestelling updaten
					
                    $this->bestellen_ID = $bestelling->bestelling_ID;
					
                    $this->bestellingen_model->updateBestelling($this->bestellen_ID, $data);
					
                    // Bestelling "los" updaten
                    $bestelling_los_data = array(
						'bestelling_ID' => $this->bestellen_ID
                    );
					
                    $this->bestellingen_model->updateBestellingLos($this->bestellen_ID, $bestelling_los_data);
					
                    // Huidige producten loskoppelen
					
                    $this->bestellingen_model->deleteProductenByBestellingID($this->bestellen_ID);
                }
			}
            if($this->session->userdata('gebruiker_rechten') != 'test') {
				$this->session->set_userdata('bestellen_ID', $this->bestellen_ID);
            }
			// Producten koppelen aan bestelling
			
            if($this->session->userdata('gebruiker_rechten') != 'test') {
				foreach ($this->bestellen_producten as $product) {
					$data = array(
						'product_ID' => $product,
                        'bestelling_ID' => $this->bestellen_ID
                    );
					
                    $this->bestellingen_model->insertProductBestelling($data);
                }
            }
		}
		else
		{
			if($this->session->userdata('gebruiker_rechten') != 'test') {
				if ($bestelling != null) {
					// Huidige bestelling verwijderen
					
                    $this->bestellingen_model->deleteBestellingLos($bestelling->bestelling_ID);
                }
            }
		}
		
        $this->load->model('betaalmethodes_model');
        $betaal_methodes = $this->betaalmethodes_model->getMethodes();
		
        $pay_images = array(
			array ('id' => 10, 'img' => 'https://admin.pay.nl/images/payment_profiles/10.gif'),
            array ('id' => 1813, 'img' => 'https://admin.pay.nl/images/payment_profiles/1813.gif'),
            array ('id' => 436, 'img' => 'https://admin.pay.nl/images/payment_profiles/436.gif'),
            array ('id' => 706, 'img' => 'https://admin.pay.nl/images/payment_profiles/706.gif')
        );
		
        $this->data['betaal_methodes'] = $betaal_methodes;
        $this->data['pay_images'] = $pay_images;
        $this->data['paymentList'] = Paynl\Paymentmethods::getList();
		
        $this->systeem_kortingen = array();

        $producten = $this->producten_model->getProducten();
        $connecties = $this->session->userdata('kortingscode_connecties');
        $kortingscode = $this->session->userdata('kortingscode');
		
        foreach ($producten as $product) {
			if (!empty($connecties)) {
				foreach ($connecties as $connectie) {
					if ($product->product_ID == $connectie->product_ID) {
						if($product->product_prijs_naderhand != 0){
							$product_amount = $product->product_prijs_naderhand;
						}else{
							$product_amount =  $product->product_prijs;
						}
                        if ($kortingscode->kortingscode_percentage > 0) {
							$korting = $product_amount / 100 * $kortingscode->kortingscode_percentage;
                            $kortingsprijs = $product_amount - $korting;
                        } elseif ($kortingscode->kortingscode_vast_bedrag > 0) {
							$korting = $kortingscode->kortingscode_vast_bedrag;
                            $kortingsprijs = $product_amount - $kortingscode->kortingscode_vast_bedrag;
                        }
						
                        $product->korting_prijs = $kortingsprijs;
                        $product->korting = $korting;
                    }
                }
            }
        }
        foreach ($producten as $product) {
			if (!empty($this->bestellen_producten)) {
                foreach ($this->bestellen_producten as $checked_ID) {
					if ($checked_ID == $product->product_ID) {
                        if (!empty($product->korting_prijs)) {
                            array_push($this->systeem_kortingen, array('titel' => $product->product_naam, 'bedrag' => $product->korting));
                        }
                    }
                }
            }
        }

		if(!empty($this->systeem_kortingen)) {
			if (count($this->systeem_kortingen) > 1) {
				foreach ($this->systeem_kortingen as $korting) {
					$bestellen_bedrag -= $korting['bedrag'];
				}
			} else {
				if (!empty($this->systeem_kortingen)) {
					$bestellen_bedrag -= $this->systeem_kortingen[0]['bedrag'];
				}
			}
		}

		if(!empty($this->bestellen_kortingscode)) {
			if(count($this->bestellen_kortingscode) > 1) {
				foreach ($this->bestellen_kortingscode as $korting) {
					array_push($this->systeem_kortingen, array('titel' => $product->product_naam, 'bedrag' => $korting));
					$bestellen_bedrag -= $korting['bedrag'];
				}
			}
		}

        $items = array();

        if (!empty($this->producten)) {
            foreach ($this->producten as $product) {
                $discount = 0;
                if (!empty($product->korting_prijs)) {
					if($product->product_prijs_naderhand != 0){
						$product_amount = $product->product_prijs_naderhand;
					}else{
						$product_amount =  $product->product_prijs;
					}
                    $discount = $product_amount - $product->korting_prijs;
                }

                $items[] = array("productId" => $product->product_ID,
                    "productType" => "Product",
                    "description" => $product->product_naam,
                    "price" => $product_amount,
                    "quantity" => "1",
                    "vatCode" => "H",
                    "vatPercentage" => "21",
                    "discount" => $discount
                );
            }
        }

        if(!empty($this->bestellen_kortingscode)) {
            $this->load->model('kortingscodes_model');
            $kortingscode = $this->kortingscodes_model->getKortingscodesByCode($this->bestellen_kortingscode);
        }

        if ((!empty($kortingscode) && $kortingscode->kortingscode_in3 == 1) || empty($kortingscode)) {
            $this->in3_aan = 1;
        }

        // PAGINA TONEN
        $this->data['systeem_kortingen']				= $this->systeem_kortingen;
        $this->data['bestellen_bedrijfsnaam'] 			= $this->bestellen_bedrijfsnaam;
		$this->data['bestellen_voornaam'] 				= $this->bestellen_voornaam;
		$this->data['bestellen_tussenvoegsel'] 			= $this->bestellen_tussenvoegsel;
		$this->data['bestellen_achternaam'] 			= $this->bestellen_achternaam;
		$this->data['bestellen_geslacht'] 				= $this->bestellen_geslacht;
		$this->data['bestellen_geboortedatum_dag'] 		= $this->bestellen_geboortedatum_dag;
		$this->data['bestellen_geboortedatum_maand'] 	= $this->bestellen_geboortedatum_maand;
		$this->data['bestellen_geboortedatum_jaar'] 	= $this->bestellen_geboortedatum_jaar;
		$this->data['bestellen_adres'] 					= $this->bestellen_adres;
		$this->data['bestellen_postcode'] 				= $this->bestellen_postcode;
		$this->data['bestellen_plaats'] 				= $this->bestellen_plaats;
		$this->data['bestellen_telefoon'] 				= $this->bestellen_telefoon;
		$this->data['bestellen_mobiel'] 				= $this->bestellen_mobiel;
		$this->data['bestellen_emailadres'] 			= $this->bestellen_emailadres;
		$this->data['bestellen_bedrag'] 				= $bestellen_bedrag;
		$this->data['bestellen_ID'] 					= $this->bestellen_ID;
		$this->data['bestellen_producten'] 				= $this->bestellen_producten;
		$this->data['bestellen_afleveren_adres'] 		= $this->bestellen_afleveren_adres;
		$this->data['bestellen_afleveren_postcode'] 	= $this->bestellen_afleveren_postcode;
		$this->data['bestellen_afleveren_plaats'] 		= $this->bestellen_afleveren_plaats;
		$this->data['in3_aan'] 		            = $this->in3_aan;

        $this->session->set_userdata('bestellen_bedrag', $bestellen_bedrag);
        $this->session->set_userdata('systeem_kortingen', $this->systeem_kortingen);
        $this->session->set_userdata('bestellen_items', $items);
        $this->session->set_userdata('bestellen_ID', $this->bestellen_ID);
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cursistenmodule/bestellen_bevestigen';
		$this->load->view('cursistenmodule/template', $pagina);
	}
	
	
	private function _removeSpecialCharacters($str)
	{
		$invalid = array('Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e',  'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y',  'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', "`" => "'", "´" => "'", "„" => ",", "`" => "'", "´" => "'", "“" => "\"", "”" => "\"", "´" => "'", "&acirc;€™" => "'", "{" => "", "~" => "", "–" => "-", "’" => "'");
		$str = str_replace(array_keys($invalid), array_values($invalid), $str);
		return $str;
	}
	
	
	
	////////////////////////
	// GEGEVENS FORMULIER //
	////////////////////////
	
	private function _gegevens_formulier_ophalen()
	{
		// GEGEVENS OPHALEN UIT SESSIE
		
		$this->bestellen_ID						= $this->session->userdata('bestellen_ID');
		$this->bestellen_bedrijfsnaam 			= $this->session->userdata('bestellen_bedrijfsnaam');
		$this->bestellen_voornaam 				= $this->session->userdata('bestellen_voornaam');
		$this->bestellen_tussenvoegsel 			= $this->session->userdata('bestellen_tussenvoegsel');
		$this->bestellen_achternaam 			= $this->session->userdata('bestellen_achternaam');
		$this->bestellen_geslacht 				= $this->session->userdata('bestellen_geslacht');
		$this->bestellen_geboortedatum_dag 		= $this->session->userdata('bestellen_geboortedatum_dag');
		$this->bestellen_geboortedatum_maand 	= $this->session->userdata('bestellen_geboortedatum_maand');
		$this->bestellen_geboortedatum_jaar 	= $this->session->userdata('bestellen_geboortedatum_jaar');
		$this->bestellen_adres 					= $this->session->userdata('bestellen_adres');
		$this->bestellen_postcode 				= $this->session->userdata('bestellen_postcode');
		$this->bestellen_plaats 				= $this->session->userdata('bestellen_plaats');
		$this->bestellen_telefoon 				= $this->session->userdata('bestellen_telefoon');
		$this->bestellen_mobiel 				= $this->session->userdata('bestellen_mobiel');
		$this->bestellen_emailadres 			= $this->session->userdata('bestellen_emailadres');
		$this->bestellen_producten				= $this->session->userdata('bestellen_producten');
		$this->bestellen_afleveren_adres		= $this->session->userdata('bestellen_afleveren_adres');
		$this->bestellen_afleveren_postcode		= $this->session->userdata('bestellen_afleveren_postcode');
		$this->bestellen_afleveren_plaats		= $this->session->userdata('bestellen_afleveren_plaats');
	}
	
	private function _gegevens_formulier_opslaan()
	{
		// GEGEVENS OPSLAAN IN SESSIE
		
		$userdata = array(
			'bestellen_ID' => $this->bestellen_ID,
			'bestellen_bedrijfsnaam' => $this->bestellen_bedrijfsnaam,
			'bestellen_voornaam' => $this->bestellen_voornaam,
			'bestellen_tussenvoegsel' => $this->bestellen_tussenvoegsel,
			'bestellen_achternaam' => $this->bestellen_achternaam,
			'bestellen_geslacht' => $this->bestellen_geslacht,
			'bestellen_geboortedatum_dag' => $this->bestellen_geboortedatum_dag,
			'bestellen_geboortedatum_maand' => $this->bestellen_geboortedatum_maand,
			'bestellen_geboortedatum_jaar' => $this->bestellen_geboortedatum_jaar,
			'bestellen_adres' => $this->bestellen_adres,
			'bestellen_postcode' => $this->bestellen_postcode,
			'bestellen_plaats' => $this->bestellen_plaats,
			'bestellen_telefoon' => $this->bestellen_telefoon,
			'bestellen_mobiel' => $this->bestellen_mobiel,
			'bestellen_emailadres' => $this->bestellen_emailadres,
			'bestellen_producten' => $this->bestellen_producten,
			'bestellen_afleveren_adres' => $this->bestellen_afleveren_adres,
			'bestellen_afleveren_postcode' => $this->bestellen_afleveren_postcode,
			'bestellen_afleveren_plaats' => $this->bestellen_afleveren_plaats
		);
		
		$this->session->set_userdata($userdata);
	}
	
	private function _gegevens_formulier_verwijderen()
	{
		// GEGEVENS VERWIJDEREN UIT SESSIE
		
		$this->session->unset_userdata('bestellen_ID');
		$this->session->unset_userdata('bestellen_bedrijfsnaam');
		$this->session->unset_userdata('bestellen_voornaam');
		$this->session->unset_userdata('bestellen_tussenvoegsel');
		$this->session->unset_userdata('bestellen_achternaam');
		$this->session->unset_userdata('bestellen_geslacht');
		$this->session->unset_userdata('bestellen_geboortedatum_dag');
		$this->session->unset_userdata('bestellen_geboortedatum_maand');
		$this->session->unset_userdata('bestellen_geboortedatum_jaar');
		$this->session->unset_userdata('bestellen_adres');
		$this->session->unset_userdata('bestellen_postcode');
		$this->session->unset_userdata('bestellen_plaats');
		$this->session->unset_userdata('bestellen_telefoon');
		$this->session->unset_userdata('bestellen_mobiel');
		$this->session->unset_userdata('bestellen_emailadres');
		$this->session->unset_userdata('bestellen_producten');
		$this->session->unset_userdata('bestellen_afleveren_adres');
		$this->session->unset_userdata('bestellen_afleveren_postcode');
		$this->session->unset_userdata('bestellen_afleveren_plaats');
        $this->session->unset_userdata('bestellen_bedrag');
	}
	
	
	
	///////////////////////////////////////////
	// BESTELLEN AFRONDEN VIA LINK IN E-MAIL //
	///////////////////////////////////////////
	
	public function afronden($bestelling_ID)
	{
		if($bestelling_ID == null) redirect('cursistenmodule/producten');
		
		// BESTELLING OPHALEN
		
		$this->load->model('bestellingen_model');
		$bestelling = $this->bestellingen_model->getBestellingLosByID($bestelling_ID);
		if($bestelling == null) redirect('cursistenmodule/producten');
			
		
		// GEGEVENS FORMULIER INITIALISEREN
		
		$bestellen_geboortedatum = explode('-', $bestelling->gebruiker_geboortedatum);
		
		$this->bestellen_ID = $bestelling->bestelling_ID;
		$this->bestellen_bedrijfsnaam = $bestelling->gebruiker_bedrijfsnaam;
		$this->bestellen_voornaam = $bestelling->gebruiker_voornaam;
		$this->bestellen_tussenvoegsel = $bestelling->gebruiker_tussenvoegsel;
		$this->bestellen_achternaam = $bestelling->gebruiker_achternaam;
		$this->bestellen_geslacht = $bestelling->gebruiker_geslacht;
		$this->bestellen_geboortedatum_dag = $bestellen_geboortedatum[2];
		$this->bestellen_geboortedatum_maand = $bestellen_geboortedatum[1];
		$this->bestellen_geboortedatum_jaar = $bestellen_geboortedatum[0];
		$this->bestellen_adres = $bestelling->gebruiker_adres;
		$this->bestellen_postcode = $bestelling->gebruiker_postcode;
		$this->bestellen_plaats = $bestelling->gebruiker_plaats;
		$this->bestellen_telefoon = $bestelling->gebruiker_telefoonnummer;
		$this->bestellen_mobiel = $bestelling->gebruiker_mobiel;
		$this->bestellen_emailadres = $bestelling->gebruiker_emailadres;
		
		if(!empty($bestelling->bestelling_ID))
		{
			$producten = $this->bestellingen_model->getProductenByBestellingID($bestelling->bestelling_ID);
			
			$this->bestellen_producten = array();
			foreach($producten as $product) $this->bestellen_producten[] = $product->product_ID;
			
			$this->bestellen_afleveren_adres = $bestelling->bestelling_adres;
			$this->bestellen_afleveren_postcode = $bestelling->bestelling_postcode;
			$this->bestellen_afleveren_plaats = $bestelling->bestelling_plaats;
		}
		
		$this->_gegevens_formulier_opslaan();
		
		redirect('cursistenmodule/bestellen/bevestigen');
	}
	
	
	
	
	
	
	///////////////
	// AANGEMELD //
	///////////////
	
	public function besteld($bestelling_ID = null)
	{
		// Controleren of alle gegevens zijn meegestuurd
        if($this->session->userdata('gebruiker_rechten') != 'test') {
            if ($bestelling_ID == null) redirect('cursistenmodule/producten');
        }
		
		// Models laden
		
		$this->load->model('gebruikers_model');
		$this->load->model('bestellingen_model');
		
		
		// Aanmelding ophalen
        if($this->session->userdata('gebruiker_rechten') != 'test') {
            $bestelling = $this->bestellingen_model->getBestellingLosByID($bestelling_ID);

            // Controleren of de bestelling bestaat

            if ($bestelling == null) redirect('cursistenmodule/producten');

            // Controleren of de gebruiker een eigen bestelling bekijkt

            if ($bestelling->gebruiker_ID != $this->session->userdata('gebruiker_ID')) redirect('cursistenmodule/producten');

            // Controleren of iDEAL waardes heeft gepost
        }

        if($this->session->userdata('gebruiker_rechten') != 'test') {
            try {
                $transaction = \Paynl\Transaction::getForReturn();

                if ($transaction->isPaid() || $transaction->isPending()) {
                    if ($transaction->isPaid()) {
                        // Bestelling status updaten

                        $data = array(
                            'bestelling_ideal_tijdstip' => date('Y-m-d H:i:s')
                        );

                        $this->bestellingen_model->updateBestellingLos($bestelling->bestelling_ID, $data);

                        if ($bestelling->bestelling_betaald_datum == '0000-00-00 00:00:00') {
                            // Bestelling betaald datum updaten
                            $update_bestelling = $this->bestellingen_model->updateBestellingLos($bestelling->bestelling_ID, array('bestelling_betaald_datum' => date('Y-m-d H:i:s')));

                            if ($update_bestelling) {
                                // E-mail bevestiging van bestelling
                                $this->_email_gebruiker($bestelling->bestelling_ID);

                                // Bevestiging van bestelling sturen naar localhost
                                $this->_email_bestelling($bestelling->bestelling_ID);

                                // Gegevens uit sessie verwijderen

                                $this->_gegevens_formulier_verwijderen();
                            }
                        }
                    }
                } elseif ($transaction->isCanceled()) {
                    $status = 'canceled';
                    redirect('cursistenmodule/producten');

                }
            } catch (\Paynl\Error\Error $e) {
                echo "Fout: " . $e->getMessage();
            }
        }

        $betaald_bedrag = 0;
        if($this->session->userdata('gebruiker_rechten') != 'test') {
            $producten = $this->bestellingen_model->getProductenByBestellingID($bestelling->bestelling_ID);
        }
        $systeem_kortingen = $this->session->userdata('systeem_kortingen');
        $betaal_methode = $this->session->userdata('betaal_methode');

        if(!empty($betaal_methode)) {
            $betaald_bedrag = $betaald_bedrag + $betaal_methode;
            $this->data['betaal_methode'] = $betaal_methode;
        }

        if(!empty($producten)) {
            foreach ($producten as $product) {
				if($product->product_prijs_naderhand != 0){
					$product_amount = $product->product_prijs_naderhand;
				}else{
					$product_amount =  $product->product_prijs;
				}
                $betaald_bedrag = $betaald_bedrag + $product_amount;
            }
        }

        if(!empty($systeem_kortingen)) {
            foreach ($systeem_kortingen as $korting) {
                $betaald_bedrag = $betaald_bedrag - $korting['bedrag'];
            }
        }

        $this->session->unset_userdata('systeem_kortingen');
        $this->session->unset_userdata('betaal_methode');

		// Pagina weergeven
        if($this->session->userdata('gebruiker_rechten') != 'test') {
            $this->data['bestelling'] = $bestelling;
            $this->data['bestelling_producten'] = $producten;
        }
		$this->data['betaald_bedrag'] = $betaald_bedrag;
		$this->data['systeem_kortingen'] = $systeem_kortingen;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cursistenmodule/bestellen_besteld';
		$this->load->view('cursistenmodule/template', $pagina);
	}



	////////////////////////
	// IDEAL STATUSUPDATE //
	////////////////////////
	
	/*
	public function statusupdate()
	{
		// Controleren of iDEAL waardes heeft gepost
		
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			// Waardes ophalen
			
			$AMOUNT 	= $_POST['amount'];	
			$NCERROR 	= $_POST['NCERROR'];
			$ORDERID 	= $_POST['orderID'];
			$PAYID 		= $_POST['PAYID'];
			$STATUS 	= $_POST['STATUS'];
			$SHASIGN	= $_POST['SHASIGN'];
			
			// Sha opbouwen aan de hand van waardes
			
			$SHAPASS 	= 'MaFl2105He0312WeBe8609';
			
			$SHASTR		 = 'AMOUNT='.$AMOUNT.$SHAPASS;
			$SHASTR		.= 'NCERROR='.$NCERROR.$SHAPASS;
			$SHASTR		.= 'ORDERID='.$ORDERID.$SHAPASS;
			$SHASTR		.= 'PAYID='.$PAYID.$SHAPASS;
			$SHASTR		.= 'STATUS='.$STATUS.$SHAPASS;
			
			// Controleren of onze sha gelijk is aan die van iDEAL
			
			if(strtoupper(sha1($SHASTR)) == $SHASIGN)
			{
				// Models laden
				
				$this->load->model('gebruikers_model');
				$this->load->model('aanmeldingen_model');
				
				
				// Aanmelding ophalen
				
				$ORDERID_explode = explode('-', $ORDERID);
				$ID = $ORDERID_explode[0];
				
				$aanmelding = $this->aanmeldingen_model->getBestellingByID($ID);
				
				
				// Controleren of de aanmelding bestaat
				
				if($aanmelding != null)
				{
					// Aanmelding status updaten
					
					$data = array(
						'aanmelding_ideal_ID' => $PAYID,
						'aanmelding_ideal_status' => $STATUS,
						'aanmelding_ideal_tijdstip' => date('Y-m-d H:i:s')
					);
					
					$this->aanmeldingen_model->updateAanmeldingByID($aanmelding->aanmelding_ID, $data);
					
					
					// Controleren of de aanmelding is betaald
					
					if($STATUS == 9)
					{
						// Controleren of de aanmelding nog niet is betaald
						
						if($aanmelding->aanmelding_betaald_datum == '0000-00-00 00:00:00')
						{
							// Aanmelding betaald datum updaten
							
							$update_aanmelding = $this->aanmeldingen_model->updateAanmeldingByID($aanmelding->aanmelding_ID, array('aanmelding_betaald_datum' => date('Y-m-d H:i:s')));
							
							if($update_aanmelding)
							{
								// Controleren of de gebruiker een wachtwoord nodig heeft en of hij deze heeft
								
								if($aanmelding->aanmelding_type == 'workshop' && $aanmelding->gebruiker_wachtwoord == '')
								{
									// Wachtwoord genereren en gebruiker activeren
									
									$this->aanmelden_wachtwoord = substr(str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
									
									$update_gebruiker = $this->gebruikers_model->updateGebruiker($aanmelding->gebruiker_ID, array('gebruiker_wachtwoord' => sha1($this->aanmelden_wachtwoord), 'gebruiker_status' => 'actief'));
									
									if($update_gebruiker)
									{
										// E-mail bevestiging van aanmelding incl. wachtwoord
										
										$this->_email_gebruiker($aanmelding, $this->aanmelden_wachtwoord);
									}
								}
								else
								{
									// Controleren of de gebruiker geactiveerd moet worden
									
									if($aanmelding->gebruiker_status != 'actief')
									{
										// Gebruiker activeren
										
										$this->gebruikers_model->updateGebruiker($aanmelding->gebruiker_ID, array('gebruiker_status' => 'actief'));
									}
									
									
									// E-mail bevestiging van aanmelding
									
									$this->_email_gebruiker($aanmelding);
								}
								
								
								// Bevestiging van aanmelding sturen naar localhost
								
								$this->_email_klant($aanmelding, 'iDEAL');
							}
						}
					}
				}
			}
		}
	}
	*/
	
	
	//////////////
	// E-MAILEN //
	//////////////
	
	private function _email_bestelling($bestelling_ID)
	{
		// BESTELLING OPHALEN
		$this->load->model('bestellingen_model');
		$bestelling = $this->bestellingen_model->getBestellingLosByID($bestelling_ID);
		
		if($bestelling != null)
		{
			// PRODUCTEN OPHALEN
			
			$producten = $this->bestellingen_model->getProductenByBestellingID($bestelling->bestelling_ID);
			
			if(sizeof($producten))
			{
				// KLANT E-MAILEN
			
				$email_van_emailadres = 'info@localhost';
				$email_van_naam = 'localhost';
				$email_aan_emailadres = 'deelnameformulier@localhost';
				$email_aan_naam = 'localhost';
				
				
				// PRODUCT OVERZICHT MAKEN
				
				$email_producten = '<table cellpadding="10" cellspacing="0" width="100%" border="1">';
				
				foreach($producten as $product)
				{
					if($product->product_prijs_naderhand != 0){
						$product_amount = $product->product_prijs_naderhand;
					}else{
						$product_amount =  $product->product_prijs;
					}
					$email_producten .= '<tr>';
					$email_producten .= '<td>'.$product->product_naam.'</td>';
					$email_producten .= '<td align="right">&euro; '.$product_amount.',00</td>';
					$email_producten .= '</tr>';
				}

                $betaald_bedrag = $this->session->userdata('bestellen_bedrag');
                $systeem_kortingen = $this->session->userdata('systeem_kortingen');
                $betaal_methode = $this->session->userdata('betaal_methode');

                $this->session->unset_userdata('bestellen_bedrag');

                if(!empty($betaal_methode)) {
                    $betaald_bedrag = $betaald_bedrag + $betaal_methode;

                    $email_producten .= '<tr>';
                    $email_producten .= '<td>Betaalmethode toeslag</td>';
                    $email_producten .= '<td align="right">&euro; '. money_format('%.2n' , $betaal_methode).'</td>';
                    $email_producten .= '</tr>';
                }

                if(!empty($systeem_kortingen)) {
                    foreach($systeem_kortingen as $korting) {
                        $email_producten .= '<tr><td>Korting ('.$korting['titel'].')</td><td class="prijs" align="right">- &euro;'. money_format('%.2n' ,$korting['bedrag']) .'</td></tr>';
                    }
                }


                $email_producten .= '<tr>';
                $email_producten .= '<td>Totaal</td>';
                $email_producten .= '<td align="right">&euro; '.  money_format('%.2n' , $betaald_bedrag) .'</td>';
                $email_producten .= '</tr>';


                $email_producten .= '</table>';
				
				// BERICHT OPSTELLEN
				
				$email_titel = 'Bestelling #'.$bestelling_ID;
				
				$email_bericht = '
					<h1>'.$email_titel.'</h1>
					<p>'.$bestelling->gebruiker_voornaam.' '.$bestelling->gebruiker_tussenvoegsel.' '.$bestelling->gebruiker_achternaam . ' heeft zojuist de onderstaande producten besteld.</p>
					'.$email_producten.'
					<p><strong>Adresgegevens</strong></p>
					<table cellpadding="10" cellspacing="0" width="100%" border="1">
						<tr>
							<td>Naam</td>
							<td>'.$bestelling->gebruiker_voornaam.' '.$bestelling->gebruiker_tussenvoegsel.' '.$bestelling->gebruiker_achternaam.'</td>
						</tr>
						<tr>
							<td>Adres</td>
							<td>'.$bestelling->bestelling_adres.'</td>
						</tr>
						<tr>
							<td>Postcode</td>
							<td>'.$bestelling->bestelling_postcode.'</td>
						</tr>
						<tr>
							<td>Plaats</td>
							<td>'.$bestelling->bestelling_plaats.'</td>
						</tr>
					</table>
					<p>Met vriendelijke groet,</p>
					<p>localhost</p>
					';
				
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
					//'bcc_address' => 'mark@flitsend-webdesign.nl',
				    'headers' => array('Reply-To' => $email_van_emailadres),
				    'track_opens' => true,
				    'track_clicks' => true,
				    'auto_text' => true
				);
				
				$this->_email($email);
			}
		}
	}
	
	private function _email_gebruiker($bestelling_ID)
	{
		// BESTELLING OPHALEN
		$this->load->model('bestellingen_model');
		$bestelling = $this->bestellingen_model->getBestellingLosByID($bestelling_ID);

		if($bestelling != null)
		{
			// GEBRUIKER E-MAILEN

			$email_van_emailadres = 'info@localhost';
			$email_van_naam = 'localhost';
			$email_aan_emailadres = $bestelling->gebruiker_emailadres;
			$email_aan_naam = $bestelling->gebruiker_naam;

			// BERICHT OPSTELLEN

			$email_titel = 'localhost bestelling #' . $bestelling->bestelling_ID;
			$email_tekst = '<p>Je hebt zojuist producten besteld op localhost. Hieronder vind je een overzicht van jouw bestelling.</p>';

			$producten = $this->bestellingen_model->getProductenByBestellingID($bestelling->bestelling_ID);
	
			if(sizeof($producten))
			{
				// PRODUCT OVERZICHT MAKEN
	
				$email_tekst .= '<table cellpadding="10" cellspacing="0" width="100%" border="1">';

				foreach($producten as $product)
				{
					if($product->product_prijs_naderhand != 0){
						$product_amount = $product->product_prijs_naderhand;
					}else{
						$product_amount =  $product->product_prijs;
					}
					$email_tekst .= '<tr>';
					$email_tekst .= '<td>'.$product->product_naam.'</td>';
					$email_tekst .= '<td align="right">&euro; '.money_format('%.2n' ,$product_amount).'</td>';
					$email_tekst .= '</tr>';
				}

				$betaald_bedrag = $this->session->userdata('bestellen_bedrag');
                $systeem_kortingen = $this->session->userdata('systeem_kortingen');
                $betaal_methode = $this->session->userdata('betaal_methode');

                if(!empty($betaal_methode)) {
                    $betaald_bedrag = $betaald_bedrag + $betaal_methode;

                    $email_tekst .= '<tr>';
                    $email_tekst .= '<td>Betaalmethode toeslag</td>';
                    $email_tekst .= '<td align="right">&euro; '. money_format('%.2n' , $betaal_methode).'</td>';
                    $email_tekst .= '</tr>';
                }

                 if(!empty($systeem_kortingen)) {
                     foreach($systeem_kortingen as $korting) {
                         $email_tekst .= '<tr><td>Korting ('.$korting['titel'].')</td><td class="prijs" align="right">- &euro;'. money_format('%.2n' ,$korting['bedrag']) .'</td></tr>';
                     }
                 }

                $email_tekst .= '<tr>';
                $email_tekst .= '<td>Totaal</td>';
                $email_tekst .= '<td align="right">&euro; '.  money_format('%.2n' , $betaald_bedrag) .'</td>';
                $email_tekst .= '</tr>';
				
				$email_tekst .= '</table>';

				$email_bericht = '
				<h1>'.$email_titel.'</h1>
				<p>Beste '.$bestelling->gebruiker_voornaam.',</p>
				'.$email_tekst.'
				<p>Met vriendelijke groet,</p>
				<p>localhost</p>';

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
					//'bcc_address' => 'mark@flitsend-webdesign.nl',
					'headers' => array('Reply-To' => $email_van_emailadres),
					'track_opens' => true,
					'track_clicks' => true,
					'auto_text' => true
				);

				$this->_email($email);
			}
		}
	}
	
	private function _email($email)
	{
		// E-MAIL VERZENDEN VIA MANDRILL
		
		$this->load->helper('mandrill');
		// $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
		$mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');

		$feedback = $mandrill->messages->send($email);
		
		if($feedback[0]['status'] == 'sent')
		{
			// echo 'E-mail verzonden';
		}
		else
		{
			// echo 'Er kon geen e-mail worden verzonden';
		}
	}

    public function betaal_optie()
    {
        $this->load->model('betaalmethodes_model');
        $betaal_methodes = $this->betaalmethodes_model->getMethodes();

        // PAYNL INSTELLINGEN
        if(isset($_POST['payment_option'])) {
            $paymentOptie = $_POST['payment_option'];

            $userdata = $this->session->all_userdata();

            $normalReturnUrl = base_url('cursistenmodule/bestellen/besteld/' . $userdata['bestellen_ID']);
            if($this->session->userdata('gebruiker_rechten') != 'test') {
                foreach ($betaal_methodes as $methode) {
                    if ($methode->pay_ID == $paymentOptie) {
                        $procent = ($methode->percentage / 100) * $userdata['bestellen_bedrag'];
                        $userdata['bestellen_bedrag'] = $userdata['bestellen_bedrag'] + $procent;
                        $this->session->set_userdata('betaal_methode', $procent);
                    }
                }

                $vandaag = new DateTime();

                if (preg_match('/([^\d]+)\s?(.+)/i', $userdata['bestellen_adres'], $result)) {
                    $straatNaam = $result[1];
                    $huisNummer = $result[2];
                }

                $transaction = Paynl\Transaction::start(array(
                    'paymentMethod' => $paymentOptie,
                    'amount' => $userdata['bestellen_bedrag'],
                    'returnUrl' => $normalReturnUrl,
                    'description' => "#" . $userdata['bestellen_ID'],
                    'language' => "NL",
                    'ipAddress' => \Paynl\Helper::getIp(),
                    'enduser' => array(
                        'initials' => substr($userdata['bestellen_voornaam'], 0, 1),
                        'lastName' => $userdata['bestellen_tussenvoegsel'] ? $userdata['bestellen_tussenvoegsel'] . ' ' . $userdata['bestellen_achternaam'] : $userdata['bestellen_achternaam'],
                        'gender' => substr($userdata['bestellen_geslacht'], 0, 1),
                        'dob' => $userdata['bestellen_geboortedatum_dag'] . '-' . $userdata['bestellen_geboortedatum_maand'] . '-' . $userdata['bestellen_geboortedatum_jaar'],
                        'phoneNumber' => $userdata['bestellen_telefoon'],
                        'emailAddress' => $userdata['bestellen_emailadres'],
                        'address' => array(
                            'streetName' => $straatNaam,
                            'zipCode' => $userdata['bestellen_postcode'],
                            'streetNumber' => $huisNummer,
                            'city' => $userdata['bestellen_plaats'],
                            'country' => 'NL',
                        ),

                        'invoiceAddress' => array(
                            'initials' => substr($userdata['bestellen_voornaam'], 0, 1),
                            'lastName' => $userdata['bestellen_achternaam'],
                            'streetName' => $straatNaam,
                            'streetNumber' => $huisNummer,
                            'zipCode' => $userdata['bestellen_postcode'],
                            'city' => $userdata['bestellen_plaats'],
                            'country' => 'NL',
                        ),
                    ),
                    'saleData' => array(
                        'orderData' => $userdata['bestellen_items'],
                        "deliveryDate" => $vandaag->format('d-m-Y'),
                        "invoiceDate" => $vandaag->format('d-m-Y')
                    ),
                ));


                redirect($transaction->getRedirectUrl());
            } else {
                redirect($normalReturnUrl);
            }
        }
    }
}
