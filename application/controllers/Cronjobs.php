<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Cronjobs extends CI_Controller
{
    private $mandrill;

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('mandrill');
        // $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
        $this->mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
    }

    public function huiswerk()
    {
        // 0 9 * * * /opt/plesk/php/7.4/bin/php /var/www/vhosts/localhost/httpdocs/index.php cronjobs huiswerk
        // 0 12 * * * /opt/plesk/php/7.4/bin/php /var/www/vhosts/localhost/httpdocs/index.php cronjobs huiswerk
        // 0 15 * * * /opt/plesk/php/7.4/bin/php /var/www/vhosts/localhost/httpdocs/index.php cronjobs huiswerk

        $this->load->model('huiswerk_model');
        $huiswerk = $this->huiswerk_model->getResultatenOnbekend();

        if (sizeof($huiswerk) > 0) {
            $overzicht = '<table cellpadding="10" cellspacing="0" width="100%" border="1">';

            foreach ($huiswerk as $item) {
                $overzicht .= '<tr>';

                if ($item->resultaat_opnieuw_ingestuurd_datum != '' && $item->resultaat_opnieuw_ingestuurd_datum != '0000-00-00 00:00:00') {
                    $overzicht .= '<td><strong>' . date('d/m/y', strtotime($item->resultaat_opnieuw_ingestuurd_datum)) . '</strong><br />' . date('H:i:s', strtotime($item->resultaat_opnieuw_ingestuurd_datum)) . '</td>';
                    $overzicht .= '<td><strong>' . $item->gebruiker_naam . '</strong><br />2e keer ingestuurd</td>';
                } else {
                    $overzicht .= '<td><strong>' . date('d/m/y', strtotime($item->resultaat_ingestuurd_datum)) . '</strong><br />' . date('H:i:s', strtotime($item->resultaat_ingestuurd_datum)) . '</td>';
                    $overzicht .= '<td><strong>' . $item->gebruiker_naam . '</strong><br />1e keer ingestuurd</td>';
                }

                $overzicht .= '<td><strong>' . $item->les_titel . '</strong><br />' . $item->workshop_titel . '</td>';
                $overzicht .= '</tr>';
            }

            $overzicht .= '</table>';

            if (sizeof($huiswerk) == 1) $email_onderwerp = '1 les ingestuurd';
            else $email_onderwerp = sizeof($huiswerk) . ' lessen ingestuurd';

            $email_bericht = '<h1>' . $email_onderwerp . '</h1>';
            $email_bericht .= '<p>Ga naar het <a href="https://localhost" title="Ga naar het CMS" target="_blank">CMS</a> om het huiswerk te beoordelen.</p>';
            $email_bericht .= $overzicht;
            $email_bericht .= '<p>Met vriendelijke groet,</p>';
            $email_bericht .= '<p>localhost</p>';

            // OVERZICHT E-MAILEN

            $email = array(
                'html' => $email_bericht,
                'subject' => $email_onderwerp,
                'from_email' => 'info@localhost',
                'from_name' => 'localhost',
                'to' => array(
                    array(
                        'email' => 'huiswerk@localhost',
                        'name' => 'localhost',
                        'type' => 'to'
                    )
                ),
                'headers' => array('Reply-To' => 'info@localhost'),
                'track_opens' => true,
                'track_clicks' => true,
                'auto_text' => true
            );

            $this->load->helper('mandrill');
            $mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
            // $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
            $mandrill->messages->send($email);

            echo $overzicht;
        }
    }

    public function elkuur()
    {
        // 0 * * * * /opt/plesk/php/7.4/bin/php /var/www/vhosts/localhost/httpdocs/index.php cronjobs elkuur

        $this->_aanmeldingen();
        $this->_bestellingen();
        $this->_verlopen_aanmeldingen();
        $this->_verlopen_bestellingen();
        $this->_deelnemer_update();
        $this->_deelnemerslijst();
    }

    public function elkeochtend()
    {
        // 0 8 * * *  /opt/plesk/php/7.4/bin/php /var/www/vhosts/localhost/httpdocs/index.php cronjobs elkeochtend
        $this->_herinneringsmail();
        $this->_voorbereidingsmail();
        $this->_feedbackmail();
        $this->_downloadlinkmail();
        $this->_aantaldeelnemerscheck();
    }

    public function elkedag()
    {
        // 0 0 * * * /opt/plesk/php/7.4/bin/php /var/www/vhosts/localhost/httpdocs/index.php cronjobs elkedag
        $this->_uitnodigingen();
        $this->_aanmeldingen_archief();
        $this->_verlopen_kortingscodes();
        $this->_groepen_archiveren();
    }

    public function elkeweek()
    {
        // 0 0 * * 1 /opt/plesk/php/7.4/bin/php /var/www/vhosts/localhost/httpdocs/index.php cronjobs elkeweek
        $this->_verjaardagmail();
    }

    public function _verjaardagmail()
    {

        $this->load->model('gebruikers_model');
        $this->load->model('aanmeldingen_model');
        $this->load->model('lessen_model');

        $deelnemers = $this->gebruikers_model->getDeelnemers();
        $deelnemers_bijna_jarig = array();
        $deelnemers_opsturen = array();
        $vandaag = new DateTime();
        $half_jaar_geleden = new DateTime();
        $week_later = new DateTime();
        $week_later = $week_later->add(DateInterval::createFromDateString('7 days'));
        $half_jaar_geleden = $half_jaar_geleden->sub(DateInterval::createFromDateString('6 months'));

        foreach ($deelnemers as $deelnemer) {
            if (($vandaag->format('m-d') < substr($deelnemer->gebruiker_geboortedatum, 5, 5)) && (substr($deelnemer->gebruiker_geboortedatum, 5, 5) < $week_later->format('m-d'))) {
                array_push($deelnemers_bijna_jarig, $deelnemer);
            }
        }

        if (!empty($deelnemers_bijna_jarig)) {
            foreach ($deelnemers_bijna_jarig as $item) {
                $laatste_les = '';

                $groep_lessen = $this->lessen_model->getGroepLessenByGebruikerID($item->gebruiker_ID);
                $indi_lessen = $this->lessen_model->getIndividueelLessenByGebruikerID($item->gebruiker_ID);

                $lessen = array_merge($groep_lessen, $indi_lessen);

                foreach ($lessen as $les) {
                    if (date('d-m-Y H:i:s', strtotime($les->les_datum)) > date('d-m-Y H:i:s', strtotime($laatste_les))) {
                        $laatste_les = $les->les_datum;
                    }
                }

                if ($half_jaar_geleden->format('d-m-Y H:i:s') < date('d-m-Y H:i:s', strtotime($laatste_les))) {
                    array_push($deelnemers_opsturen, $item);
                }
            }
        }

        $tekst = "";

        if (!empty($deelnemers_opsturen)) {
            foreach ($deelnemers_opsturen as $gebruiker) {
                // opsturen
                $tekst .= "<p>" . $gebruiker->gebruiker_naam . ": " . $gebruiker->gebruiker_geboortedatum . " " . $gebruiker->gebruiker_adres . " " . $gebruiker->gebruiker_postcode . " " . $gebruiker->gebruiker_plaats . "<p>";
            }

            // E-mail instellingen

            $email = array(
                'html' => $tekst,
                'subject' => "Aankomende verjaardagen",
                'from_email' => "info@localhost",
                'from_name' => "localhost",
                'to' => array(
                    array(
                        'email' => "info@localhost",
                        'name' => "localhost",
                        'type' => 'to'
                    )
                ),
                'track_opens' => true,
                'track_clicks' => true,
                'auto_text' => true
            );

            $this->load->helper('mandrill');
            $mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
            //    $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
            $feedback = $mandrill->messages->send($email);
        }
    }

    public function _groepen_archiveren()
    {
        $this->load->model('groepen_model');
        $alle_groepen = $this->groepen_model->getAlleGroepen();
        $vandaag = new DateTime();

        foreach ($alle_groepen as $groep) {
            if ($vandaag->format('Y-m-d') == $groep->groep_archief_datum) {
                $data = array(
                    'groep_archiveren' => '1'
                );

                $query = $this->groepen_model->GroepArchiverenByID($groep->groep_ID, $data);
            }
        }
    }
    private function _uitnodigingen()
    {
        $this->load->model('lessen_model');
        $this->load->model('groepen_model');

        $uitnodigingen = $this->lessen_model->getUitnodigingenMetGroepLes();
        if (sizeof($uitnodigingen)) {
            $vandaag = new DateTime();
            foreach ($uitnodigingen as $uitnodiging) {
                // Groep les datum + opgegeven aantal dagen na afloop
                $verzendDatum = new DateTime($uitnodiging->groep_les_datum);
                $verzendDatum->add(DateInterval::createFromDateString($uitnodiging->uitnodiging_dagen_na_afloop . ' days')); // aantal dagen na afloop toevoegen
                // Zelfde dag?
                if ($vandaag->format('Y/m/d') == $verzendDatum->format('Y/m/d')) {
                    $deelnemers = $this->groepen_model->getGroepDeelnemers($uitnodiging->groep_ID);
                    foreach ($deelnemers as $deelnemer) {
                        $item_tekst = $this->ReplaceTags($deelnemer->gebruiker_ID, $uitnodiging->uitnodiging_tekst, $uitnodiging->groep_ID);

                        /////////////////////
                        // BERICHT OPSLAAN //
                        /////////////////////
                        $bericht = array(
                            'bericht_onderwerp' => $uitnodiging->uitnodiging_onderwerp,
                            'bericht_tekst' => $item_tekst,
                            'bericht_datum' => date('Y-m-d H:i:s'),
                            'bericht_afzender_ID' => 1610,
                            'bericht_afzender_type' => 'deelnemer',
                            'bericht_ontvanger_ID' => $deelnemer->gebruiker_ID,
                            'bericht_no_reply' => 1
                        );

                        $verzonden = $this->berichten_model->verzendBericht($bericht);

                        $this->_verstuur_email("Nieuwe uitnodiging ontvangen", 1610, $deelnemer->gebruiker_ID);
                    }
                }
            }
        }
    }

    private function _deelnemer_update()
    {
        $this->load->model('gebruikers_model');

        $kandidaten = $this->gebruikers_model->getKandidaten();

        if (sizeof($kandidaten) > 0) {
            foreach ($kandidaten as $kandidaat) {
                $aanmeldingen = $this->gebruikers_model->getGebruikerAanmeldingenBetaald($kandidaat->gebruiker_ID);
                $kandidaat_rechten = true;

                if (sizeof($aanmeldingen) > 0) {
                    foreach ($aanmeldingen as $aanmelding) {
                        if ($aanmelding->volledige_cursistenmodule == 1) {
                            $kandidaat_rechten = false;
                        }
                    }
                }

                if (!$kandidaat_rechten) {
                    $this->gebruikers_model->updateGebruiker($kandidaat->gebruiker_ID, array('gebruiker_rechten' => "Deelnemer"));
                }
            }
        }
    }

    private function _aanmeldingen_archief()
    {
        $this->load->model('aanmeldingen_model');
        $aanmeldingen = $this->aanmeldingen_model->getAanmeldingenVoorArchief();

        if (sizeof($aanmeldingen) > 0) {
            foreach ($aanmeldingen as $aanmelding) {
                if (strtotime($aanmelding->aanmelding_datum) < mktime(0, 0, 0, date("m") - 6, date("d"),   date("Y"))) {
                    $data = array('aanmelding_archief' => 1);
                    $this->aanmeldingen_model->updateAanmeldingByID($aanmelding->aanmelding_ID, $data);
                }
            }
        }
    }

    private function _verlopen_kortingscodes()
    {
        $this->load->model('kortingscodes_model');
        $vandaag = new DateTime();

        $verlopen_codes = $this->kortingscodes_model->getVerlopenKortingscodes($vandaag->format('Y-m-d'));

        if (!empty($verlopen_codes)) {
            foreach ($verlopen_codes as $item) {
                $this->kortingscodes_model->deleteKortingscode($item->kortingscode_ID);
                $this->kortingscodes_model->deleteKortingConnecties($item->kortingscode_ID);
            }
        }
    }

    private function _aanmeldingen()
    {
        $this->load->model('aanmeldingen_model');
        $aanmeldingen = $this->aanmeldingen_model->getAanmeldingenNietBetaald();

        if (sizeof($aanmeldingen) > 0) {
            foreach ($aanmeldingen as $aanmelding) {

                $link = 'https://localhost/aanmelden/afronden/' . $aanmelding->aanmelding_type . '/' . $aanmelding->workshop_url . '/' . $aanmelding->aanmelding_ID . '/' . $aanmelding->aanmelding_code;

                if ($aanmelding->aanmelding_type == 'workshop') {
                    $email_onderwerp = 'Aanmelding ' . $aanmelding->workshop_titel . ' afronden';
                    $aangemeld_voor = $aanmelding->workshop_titel;
                } else if ($aanmelding->aanmelding_type == 'kennismakingsworkshop') {
                    $email_onderwerp = 'Aanmelding ' . $aanmelding->kennismakingsworkshop_titel . ' afronden';
                    $aangemeld_voor = $aanmelding->kennismakingsworkshop_titel;
                    $link = 'https://localhost/aanmelden/afronden/' . $aanmelding->aanmelding_type . '/' . date('d-m-Y', strtotime($aanmelding->kennismakingsworkshop_datum)) . '/' . $aanmelding->aanmelding_ID . '/' . $aanmelding->aanmelding_code;
                } else {
                    $email_onderwerp = 'Aanmelding ' . $aanmelding->aanmelding_type . ' ' . $aanmelding->workshop_titel . ' afronden';
                    $aangemeld_voor = $aanmelding->aanmelding_type . ' van de ' . $aanmelding->workshop_titel;
                }


                $email_bericht  = '<h1>' . $email_onderwerp . '</h1>';
                $email_bericht .= '<p>Beste ' . ucfirst($aanmelding->gebruiker_voornaam) . ',</p>';
                $email_bericht .= '<p>Volgens onze informatie heb je jezelf op ' . date('d-m-Y', strtotime($aanmelding->aanmelding_datum)) . ' om ' . date('H.i', strtotime($aanmelding->aanmelding_datum)) . ' uur geprobeerd aan te melden voor de ' . $aangemeld_voor . '. De betaling is alleen nog niet afgerond. <a href="' . $link . '" title="' . $email_onderwerp . '">Klik hier</a> om de betaling alsnog af te ronden.</p>';
                $email_bericht .= '<p>Let op: De link is slechts 24 uur beschikbaar, daarna wordt je aanmelding automatisch uit ons systeem verwijderd.</p>';
                $email_bericht .= '<p>Met vriendelijk groet,</p>';
                $email_bericht .= '<p>localhost</p>';

                $email = array(
                    'html' => $email_bericht,
                    'subject' => $email_onderwerp,
                    'from_email' => 'info@localhost',
                    'from_name' => 'localhost',
                    'to' => array(
                        array(
                            'email' => $aanmelding->gebruiker_emailadres,
                            'name' => $aanmelding->gebruiker_naam,
                            'type' => 'to'
                        )
                    ),
                    //'bcc_address' => 'mark@flitsend-webdesign.nl',
                    'headers' => array('Reply-To' => 'info@localhost'),
                    'track_opens' => true,
                    'track_clicks' => true,
                    'auto_text' => true
                );

                $this->_stuurEmail($email);

                // UPDATE AANMELDING

                $data = array('aanmelding_herinnering_datum' => date('Y-m-d H:i:s'));
                $this->aanmeldingen_model->updateAanmeldingByID($aanmelding->aanmelding_ID, $data);
            }
        }
    }

    public function _voorbereidingsmail()
    {
        $this->load->model('lessen_model');
        $this->load->model('gebruikers_model');
        $this->load->model('media_model');
        $this->load->model('groepen_model');
        $this->load->model('berichten_model');

        $this->gebruikers = $this->gebruikers_model->getDeelnemers();

        if (sizeof($this->gebruikers)) {
            foreach ($this->gebruikers as $this->gebruiker) {
                $les_individueel = array();
                $les_groep = array();

                $les_individueel_temp = $this->lessen_model->getIndividueelLessenByGebruikerID($this->gebruiker->gebruiker_ID);
                $les_groep_temp = $this->lessen_model->getGroepLessenByGebruikerID($this->gebruiker->gebruiker_ID);

                if (!empty($les_individueel_temp)) {
                    $les_individueel = $les_individueel_temp;
                }
                if (!empty($les_groep_temp)) {
                    $les_groep = $les_groep_temp;
                }

                $this->lessen = array_merge($les_groep, $les_individueel);

                if (!empty($this->lessen)) {
                    $laatste_les_datum = new DateTime;
                    $laatste_workshop = '';

                    foreach ($this->lessen as $this->les_IDs) {
                        $this->les = $this->lessen_model->getLesByID($this->les_IDs->les_ID);
                        $this->groep = $this->groepen_model->getGroepByID($this->les_IDs->groep_ID);
                        $laatste_les_datum = new Datetime($this->les_IDs->les_datum);
                        $laatste_workshop = $this->les->workshop_ID;

                        if (!empty($this->groep)) {

                            if ($this->groep->groep_geautomatiseerde_mails == '1') {

                                if (!empty($this->les)) {

                                    if ($this->les->workshop_ID == $laatste_workshop) {

                                        if (!empty($this->les->les_voorbereidingsmail) && $this->les_IDs->les_voorbereidingsmail_verstuurd == 0) {

                                            $vandaag = new DateTime();

                                            if (!empty($laatste_les_datum)) {
                                                // $laatste_les_datum->add(DateInterval::createFromDateString('1 days'));
                                                $laatste_les_datum->sub(DateInterval::createFromDateString('7 days'));
                                            }

                                            if ($laatste_les_datum->format('Y/m/d') == $vandaag->format('Y/m/d')) {
                                                $media = array();

                                                $voorbereidingsmail_media = $this->media_model->getMediaByContentID('voorbereidingsmail', $this->les->les_ID);

                                                if ($this->les_IDs->groep_ID) {
                                                    $item_tekst = $this->ReplaceTags($this->gebruiker->gebruiker_ID, $this->les->les_voorbereidingsmail, $this->les_IDs->groep_ID);
                                                } else {
                                                    $item_tekst = $this->ReplaceTags($this->gebruiker->gebruiker_ID, $this->les->les_voorbereidingsmail);
                                                }

                                                /////////////////////
                                                // BERICHT OPSLAAN //
                                                /////////////////////
                                                $bericht = array(
                                                    'bericht_onderwerp' => $this->les->les_titel,
                                                    'bericht_tekst' => $item_tekst,
                                                    'bericht_datum' => date('Y-m-d H:i:s'),
                                                    'bericht_afzender_ID' => 1610,
                                                    'bericht_afzender_type' => 'deelnemer',
                                                    'bericht_ontvanger_ID' => $this->gebruiker->gebruiker_ID,
                                                    'bericht_no_reply' => 1
                                                );

                                                $verzonden = $this->berichten_model->verzendBericht($bericht);

                                                if (!empty($voorbereidingsmail_media)) {
                                                    foreach ($voorbereidingsmail_media as $item) {
                                                        $connectie = array('media_ID' => $item->media_ID, 'media_positie' => $item->media_positie, 'content_type' => 'bericht', 'content_ID' => $verzonden);
                                                        $this->media_model->insertMediaConnectie($connectie);
                                                    }
                                                }

                                                if ($this->gebruiker->gebruiker_instelling_email_updates == 'ja') {
                                                    $this->_verstuur_email($this->les->les_titel, 1610, $this->gebruiker->gebruiker_ID);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function _verstuur_email($onderwerp, $afzender_ID, $ontvanger_ID)
    {
        // Gegevens van afzender en ontvanger ophalen

        $this->load->model('gebruikers_model');
        $afzender    = $this->gebruikers_model->getGebruikerByID($afzender_ID);
        $ontvanger     = $this->gebruikers_model->getGebruikerByID($ontvanger_ID);


        // Afzender en ontvanger initialiseren

        $email_van_emailadres = 'info@localhost';
        $email_van_naam = 'localhost';
        $email_aan_emailadres = $ontvanger->gebruiker_emailadres;
        $email_aan_naam = $ontvanger->gebruiker_naam;


        // Ander e-mailadres instellen voor berichten aan de docent

        if ($ontvanger->gebruiker_ID == 1610) $email_aan_emailadres = 'berichten@localhost';


        // E-mail bericht opstellen

        $email_bericht = '
			<h1>' . $onderwerp . '</h1>
			<p>Beste ' . $ontvanger->gebruiker_voornaam . ',</p>
			<p>Je hebt een nieuw bericht ontvangen van ' . $afzender->gebruiker_naam . '. Ga naar de <a href="https://localhost" title="Bezoek de website van localhost" target="_blank">Cursistenmodule</a> om het bericht te lezen en eventueel te beantwoorden.</p>
			<p>Met vriendelijke groet,</p>
			<p>localhost</p>';


        // E-mail instellingen

        $email = array(
            'html' => $email_bericht,
            'subject' => $onderwerp,
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



        /////////////////////////////
        // E-MAIL UPDATE VERZENDEN //
        /////////////////////////////

        $this->load->helper('mandrill');
        $mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
        //    $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
        $feedback = $mandrill->messages->send($email);

        if ($feedback[0]['status'] == 'sent') {
            // echo 'E-mail verzonden';
        } else {
            // echo 'Er kon geen e-mail worden verzonden';
        }
    }

    private function _aantaldeelnemerscheck()
    {
        $this->load->model('groepen_model');
        $this->load->model('gebruikers_model');
        $this->load->model('media_model');
        $this->load->model('berichten_model');

        $this->groepen = $this->groepen_model->getGroepen();

        if (sizeof($this->groepen)) {
            foreach ($this->groepen as $this->groep) {
                if (!empty($this->groep->groep_drempelwaarde_versturen) && !empty($this->groep->groep_min_gebruikers)) {
                    $datum_verzenden = new DateTime();
                    $datum_verzenden->add(DateInterval::createFromDateString($this->groep->groep_drempelwaarde_versturen . ' days'));
                    $startdatum = new DateTime($this->groep->groep_startdatum);

                    // Zelfde dag?
                    if ($datum_verzenden->format('Y/m/d') == $startdatum->format('Y/m/d')) {
                        $deelnemers = $this->groepen_model->getGroepDeelnemers($this->groep->groep_ID);

                        if (count($deelnemers) < $this->groep->groep_min_gebruikers) {
                            /////////////////////
                            // BERICHT OPSLAAN //
                            /////////////////////
                            $bericht = array(
                                'bericht_onderwerp' => $this->groep->groep_naam . " - Niet genoeg deelnemers",
                                'bericht_tekst' => "De groep " . $this->groep->groep_naam . " heeft niet genoeg deelnemers.",
                                'bericht_datum' => date('Y-m-d H:i:s'),
                                'bericht_afzender_ID' => 2031,
                                'bericht_afzender_type' => 'deelnemer',
                                'bericht_ontvanger_ID' => 1610,
                                'bericht_no_reply' => 1
                            );
                            $verzonden = $this->berichten_model->verzendBericht($bericht);
                        }
                    }
                }
            }
        }
    }

    private function _feedbackmail()
    {
        $this->load->model('workshops_model');
        $this->load->model('lessen_model');
        $this->load->model('gebruikers_model');
        $this->load->model('media_model');
        $this->load->model('groepen_model');
        $this->load->model('berichten_model');

        $this->workshops = $this->workshops_model->getWorkshops();

        if (sizeof($this->workshops)) {
            foreach ($this->workshops as $this->workshop) {
                if (!empty($this->workshop->workshop_feedbackmail)) {
                    $groepen = $this->groepen_model->getGroepenByWorkshopID($this->workshop->workshop_ID);

                    if (!empty($groepen)) {
                        foreach ($groepen as $groep) {
                            if ($groep->groep_feedback_mail == '1') {
                                $vandaag = new DateTime();
                                $laatsteLesDatum = new DateTime();
                                $lessen = $this->lessen_model->getLessenByGroepID($groep->groep_ID);

                                for ($i = 0; $i < sizeof($lessen); $i++) {
                                    $laatsteLesDatum = new DateTime($lessen[$i]->groep_les_datum);
                                }

                                $vandaag->sub(DateInterval::createFromDateString($this->workshop->feedbackmail_dagen_erna_versturen . ' days')); // aantal dagen na afloop toevoegen

                                // Zelfde dag?
                                if ($vandaag->format('Y/m/d') == $laatsteLesDatum->format('Y/m/d')) {
                                    $deelnemers = $this->groepen_model->getGroepDeelnemers($groep->groep_ID);

                                    if (!empty($deelnemers)) {
                                        foreach ($deelnemers as $deelnemer) {
                                            $media = array();
                                            $feedbackmail_media = $this->media_model->getMediaByContentID('feedbackmail', $this->workshop->workshop_ID);

                                            if ($deelnemer->aanmelding_betaald_datum != '0000-00-00 00:00:00') {
                                                $item_tekst = $this->ReplaceTags($deelnemer->gebruiker_ID, $this->workshop->workshop_feedbackmail, $groep->groep_ID);

                                                /////////////////////
                                                // BERICHT OPSLAAN //
                                                /////////////////////
                                                $bericht = array(
                                                    'bericht_onderwerp' => "Feedback op " . $this->workshop->workshop_titel . "?",
                                                    'bericht_tekst' => $item_tekst,
                                                    'bericht_datum' => date('Y-m-d H:i:s'),
                                                    'bericht_afzender_ID' => 1610,
                                                    'bericht_afzender_type' => 'deelnemer',
                                                    'bericht_ontvanger_ID' => $deelnemer->gebruiker_ID,
                                                    'bericht_no_reply' => 1
                                                );

                                                $verzonden = $this->berichten_model->verzendBericht($bericht);

                                                if (!empty($feedbackmail_media)) {
                                                    foreach ($feedbackmail_media as $item) {
                                                        $connectie = array('media_ID' => $item->media_ID, 'media_positie' => $item->media_positie, 'content_type' => 'bericht', 'content_ID' => $verzonden);
                                                        $this->media_model->insertMediaConnectie($connectie);
                                                    }
                                                }

                                                if ($deelnemer->gebruiker_instelling_email_updates == 'ja') {
                                                    $this->_verstuur_email($this->workshop->workshop_titel, 1610, $deelnemer->gebruiker_ID);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function _downloadlinkmail()
    {
        $this->load->model('groepen_model');
        $this->load->model('gebruikers_model');
        $this->load->model('media_model');
        $this->load->model('berichten_model');

        $this->groepen = $this->groepen_model->getGroepen();

        if (sizeof($this->groepen)) {
            foreach ($this->groepen as $this->groep) {
                if ($this->groep->groep_geautomatiseerde_mails == '1') {
                    if (!empty($this->groep->groep_downloadlinkmail)) {
                        $vandaag = new DateTime();
                        $startdatum = new DateTime($this->groep->groep_startdatum);

                        // Zelfde dag?
                        if ($vandaag->format('Y/m/d') == $startdatum->format('Y/m/d')) {
                            $deelnemers = $this->groepen_model->getGroepDeelnemers($this->groep->groep_ID);

                            if (!empty($deelnemers)) {
                                foreach ($deelnemers as $deelnemer) {
                                    $media = array();
                                    $downloadlinkmail_media = $this->media_model->getMediaByContentID('downloadlinkmail', $this->groep->workshop_ID);

                                    if ($deelnemer->aanmelding_betaald_datum != '0000-00-00 00:00:00') {
                                        $item_tekst = $this->ReplaceTags($deelnemer->gebruiker_ID, $this->groep->groep_downloadlinkmail, $this->groep->groep_ID);

                                        /////////////////////
                                        // BERICHT OPSLAAN //
                                        /////////////////////
                                        $bericht = array(
                                            'bericht_onderwerp' => "Downloadlink voor " . $this->workshop->workshop_titel,
                                            'bericht_tekst' => $item_tekst,
                                            'bericht_datum' => date('Y-m-d H:i:s'),
                                            'bericht_afzender_ID' => 1610,
                                            'bericht_afzender_type' => 'deelnemer',
                                            'bericht_ontvanger_ID' => $deelnemer->gebruiker_ID,
                                            'bericht_no_reply' => 1
                                        );
                                        $verzonden = $this->berichten_model->verzendBericht($bericht);

                                        if (!empty($downloadlinkmail_media)) {
                                            foreach ($downloadlinkmail_media as $item) {
                                                $connectie = array('media_ID' => $item->media_ID, 'media_positie' => $item->media_positie, 'content_type' => 'bericht', 'content_ID' => $verzonden);
                                                $this->media_model->insertMediaConnectie($connectie);
                                            }
                                        }

                                        if ($deelnemer->gebruiker_instelling_email_updates == 'ja') {
                                            $this->_verstuur_email($this->groep->workshop_titel, 1610, $deelnemer->gebruiker_ID);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function _bestellingen()
    {
        $this->load->model('bestellingen_model');
        $bestellingen = $this->bestellingen_model->getBestellingenLosNietBetaald();
        if (sizeof($bestellingen) > 0) {
            foreach ($bestellingen as $bestelling) {
                $link = 'https://localhost/cursistenmodule/bestellen/afronden/' . $bestelling->bestelling_ID;
                $email_onderwerp = 'Bestelling afronden';
                $email_bericht  = '<h1>' . $email_onderwerp . '</h1>';
                $email_bericht .= '<p>Beste ' . ucfirst($bestelling->gebruiker_voornaam) . ',</p>';
                $email_bericht .= '<p>Volgens onze informatie heb je op ' . date('d-m-Y', strtotime($bestelling->bestelling_datum)) . ' om ' . date('H.i', strtotime($bestelling->bestelling_datum)) . ' uur een bestelling gedaan op localhost. De betaling is alleen nog niet afgerond. <a href="' . $link . '" title="' . $email_onderwerp . '">Klik hier</a> om de betaling alsnog af te ronden. </p>';
                $email_bericht .= '<p>Let op: De link is slechts 24 uur beschikbaar, daarna wordt je bestelling automatisch uit ons systeem verwijderd.</p>';
                $email_bericht .= '<p>Met vriendelijk groet,</p>';
                $email_bericht .= '<p>localhost</p>';
                $email = array(
                    'html' => $email_bericht,
                    'subject' => $email_onderwerp,
                    'from_email' => 'info@localhost',
                    'from_name' => 'localhost',
                    'to' => array(
                        array(
                            'email' => $bestelling->gebruiker_emailadres,
                            'name' => $bestelling->gebruiker_naam,
                            'type' => 'to'
                        )
                    ),
                    //'bcc_address' => 'mark@flitsend-webdesign.nl',
                    'headers' => array('Reply-To' => 'info@localhost'),
                    'track_opens' => true,
                    'track_clicks' => true,
                    'auto_text' => true
                );
                $this->_stuurEmail($email);
                // UPDATE BESTELLING
                $data = array('bestelling_herinnering_datum' => date('Y-m-d H:i:s'));
                $this->bestellingen_model->updateBestellingLos($bestelling->bestelling_ID, $data);
            }
        }
    }
    private function _stuurEmail($email)
    {
        $this->mandrill->messages->send($email);
    }

    private function _verlopen_aanmeldingen()
    {
        $this->load->model('aanmeldingen_model');
        $aanmeldingen = $this->aanmeldingen_model->getAanmeldingenZijnVerlopen();

        // MailChimp API credentials
        $apiKey = 'd64e65814f08ffb01a43beced5404ff6-us7';
        $listID = '76cbccab02';

        if (sizeof($aanmeldingen) > 0) {
            foreach ($aanmeldingen as $aanmelding) {
                $this->load->model('aanmeldingen_model');
                $memberID = md5(strtolower($aanmelding->gebruiker_emailadres));
                $dataCenter = substr($apiKey, strpos($apiKey, '-') + 1);
                $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $memberID;

                if ($aanmelding->aanmelding_type == "stemtest") {
                    $type = "Ja";
                } else {
                    $type = "";
                }

                $json = json_encode([
                    'email_address' => $aanmelding->gebruiker_emailadres,
                    'status' => 'subscribed',
                    'merge_fields' => [
                        'EMAIL' => $aanmelding->gebruiker_emailadres,
                        'NAME' => $aanmelding->gebruiker_naam,
                        'WORKSHOP' => $aanmelding->workshop_titel,
                        'SOORT' => 'niet betalend',
                        'DATUM' => '',
                        'STEMTEST'  => $type
                    ]
                ]);

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                $result = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                $data = array(
                    'aanmelding_verlopen' => 1
                );

                $this->aanmeldingen_model->updateAanmeldingByID($aanmelding->aanmelding_ID, $data);
            }
        }
    }

    private function _verlopen_bestellingen()
    {
        $this->load->model('bestellingen_model');
        $bestellingen = $this->bestellingen_model->getBestellingenLosVerlopen();
        if (sizeof($bestellingen) > 0) {
            foreach ($bestellingen as $bestelling) {
                if ($this->bestellingen_model->deleteBestellingLos($bestelling->bestelling_ID)) {
                    echo 'Bestelling ' . $bestelling->bestelling_ID . ' van ' . $bestelling->gebruiker_naam . ' verwijderd<br />';
                } else {
                    echo 'Bestelling ' . $bestelling->bestelling_ID . ' van ' . $bestelling->gebruiker_naam . ' NIET verwijderd<br />';
                }
            }
        }
    }

    private function _herinneringsmail()
    {
        $this->load->model('workshops_model');
        $this->load->model('gebruikers_model');
        $this->load->model('media_model');
        $this->load->model('groepen_model');
        $this->load->model('berichten_model');

        $this->workshops = $this->workshops_model->getWorkshops();

        if (sizeof($this->workshops)) {
            foreach ($this->workshops as $this->workshop) {
                if (!empty($this->workshop->workshop_herinneringsmail)) {
                    $groepen = $this->groepen_model->getGroepenByWorkshopID($this->workshop->workshop_ID);
                    if (!empty($groepen)) {
                        foreach ($groepen as $groep) {
                            $vandaag = new DateTime();
                            $verzendDatum = new DateTime($groep->groep_startdatum);
                            $vandaag->add(DateInterval::createFromDateString($this->workshop->herinneringsmail_dagen_ervoor_versturen . ' days')); // aantal dagen na afloop toevoegen

                            if ($groep->groep_geautomatiseerde_mails == '1') {
                                // Zelfde dag?
                                if ($vandaag->format('Y/m/d') == $verzendDatum->format('Y/m/d')) {
                                    $deelnemers = $this->groepen_model->getGroepDeelnemers($groep->groep_ID);

                                    if (!empty($deelnemers)) {
                                        foreach ($deelnemers as $deelnemer) {
                                            $media = array();
                                            $herinneringsmail_media = $this->media_model->getMediaByContentID('herinneringsmail', $this->workshop->workshop_ID);

                                            if ($deelnemer->aanmelding_betaald_datum != '0000-00-00 00:00:00') {
                                                $item_tekst = $this->ReplaceTags($deelnemer->gebruiker_ID, $this->workshop->workshop_herinneringsmail, $groep->groep_ID);

                                                /////////////////////
                                                // BERICHT OPSLAAN //
                                                /////////////////////
                                                $bericht = array(
                                                    'bericht_onderwerp' => "Herinneringsmail voor " . $this->workshop->workshop_titel,
                                                    'bericht_tekst' => $item_tekst,
                                                    'bericht_datum' => date('Y-m-d H:i:s'),
                                                    'bericht_afzender_ID' => 1610,
                                                    'bericht_afzender_type' => 'deelnemer',
                                                    'bericht_ontvanger_ID' => $deelnemer->gebruiker_ID,
                                                    'bericht_no_reply' => 1
                                                );

                                                $verzonden = $this->berichten_model->verzendBericht($bericht);

                                                if (!empty($herinneringsmail_media)) {
                                                    foreach ($herinneringsmail_media as $item) {
                                                        $connectie = array('media_ID' => $item->media_ID, 'media_positie' => $item->media_positie, 'content_type' => 'bericht', 'content_ID' => $verzonden);
                                                        $this->media_model->insertMediaConnectie($connectie);
                                                    }
                                                }

                                                if ($deelnemer->gebruiker_instelling_email_updates == 'ja') {
                                                    $this->_verstuur_email($this->workshop->workshop_titel, 1610, $deelnemer->gebruiker_ID);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    function ReplaceTags($gebruiker_ID, $email_tekst, $groep_ID = null)
    {
        $this->load->model('gebruikers_model');
        $this->load->model('groepen_model');
        $this->load->model('workshops_model');

        $deelnemer = $this->gebruikers_model->getGebruikerByID($gebruiker_ID);

        $email_tekst = str_replace('[voornaam]', $deelnemer->gebruiker_voornaam, $email_tekst);
        $email_tekst = str_replace('[achternaam]', $deelnemer->gebruiker_achternaam, $email_tekst);

        if ($groep_ID) {
            $start_datum  = $this->groepen_model->getStartdatumByGroepID($groep_ID);
            $workshop = $this->workshops_model->getWorkshopByGroepID($groep_ID);

            $start_datum = new DateTime($start_datum);

            $email_tekst = str_replace('[workshop]', $workshop[0]->workshop_titel, $email_tekst);
            $email_tekst = str_replace('[startdatum]', $start_datum->format('d-m-Y'), $email_tekst);
        }

        return  $email_tekst;
    }

    public function _deelnemerslijst()
    {
        $this->load->model('lessen_model');

        $lessen = $this->lessen_model->getAanstaandeLessen_new();

        if ($lessen) {

            foreach ($lessen as $les) {

                $les_time = date('H:i:s', strtotime($les->groep_les_datum));
                $workshop_ID = $les->workshop_ID;
                $group_ID = $les->groupID;
                $les_ID = $les->les_ID;
                $group_name = $les->groep_naam;
                $docent = $this->lessen_model->getTeacherEmail($les->docent_ID);
                $email = $docent->Email;
                $gebruiker = $this->lessen_model->getAanmeldingByID($workshop_ID, $group_ID, $les_ID);
                $contentdata ='<table style="width:100%; border-collapse: collapse; border: 1px solid black;" >
                                <thead>
                                <tr>
                                    <th style="border: 1px solid black ; text-align:start" scope="col">Deelnemer</th>
                                    <th style="border: 1px solid black ; text-align:start" scope="col">Telefoonnummer</th>
                                    <th style="border: 1px solid black ; text-align:start" scope="col">Mobiel</th>
                                    <th style="border: 1px solid black ; text-align:start" scope="col">Email</th>
                                    <th style="border: 1px solid black ; text-align:start" scope="col">Aanwezig</th>
                                </tr>
                                </thead>
                                <tbody>';
                            if (!empty($gebruiker)) {
                                foreach ($gebruiker as $key => $group) {
                                    if ($group->aanwezigheid_aanwezig == "nee") {
                                        $attendance = 'nee';
                                    } else {
                                        $attendance = 'ja';
                                    }
                $contentdata .=     '<tr>
                                        <td style="border: 1px solid black ;" >' . $group->gebruiker_naam . '</td>
                                        <td style="border: 1px solid black ;" >' . $group->gebruiker_telefoonnummer
                                        . '</td>
                                        <td style="border: 1px solid black ;" >' . $group->gebruiker_mobiel . '</td>
                                        <td style="border: 1px solid black ;" >' . $group->gebruiker_emailadres .
                                        '</td>
                                        <td style="border: 1px solid black ;" >' . $attendance . '</td>
                                    </tr>';
                                                }
                                            }
                $contentdata .= '</tbody></table>';
                $email_bericht = '<h1>Deelnemerslijst - ' . $group_name . '</h1>';
                $email_bericht .= '<p>In deze tabel vind je de deelnemerslijst van groep' . $group_name .
                ' voor de les die om ' . $les_time . ' gaat beginnen</p>';
                $email_bericht .= $contentdata;
                $email_bericht .= '<p>Met vriendelijke groet,</p>';
                $email_bericht .= '<p>localhost</p>';
                $email = array(
                    'html' => $email_bericht,
                    'subject' => 'Deelnemerslijst - ' . $group_name,
                    'from_email' => 'info@localhost',
                    'from_name' => 'localhost',
                    'to' => array(
                        array(
                            'email' => $email,
                            'name' => 'localhost',
                            'type' => 'to'
                        )
                    ),
                    'bcc_address' => 'edwin@brainstormit.nl',
                    'headers' => array('Reply-To' => 'info@localhost'),
                    'track_opens' => true,
                    'track_clicks' => true,
                    'auto_text' => true
                );

                $this->load->helper('mandrill');
                $mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
                // $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
                $mandrill->messages->send($email);
                exit;
            }
        } else {
            //get from les table
        }
    }
}
