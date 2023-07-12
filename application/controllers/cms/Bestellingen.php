<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bestellingen extends CI_Controller
{
	private $data = array();
	
	public function __construct()
	{
		parent::__construct();
		
		// Rechten controleren en aantal nieuwe items ophalen
		
		$this->load->library('algemeen');
		$this->load->library('utilities');
        $this->algemeen->cms();
        if($this->session->userdata('beheerder_rechten') == 'contentmanager') redirect('cms/rechten');
	}
	
	
	
	/* ============= */
	/* = OVERZICHT = */
	/* ============= */
	
	public function index()
    {
        $zoek_lijst = "";
        $item_zoeken = "";

        if(isset($_POST['submit'])){
            $item_zoeken = trim($_POST['item_zoeken']);
            if(!empty($item_zoeken)) {
            $zoek_lijst_temp = $this->bestellingen_model->getBestellingenVerzendenZoeken($item_zoeken);
            $zoek_lijst_los_temp = $this->bestellingen_model->getBestellingenLosVerzendenZoeken($item_zoeken);
            $zoek_lijst = array_merge($zoek_lijst_temp, $zoek_lijst_los_temp);
            }
        }

        $bestellingenlijst = '';
        $this->data['bestellingenlijst'] = $bestellingenlijst;

        // Bestellingen die verzonden moeten worden
        $bestellingen_met_aanmelding_verzenden = $this->bestellingen_model->getBestellingenVerzenden();
        $bestellingen_zonder_aanmelding_verzenden = $this->bestellingen_model->getBestellingenLosVerzenden();

        // Huur bestellingen die verzonden moeten worden
        $bestellingen_verzenden_huur = $this->bestellingen_model->getBestellingenVerzendenHuur();
        $bestellingen_verzonden_huur = $this->bestellingen_model->getBestellingenVerzondenHuur();

        // Bestellingen met en zonder aanmelding samenvoegen
        $bestellingen_verzenden = array_merge($bestellingen_met_aanmelding_verzenden, $bestellingen_zonder_aanmelding_verzenden);

        // De te verzenden bestellingen oplopend sorteren op "besteling ID"
        $this->data['bestellingen_verzenden'] = Utilities::array_orderby($bestellingen_verzenden, 'bestelling_ID', SORT_ASC);

        // Bestellingen die verzonden zijn
        $bestellingen_met_aanmelding_verzonden = $this->bestellingen_model->getBestellingenVerzonden();
        $bestellingen_zonder_aanmelding_verzonden = $this->bestellingen_model->getBestellingenLosVerzonden();

        // Bestellingen met en zonder aanmelding samenvoegen
        $bestellingen_verzonden = array_merge($bestellingen_met_aanmelding_verzonden, $bestellingen_zonder_aanmelding_verzonden);

        // Verzonden bestellingen oplopend sorteren op "verzonden datum"
        $bestellingen_verzonden = Utilities::array_orderby($bestellingen_verzonden, 'bestelling_verzonden_datum', SORT_DESC);

        $this->data['bestellingen_verzonden'] = array_slice($bestellingen_verzonden, 0, 10);

        $this->data['zoek_lijst'] = $zoek_lijst;
        $this->data['bestellingen_verzenden_huur'] = $bestellingen_verzenden_huur;
        $this->data['bestellingen_verzonden_huur'] = $bestellingen_verzonden_huur;
        $this->data['item_zoeken'] = $item_zoeken;

        // PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/bestellingen';
		$this->load->view('cms/template', $pagina);
	}
	
	
	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */
	
	public function detail($item_ID = null)
	{
		if($item_ID == null) redirect('cms/bestellingen');

		$item_verzonden				= '';
        $item_verzonden_feedback 	= '';
        $producten = array();

		// Bestelling ophalen
		
		$this->load->model('bestellingen_model');
		$bestelling = $this->bestellingen_model->getBestellingByID($item_ID);
		if($bestelling == null)
		{
			$bestelling_los = $this->bestellingen_model->getBestellingLosById($item_ID);
			
			if($bestelling_los == null)
			{
				redirect('cms');
			} 
			else 
			{
				$bestelling = $bestelling_los;
			}
		}
		
		// Producten ophalen
		
		$producten = $this->bestellingen_model->getProductenByBestellingID($item_ID);
		
		
		// FORMULIER VERZONDEN
		
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if(isset($_POST['item_verzonden']))
			{
                $producten_temp     = $_POST['producten'];
				$item_verzonden     = $this->input->post('item_verzonden');

                if(!empty($producten_temp)) {
                    foreach($producten_temp as $key => $product) {
                        $data = array(
                            'verzonden' => $product
                        );

                    $q = $this->bestellingen_model->updateBestellingProducten($key, $data);
                    }
                }

				if($item_verzonden == 'ja')
				{
                    $data = array('bestelling_verzonden_datum' => date('Y-m-d H:i:s'));
                    
                    if(!empty($producten_temp)) {
                        foreach($producten_temp as $key => $product) {
                            $qe_data = array(
                                'verzonden' => 1
                            );
    
                        $q = $this->bestellingen_model->updateBestellingProducten($key, $qe_data);
                        }
                    }
				}
				else
				{
					$data = array('bestelling_verzonden_datum' => NULL);
				}
					
				$qe = $this->bestellingen_model->updateBestelling($item_ID, $data);
				
				if($q || $qe)
                {
					redirect('cms/bestellingen/'.$item_ID);
				}
				else
				{
					echo 'Item wijzigen mislukt. Probeer het nog eens.';
				}
			}
		}
		else
		{
			$item_verzonden = $bestelling->bestelling_verzonden_datum;
			
			if($item_verzonden == '') $item_verzonden = 'nee';
			else $item_verzonden = 'ja';
		}
		
		// PAGINA TONEN
		
		$this->data['bestelling'] 		= $bestelling;
		$this->data['producten'] 		= $producten;
		
		$this->data['item_ID'] 						= $item_ID;
		$this->data['item_verzonden'] 				= $item_verzonden;
		$this->data['item_verzonden_feedback'] 		= $item_verzonden_feedback;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/bestellingen_detail';
		$this->load->view('cms/template', $pagina);
	}
	
	
	
	/* ========================= */
	/* = TOEVOEGEN EN WIJZIGEN = */
	/* ========================= */
	
	public function toevoegen()
	{
		$this->_toevoegen_wijzigen('toevoegen');
	}
	
	public function wijzigen($item_ID = null)
	{
		if($item_ID == null) redirect('cms/bestellingen');
		$this->_toevoegen_wijzigen('wijzigen', $item_ID);
	}
	
	private function _toevoegen_wijzigen($actie, $item_ID = null)
	{
        if($this->session->userdata('beheerder_rechten') == 'opleidingsmedewerker') redirect('cms/rechten');

        $item_betaald                = '';
        $item_adres                  = '';
        $item_postcode_letters       = '';
        $item_postcode_cijfers       = '';
        $item_plaats                 = '';

        $item_betaald_feedback    = '';
        $item_adres_feedback      = '';
        $item_postcode_feedback   = '';
        $item_plaats_feedback     = '';

		// FORMULIER VERZONDEN

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fouten = 0;

            $item_adres = trim($_POST['item_adres']);
            $item_postcode_cijfers = trim($_POST['item_postcode_cijfers']);
            $item_postcode_letters = trim($_POST['item_postcode_letters']);
            $item_plaats = trim($_POST['item_plaats']);

            if (empty($item_adres)) {
                $fouten++;
                $item_adres_feedback = 'Graag invullen';
            }

            if (empty($item_postcode_cijfers) || empty($item_postcode_letters)) {
                $fouten++;
                $item_postcode_feedback = 'Graag invullen';
            }

            if (empty($item_plaats)) {
                $fouten++;
                $item_plaats_feedback = 'Graag invullen';
            }


            if ($fouten == 0) {
                $item = $this->bestellingen_model->getBestellingByID($item_ID);
                if($item == null)
                {
                    $bestelling_los = $this->bestellingen_model->getBestellingLosById($item_ID);

                    if($bestelling_los == null)
                    {
                        redirect('cms');
                    }
                    else
                    {
                        $item = $bestelling_los;
                    }
                }

                if($item->aanmelding_ID != null)
                {
                    $betaald = 'bestelling_betaald_datum';
                    $betaald_datum = $item->aanmelding_betaald_datum;
                }
                else
                {
                    $betaald = 'bestelling_betaald_datum';
                    $betaald_datum = $item->bestelling_betaald_datum;
                }

                if ($betaald_datum == '0000-00-00 00:00:00') {
                    if ($_POST['item_betaald'] == 'ja') {
                        $betaald_datum = date("Y-m-d H:i:s");
                    }
                }

                if ($_POST['item_betaald'] == 'nee') {
                    $betaald_datum = '0000-00-00 00:00:00';
                }

                $data = array(
                    'bestelling_adres' => $item_adres,
                    'bestelling_postcode' => $item_postcode_cijfers . ' ' . $item_postcode_letters,
                    'bestelling_plaats' => $item_plaats,
                );

                if ($actie == 'toevoegen') {
                    $q = $this->bestellingen_model->insertBestelling($data);
                } else {
                    $item = $this->bestellingen_model->getBestellingByID($item_ID);
                    if($item == null)
                    {
                        $bestelling_los = $this->bestellingen_model->getBestellingLosById($item_ID);

                        $data = array(
                            'bestelling_adres' => $item_adres,
                            'bestelling_postcode' => $item_postcode_cijfers . ' ' . $item_postcode_letters,
                            'bestelling_plaats' => $item_plaats,
                        );
                        $q = $this->bestellingen_model->updateBestelling($bestelling_los->bestelling_ID, $data);
                        $data = array('bestelling_betaald_datum' => $betaald_datum);
                        $qu = $this->bestellingen_model->updateBestellingLos($bestelling_los->bestelling_ID, $data);
                    } else {
                        $q = $this->bestellingen_model->updateBestelling($item_ID, $data);
                    }


                }

                if ($q || $qu) {
                    if ($actie == 'toevoegen') redirect('cms/bestellingen');
                    else redirect('cms/bestellingen/' . $item_ID);
                } else {
                    echo 'Item ' . $actie . ' mislukt. Probeer het nog eens.';
                }
            }
        }

            if ($actie == 'wijzigen') {
                $item = $this->bestellingen_model->getBestellingByID($item_ID);
                if($item == null)
                {
                    $bestelling_los = $this->bestellingen_model->getBestellingLosById($item_ID);

                    if($bestelling_los == null)
                    {
                        redirect('cms');
                    }
                    else
                    {
                        $item = $bestelling_los;
                    }
                }

                $item_deelnemer = $item->gebruiker_naam;
                $item_adres = $item->bestelling_adres;
                $item_postcode = explode(' ', $item->bestelling_postcode);

                if (count($item_postcode) < 2) {
                    $item_postcode[0] = substr($item->bestelling_postcode, 0, 4);
                    $item_postcode[1] = substr($item->bestelling_postcode, 4);
                }

                $item_postcode_cijfers = $item_postcode[0];
                $item_postcode_letters = $item_postcode[1];
                $item_plaats = $item->bestelling_plaats;
            }


		// PAGINA TONEN

		$this->data['actie'] = $actie;

		$this->data['item_ID'] 							= $item_ID;
		$this->data['item'] 							= $item;
		$this->data['item_deelnemer'] 					= $item_deelnemer;
		$this->data['item_adres'] 						= $item_adres;
		$this->data['item_betaald'] 					= $item_betaald;
		$this->data['item_postcode_cijfers'] 			= $item_postcode_cijfers;
		$this->data['item_postcode_letters'] 			= $item_postcode_letters;
		$this->data['item_plaats'] 						= $item_plaats;

		$this->data['item_adres_feedback'] 				= $item_adres_feedback;
		$this->data['item_betaald_feedback'] 			= $item_betaald_feedback;
		$this->data['item_postcode_feedback'] 			= $item_postcode_feedback;
		$this->data['item_plaats_feedback'] 			= $item_plaats_feedback;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/bestellingen_wijzigen';
		$this->load->view('cms/template', $pagina);
	}
	
	
	
	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */
	
	public function verwijderen($item_ID = null, $bevestiging = null)
	{
        if($this->session->userdata('beheerder_rechten') == 'opleidingsmedewerker') redirect('cms/rechten');
		if($item_ID == null) redirect('cms/bestellingen');

		$this->load->model('bestellingen_model');
        $item = $this->bestellingen_model->getBestellingByID($item_ID);
        if($item == null)
        {
            $bestelling_los = $this->bestellingen_model->getBestellingLosById($item_ID);

            if($bestelling_los == null)
            {
                redirect('cms');
            }
            else
            {
                $item = $bestelling_los;
            }
        }

		if($item == null) redirect('cms/bestellingen');
		$this->data['item'] = $item;


		// ITEM VERWIJDEREN

		if($bevestiging == 'ja')
		{
			$q = $this->bestellingen_model->deleteBestelling($item_ID);
			if($q) redirect('cms/bestellingen');
			else echo 'de bestelling kon niet worden verwijderd. Probeer het nog eens.';
		}


		// PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/bestellingen_verwijderen';
		$this->load->view('cms/template', $pagina);

    }

    public function export_verzonden() {
        if(empty($_POST['actie'])) redirect('cms/bestellingen');
        if(empty($_POST['geselecteerde_bestellingen'])) redirect('cms/bestellingen');

        if($_POST['actie'] == "verzenden") {
            $this->verzonden($_POST['geselecteerde_bestellingen']);
        } else {
            $this->exporteren($_POST['geselecteerde_bestellingen']);
        }
    }
    
    private function exporteren($geselecteerde_bestellingen)
    {
        if(empty($geselecteerde_bestellingen)) redirect('cms/bestellingen');

        $this->load->model('bestellingen_model');
        $this->load->model('gebruikers_model');

        $bestellingenlijst = $geselecteerde_bestellingen;
        require(dirname(dirname(__FILE__)) . '/cms/Csv.php');
        $body_items = array();
        foreach ($bestellingenlijst as $bestelling_ID) {
            $bestelling = $this->bestellingen_model->getBestellingenExport($bestelling_ID);
            $bestelling = $bestelling[0];

            if($bestelling->aanmelding_ID == null) {
                $gebruiker = $this->bestellingen_model->getBestellingLosByID($bestelling->bestelling_ID);

                if (preg_match('/([^\d]+)\s?(.+)/i', $gebruiker->bestelling_adres, $gebruiker_adres) )
                {
                    // $result[1] will have the steet name
                    $streetName = $gebruiker_adres[1];
                    // and $result[2] is the number part. 
                    $streetNumber = $gebruiker_adres[2];
                }

                if(empty($gebruiker->gebruiker_bedrijfsnaam)) { $gebruiker->gebruiker_bedrijfsnaam = $gebruiker->gebruiker_tussenvoegsel . " " . $gebruiker->gebruiker_achternaam; }

                $body_items[] = array($gebruiker->bestelling_ID, $gebruiker->gebruiker_bedrijfsnaam, $gebruiker->gebruiker_voornaam, $gebruiker->gebruiker_tussenvoegsel . " " . $gebruiker->gebruiker_achternaam,
                $streetName, $streetNumber, "", $gebruiker->bestelling_postcode, $gebruiker->bestelling_plaats,
                "NL", $gebruiker->gebruiker_emailadres, $gebruiker->gebruiker_telefoonnummer, $gebruiker->gebruiker_mobiel, " ", " ", " ", " ", " ");
            } else {
                if (preg_match('/([^\d]+)\s?(.+)/i', $bestelling->bestelling_adres, $gebruiker_adres) )
                {
                    // $result[1] will have the steet name
                    $streetName = $gebruiker_adres[1];
                    // and $result[2] is the number part. 
                    $streetNumber = $gebruiker_adres[2];
                }
                
                if(empty($bestelling->gebruiker_bedrijfsnaam)) { $bestelling->gebruiker_bedrijfsnaam = $bestelling->gebruiker_tussenvoegsel . " " . $bestelling->gebruiker_achternaam; }

                $body_items[] = array($bestelling->bestelling_ID, $bestelling->gebruiker_bedrijfsnaam, $bestelling->gebruiker_voornaam, $bestelling->gebruiker_tussenvoegsel . " " . $bestelling->gebruiker_achternaam,
                        $streetName, $streetNumber, "", $bestelling->bestelling_postcode, $bestelling->bestelling_plaats,
                            "NL", $bestelling->gebruiker_emailadres, $bestelling->gebruiker_telefoonnummer, $bestelling->gebruiker_mobiel, " ", " ", " ", " ", " ");
            }
        }

        $csv = new CSV();
        $csv->set_header_items(array(('Referentie'), ('Bedrijfsnaam'), ('Voornaam'), ('Achternaam'), ('Straatnaam'), ('Huisnummer'), ('Huisnummer toevoeging'), ('Postcode'), ('Plaatsnaam'), ('Landcode'), ('Email'), ('Telefoon'), ('Mobiel nummer'), ('Gebouw'), ('Verdieping'), ('Afdeling'), ('Deurcode'), ('Aflever referentie')));
        $csv->set_body_items($body_items);
        $csv->output_as_downloadCSV('Export-bestellingen.csv', ";"); // prompts the user to download the file as 'export.csv' by default

        // PAGINA TONEN

        $pagina['data'] = $this->data;
        redirect('cms/bestellingen/');
    }

    private function verzonden($geselecteerde_bestellingen)
    {
        if(empty($geselecteerde_bestellingen)) redirect('cms/bestellingen');

        $this->load->model('bestellingen_model');
        $this->load->model('gebruikers_model');

        $bestellingenlijst = $geselecteerde_bestellingen;

        foreach ($bestellingenlijst as $bestelling_ID) {
            $data = array('bestelling_verzonden_datum' => date('Y-m-d H:i:s'));
                    
            $qe = $this->bestellingen_model->updateBestelling($bestelling_ID, $data);
        }

        redirect('cms/bestellingen/');
    }
}