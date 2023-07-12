<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lessen extends CI_Controller
{
    private $data = array();

    public function __construct()
    {
        parent::__construct();

        // Rechten controleren en aantal nieuwe items ophalen

        $this->load->helper('mandrill');
        // $mandrill = new Mandrill('06imZPB0ZEpogG0DZLzQPA'); // test
        $this->mandrill = new Mandrill('fuFafz8JJAoqFrYnWwDABw');
        $this->load->library('algemeen');
        $this->algemeen->cms();
        if($this->session->userdata('beheerder_rechten') == 'contentmanager') redirect('cms/rechten');
    }


    /* ============= */
    /* = OVERZICHT = */
    /* ============= */

    public function index()
    {
        redirect('cms/lessen/workshops');
    }

    public function workshops() {
        $this->load->model('workshops_model');
        $workshops = $this->workshops_model->getWorkshopsLessenOverzicht();

        $this->load->model('lessen_model');
        $les_types = $this->lessen_model->getLesTypes();

        $beoordelingscriteria = $this->lessen_model->getBeoordelingscriteria();

        // PAGINA TONEN
        $this->data['workshops'] = $workshops;
        $this->data['les_types'] = $les_types;
        $this->data['beoordelingscriteria'] = $beoordelingscriteria;
        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/lessen_workshops';
        $this->load->view('cms/template', $pagina);
    }

    public function pagina($workshop_ID, $p = 1)
    {
        $this->load->model('lessen_model');
        $this->load->model('workshops_model');
        $this->load->model('docenten_model');

        $workshop = $this->workshops_model->getWorkshopByID($workshop_ID);
        if(!$workshop) redirect('cms/lessen');
        if ($this->session->userdata('beheerder_rechten') != 'docent') {
            $aantal_items = $this->lessen_model->getLessenAantalWorkshop($workshop_ID);
            $per_pagina = 10;
            $aantal_paginas = ceil($aantal_items / $per_pagina);
            $huidige_pagina = $p;
            $lessen = $this->lessen_model->getLessenWorkshop_ID($per_pagina, $huidige_pagina, $workshop_ID);

            if(!empty($lessen)) {
                foreach($lessen as $les) {
                    $beoordeling = $this->lessen_model->getAVGlesBeoordeling($les->les_ID);
                    $les->les_beoordeling = $beoordeling[0]->les_beoordeling;
                }
            }
        } else {
            /** Process for docent accounts. **/
            $docentInfo = $this->docenten_model->getDocentByGebruikerID($this->session->userdata('beheerder_ID')); // Get docent ID since docent_ID does not exist on the session variable.
            $docent_ID = (count($docentInfo) > 0 ? $docentInfo->docent_ID : 0);
            $aantal_items = $this->lessen_model->getLessenAantalDocentWorkshop($docent_ID, $workshop_ID);
            $per_pagina = 10;
            $aantal_paginas = ceil($aantal_items / $per_pagina);
            $huidige_pagina = $p;
            $lessen = $this->lessen_model->getLessenDocentWorkshop($per_pagina, $huidige_pagina, $docent_ID, $workshop_ID);
        }
        // Controleren of de paginanummer te hoog is
        if ($p > 1 && sizeof($lessen) == 0) redirect('cms/lessen');

        // PAGINA TONEN
        $this->data['lessen'] = $lessen;
        $this->data['workshop'] = $workshop;
        $this->data['aantal_paginas'] = $aantal_paginas;
        $this->data['huidige_pagina'] = $huidige_pagina;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/lessen';
        $this->load->view('cms/template', $pagina);
    }

    public function voorbereidingsmail($item_ID = null)
    {
        $item_email = '';
        $media = '';
        $fouten = 0;
        $actie = 'lessen';
        $groepen_feedback = '';
        $individu_feedback = '';

        $this->load->model('lessen_model');
        $this->load->model('media_model');
        $this->load->model('groepen_model');
        $this->load->model('workshops_model');

        $les = $this->lessen_model->getLesByID($item_ID);
        $voorbereidingsmail_media = $this->media_model->getMediaByContentID('voorbereidingsmail', $item_ID);
        $groepen_temp = $this->groepen_model->getGroepenByLesID($item_ID);
        $groepen = array();
        $individuen = $this->lessen_model->getIndividueelLessenByLesIDenWorkshopID($les->les_ID, $les->workshop_ID);

        if(sizeof($groepen_temp) > 0) {
            foreach($groepen_temp as $groep) {
                if($groep->les_voorbereidingsmail_verstuurd == 0) {
                    $groepen[] = $groep;
                }
            }
        }

        if (!empty($les->les_voorbereidingsmail)) {
            $item_email = $les->les_voorbereidingsmail;
        }

        if (!empty($voorbereidingsmail_media)) {
            $media = $voorbereidingsmail_media;
        }

        // form submitted
        if (isset($_POST['item_email'])) {
            if ($fouten == 0) {
                $item_email = trim($_POST['item_email']);

                $data = array(
                    'les_voorbereidingsmail' => $item_email,
                );

                $q = $this->lessen_model->updateLes($item_ID, $data);

                $this->media_model->verwijderConnecties('voorbereidingsmail', $item_ID);

                $media_IDs = explode(',', $_POST['item_media']);

                for ($i = 0; $i < sizeof($media_IDs); $i++) {
                    if ($media_IDs[$i] > 0) {
                        $connectie = array('media_ID' => $media_IDs[$i], 'media_positie' => $i, 'content_type' => 'voorbereidingsmail', 'content_ID' => $item_ID);
                        $this->media_model->insertMediaConnectie($connectie);
                    }
                }
                redirect('cms/lessen/wijzigen/' . $item_ID);
            }
        }

        $this->data['groepen_feedback'] = $groepen_feedback;
        $this->data['individu_feedback'] = $individu_feedback;
        $this->data['les_titel'] = $les->les_titel;
        $this->data['groepen'] = $groepen;
        $this->data['individuen'] = $individuen;
        $this->data['actie'] = $actie;
        $this->data['item_ID'] = $item_ID;
        $this->data['media'] = $media;
        $this->data['item_email'] = $item_email;

        // PAGINA TONEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/voorbereidingsmail_wijzigen';
        $this->load->view('cms/template', $pagina);
    }

    public function voorbereidingsmail_versturen($item_ID)
    {
        if (empty($item_ID)) {
            redirect('cms/lessen/wijzigen/' . $item_ID);
        }

        $item_email = '';
        $media = '';
        $fouten = 0;
        $actie = 'lessen';
        $groepen_feedback = '';
        $individu_feedback = '';

        $this->load->model('lessen_model');
        $this->load->model('media_model');
        $this->load->model('groepen_model');
        $this->load->model('gebruikers_model');

        $les = $this->lessen_model->getLesByID($item_ID);
        $voorbereidingsmail_media = $this->media_model->getMediaByContentID('voorbereidingsmail', $item_ID);
        $groepen_temp = $this->groepen_model->getGroepenByLesID($item_ID);
        $groepen = array();
        $individuen = $this->lessen_model->getIndividueelLessenByLesIDenWorkshopID($les->les_ID, $les->workshop_ID);

        if(sizeof($groepen_temp) > 0) {
            foreach($groepen_temp as $groep) {
                if($groep->les_voorbereidingsmail_verstuurd == 0) {
                    $groepen[] = $groep;
                }
            }
        }

        if (!empty($les->les_voorbereidingsmail)) {
            $item_email = $les->les_voorbereidingsmail;
        }

        if (!empty($voorbereidingsmail_media)) {
            $media = $voorbereidingsmail_media;
        }


        $les = $this->lessen_model->getLesByID($item_ID);

        if($les->workshop_type == 'groep' || $les->workshop_type == 'online') {
            $planned_les = $this->lessen_model->getGroepLesByLesID($item_ID, $_POST['Groepen']);
        } else {
            $planned_les = $this->lessen_model->getLesIndividuByID($item_ID);
        }

        if(!empty($_POST['Groepen']) && !empty($les->les_voorbereidingsmail)) {
                $gebruikers = $this->groepen_model->getGroepDeelnemers($_POST['Groepen']);

                if (sizeof($gebruikers)) {
                    if ($planned_les->les_voorbereidingsmail_verstuurd == 0) {
                        foreach ($gebruikers as $gebruiker) {
                            if (!empty($les->les_voorbereidingsmail)) {
                                $voorbereidingsmail_media = $this->media_model->getMediaByContentID('voorbereidingsmail', $item_ID);

                                if ($les->workshop_type == 'groep' || $les->workshop_type == 'online') {
                                    $item_tekst = $this->ReplaceTags($gebruiker->gebruiker_ID, $les->les_voorbereidingsmail, $planned_les->groep_ID);
                                } else {
                                    $item_tekst = $this->ReplaceTags($gebruiker->gebruiker_ID, $les->les_voorbereidingsmail);
                                        }

                                /////////////////////
                                // BERICHT OPSLAAN //
                                /////////////////////
                                $bericht = array(
                                    'bericht_onderwerp' => $les->les_titel,
                                    'bericht_tekst' => $item_tekst,
                                    'bericht_datum' => date('Y-m-d H:i:s'),
                                    'bericht_afzender_ID' => 1610,
                                    'bericht_afzender_type' => 'deelnemer',
                                    'bericht_ontvanger_ID' => $gebruiker->gebruiker_ID,
                                    'bericht_no_reply' => 1
                                );

                                $verzonden = $this->berichten_model->verzendBericht($bericht);

                                if (!empty($voorbereidingsmail_media)) {
                                    foreach($voorbereidingsmail_media as $item) {
                                        $connectie = array('media_ID' => $item->media_ID, 'media_positie' => $item->media_positie, 'content_type' => 'bericht', 'content_ID' => $verzonden);
                                        $this->media_model->insertMediaConnectie($connectie);
                                    }
                                            }

                                if($gebruiker->gebruiker_instelling_email_updates == 'ja') {
                                    $this->_verstuur_email($les->les_titel, 1610, $gebruiker->gebruiker_ID);
                                }
                            }
                        }
                        $data = array(
                            'les_voorbereidingsmail_verstuurd' => 1
                        );

                        if(!empty($planned_les->groep_les_ID)) {
                            $q = $this->lessen_model->updateGroepLes($planned_les->groep_les_ID, $data);
                        } else {
                            $q = $this->lessen_model->updateIndividuLes($planned_les->individu_les_ID, $data);
                        }

                        if($q)
                        {
                            redirect('cms/lessen/wijzigen/'.$item_ID);
                        }
                        else
                        {
                            echo 'Er ging iets mis probeer het nog een keer';
                        }
                    }
                }
            }


        $this->data['groepen_feedback'] = $groepen_feedback;
        $this->data['individu_feedback'] = $individu_feedback;
        $this->data['les_titel'] = $les->les_titel;
        $this->data['groepen'] = $groepen;
        $this->data['individuen'] = $individuen;
        $this->data['actie'] = $actie;
        $this->data['item_ID'] = $item_ID;
        $this->data['media'] = $media;
        $this->data['item_email'] = $item_email;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/voorbereidingsmail_wijzigen';
        $this->load->view('cms/template', $pagina);
    }

    private function _verstuur_email($onderwerp, $afzender_ID, $ontvanger_ID)
    {
        // Gegevens van afzender en ontvanger ophalen

        $this->load->model('gebruikers_model');
        $afzender   = $this->gebruikers_model->getGebruikerByID($afzender_ID);
        $ontvanger  = $this->gebruikers_model->getGebruikerByID($ontvanger_ID);


        // Afzender en ontvanger initialiseren

        $email_van_emailadres = 'info@localhost';
        $email_van_naam = 'localhost';
        $email_aan_emailadres = $ontvanger->gebruiker_emailadres;
        $email_aan_naam = $ontvanger->gebruiker_naam;


        // Ander e-mailadres instellen voor berichten aan de docent

        if($ontvanger->gebruiker_ID == 1610) $email_aan_emailadres = 'berichten@localhost';


        // E-mail bericht opstellen

        $email_bericht = '
            <h1>'.$onderwerp.'</h1>
            <p>Beste '.$ontvanger->gebruiker_voornaam.',</p>
            <p>Je hebt een nieuw bericht ontvangen van '.$afzender->gebruiker_naam.'. Ga naar de <a href="https://localhost" title="Bezoek de website van localhost" target="_blank">Cursistenmodule</a> om het bericht te lezen en eventueel te beantwoorden.</p>
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

        if($feedback[0]['status'] == 'sent')
        {
            // echo 'E-mail verzonden';
        }
        else
        {
            // echo 'Er kon geen e-mail worden verzonden';
        }
    }

    public function voorbereidingsmail_versturen_individu($item_ID)
    {
        if (empty($item_ID)) {
            redirect('cms/lessen/wijzigen/' . $item_ID);
        }

        $item_email = '';
        $media = '';
        $fouten = 0;
        $actie = 'lessen';
        $groepen_feedback = '';
        $individu_feedback = '';

        $this->load->model('lessen_model');
        $this->load->model('media_model');
        $this->load->model('groepen_model');
        $this->load->model('gebruikers_model');

        $les = $this->lessen_model->getLesByID($item_ID);
        $voorbereidingsmail_media = $this->media_model->getMediaByContentID('voorbereidingsmail', $item_ID);
        $groepen_temp = $this->groepen_model->getGroepenByLesID($item_ID);
        $groepen = array();
        $individuen = $this->lessen_model->getIndividueelLessenByLesIDenWorkshopID($les->les_ID, $les->workshop_ID);

        if(sizeof($groepen_temp) > 0) {
            foreach($groepen_temp as $groep) {
                if($groep->les_voorbereidingsmail_verstuurd == 0) {
                    $groepen[] = $groep;
                }
            }
        }

        if (!empty($les->les_voorbereidingsmail)) {
            $item_email = $les->les_voorbereidingsmail;
        }

        if (!empty($voorbereidingsmail_media)) {
            $media = $voorbereidingsmail_media;
        }

        $les = $this->lessen_model->getLesByID($item_ID);

        $planned_les = $this->lessen_model->getLesIndibiduByLesIDenGebruiker_ID($item_ID, $_POST['deelnemers']);

        if(!empty($_POST['deelnemers']) && !empty($les->les_voorbereidingsmail)) {
            if (sizeof($planned_les)) {
                if ($planned_les->les_voorbereidingsmail_verstuurd == 0) {
                        if (!empty($les->les_voorbereidingsmail)) {
                            $media = array();
                            $voorbereidingsmail_media = $this->media_model->getMediaByContentID('voorbereidingsmail', $item_ID);

                            if (!empty($voorbereidingsmail_media)) {
                                foreach ($voorbereidingsmail_media as $item) {
                                    if ($item->media_type == 'pdf') {
                                        $media_src = base_url('media/pdf/' . $item->media_src);
                                        $extension = '.pdf';
                                    } elseif ($item->media_type == 'afbeelding') {
                                        $media_src = base_url('media/afbeeldingen/origineel/' . $item->media_src);
                                        $extension = '.png';
                                    } elseif ($item->media_type == 'mp3') {
                                        $media_src = base_url('media/audio/' . $item->media_src);
                                        $extension = '.mp3';
                                    }

                                    if ($item->media_type != 'video') {
                                        $attachment = file_get_contents($media_src);
                                        $attachment_encoded = base64_encode($attachment);

                                        if ($item->media_type == 'pdf') {
                                            $filehandle = fopen('media/pdf/' . $item->media_src, 'rb');
                                            $data = fread($filehandle, filesize('media/pdf/' . $item->media_src));
                                            fclose($filehandle);

                                            $attachment_encoded = chunk_split(base64_encode($data));
                                        }

                                        $media[] = array('content' => $attachment_encoded, 'type' => $item->media_type, 'name' => $item->media_titel . $extension);
                                    }
                                }
                            }

                            $email = array(
                                'html' => $les->les_voorbereidingsmail,
                                'subject' => $les->les_titel,
                                'from_email' => 'info@localhost',
                                'from_name' => 'info@localhost',
                                'to' => array(
                                    array(
                                        'email' => $planned_les->gebruiker_emailadres,
                                        'name' => $planned_les->gebruiker_naam,
                                        'type' => 'to'
                                    )
                                ),
                                'bcc_address' => '',
                                'headers' => array('Reply-To' => 'info@localhost'),
                                'track_opens' => true,
                                'track_clicks' => true,
                                'auto_text' => true,
                                'attachments' =>
                                    $media
                            );
                            $this->mandrill->messages->send($email);
                        }
                    }
                    $data = array(
                        'les_voorbereidingsmail_verstuurd' => 1
                    );

                    $this->lessen_model->updateIndividuLes($planned_les->individu_les_ID, $data);
                }
        }

        $this->data['groepen_feedback'] = $groepen_feedback;
        $this->data['individu_feedback'] = $individu_feedback;
        $this->data['les_titel'] = $les->les_titel;
        $this->data['groepen'] = $groepen;
        $this->data['individuen'] = $individuen;
        $this->data['actie'] = $actie;
        $this->data['item_ID'] = $item_ID;
        $this->data['media'] = $media;
        $this->data['item_email'] = $item_email;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/voorbereidingsmail_wijzigen';
        $this->load->view('cms/template', $pagina);
    }

    /* ============ */
    /* = BEKIJKEN = */
    /* ============ */

    public function detail($item_ID = null)
    {
        if($item_ID == null) redirect('cms/lessen');

        $this->load->model('lessen_model');
        $this->load->model('docenten_model');
        $item = $this->lessen_model->getLesByID($item_ID);
        if($item == null) redirect('cms/lessen');
        $this->data['item'] = $item;

        if ($item->docent_ID != null) {
            $docent = $this->docenten_model->getDocentByGebruikerID($item->docent_ID);
            $this->data['docent'] = $docent;
        }
        // MEDIA OPHALEN

        $this->load->model('media_model');
        $media = $this->media_model->getMediaByContentID('les', $item_ID);
        $this->data['media'] = $media;

        $media_nieuw = $this->media_model->getMediaNieuwByContentID('les', $item_ID);
        $this->data['media_nieuw'] = $media_nieuw;

        $item_type = $this->lessen_model->getLesTypeByID($item->les_type_ID);
        $this->data['item_type'] = $item_type;

        // PAGINA LADEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/lessen_detail';
        $this->load->view('cms/template', $pagina);
    }



    /* ========================= */
    /* = TOEVOEGEN EN WIJZIGEN = */
    /* ========================= */

    public function toevoegen($workshop_ID = null)
    {
        if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker') redirect('cms/rechten');
        $this->_toevoegen_wijzigen('toevoegen', $workshop_ID);
    }

    public function wijzigen($item_ID = null)
    {
        if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker') redirect('cms/rechten');
        if($item_ID == null) redirect('cms/lessen');
        $this->_toevoegen_wijzigen('wijzigen', $item_ID);
    }

    private function _toevoegen_wijzigen($actie, $item_ID = null)
    {
        if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker') redirect('cms/rechten');
        $this->load->model('lessen_model');
        $this->load->model('workshops_model');
        $this->load->model('media_model');
        $this->load->model('docenten_model');

        $item_titel                     = '';
        $item_beschrijving              = '';
        $item_les_type                  = '';
        $item_huiswerk                  = '';
        $item_huiswerk_aantal           = '';
        $item_locatie                   = '';
        $item_workshop                  = '';
        $item_docent                    = '';
        $item_voorbereidingsmail        = '';
        $item_first_les                 = false;
        $item_video_url                 = '';
        $item_video_type                = 'vimeo';
        $item_typeform_url              = '';

        $item_titel_feedback            = '';
        $item_beschrijving_feedback     = '';
        $item_huiswerk_feedback         = '';
        $item_huiswerk_aantal_feedback  = '';
        $item_locatie_feedback          = '';
        $item_workshop_feedback         = '';
        $item_video_url_feedback        = 'voorbeeld van url: https://vimeo.com/123456/123456 meerdere videos scheiden door ; https://vimeo.com/123456/123456;https://vimeo.com/123456/123456';
        $item_typeform_url_feedback     = '';
        $item_placeholder               = '';


        // FORMULIER VERZONDEN

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $fouten                 = 0;

            $item_titel             = trim($_POST['item_titel']);
            $item_beschrijving      = trim($_POST['item_beschrijving']);
            $item_type              = trim($_POST['item_type']);
            $item_media             = trim($_POST['item_media']);
            $item_huiswerk          = trim($_POST['item_huiswerk']);
            $item_huiswerk_aantal   = trim($_POST['item_huiswerk_aantal']);
            $item_workshop          = trim($_POST['item_workshop']);
            $item_docent            = trim($_POST['item_nagekeken_door']);
            $item_video_url         = trim($_POST['item_video_url']);
            $item_video_type        = trim($_POST['item_video_type']);
            $item_typeform_url      = trim($_POST['item_typeform_url']);

            if(empty($item_titel))
            {
                $fouten++;
                $item_titel_feedback = 'Graag invullen';
            }

            if(empty($item_beschrijving))
            {
                $fouten++;
                $item_beschrijving_feedback = 'Graag invullen';
            }

            if(!empty($item_huiswerk_aantal))
            {
                if(!is_numeric($item_huiswerk_aantal))
                {
                    $fouten++;
                    $item_huiswerk_aantal_feedback = 'Graag een getal invullen';
                }
                else
                {
                    if(empty($item_huiswerk))
                    {
                        $fouten++;
                        $item_huiswerk_feedback = 'Graag invullen';
                    }
                }
            }

            if(empty($item_workshop))
            {
                $fouten++;
                $item_workshop_feedback = 'Graag selecteren';
            }

            if(isset($_POST['item_locatie']))
            {
                $item_locatie = $_POST['item_locatie'];
            }

            if($fouten == 0)
            {
                // TOEVOEGEN / UPDATEN

                $data = array(
                    'les_titel' => $item_titel,
                    'les_beschrijving' => $item_beschrijving,
                    'les_type_ID' => $item_type,
                    'les_huiswerk' => $item_huiswerk,
                    'les_huiswerk_aantal' => $item_huiswerk_aantal,
                    'les_locatie' => $item_locatie,
                    'workshop_ID' => $item_workshop,
                    'docent_ID' => $item_docent,
                    'les_video_url' => $item_video_url,
                    'les_video_type' => $item_video_type,
                    'les_typeform_url' => $item_typeform_url
                );

                if($actie == 'toevoegen')
                {
                    // LES TOEVOEGEN

                    $item_ID = $this->lessen_model->insertLes($data);
                }
                else
                {
                    // VERWIJDER MEDIA

                    $this->media_model->verwijderConnecties('les', $item_ID);

                    // LES UPDATEN

                    $q = $this->lessen_model->updateLes($item_ID, $data);
                }

                // NIEUWE MEDIA KOPPELEN

                if(!empty($item_media))
                {
                    $media_IDS = explode(',', $item_media);

                    for($i = 0; $i < sizeof($media_IDS); $i++)
                    {
                        if($media_IDS[$i] > 0)
                        {
                            $connectie = array('media_ID' => $media_IDS[$i], 'media_positie' => $i, 'content_type' => 'les', 'content_ID' => $item_ID);
                            $this->media_model->insertMediaConnectie($connectie);
                        }
                    }
                }

                if($actie == 'toevoegen') redirect('cms/workshops/'.$item_workshop.'#lessen');
                else redirect('cms/lessen/'.$item_ID);
            }
        }
        else
        {
            if($actie == 'wijzigen')
            {
                $item = $this->lessen_model->getLesByID($item_ID);
                if($item == null) redirect('cms/lessen');

                $item_titel                 = $item->les_titel;
                $item_beschrijving          = $item->les_beschrijving;
                $item_huiswerk              = $item->les_huiswerk;
                $item_huiswerk_aantal       = $item->les_huiswerk_aantal;
                $item_locatie               = $item->les_locatie;
                $item_workshop              = $item->workshop_ID;
                $item_docent                = $item->docent_ID;
                $item_voorbereidingsmail    = $item->les_voorbereidingsmail;
                $item_les_type              = $item->les_type_ID;
                $item_video_url             = $item->les_video_url;
                $item_video_type            = $item->les_video_type;
                $item_typeform_url          = $item->les_typeform_url;

                $lessen = $this->lessen_model->getLessenWorkshop($item->workshop_ID);

                $i = 1;

                if (!empty($lessen)) {
                    foreach ($lessen as $les) {
                        if ($les->les_ID != $item_ID) {
                            $i = $i + 1;
                        } else {
                            break;
                        }
                    }
                }

                if ($i == 1) {
                    $item_first_les  = true;
                }
            }
            else
            {
                $item_workshop = $item_ID;
            }
        }


        // WORKSHOP OPHALEN

        $workshops = $this->workshops_model->getLessenWorkshops();
        $this->data['workshops'] = $workshops;


        // DOCENTEN OPHALEN

        $docenten = $this->docenten_model->getDocenten();
        $this->data['docenten'] = $docenten;

        // MEDIA OPHALEN

        $media = $this->media_model->getMediaByContentID('les', $item_ID);
        $media_nieuw = $this->media_model->getMediaNieuwByContentID('les', $item_ID);
        $this->data['media'] = $media;

        if(!empty($media_nieuw)) {
            foreach($media_nieuw as $item) {
                if($item->media_ingang != '0000-00-00') {
                    $item_media_overschrijven = true;
                    $this->data['item_media_overschrijven'] = $item_media_overschrijven;
                }
            }
        }

        $item_placeholder = $this->media_model->getMediaByContentID('placeholder', $item_ID);

        $item_types = $this->lessen_model->getLesTypes();

            // PAGINA TONEN

        $this->data['actie'] = $actie;

        $this->data['item_types']               = $item_types;
        $this->data['item_ID']                  = $item_ID;
        $this->data['item_titel']               = $item_titel;
        $this->data['item_beschrijving']        = $item_beschrijving;
        $this->data['item_huiswerk']            = $item_huiswerk;
        $this->data['item_huiswerk_aantal']     = $item_huiswerk_aantal;
        $this->data['item_locatie']             = $item_locatie;
        $this->data['item_workshop']            = $item_workshop;
        $this->data['item_docent']              = $item_docent;
        $this->data['item_voorbereidingsmail']  = $item_voorbereidingsmail;
        $this->data['item_first_les']           = $item_first_les;
        $this->data['item_les_type']            = $item_les_type;
        $this->data['item_video_url']           = $item_video_url;
        $this->data['item_video_type']          = $item_video_type;
        $this->data['item_typeform_url']        = $item_typeform_url;
        $this->data['item_placeholder']         = $item_placeholder;

        $this->data['item_titel_feedback']              = $item_titel_feedback;
        $this->data['item_beschrijving_feedback']       = $item_beschrijving_feedback;
        $this->data['item_huiswerk_feedback']           = $item_huiswerk_feedback;
        $this->data['item_huiswerk_aantal_feedback']    = $item_huiswerk_aantal_feedback;
        $this->data['item_locatie_feedback']            = $item_locatie_feedback;
        $this->data['item_workshop_feedback']           = $item_workshop_feedback;
        $this->data['item_video_url_feedback']          = $item_video_url_feedback;
        $this->data['item_typeform_url_feedback']       = $item_typeform_url_feedback;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/lessen_wijzigen';
        $this->load->view('cms/template', $pagina);
    }



    /* =============== */
    /* = VERWIJDEREN = */
    /* =============== */

    public function verwijderen($item_ID = null, $bevestiging = null)
    {
        if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker') redirect('cms/rechten');
        if($item_ID == null) redirect('cms/lessen');

        $this->load->model('lessen_model');
        $item = $this->lessen_model->getLesByID($item_ID);
        if($item == null) redirect('cms/lessen');
        $this->data['item'] = $item;


        // > Controleren of de les is ingepland, zo ja, dit melden aan de beheerder


        // ITEM VERWIJDEREN

        if($bevestiging == 'ja')
        {
            // Verwijder media connecties

            $this->load->model('media_model');
            $this->media_model->verwijderConnecties('les', $item_ID);


            // Verwijderen groepslessen

            $this->lessen_model->deleteGroepLesByLesID($item_ID);


            // Verwijder les

            $q = $this->lessen_model->deleteLes($item_ID);
            if($q) redirect('cms/lessen');
            else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
        }


        // PAGINA TONEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/lessen_verwijderen';
        $this->load->view('cms/template', $pagina);
    }

    public function live_verwijderen($item_ID = null, $groep_ID = null, $bevestiging = null)
    {
        if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker') redirect('cms/rechten');
        if($item_ID == null) redirect('cms/groepen');

        $this->load->model('lessen_model');
        $item = $this->lessen_model->getLesByID($item_ID);
        if($item == null) redirect('cms/groepen/'.$groep_ID);
        $this->data['item'] = $item;
        $this->data['groep_ID'] = $groep_ID;

        // > Controleren of de les is ingepland, zo ja, dit melden aan de beheerder

        // ITEM VERWIJDEREN

        if($bevestiging == 'ja')
        {
            // Verwijder media connecties

            $this->load->model('media_model');
            $this->media_model->verwijderConnecties('les', $item_ID);

            // Verwijderen groepslessen

            $this->lessen_model->deleteGroepLesByLesID($item_ID);

            // Verwijder les

            $q = $this->lessen_model->deleteLes($item_ID);
            if($q) redirect('cms/groepen/' .$groep_ID);
            else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
        }


        // PAGINA TONEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/live_lessen_verwijderen';
        $this->load->view('cms/template', $pagina);
    }

    /* =============== */
    /* = VERWIJDEREN = */
    /* =============== */

    public function type_verwijderen($item_ID = null, $actie = null)
    {
        // type ophalen

        if($item_ID == null) redirect('cms/lessen');
        $this->load->model('lessen_model');
        $les_type = $this->lessen_model->getLesTypeByID($item_ID);

        $this->data['item'] = $les_type;

        /////////////////////////
        // TYPE VERWIJDEREN    //
        /////////////////////////

        if($actie == 'ja')
        {
            $q = $this->lessen_model->deleteLesType($item_ID);

            if($q) {
                redirect('cms/lessen');
            } else {
                echo 'Item kon niet worden verwijderd. Probeer het nog eens.';
            }
        }

        //////////////////
        // PAGINA TONEN //
        //////////////////

        $this->data['item_ID'] = $item_ID;
        $this->data['pagina'] = $actie;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/les_type_verwijderen';
        $this->load->view('cms/template', $pagina);
    }

    public function type_toevoegen($item_ID = null)
    {
        if($this->session->userdata('beheerder_rechten') != 'admin') redirect('cms/rechten');
        $this->_type_toevoegen_wijzigen('toevoegen', $item_ID);
    }

    public function type_wijzigen($item_ID = null)
    {
        if($item_ID == null) redirect('cms/lessen');
        $this->_type_toevoegen_wijzigen('wijzigen', $item_ID);
    }

    private function _type_toevoegen_wijzigen($actie, $item_ID = null)
    {
        $this->load->model('lessen_model');

        $item_soort             = '';
        $item_beschikbaar       = 0;
        $item_weergeven         = 0;
        $item_gekoppeld_aan     = 0;

        $item_soort_feedback    = '';


        // FORMULIER VERZONDEN

        if(isset($_POST['item_soort']))
        {
            $fouten                 = 0;
            $item_soort = trim($_POST['item_soort']);

            if(isset($_POST['item_beschikbaar']))
            {
                $item_beschikbaar = trim($_POST['item_beschikbaar']);
            }

            if(isset($_POST['item_weergeven']))
            {
                $item_weergeven = trim($_POST['item_weergeven']);
            }

            if(isset($_POST['item_gekoppeld_aan']))
            {
                $item_gekoppeld_aan = trim($_POST['item_gekoppeld_aan']);
            }

            if(isset($_POST['item_soort'])) $item_soort = $_POST['item_soort'];
            if(empty($item_soort))
            {
                $fouten++;
                $item_soort_feedback = 'Graag invullen';
            }

            if($fouten == 0)
            {
                // TOEVOEGEN / UPDATEN

                $data = array(
                    'les_type_soort' => $item_soort,
                    'les_beschikbaar' => $item_beschikbaar,
                    'les_weergeven' => $item_weergeven,
                    'les_gekoppeld_aan' => $item_gekoppeld_aan
                );

                if($actie == 'toevoegen') $q = $this->lessen_model->insertLesType($data);
                else $q = $this->lessen_model->updateLesType($item_ID, $data);

                if($q)
                {
                    if($actie == 'toevoegen') redirect('cms/lessen');
                    else redirect('cms/lessen/');
                }
                else
                {
                    echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
                }
            }
        }

        if($actie == 'wijzigen') {
            $les_type = $this->lessen_model->getLesTypeByID($item_ID);
            if($les_type == null) redirect('cms/lessen');
            $item_soort = $les_type[0]->les_type_soort;
            $item_weergeven = $les_type[0]->les_weergeven;
            $item_beschikbaar = $les_type[0]->les_beschikbaar;
            $item_gekoppeld_aan = $les_type[0]->les_gekoppeld_aan;
        }

        $this->data['item_ID']              = $item_ID;
        $this->data['actie']                = $actie;
        $this->data['item_soort']           = $item_soort;
        $this->data['item_beschikbaar']     = $item_beschikbaar;
        $this->data['item_weergeven']       = $item_weergeven;
        $this->data['item_gekoppeld_aan']   = $item_gekoppeld_aan;
        $this->data['item_soort_feedback']  = $item_soort_feedback;

        // PAGINA TONEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/les_type_wijzigen';
        $this->load->view('cms/template', $pagina);
    }


    /* ========================= */
    /* = INPLANNEN EN WIJZIGEN = */
    /* ========================= */

    public function inplannen($les_ID, $groep_ID)
    {
        if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker') redirect('cms/rechten');
        $this->_inplannen_groep('inplannen', $les_ID, $groep_ID);
    }

    public function groep($les_ID, $groep_ID, $groep_les_ID)
    {
        if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker') redirect('cms/rechten');
        $this->_inplannen_groep('wijzigen', $les_ID, $groep_ID, $groep_les_ID);
    }

    private function _inplannen_groep($actie = null, $les_ID = null, $groep_ID = null, $groep_les_ID = null)
    {
        if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker') redirect('cms/rechten');
        $this->load->model('lessen_model');
        $this->load->model('groepen_model');
        $this->load->model('workshops_model');
        $this->load->model('docenten_model');

        $item_locatie_ID        = '';
        $item_datum_dag         = '';
        $item_datum_maand       = '';
        $item_datum_jaar        = '';
        $item_tijd_uren         = '';
        $item_tijd_minuten      = '';
        $item_eindtijd_uren     = '';
        $item_eindtijd_minuten  = '';
        $item_adres             = 'Middelstegracht 89u';
        $item_postcode          = '2312 TT';
        $item_plaats            = 'Leiden';
        $docent_ID              = '';
        $technicus              = '';
        $item_gekoppeld_aan     = '';
        $item_dagen_ervoor_beschikbaar = 0;

        $item_datum_feedback        = '';
        $item_tijd_feedback         = '';
        $item_eindtijd_feedback     = '';
        $item_adres_feedback        = '';
        $item_postcode_feedback     = '';
        $item_plaats_feedback       = '';

        $groep_les                  = '';


        $les = $this->lessen_model->getLesByID($les_ID);
        if($les == null) redirect('cms/groepen/'.$groep_ID);

        $groep = $this->groepen_model->getGroepByID($groep_ID);
        if($groep == null) redirect('cms/groepen/'.$groep_ID);

        $workshop = $this->workshops_model->getWorkshopByID($groep->workshop_ID);
        if($workshop == null) redirect('cms/groepen/'.$groep_ID);

        $docenten = $this->docenten_model->getAllDocenten();

        // FORMULIER VERZONDEN

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $fouten                 = 0;

            if(empty($les->les_beschikbaar) || $les->les_beschikbaar == 0) {
                $item_datum_dag         = trim($_POST['item_datum_dag']);
                $item_datum_maand       = trim($_POST['item_datum_maand']);
                $item_datum_jaar        = trim($_POST['item_datum_jaar']);
                $item_tijd_uren         = trim($_POST['item_tijd_uren']);
                $item_tijd_minuten      = trim($_POST['item_tijd_minuten']);
                $item_eindtijd_uren     = trim($_POST['item_eindtijd_uren']);
                $item_eindtijd_minuten  = trim($_POST['item_eindtijd_minuten']);
            }

            if(!empty($les->les_gekoppeld_aan) && $les->les_gekoppeld_aan == 1) {
                $item_gekoppeld_aan             = trim($_POST['item_gekoppeld_aan']);
                $item_dagen_ervoor_beschikbaar  = trim($_POST['item_dagen_ervoor_beschikbaar']);
            }

            $docent_ID              = trim($_POST['item_docent']);
            $technicus              = trim($_POST['technicus']);

            if((empty($item_datum_dag) || empty($item_datum_maand) || empty($item_datum_jaar)) && $workshop->workshop_soort == 'normaal')
            {
                $fouten++;
                $item_datum_feedback = 'Graag invullen';
            }

            if((empty($item_tijd_uren) || empty($item_tijd_minuten)) && $workshop->workshop_soort == 'normaal')
            {
                $fouten++;
                $item_tijd_feedback = 'Graag invullen';
            }

            if((empty($item_eindtijd_uren) || empty($item_eindtijd_minuten)) && $workshop->workshop_soort == 'normaal')
            {
                $fouten++;
                $item_eindtijd_feedback = 'Graag invullen';
            }

            if($les->les_locatie == 'studio')
            {
                $item_adres             = trim($_POST['item_adres']);
                $item_postcode          = trim($_POST['item_postcode']);
                $item_plaats            = trim($_POST['item_plaats']);
                $item_locatie_ID        = trim($_POST['item_locatie_ID']);

                if(empty($item_adres))
                {
                    $fouten++;
                    $item_adres_feedback = 'Graag invullen';
                }

                if(empty($item_postcode))
                {
                    $fouten++;
                    $item_postcode_feedback = 'Graag invullen';
                }

                if(empty($item_plaats))
                {
                    $fouten++;
                    $item_plaats_feedback = 'Graag invullen';
                }
            }

            if($fouten == 0)
            {
                // TOEVOEGEN / UPDATEN
                $data = array(
                    'les_locatie_ID' => $item_locatie_ID,
                    'groep_les_datum' => $item_datum_jaar.'-'.$item_datum_maand.'-'.$item_datum_dag.' '.$item_tijd_uren.':'.$item_tijd_minuten.':00',
                    'groep_les_eindtijd' => $item_eindtijd_uren.':'.$item_eindtijd_minuten.':00',
                    'groep_les_adres' => $item_adres,
                    'groep_les_postcode' => $item_postcode,
                    'groep_les_plaats' => $item_plaats,
                    'groep_ID' => $groep_ID,
                    'les_ID' => $les_ID,
                    'docent_ID' => $docent_ID,
                    'technicus' => $technicus,
                    'les_gekoppeld_aan_ID' => $item_gekoppeld_aan,
                    'les_dagen_ervoor_beschikbaar' => $item_dagen_ervoor_beschikbaar
                );

                if($actie == 'inplannen') $q = $this->lessen_model->insertGroepLes($data);
                else $q = $this->lessen_model->updateGroepLes($groep_les_ID, $data);

                if($q)
                {
                    redirect('cms/groepen/'.$groep_ID.'#lessen');
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
                $groep_les = $this->lessen_model->getGroepLesByID($groep_les_ID);
                if($groep_les == null) redirect('cms/lessen');

                $item_datum         = $groep_les->groep_les_datum;
                $item_adres         = $groep_les->groep_les_adres;
                $item_postcode      = $groep_les->groep_les_postcode;
                $item_plaats        = $groep_les->groep_les_plaats;
                $les_ID             = $groep_les->les_ID;
                $groep_ID           = $groep_les->groep_ID;
                $item_locatie_ID    = $groep_les->les_locatie_ID;
                $docent_ID          = $groep_les->docent_ID;
                $technicus          = $groep_les->technicus;
                $item_gekoppeld_aan = $groep_les->les_gekoppeld_aan_ID;
                $item_dagen_ervoor_beschikbaar = $groep_les->les_dagen_ervoor_beschikbaar;

                $datum_tijd             = explode(' ', $item_datum);
                $datum                  = explode('-', $datum_tijd[0]);
                $tijd                   = explode(':', $datum_tijd[1]);
                $item_datum_dag         = $datum[2];
                $item_datum_maand       = $datum[1];
                $item_datum_jaar        = $datum[0];
                $item_tijd_uren         = $tijd[0];
                $item_tijd_minuten      = $tijd[1];

                if(!empty($groep_les->groep_les_eindtijd))
                {
                    $eindtijd               = explode(':', $groep_les->groep_les_eindtijd);
                    $item_eindtijd_uren     = $eindtijd[0];
                    $item_eindtijd_minuten  = $eindtijd[1];
                }
            }
        }

        // ANDERE LESSEN OPHALEN
        $lessen = $this->lessen_model->getLessenByWorkshopID($groep->workshop_ID);
        $this->data['lessen']       = $lessen;

        $this->data['technicus']    = $technicus;
        $this->data['actie']        = $actie;
        $this->data['les']          = $les;
        $this->data['groep']        = $groep;
        $this->data['workshop']     = $workshop;
        $this->data['docenten']     = $docenten;
        $this->data['docent_ID']    = $docent_ID;
        $this->data['item_gekoppeld_aan'] = $item_gekoppeld_aan;
        $this->data['item_dagen_ervoor_beschikbaar'] = $item_dagen_ervoor_beschikbaar;

        $this->data['groep_les_ID'] = $groep_les_ID;

        $this->data['item_datum_dag']           = $item_datum_dag;
        $this->data['item_locatie_ID']          = $item_locatie_ID;
        $this->data['item_datum_maand']         = $item_datum_maand;
        $this->data['item_datum_jaar']          = $item_datum_jaar;
        $this->data['item_tijd_uren']           = $item_tijd_uren;
        $this->data['item_tijd_minuten']        = $item_tijd_minuten;
        $this->data['item_eindtijd_uren']       = $item_eindtijd_uren;
        $this->data['item_eindtijd_minuten']    = $item_eindtijd_minuten;

        $this->data['item_adres']               = $item_adres;
        $this->data['item_postcode']            = $item_postcode;
        $this->data['item_plaats']              = $item_plaats;

        $this->data['item_datum_feedback']      = $item_datum_feedback;
        $this->data['item_tijd_feedback']       = $item_tijd_feedback;
        $this->data['item_eindtijd_feedback']   = $item_eindtijd_feedback;

        $this->data['item_adres_feedback']      = $item_adres_feedback;
        $this->data['item_postcode_feedback']   = $item_postcode_feedback;
        $this->data['item_plaats_feedback']     = $item_plaats_feedback;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/lessen_inplannen';
        $this->load->view('cms/template', $pagina);
    }



    /* =============== */
    /* = VERWIJDEREN = */
    /* =============== */

    public function uitplannen($item_ID = null, $bevestiging = null)
    {
        if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker') redirect('cms/rechten');
        if($item_ID == null) redirect('cms/lessen');


        $this->load->model('lessen_model');
        $item = $this->lessen_model->getGroepLesByID($item_ID);
        if($item == null) redirect('cms/lessen');
        $this->data['item'] = $item;

        // > Controleren of de les is ingepland, zo ja, dit melden aan de beheerder


        // ITEM VERWIJDEREN

        if($bevestiging == 'ja')
        {
            // Verwijder groepsles

            $q = $this->lessen_model->deleteGroepLesByID($item_ID);
            if($q) redirect('cms/groepen/'.$item->groep_ID.'#lessen');
            else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
        }

        // PAGINA TONEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/lessen_uitplannen';
        $this->load->view('cms/template', $pagina);
    }

    public function extra_toevoegen($workshop_ID = null)
    {
        $this->_extra_toevoegen_wijzigen('toevoegen', $workshop_ID);
    }

    public function extra_wijzigen($item_ID = null, $les_ID = null)
    {
        if($item_ID == null) redirect('cms/lessen');
        $this->_extra_toevoegen_wijzigen('wijzigen', $item_ID, $les_ID);
    }

    private function _extra_toevoegen_wijzigen($actie, $item_ID = null, $les_ID = null)
    {
        $this->load->model('lessen_model');
        $this->load->model('groepen_model');
        $this->load->model('workshops_model');

        $item_titel = 'Extra les';
        $item_beschrijving = 'Dit is een extra ingeplande (studio)les. Bij deze les hoort geen verdere informatie.';
        $item_locatie = 'studio';
        $item_datum_dag         = '';
        $item_datum_maand       = '';
        $item_datum_jaar        = '';
        $item_tijd_uren         = '';
        $item_tijd_minuten      = '';
        $item_eindtijd_uren     = '';
        $item_eindtijd_minuten  = '';
        $item_adres             = '';
        $item_postcode          = '';
        $item_plaats            = '';

        $item_datum_feedback        = '';
        $item_tijd_feedback         = '';
        $item_eindtijd_feedback     = '';
        $item_adres_feedback        = '';
        $item_postcode_feedback     = '';
        $item_plaats_feedback       = '';
        $item_titel_feedback        = '';
        $item_beschrijving_feedback = '';
        $item_locatie_feedback      = '';

        $item_groep = $this->groepen_model->getGroepByID($item_ID);
        if($item_groep == null) redirect('cms/groepen/'.$item_ID);

        $workshop = $this->workshops_model->getWorkshopByID($item_groep->workshop_ID);
        if($workshop == null) redirect('cms/workshops/'.$item_groep->workshop_ID);

        // FORMULIER VERZONDEN

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fouten = 0;

            $item_titel = trim($_POST['item_titel']);
            $item_beschrijving = trim($_POST['item_beschrijving']);

            if (empty($item_titel)) {
                $fouten++;
                $item_titel_feedback = 'Graag invullen';
            }

            if (empty($item_beschrijving)) {
                $fouten++;
                $item_beschrijving_feedback = 'Graag invullen';
            }

            if (isset($_POST['item_locatie'])) {
                $item_locatie = $_POST['item_locatie'];
            }

            $item_datum_dag         = trim($_POST['item_datum_dag']);
            $item_datum_maand       = trim($_POST['item_datum_maand']);
            $item_datum_jaar        = trim($_POST['item_datum_jaar']);
            $item_tijd_uren         = trim($_POST['item_tijd_uren']);
            $item_tijd_minuten      = trim($_POST['item_tijd_minuten']);
            $item_eindtijd_uren     = trim($_POST['item_eindtijd_uren']);
            $item_eindtijd_minuten  = trim($_POST['item_eindtijd_minuten']);
            $item_adres             = trim($_POST['item_adres']);
            $item_postcode          = trim($_POST['item_postcode']);
            $item_plaats            = trim($_POST['item_plaats']);

            if(empty($item_datum_dag) || empty($item_datum_maand) || empty($item_datum_jaar))
            {
                $fouten++;
                $item_datum_feedback = 'Graag invullen';
            }

            if(empty($item_tijd_uren) || empty($item_tijd_minuten))
            {
                $fouten++;
                $item_tijd_feedback = 'Graag invullen';
            }

            if(empty($item_eindtijd_uren) || empty($item_eindtijd_minuten))
            {
                $fouten++;
                $item_eindtijd_feedback = 'Graag invullen';
            }

            if(empty($item_adres))
            {
                $fouten++;
                $item_adres_feedback = 'Graag invullen';
            }

            if(empty($item_postcode))
            {
                $fouten++;
                $item_postcode_feedback = 'Graag invullen';
            }

            if(empty($item_plaats))
            {
                $fouten++;
                $item_plaats_feedback = 'Graag invullen';
            }

            if ($fouten == 0) {
                // TOEVOEGEN / UPDATEN

                $data = array(
                    'les_positie' => '9999',
                    'les_titel' => $item_titel,
                    'les_beschrijving' => $item_beschrijving,
                    'les_locatie' => $item_locatie,
                    'workshop_ID' => $item_groep->workshop_ID,
                    'groep_ID' => $item_ID
                );

                if ($actie == 'toevoegen') {
                    // LES TOEVOEGEN
                    $les_ID = $this->lessen_model->insertLes($data);
                }

                if (!empty($les_ID)) {
                    if ($actie == 'wijzigen') {
                        // LES TOEVOEGEN
                        $les = $this->lessen_model->getGroepLesByLesID($les_ID, $item_ID);

                        $q = $this->lessen_model->updateLes($les_ID, $data);
                    }
                }

                $data = array(
                    'groep_les_datum' => $item_datum_jaar.'-'.$item_datum_maand.'-'.$item_datum_dag.' '.$item_tijd_uren.':'.$item_tijd_minuten.':00',
                    'groep_les_eindtijd' => $item_eindtijd_uren.':'.$item_eindtijd_minuten.':00',
                    'groep_les_adres' => $item_adres,
                    'groep_les_postcode' => $item_postcode,
                    'groep_les_plaats' => $item_plaats,
                    'groep_ID' => $item_groep->groep_ID,
                    'les_ID' => $les_ID
                );

                if($actie == 'toevoegen') $q = $this->lessen_model->insertGroepLes($data);
                if($actie == 'wijzigen') $q = $this->lessen_model->updateGroepLes($les->groep_les_ID, $data);

                redirect('cms/groepen/'.$item_groep->groep_ID.'#lessen');
            }
        } else {
            if ($actie == 'wijzigen') {
                if (!empty($les_ID)) {
                    $item = $this->lessen_model->getExtraLesByIDandGroepID($les_ID, $item_ID);
                } else {
                    redirect('cms/lessen/');
                }

                $item_titel = $item->les_titel;
                $item_beschrijving = $item->les_beschrijving;
                $item_locatie = $item->les_locatie;
                $item_workshop = $item->workshop_ID;

                $groep_les = $this->lessen_model->getGroepLesByLesID($les_ID, $item_ID);

                if($groep_les == null) redirect('cms/lessen');

                $item_datum         = $groep_les->groep_les_datum;
                $item_adres         = $groep_les->groep_les_adres;
                $item_postcode      = $groep_les->groep_les_postcode;
                $item_plaats        = $groep_les->groep_les_plaats;

                $datum_tijd             = explode(' ', $item_datum);
                $datum                  = explode('-', $datum_tijd[0]);
                $tijd                   = explode(':', $datum_tijd[1]);
                $item_datum_dag         = $datum[2];
                $item_datum_maand       = $datum[1];
                $item_datum_jaar        = $datum[0];
                $item_tijd_uren         = $tijd[0];
                $item_tijd_minuten      = $tijd[1];

                if(!empty($groep_les->groep_les_eindtijd))
                {
                    $eindtijd               = explode(':', $groep_les->groep_les_eindtijd);
                    $item_eindtijd_uren     = $eindtijd[0];
                    $item_eindtijd_minuten  = $eindtijd[1];
                }
            }
        }


        // WORKSHOP OPHALEN

        $workshops = $this->workshops_model->getLessenWorkshops();
        $this->data['workshops'] = $workshops;

        // PAGINA TONEN

        $this->data['actie'] = $actie;

        $this->data['item_ID']                  = $item_ID;
        $this->data['les_ID']                   = $les_ID;
        $this->data['item_titel']               = $item_titel;
        $this->data['item_beschrijving']        = $item_beschrijving;
        $this->data['item_locatie']             = $item_locatie;
        $this->data['item_datum_dag']           = $item_datum_dag;
        $this->data['item_datum_maand']         = $item_datum_maand;
        $this->data['item_datum_jaar']          = $item_datum_jaar;
        $this->data['item_tijd_uren']           = $item_tijd_uren;
        $this->data['item_tijd_minuten']        = $item_tijd_minuten;
        $this->data['item_eindtijd_uren']       = $item_eindtijd_uren;
        $this->data['item_eindtijd_minuten']    = $item_eindtijd_minuten;
        $this->data['item_adres']               = $item_adres;
        $this->data['item_postcode']            = $item_postcode;
        $this->data['item_plaats']              = $item_plaats;

        $this->data['item_datum_feedback']      = $item_datum_feedback;
        $this->data['item_tijd_feedback']       = $item_tijd_feedback;
        $this->data['item_eindtijd_feedback']   = $item_eindtijd_feedback;
        $this->data['item_adres_feedback']      = $item_adres_feedback;
        $this->data['item_postcode_feedback']   = $item_postcode_feedback;
        $this->data['item_plaats_feedback']     = $item_plaats_feedback;
        $this->data['item_titel_feedback'] = $item_titel_feedback;
        $this->data['item_beschrijving_feedback'] = $item_beschrijving_feedback;
        $this->data['item_locatie_feedback'] = $item_locatie_feedback;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/extra_les_wijzigen';
        $this->load->view('cms/template', $pagina);
    }

    public function les_beoordelingen($item_ID = null) {
        if($item_ID == null) {
            redirect('cms/lessen/lessen_beoordelingen');
        }

        $this->load->model('lessen_model');

        $les = $this->lessen_model->getLesByID($item_ID);
        $beoordelingen = $this->lessen_model->getLessenBeoordelingByID($item_ID);

        $this->data['beoordelingen'] = $beoordelingen;
        $this->data['les'] = $les;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/les_beoordelingen';
        $this->load->view('cms/template', $pagina);
    }

    public function gratis_les_detail($item_ID = null)
    {
        if($item_ID == null) redirect('cms/workshops/#gratis_workshop');

        $this->load->model('lessen_model');
        $item = $this->lessen_model->getGratisLesByID($item_ID);
        if($item == null) redirect('cms/workshops/#gratis_workshop');
        $this->data['item'] = $item;

        // PAGINA LADEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/gratis_les_detail';
        $this->load->view('cms/template', $pagina);
    }

    public function gratis_les_toevoegen($les_ID = null)
    {
        $this->_gratis_les_toevoegen_wijzigen('toevoegen', $les_ID);
    }

    public function gratis_les_wijzigen($les_ID = null)
    {
        if($les_ID == null) redirect('cms/workshops/#gratis_workshop');
        $this->_gratis_les_toevoegen_wijzigen('wijzigen', $les_ID);
    }

    private function _gratis_les_toevoegen_wijzigen($actie, $les_ID = null)
    {
        $this->load->model('lessen_model');
        $this->load->model('groepen_model');
        $this->load->model('workshops_model');

        $item_titel             = '';
        $item_tekst             = '';
        $item_youtube_link      = '';
        $item_datum_dag         = '';
        $item_datum_maand       = '';
        $item_datum_jaar        = '';

        $item_titel_feedback            = '';
        $item_tekst_feedback            = '';
        $item_youtube_link_feedback     = '';
        $item_datum_feedback                = '';

        // FORMULIER VERZONDEN

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fouten = 0;

            $item_titel = trim($_POST['item_titel']);
            $item_tekst = trim($_POST['item_tekst']);
            $item_youtube_link = trim($_POST['item_youtube_link']);

            if (empty($item_titel)) {
                $fouten++;
                $item_titel_feedback = 'Graag invullen';
            }

            if (empty($item_tekst)) {
                $fouten++;
                $item_tekst_feedback = 'Graag invullen';
            }

            $item_datum_dag         = trim($_POST['item_datum_dag']);
            $item_datum_maand       = trim($_POST['item_datum_maand']);
            $item_datum_jaar        = trim($_POST['item_datum_jaar']);

            if(empty($item_datum_dag) || empty($item_datum_maand) || empty($item_datum_jaar))
            {
                $fouten++;
                $item_datum_feedback = 'Graag invullen';
            }

            if(empty($item_youtube_link))
            {
                $fouten++;
                $item_youtube_link_feedback = 'Graag invullen';
            }

            if ($fouten == 0) {
                // TOEVOEGEN / UPDATEN

                $video_ID = explode('v=' , $item_youtube_link);
                $video_ID = $video_ID[1];
                $extend = strpos($video_ID, '&');

                if($extend == true) {
                    $video_ID = substr($video_ID, 0, $extend);
                }

                $data = array(
                    'les_titel' => $item_titel,
                    'les_tekst' => $item_tekst,
                    'les_youtube_link' => "https://www.youtube.com/embed/".$video_ID,
                    'les_publicatiedatum' =>  $item_datum_jaar.'-'.$item_datum_maand.'-'.$item_datum_dag.' 00:00:00',
                );

                if ($actie == 'toevoegen') {
                    // LES TOEVOEGEN
                    $les_ID = $this->lessen_model->insertGratisLes($data);
                }

                if (!empty($les_ID)) {
                    if ($actie == 'wijzigen') {
                        // LES TOEVOEGEN
                        $q = $this->lessen_model->updateGratisLes($les_ID, $data);
                    }
                }

                redirect('cms/workshops/#gratis_workshop');
            }
        } else {
            if ($actie == 'wijzigen') {
                if (!empty($les_ID)) {
                    $item = $this->lessen_model->getGratisLesByID($les_ID);
                } else {
                    redirect('cms/workshops/#gratis_workshop');
                }

                $item_titel = $item[0]->les_titel;
                $item_tekst = $item[0]->les_tekst;
                $item_youtube_link = $item[0]->les_youtube_link;
                $item_datum        = $item[0]->les_publicatiedatum;

                $video_ID = explode('embed/' , $item_youtube_link);
                $item_youtube_link = "https://www.youtube.com/watch?v=". $video_ID[1];

                $datum_tijd             = explode(' ', $item_datum);
                $datum                  = explode('-', $datum_tijd[0]);
                $item_datum_dag         = $datum[2];
                $item_datum_maand       = $datum[1];
                $item_datum_jaar        = $datum[0];
            }
        }

        // PAGINA TONEN

        $this->data['actie'] = $actie;

        $this->data['item_ID']                  = $les_ID;
        $this->data['item_titel']               = $item_titel;
        $this->data['item_tekst']               = $item_tekst;
        $this->data['item_youtube_link']        = $item_youtube_link;
        $this->data['item_datum_dag']           = $item_datum_dag;
        $this->data['item_datum_maand']         = $item_datum_maand;
        $this->data['item_datum_jaar']          = $item_datum_jaar;

        $this->data['item_datum_feedback']              = $item_datum_feedback;
        $this->data['item_titel_feedback']              = $item_titel_feedback;
        $this->data['item_youtube_link_feedback']       = $item_youtube_link_feedback;
        $this->data['item_tekst_feedback']      = $item_tekst_feedback;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/gratis_les_wijzigen';
        $this->load->view('cms/template', $pagina);
    }

    public function gratis_les_verwijderen($item_ID = null, $bevestiging = null)
    {
        if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker') redirect('cms/rechten');
        if($item_ID == null) redirect('cms/workshops/#gratis_workshop');

        $this->load->model('lessen_model');
        $item = $this->lessen_model->getGratisLesByID($item_ID);
        if($item == null) redirect('cms/workshops/#gratis_workshop');
        $this->data['item'] = $item;

        // ITEM VERWIJDEREN

        if($bevestiging == 'ja')
        {
            // Verwijder les

            $q = $this->lessen_model->deleteGratisLes($item_ID);
            if($q) redirect('cms/workshops/#gratis_workshop');
            else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
        }


        // PAGINA TONEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/gratis_les_verwijderen';
        $this->load->view('cms/template', $pagina);
    }

    public function live_toevoegen($groep_ID = null, $les_ID = null)
    {
        $this->_live_toevoegen_wijzigen('toevoegen', $groep_ID, $les_ID);
    }

    public function live_wijzigen($item_ID = null, $les_ID = null)
    {
        if($item_ID == null) redirect('cms/lessen');
        $this->_live_toevoegen_wijzigen('wijzigen', $item_ID, $les_ID);
    }

    private function _live_toevoegen_wijzigen($actie, $item_ID = null, $les_ID = null)
    {
        $this->load->model('lessen_model');
        $this->load->model('groepen_model');
        $this->load->model('workshops_model');
        $this->load->model('docenten_model');

        $item_titel = 'Live video les';
        $item_beschrijving = 'Dit is een extra ingeplande videoles. Bij deze les hoort geen verdere informatie.';
        $item_locatie           = 'online';
        $item_datum_dag         = '';
        $item_datum_maand       = '';
        $item_datum_jaar        = '';
        $item_tijd_uren         = '';
        $item_tijd_minuten      = '';
        $item_eindtijd_uren     = '';
        $item_eindtijd_minuten  = '';
        $item_docent            = '';
        $docent_ID              = '';
        $technicus              = '';
        $item_video_url         = '';
        $item_video_type        = 'vimeo';

        $item_datum_feedback        = '';
        $item_tijd_feedback         = '';
        $item_eindtijd_feedback     = '';
        $item_titel_feedback        = '';
        $item_beschrijving_feedback = '';
        $item_locatie_feedback      = '';
        $item_video_url_feedback    = '';

        $item_groep = $this->groepen_model->getGroepByID($item_ID);
        if($item_groep == null) redirect('cms/groepen/'.$item_ID);

        $workshop = $this->workshops_model->getWorkshopByID($item_groep->workshop_ID);
        if($workshop == null) redirect('cms/groepen/'.$item_ID);

        $docenten = $this->docenten_model->getAllDocenten();

        if (!empty($les_ID)) {
            $item = $this->lessen_model->getLesByID($les_ID);

            $item_titel = $item->les_titel;
            $item_beschrijving = $item->les_beschrijving;
            $item_video_url = $item->les_video_url;
            $item_video_type = $item->les_video_type;
        }
        // FORMULIER VERZONDEN

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fouten = 0;

            $item_titel = trim($_POST['item_titel']);
            $item_beschrijving = trim($_POST['item_beschrijving']);

            if (empty($item_titel)) {
                $fouten++;
                $item_titel_feedback = 'Graag invullen';
            }

            if (empty($item_beschrijving)) {
                $fouten++;
                $item_beschrijving_feedback = 'Graag invullen';
            }

            $item_datum_dag         = trim($_POST['item_datum_dag']);
            $item_datum_maand       = trim($_POST['item_datum_maand']);
            $item_datum_jaar        = trim($_POST['item_datum_jaar']);
            $item_tijd_uren         = trim($_POST['item_tijd_uren']);
            $item_tijd_minuten      = trim($_POST['item_tijd_minuten']);
            $item_eindtijd_uren     = trim($_POST['item_eindtijd_uren']);
            $item_eindtijd_minuten  = trim($_POST['item_eindtijd_minuten']);
            $item_docent            = trim($_POST['item_docent']);
            $item_video_url         = trim($_POST['item_video_url']);
            $item_video_type        = trim($_POST['item_video_type']);

            if(empty($item_datum_dag) || empty($item_datum_maand) || empty($item_datum_jaar))
            {
                $fouten++;
                $item_datum_feedback = 'Graag invullen';
            }

            if(empty($item_tijd_uren) || empty($item_tijd_minuten))
            {
                $fouten++;
                $item_tijd_feedback = 'Graag invullen';
            }

            if(empty($item_eindtijd_uren) || empty($item_eindtijd_minuten))
            {
                $fouten++;
                $item_eindtijd_feedback = 'Graag invullen';
            }

            if ($fouten == 0) {
                // TOEVOEGEN / UPDATEN

                $data = array(
                    'les_positie' => '9999',
                    'les_titel' => $item_titel,
                    'les_beschrijving' => $item_beschrijving,
                    'workshop_ID' => $item_groep->workshop_ID,
                    'groep_ID' => $item_ID,
                    'les_type_ID' => 21,
                    'docent_ID' => $item_docent,
                    'les_video_url' => $item_video_url,
                    'les_video_type' => $item_video_type,
                );

                if ($actie == 'toevoegen' && empty($les_ID)) {
                    // LES TOEVOEGEN
                    $les_ID = $this->lessen_model->insertLes($data);
                }

                if (!empty($les_ID)) {
                    if ($actie == 'wijzigen') {
                        // LES TOEVOEGEN
                        $les = $this->lessen_model->getGroepLesByLesID($les_ID, $item_ID);

                        $q = $this->lessen_model->updateLes($les_ID, $data);
                    }
                }

                $data = array(
                    'groep_les_datum' => $item_datum_jaar.'-'.$item_datum_maand.'-'.$item_datum_dag.' '.$item_tijd_uren.':'.$item_tijd_minuten.':00',
                    'groep_les_eindtijd' => $item_eindtijd_uren.':'.$item_eindtijd_minuten.':00',
                    'groep_ID' => $item_groep->groep_ID,
                    'les_ID' => $les_ID
                );

                if($actie == 'toevoegen') $q = $this->lessen_model->insertGroepLes($data);
                if($actie == 'wijzigen') $q = $this->lessen_model->updateGroepLes($les->groep_les_ID, $data);

                redirect('cms/groepen/'.$item_groep->groep_ID.'#lessen');
            }
        } else {
            if ($actie == 'wijzigen') {
                if (!empty($les_ID)) {
                    $item = $this->lessen_model->getLiveLesByIDandGroepID($les_ID, $item_ID);
                } else {
                    redirect('cms/lessen/');
                }

                $item_titel = $item->les_titel;
                $item_beschrijving = $item->les_beschrijving;
                $item_workshop = $item->workshop_ID;
                $item_video_url = $item->les_video_url;
                $item_video_type = $item->les_video_type;
                $item_docent = $item->docent_ID;

                $groep_les = $this->lessen_model->getGroepLesByLesID($les_ID, $item_ID);

                if($groep_les == null) redirect('cms/lessen');

                $item_datum         = $groep_les->groep_les_datum;

                $datum_tijd             = explode(' ', $item_datum);
                $datum                  = explode('-', $datum_tijd[0]);
                $tijd                   = explode(':', $datum_tijd[1]);
                $item_datum_dag         = $datum[2];
                $item_datum_maand       = $datum[1];
                $item_datum_jaar        = $datum[0];
                $item_tijd_uren         = $tijd[0];
                $item_tijd_minuten      = $tijd[1];

                if(!empty($groep_les->groep_les_eindtijd))
                {
                    $eindtijd               = explode(':', $groep_les->groep_les_eindtijd);
                    $item_eindtijd_uren     = $eindtijd[0];
                    $item_eindtijd_minuten  = $eindtijd[1];
                }
            }
        }


        // WORKSHOP OPHALEN

        $workshops = $this->workshops_model->getLessenWorkshops();
        $this->data['workshops'] = $workshops;

        // PAGINA TONEN

        $this->data['actie'] = $actie;
        $this->data['docent_ID']                = $item_docent;
        $this->data['item_ID']                  = $item_ID;
        $this->data['workshop']                 = $workshop;
        $this->data['groep']                    = $item_groep;
        $this->data['technicus']                = $technicus;
        $this->data['les_ID']                   = $les_ID;
        $this->data['item_titel']               = $item_titel;
        $this->data['docenten']                 = $docenten;
        $this->data['item_video_url']           = $item_video_url;
        $this->data['item_video_type']          = $item_video_type;
        $this->data['item_beschrijving']        = $item_beschrijving;
        $this->data['item_locatie']             = $item_locatie;
        $this->data['item_datum_dag']           = $item_datum_dag;
        $this->data['item_datum_maand']         = $item_datum_maand;
        $this->data['item_datum_jaar']          = $item_datum_jaar;
        $this->data['item_tijd_uren']           = $item_tijd_uren;
        $this->data['item_tijd_minuten']        = $item_tijd_minuten;
        $this->data['item_eindtijd_uren']       = $item_eindtijd_uren;
        $this->data['item_eindtijd_minuten']    = $item_eindtijd_minuten;

        $this->data['item_datum_feedback']          = $item_datum_feedback;
        $this->data['item_tijd_feedback']           = $item_tijd_feedback;
        $this->data['item_eindtijd_feedback']       = $item_eindtijd_feedback;
        $this->data['item_titel_feedback']          = $item_titel_feedback;
        $this->data['item_beschrijving_feedback']   = $item_beschrijving_feedback;
        $this->data['item_locatie_feedback']        = $item_locatie_feedback;
        $this->data['item_video_url_feedback']      = $item_video_url_feedback;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/live_les_wijzigen';
        $this->load->view('cms/template', $pagina);
    }

    public function mediaOverschrijven($les_ID) {
        if(empty($les_ID)) redirect('cms/lessen');
        $this->load->model('lessen_model');
        $this->load->model('workshops_model');
        $this->load->model('media_model');
        $this->load->model('docenten_model');

        $item = $this->lessen_model->getLesByID($les_ID);

        $fouten                         = 0;
        $item_datum_ingang_dag          = '';
        $item_datum_ingang_maand        = '';
        $item_datum_ingang_jaar         = '';

        $item_datum_ingang_feedback     = '';

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $item_media = trim($_POST['item_media']);
            $item_datum_ingang_dag = trim($_POST['item_datum_ingang_dag']);
            $item_datum_ingang_maand = trim($_POST['item_datum_ingang_maand']);
            $item_datum_ingang_jaar = trim($_POST['item_datum_ingang_jaar']);


            if ((empty($item_datum_ingang_dag) || empty($item_datum_ingang_maand) || empty($item_datum_ingang_jaar)) && !empty($item_media)) {
                $fouten++;
                $item_datum_ingang_feedback = 'Graag invullen';
            }


            if ($fouten == 0) {
                // VERWIJDER MEDIA

                $this->media_model->verwijderNieuwConnecties('les', $les_ID);

                // NIEUWE MEDIA KOPPELEN

                if (!empty($item_media)) {
                    $media_IDS = explode(',', $item_media);

                    for ($i = 0; $i < sizeof($media_IDS); $i++) {
                        if ($media_IDS[$i] > 0) {
                            $connectie = array('media_ID' => $media_IDS[$i], 'media_positie' => $i, 'content_type' => 'les', 'content_ID' => $les_ID, 'media_ingang' => $item_datum_ingang_jaar . '-' . $item_datum_ingang_maand . '-' . $item_datum_ingang_dag,);
                            $this->media_model->insertMediaConnectie($connectie);
                        }
                    }
                }

                redirect('cms/lessen/wijzigen/' . $les_ID);
            }
        }

        // MEDIA OPHALEN

        $media = $this->media_model->getMediaNieuwByContentID('les', $les_ID);
        $this->data['media'] = $media;

        if(!empty($media)) {
            $datum = explode('-', $media[0]->media_ingang);

            $item_datum_ingang_dag = $datum[2];
            $item_datum_ingang_maand = $datum[1];
            $item_datum_ingang_jaar = $datum[0];
        }

        $this->data['les_ID'] = $les_ID;
        $this->data['les'] = $item;
        $this->data['item_datum_ingang_dag']    = $item_datum_ingang_dag;
        $this->data['item_datum_ingang_maand']    = $item_datum_ingang_maand;
        $this->data['item_datum_ingang_jaar']       = $item_datum_ingang_jaar;

        $this->data['item_datum_ingang_feedback']    = $item_datum_ingang_feedback;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/media_overschrijven';
        $this->load->view('cms/template', $pagina);
    }

    public function placeholder_toevoegen($les_ID) {
        if(empty($les_ID)) redirect('cms/lessen');
        $this->load->model('lessen_model');
        $this->load->model('workshops_model');
        $this->load->model('media_model');
        $this->load->model('docenten_model');

        $item = $this->lessen_model->getLesByID($les_ID);

        $fouten = 0;

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $item_media = trim($_POST['item_media']);

            if ($fouten == 0) {
                // VERWIJDER MEDIA

                $this->media_model->verwijderConnecties('placeholder', $les_ID);

                // NIEUWE MEDIA KOPPELEN

                if (!empty($item_media)) {
                    $media_IDS = explode(',', $item_media);

                    for ($i = 0; $i < sizeof($media_IDS); $i++) {
                        if ($media_IDS[$i] > 0) {
                            $connectie = array('media_ID' => $media_IDS[$i], 'media_positie' => $i, 'content_type' => 'placeholder', 'content_ID' => $les_ID);
                            $this->media_model->insertMediaConnectie($connectie);
                        }
                    }
                }

                redirect('cms/lessen/wijzigen/' . $les_ID);
            }
        }

        // MEDIA OPHALEN

        $media = $this->media_model->getMediaByContentID('placeholder', $les_ID);
        $this->data['media'] = $media;

        $this->data['les_ID'] = $les_ID;
        $this->data['les'] = $item;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/les_placeholder';
        $this->load->view('cms/template', $pagina);
    }

    public function deleteOvergeschrevenMedia($les_ID) {
        if(empty($les_ID)) redirect('cms/lessen');

        $this->load->model('media_model');

        redirect('cms/lessen/wijzigen/'. $les_ID);

    }

    function ReplaceTags($gebruiker_ID, $email_tekst, $groep_ID = null) {
        $this->load->model('gebruikers_model');
        $this->load->model('groepen_model');
        $this->load->model('workshops_model');

        $deelnemer = $this->gebruikers_model->getGebruikerByID($gebruiker_ID);

        $email_tekst = str_replace('[voornaam]', $deelnemer->gebruiker_voornaam, $email_tekst);
        $email_tekst = str_replace('[achternaam]', $deelnemer->gebruiker_achternaam, $email_tekst);

        if($groep_ID) {
            $start_datum  = $this->groepen_model->getStartdatumByGroepID($groep_ID);
            $workshop = $this->workshops_model->getWorkshopByGroepID($groep_ID);

            $start_datum = new DateTime($start_datum);

            $email_tekst = str_replace('[workshop]', $workshop[0]->workshop_titel, $email_tekst);
            $email_tekst = str_replace('[startdatum]', $start_datum->format('d-m-Y'), $email_tekst);
        }

        return  $email_tekst;
    }

    public function beoordelingen()
    {
        $this->load->model('lessen_model');
        $this->load->model('workshops_model');

        $lessen1 = $this->lessen_model->getLessenByWorkshopID(37);
        $lessen2 = $this->lessen_model->getLessenByWorkshopID(59);

        $this->data['lessen1'] = $lessen1;
        $this->data['lessen2'] = $lessen2;

        $ids = array();

        foreach($lessen1 as $les) {
            array_push($ids, $les->les_ID);
        }

        if(isset($_POST['lessen1']))
        {
            $les_ID_van = $_POST['lessen1'];
            $les_ID_naar = $_POST['lessen2'];

            $data = array(
                'les_ID' => $les_ID_naar
            );

            $q = $this->lessen_model->transferBeoordeling($les_ID_van, $data);
}

        // PAGINA LADEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/beoordeling_transfer';
        $this->load->view('cms/template', $pagina);
    }
}