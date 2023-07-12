<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller
{
    private $data = array();

    public function __construct()
    {
        parent::__construct();
    }

    public function media()
    {
        $this->load->model('media_model');
        $media = $this->media_model->getMedia();
        echo json_encode($media);
    }

    public function workshop_lessen($workshopID)
    {
        $this->load->model('lessen_model');
        $lessen = $this->lessen_model->getLessenByWorkshopID($workshopID);
        echo json_encode($lessen);
    }

    public function update_prijs()
    {
        $prijs = json_decode($_GET['prijs']);
        $this->load->model('betaalmethodes_model');
        $betaling_methodes = $this->betaalmethodes_model->getMethodes();
        $prijzen = array();

        foreach($betaling_methodes as $methode) {
            array_push($prijzen, array('id' => $methode->pay_ID, 'prijs' => (($methode->percentage / 100) * $prijs) + $prijs));
        }

        echo json_encode($prijzen);
    }

    public function update_groep_select()
    {
        $workshop_ID = json_decode($_GET['id']);
        $archief = json_decode($_GET['archief']);
        $this->load->model('groepen_model');

        if ($archief == 1) {
            if ($workshop_ID != '') {
                $workshop = $this->groepen_model->getGroepenArchiefByWorkshop_ID($workshop_ID);
            } else {
                $workshop = $this->groepen_model->getGroepenArchief();
            }
        } else {
            if ($workshop_ID != '') {
                $workshop = $this->groepen_model->getGroepenByWorkshopID($workshop_ID);
            } else {
                $workshop = $this->groepen_model->getAlleGroepen();
            }
        }

        echo json_encode(array(
            'groepen' => $workshop,
        ));
    }

    public function getWorkshopInfo()
    {
        $workshop_ID = json_decode($_GET['workshop_ID']);

        $this->load->model('workshops_model');
        $workshop = $this->workshops_model->getWorkshopByID($workshop_ID);

        echo json_encode(array(
            'workshop_titel' => $workshop->workshop_titel,
            'workshop_ondertitel' => $workshop->workshop_ondertitel,
            'workshop_afkorting' => $workshop->workshop_afkorting,
            'workshop_inleiding' => $workshop->workshop_inleiding,
            'workshop_beschrijving' => $workshop->workshop_beschrijving,
            'workshop_prijs' => $workshop->workshop_prijs,
            'workshop_frequentie' => $workshop->workshop_frequentie,
            'workshop_aanmelden_tekst' => $workshop->workshop_aanmelden_tekst,
            'workshop_stemtest_prijs' => $workshop->workshop_stemtest_prijs,
            'workshop_capaciteit' => $workshop->workshop_capaciteit,
            'workshop_stemtest_dagen_korting_na_afloop' => $workshop->workshop_stemtest_dagen_korting_na_afloop,
            'workshop_toelatingseisen' => $workshop->workshop_toelatingseisen,
            'workshop_exclusief' => $workshop->workshop_exclusief,
            'workshop_duur' => $workshop->workshop_duur,
        ));
    }

    function uploadimage() {
        $this->load->library('algemeen');

        if (!empty($_FILES['file']) && !empty($_FILES['file']['name'])) {
            // we allow .gif, .jpg and .png of up to 2MB
            $extension = pathinfo($_FILES['file']['name']);
            $extension = '.' . strtolower($extension['extension']);
            $allowed_extensions = array('.jpg', '.gif', '.png', '.jpeg');
            $allowed_types = array('image/gif', 'image/jpeg', 'image/png', 'image/pjpeg');

            if (in_array($extension, $allowed_extensions) && in_array($_FILES['file']['type'], $allowed_types) && $_FILES['file']['size'] < 5000000) {
                if ($_FILES['file']['error'] > 0) {
                    if ($_FILES['file']['error'] == UPLOAD_ERR_INI_SIZE || $_FILES['file']['error'] == UPLOAD_ERR_FORM_SIZE) {
                        $file = array("error" => "filesizetype");
                        echo json_encode($file);
                    } else {
                        $file = array("error" => "cannot upload file");
                        echo json_encode($file);
                    }
                } else {
                    $filename = uniqid() . $extension;
                    if (!move_uploaded_file($_FILES['file']['tmp_name'], '/var/www/vhosts/localhost/httpdocs//media/uploads/' . $filename)) {
                        $file = array("error" => "could not move file");
                        echo json_encode($file);
                    }

                    $file = array('location' => 'https://localhost/media/uploads/' . $filename);
                    echo json_encode($file);
                }
            } else {
                $file = array("error" => "filesizetype");
                echo json_encode($file);
            }
        }
    }

    public function getDeelnemers()
    {
        if (isset($_GET['ids'])) {
            $ids = json_decode($_GET['ids']);
            $ids = explode(',', $ids);

            $this->load->model('gebruikers_model');

            $deelnemers = $this->gebruikers_model->getDeelnemersByIDArray($ids);

            echo json_encode(
                $deelnemers
            );
        }

        if (isset($_GET['groep_ID'])) {
            $item_ID = json_decode($_GET['groep_ID']);;

            $this->load->model('groepen_model');
            $this->load->model('lessen_model');

            $deelnemers = $this->groepen_model->getGroepDeelnemers($item_ID);
            $lessen = $this->lessen_model->getLessenByGroepID($item_ID);

            echo json_encode(array(
                'deelnemers' => $deelnemers
                )
            );
        }
    }

    public function getDeelnemer()
    {
        if (isset($_GET['deelnemer_ID'])) {
            $item_ID = json_decode($_GET['deelnemer_ID']);

            $this->load->model('gebruikers_model');
            $this->load->model('aanmeldingen_model');
            $this->load->model('bestellingen_model');
            $this->load->model('huiswerk_model');

            $deelnemer = $this->gebruikers_model->getGebruikerByID($item_ID);

            // Aanmeldingen ophalen
            $afspraken = $this->aanmeldingen_model->getAanmeldingenAfsprakenByGebruikerID($deelnemer->gebruiker_ID);
            $aanmeldingen = $this->aanmeldingen_model->getAanmeldingenWorkshopsByGebruikerID($deelnemer->gebruiker_ID);

            // Bestellingen ophalen
            $bestellingen_met_aanmelding = $this->bestellingen_model->getBestellingenByGebruikerID($deelnemer->gebruiker_ID);
            $bestellingen_zonder_aanmelding = $this->bestellingen_model->getBestellingenLosByGebruikerID($deelnemer->gebruiker_ID);

            // Bestellingen met en zonder aanmelding samenvoegen
            $bestellingen = array_merge($bestellingen_met_aanmelding, $bestellingen_zonder_aanmelding);

            // Resultaten ophalen
            $resultaten = $this->huiswerk_model->getResultatenByGebruikerID($deelnemer->gebruiker_ID);
            $body_items['items'] = array();

            array_push($body_items['items'], '<table style="text-align:left;">');
            array_push($body_items['items'], '<tr>');
            array_push($body_items['items'], '<th>Bedrijf</th>');
            array_push($body_items['items'], '<th>Geslacht</th>');
            array_push($body_items['items'], '<th>Email</th>');
            array_push($body_items['items'], '<th>Adres</th>');
            array_push($body_items['items'], '<th>Postcode</th>');
            array_push($body_items['items'], '<th>Telefoon</th>');
            array_push($body_items['items'], '<th>Mobiel</th></tr>');

            array_push($body_items['items'], "<tr>");
            array_push($body_items['items'], "<td>".$deelnemer->gebruiker_bedrijfsnaam."</td>");
            array_push($body_items['items'], "<td>".$deelnemer->gebruiker_geslacht."</td>");
            array_push($body_items['items'], "<td>".$deelnemer->gebruiker_emailadres."</td>");
            array_push($body_items['items'], "<td>".$deelnemer->gebruiker_adres."</td>");
            array_push($body_items['items'], "<td>".$deelnemer->gebruiker_postcode."</td>");
            array_push($body_items['items'], "<td>".$deelnemer->gebruiker_telefoonnummer."</td>");
            array_push($body_items['items'], "<td>".$deelnemer->gebruiker_mobiel."</td></tr>");
            array_push($body_items['items'], '</table><br><br>');

            array_push($body_items['items'], "<h3>Afspraken</h3>");
            array_push($body_items['items'], '<table style="text-align:left;">');
            array_push($body_items['items'], '<tr><th>Type</th>');
            array_push($body_items['items'], '<th>Titel</th>');
            array_push($body_items['items'], '<th>Afspraak</th>');
            array_push($body_items['items'], '<th>Voldoende</th></tr>');

            if (count($afspraken) == 0) {
                array_push($body_items['items'], "<tr><td>-</td>");
                array_push($body_items['items'], "<td>-</td>");
                array_push($body_items['items'], "<td>-</td>");
                array_push($body_items['items'], "<td>-</td></tr>");
            }

            foreach ($afspraken as $afspraak) {
                array_push($body_items['items'], "<tr><td>".$afspraak->workshop_type."</td>");
                array_push($body_items['items'], "<td>".$afspraak->workshop_titel."</td>");
                array_push($body_items['items'], "<td>".$afspraak->aanmelding_afspraak."</td>");
                array_push($body_items['items'], "<td>".$afspraak->aanmelding_voldoende."</td></tr>");
            }
            array_push($body_items['items'], '</table><br><br>');

            array_push($body_items['items'], "<h3>Aanmeldingen</h3>");
            array_push($body_items['items'], '<table style="text-align:left;">');
            array_push($body_items['items'], "<tr></tr>");
            array_push($body_items['items'], "<tr></tr>");
            array_push($body_items['items'], '<tr><th>Datum</th>');
            array_push($body_items['items'], '<th>Betaald</th>');
            array_push($body_items['items'], '<th>Type</th>');
            array_push($body_items['items'], '<th>Titel</th>');
            array_push($body_items['items'], '<th>Groep</th></tr>');

            if (count($aanmeldingen) == 0) {
                array_push($body_items['items'], "<tr><td>-</td>");
                array_push($body_items['items'], "<td>-</td>");
                array_push($body_items['items'], "<td>-</td>");
                array_push($body_items['items'], "<td>-</td>");
                array_push($body_items['items'], "<td>-</td></tr>");
            }

            foreach ($aanmeldingen as $aanmelding) {
                array_push($body_items['items'], "<tr></tr>");
                array_push($body_items['items'], "<tr><td>".$aanmelding->aanmelding_datum ."</td>");
                array_push($body_items['items'], "<td>".$aanmelding->aanmelding_betaald_datum."</td>");
                array_push($body_items['items'], "<td>".$aanmelding->aanmelding_type."</td>");
                array_push($body_items['items'], "<td>".$aanmelding->workshop_titel."</td>");
                array_push($body_items['items'], "<td>".$aanmelding->groep_naam."</td></tr>");
            }
            array_push($body_items['items'], '</table><br><br>');

            array_push($body_items['items'], "<h3>Bestellingen</h3>");
            array_push($body_items['items'], '<table style="text-align:left;">');
            array_push($body_items['items'], "<tr></tr>");
            array_push($body_items['items'], "<tr>");
            array_push($body_items['items'], "<td>#</td>");
            array_push($body_items['items'], "<td>Datum</td>");
            array_push($body_items['items'], "<td>Deelnemer</td>");
            array_push($body_items['items'], "<td>Betaald</td></tr>");

            if (count($bestellingen) == 0) {
                array_push($body_items['items'], "<tr><td>-</td>");
                array_push($body_items['items'], "<td>-</td>");
                array_push($body_items['items'], "<td>-</td>");
                array_push($body_items['items'], "<td>-</td>");
                array_push($body_items['items'], "<td>-</td></tr>");
            }

            foreach ($bestellingen as $bestelling) {
                array_push($body_items['items'], "<tr></tr>");
                array_push($body_items['items'], "<td>".$bestelling->bestelling_ID."</td>");

                if(!empty($bestelling->bestelling_datum)) {
                    array_push($body_items['items'], "<td>" . $bestelling->bestelling_datum . "</td>");
                } elseif(!empty($bestelling->aanmelding_datum)) {
                    array_push($body_items['items'], "<td>" . $bestelling->aanmelding_datum . "</td>");
                } else {
                    array_push($body_items['items'], "<td> - </td>");
                }

                array_push($body_items['items'], "<td>".$bestelling->gebruiker_naam."</td>");

                if(!empty($bestelling->bestelling_betaald_datum)) {
                    array_push($body_items['items'], "<td>".$bestelling->bestelling_betaald_datum."</td></tr>");
                } elseif(!empty($bestelling->aanmelding_betaald_datum)) {
                    array_push($body_items['items'], "<td>" . $bestelling->aanmelding_betaald_datum . "</td>");
                } else {
                    array_push($body_items['items'], "<td> - </td>");
                }

                array_push($body_items['items'], "<tr></tr>");
            }
            array_push($body_items['items'], '</table>');
        }

        echo json_encode(
            $body_items
        );
    }

    public function getAanwezigheid() {

        if (isset($_GET['groep_les_ID'])) {

            $item_ID = $_GET['groep_les_ID'];

            $this->load->model('aanwezigheid_model');
            $this->load->model('groepen_model');
            $this->load->model('lessen_model');

            $groep_les = $this->lessen_model->getGroepLesByID($item_ID);

            $groep = $this->groepen_model->getGroepByID($groep_les->groep_ID);

            $les = $this->lessen_model->getLesByID($groep_les->les_ID);

            $niet_aanwezig = $this->aanwezigheid_model->getAanwezigheidByGroepLesID($groep_les->groep_les_ID);

            $cursisten = $this->groepen_model->getGroepDeelnemers($groep_les->groep_ID);

            if(sizeof($cursisten) > 0) {
                foreach($cursisten as $cursist) {
                    if(sizeof($niet_aanwezig) > 0) {
                        foreach ($niet_aanwezig as $uitzondering) {
                            if ($cursist->gebruiker_ID == $uitzondering->gebruiker_ID) {
                                $cursist->aanwezig = 'nee';
                                break;
                            } else {
                                $cursist->aanwezig = 'ja';
                            }
                        }
                    } else {
                        foreach ($cursisten as $cursist) {
                                $cursist->aanwezig = 'ja';
                        }
                    }
                }
            }


            echo json_encode(array(
                'deelnemers' => $cursisten,
                'groep' => $groep,
                'les'   => $les,
                'groep_les' => $groep_les
                )
            );
        }
    }

    public function getTemplates() {
        $this->load->model('berichten_model');

        $templates = $this->berichten_model->getTemplates();

        echo json_encode(array(
            'templates' => $templates
            ));
    }

    public function opslaanTemplate() {
        $this->load->model('berichten_model');

        if (isset($_GET['template_tekst']) && isset($_GET['template_naam'])) {
            if(isset($_GET['template_ID'])) {
                $template = $this->berichten_model->getTemplatebyID($_GET['template_ID']);
            }

            $template_naam = json_decode($_GET['template_naam']);
            $template_tekst = json_decode($_GET['template_tekst']);

            $data = array(
                'template_titel' => trim($template_naam),
                'template_tekst' => trim($template_tekst)
            );

            if(!empty($template)) {
                $q = $this->berichten_model->updateTemplate($_GET['template_ID'], $data);

                if($q) {
                    echo json_encode(array(
                        'toegevoegd' => true
                    ));
                } else {
                    echo json_encode(array(
                        'toegevoegd' => false
                    ));
                }
            } else {
                $q = $this->berichten_model->insertTemplate($data);

                if($q) {
                    echo json_encode(array(
                        'toegevoegd' => true
                    ));
                } else {
                    echo json_encode(array(
                        'toegevoegd' => false
                    ));
                }
            }
        } else {
            echo json_encode(array(
                'toegevoegd' => false
            ));
        }

    }

    public function uploadAudio() {
        if(isset($_POST['ID'])) {
            $filename = $_POST['ID'] . '-' . date('Ymd');
            define('UPLOAD_DIR', '/var/www/vhosts/localhost/httpdocs//media/huiswerk/');
            if (!move_uploaded_file($_FILES['file']['tmp_name'], UPLOAD_DIR . $filename . '.mp3')) {
                $response = "Je opname is niet gelukt. Probeer het opnieuw";
                echo json_encode($response);
            } else {
                $response = "Je opname is gelukt! Luister hem nu terug en/of klik op ‘bestand uploaden’ als je tevreden bent";
                echo json_encode($response);
            }
        } else {
            $response = "Je opname is niet gelukt. Probeer het opnieuw";
            echo json_encode($response);
        }
    }

    public function uploadWAVAudio() {
        if(isset($_POST['upload_titel'])) {
            $filename = $_POST['upload_titel'];
            define('UPLOAD_DIR', '/var/www/vhosts/localhost/httpdocs//media/opdrachten/');
            if (!move_uploaded_file($_FILES['file']['tmp_name'], UPLOAD_DIR . $filename . '.wav')) {
                $response = "Je opname is niet gelukt. Probeer het opnieuw";
                echo json_encode($response);
            } else {
                $response = "Je opname is gelukt! Luister hem nu terug en/of klik op ‘bestand uploaden’ als je tevreden bent";
                echo json_encode($response);
            }
        } else {
            $response = "Je opname is niet gelukt. Probeer het opnieuw";
            echo json_encode($response);
        }
    }

    public function insertRatingAdmin() {
        if(isset($_GET['gebruiker_ID']) && isset($_GET['les_ID']) && isset($_GET['beoordeling'])) {
            $gebruiker_ID = trim(json_decode($_GET['gebruiker_ID']));
            $les_ID = trim(json_decode($_GET['les_ID']));
            $beoordeling = trim(json_decode($_GET['beoordeling']));

            $this->load->model('lessen_model');
            $bestaat = $this->lessen_model->getGebruikerBeoordeling($gebruiker_ID, $les_ID);

            $data = array(
                'gebruiker_ID' => $gebruiker_ID,
                'les_ID' => $les_ID,
                'gebruiker_beoordeling' => $beoordeling
            );

            if(!$bestaat) {
                $this->lessen_model->insertGebruikerBeoordeling($data);
            } else {
                $this->lessen_model->updateGebruikerBeoordeling($bestaat[0]->gebruiker_beoordeling_ID, $data);
            }

            $response = "Success";
            echo json_encode($response);
        }
    }

    public function insertRating() {
        if(isset($_GET['gebruiker_ID']) && isset($_GET['les_ID']) && isset($_GET['beoordeling'])) {
            $gebruiker_ID = trim(json_decode($_GET['gebruiker_ID']));
            $les_ID = trim(json_decode($_GET['les_ID']));
            $beoordeling = trim(json_decode($_GET['beoordeling']));
            $opmerking = trim(json_decode($_GET['opmerking']));

            $this->load->model('lessen_model');
            $bestaat = $this->lessen_model->getBeoordeling($gebruiker_ID, $les_ID);

            $data = array(
                'gebruiker_ID' => $gebruiker_ID,
                'les_ID' => $les_ID,
                'les_beoordeling' => $beoordeling,
                'les_opmerking' => $opmerking
            );

            if(!$bestaat) {
                $this->lessen_model->insertBeoordeling($data);
            } else {
                $this->lessen_model->updateBeoordeling($bestaat[0]->les_beoordeling_ID, $data);
            }

            $response = "Success";
            echo json_encode($response);
        }
    }

    public function insertAanwezigheid() {
        if(isset($_GET['gebruiker_ID']) && isset($_GET['les_ID']) && isset($_GET['value'])) {
            $this->load->model('aanwezigheid_model');
            $gebruiker_ID = trim(json_decode($_GET['gebruiker_ID']));
            $les_ID = trim(json_decode($_GET['les_ID']));
            $value = trim(json_decode($_GET['value']));


            if($value == 'nee') {
                $aanwezigheid = $this->aanwezigheid_model->getAanwezigheidByGroeplesIDGebruikerID($les_ID, $gebruiker_ID);

                if (!empty($aanwezigheid)) {
                    $this->aanwezigheid_model->deleteAanwezigheid($les_ID, $gebruiker_ID);
                }

                $data = array(
                    'gebruiker_ID' => $gebruiker_ID,
                    'les_ID' => $les_ID,
                    'aanwezigheid_aanwezig' => $value
                );

                // update les
                $this->aanwezigheid_model->insertAanwezigheid($data);
            } else {
                $aanwezigheid = $this->aanwezigheid_model->getAanwezigheidByGroeplesIDGebruikerID($les_ID, $gebruiker_ID);

                if (!empty($aanwezigheid)) {
                    $this->aanwezigheid_model->deleteAanwezigheid($les_ID, $gebruiker_ID);
                }
            }

            $response = "Success";
            echo json_encode($response);
        }
    }
}