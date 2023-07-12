<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Opdrachten extends OpdrachtenController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function audio()
	{
		$this->load->view('opdrachten/audio');
	}



	/* ============= */
	/* = OVERZICHT = */
	/* ============= */

	public function index()
	{
        if($this->session->userdata('gebruiker_rechten') == 'kandidaat'):
            redirect('opdrachten');
        endif;

        $this->load->model('uploads_model');
        $this->load->model('docenten_model');

		// Opdrachten ophalen

		$this->load->model('opdrachten_model');
		$opdrachten = $this->opdrachten_model->getOpdrachten();
        $this->data['opdrachten'] = $opdrachten;


		// PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'opdrachten/opdrachten';
		$this->load->view('opdrachten/template', $pagina);
    }



	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */

	public function detail($item_ID = null)
    {
        if($item_ID == null) redirect('opdrachten/opdrachten');

        $this->load->model('opdrachten_model');
        $item = $this->opdrachten_model->getOpdrachtByID($item_ID);
        if($item == null) redirect('opdrachten/opdrachten');
        $this->data['item'] = $item;

        // MEDIA OPHALEN

        $this->load->model('media_model');
        $media = $this->media_model->getMediaByContentID('les', $item_ID);
        $this->data['media'] = $media;

        $media_nieuw = $this->media_model->getMediaNieuwByContentID('opdracht', $item_ID);
        $this->data['media_nieuw'] = $media_nieuw;

        // PAGINA LADEN

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'opdrachten/opdrachten_detail';
        $this->load->view('opdrachten/template', $pagina);
    }

    public function opdracht($opdracht_url = null)
    {
        $this->load->model('opdrachten_model');
        $this->load->model('uploads_model');
        $this->load->model('media_model');
        $opdracht = $this->opdrachten_model->getOpdrachtByURL($opdracht_url);
        if($opdracht == null) redirect('opdrachten/');
        $this->data['opdracht'] = $opdracht;

        $opdracht_ingestuurd = $this->opdrachten_model->getOpdrachtAndGebruikerID((!empty($this->session->userdata('gebruiker_ID'))) ? ($this->session->userdata('gebruiker_ID')) : ($this->session->userdata('beheerder_ID')), $opdracht->opdracht_ID);
        $this->data['opdracht_ingestuurd'] = $opdracht_ingestuurd;

        $opdracht_beoordeeld = $this->opdrachten_model->getBeoordelingByOpdrachtAndGebruikerID((!empty($this->session->userdata('gebruiker_ID'))) ? ($this->session->userdata('gebruiker_ID')) : ($this->session->userdata('beheerder_ID')), $opdracht->opdracht_ID);
        $this->data['opdracht_beoordeeld'] = $opdracht_beoordeeld;

        // UPLOADS TOEVOEGEN OF INSTUREN

        $feedback = '';
        $upload_titel = '';
        $bestand_titel = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $upload_titel = trim($this->input->post('upload_titel'));

            if (isset($_POST['insturen'])) {
                // Upload insturen

                $data = array(
                    'opdracht_datum' => date('Y-m-d H:i:s'),
                    'gebruiker_ID' => (!empty($this->session->userdata('gebruiker_ID'))) ? ($this->session->userdata('gebruiker_ID')) : ($this->session->userdata('beheerder_ID')),
                    'opdracht_ID' => $opdracht->opdracht_ID
                );

                $insturen = $this->uploads_model->insertOpdrachtResultaat($data);

                if ($insturen) {
                    // Terug naar opdracht

                    redirect('opdracht/' . $opdracht->opdracht_url);
                }
            } elseif (isset($_POST['uploaden'])) {
                // Upload uploaden

                if (empty($upload_titel)) {
                    $feedback = 'Graag een titel invullen';
                } else {
                    if ($_FILES['upload_bestand']['error'] > 0) {
                        switch ($_FILES['upload_bestand']['error']) {
                            case 1:
                                $feedback = 'Het bestand is te groot';
                                break;

                            case 2:
                                $feedback = 'Het bestand is te groot';
                                break;

                            case 3:
                                $feedback = 'Het bestand is niet goed geupload';
                                break;

                            case 4:
                                $feedback = 'Graag een bestand selecteren';
                                break;

                            case 6:
                                $feedback = 'Geen tijdelijke folder';
                                break;

                            case 7:
                                $feedback = 'Kon bestand niet uploaden';
                                break;
                        }
                    } else {
                        $bestand_types = array('audio/wav');
                        $bestand_extensies = array('wav');

                        $bestand_naam = $_FILES['upload_bestand']['name'];
                        $bestand_naam = strtolower($bestand_naam);
                        $bestand_tijdelijke_naam = $_FILES['upload_bestand']['tmp_name'];

                        $bestand_naam_exploded = explode('.', $bestand_naam);
                        $bestand_type_extensie = array_pop($bestand_naam_exploded);
                        $bestand_naam_imploded = implode("-", $bestand_naam_exploded);

                        if (in_array($bestand_type_extensie, $bestand_extensies)) {
                            if (move_uploaded_file($bestand_tijdelijke_naam, './media/opdrachten/' . $upload_titel . '-' . $bestand_naam_imploded . '.' . $bestand_type_extensie)) {
                                // Upload opslaan in de database

                                $data = array(
                                    'bestand_titel' => $bestand_naam_imploded,
                                    'upload_titel' => $upload_titel,
                                    'upload_src' => $upload_titel. '-' . $bestand_naam_imploded . '.' .$bestand_type_extensie,
                                    'upload_datum' => date('Y-m-d H:i:s'),
                                    'gebruiker_ID' => (!empty($this->session->userdata('gebruiker_ID'))) ? ($this->session->userdata('gebruiker_ID')) : ($this->session->userdata('beheerder_ID')),
                                    'opdracht_ID' => $opdracht->opdracht_ID
                                );

                                $toevoegen = $this->uploads_model->insertUpload($data);

                                if ($toevoegen) {
                                    redirect('opdracht/' . $opdracht->opdracht_url);
                                } else {
                                    $feedback = 'Upload toevoegen mislukt';
                                }
                            } else {
                                $feedback = 'Het bestand is niet geüpload';
                            }
                        } else {
                            $feedback = 'Selecteer een WAV bestand';
                        }
                    }
                }

            }
        }

        // MEDIA OPHALEN

		$this->load->model('media_model');
		$media = $this->media_model->getMediaByContentID('opdracht', $opdracht->opdracht_ID);

        $placeholder = $this->media_model->getMediaByContentID('placeholder', $opdracht->opdracht_ID);
        $this->data['placeholder'] = $placeholder;
		$this->data['media'] = $media;


		// UPLOADS OPHALEN

        $uploads = $this->uploads_model->getUploads((!empty($this->session->userdata('gebruiker_ID'))) ? ($this->session->userdata('gebruiker_ID')) : ($this->session->userdata('beheerder_ID')), $opdracht->opdracht_ID);

		$this->data['uploads'] = $uploads;

        // PAGINA LADEN

		$this->data['feedback'] = $feedback;
		$this->data['upload_titel'] = $upload_titel;
        $this->data['bestand_titel'] = $bestand_titel;

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'opdrachten/opdrachten_opdracht';
        $this->load->view('opdrachten/template', $pagina);
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
		if($item_ID == null) redirect('opdrachten/opdrachten');
		$this->_toevoegen_wijzigen('wijzigen', $item_ID);
	}

	private function _toevoegen_wijzigen($actie, $item_ID = null)
	{
		$this->load->model('opdrachten_model');
		$this->load->model('media_model');

        $item_titel                     = '';
        $item_url                       = '';
        $item_beschrijving              = '';
        $item_audio_titel               = '';
        $item_media                     = '';
        $item_upload                    = '';
        $item_upload_aantal             = '';

        $item_titel_feedback            = '';
        $item_url_feedback              = '';
        $item_beschrijving_feedback     = '';
        $item_audio_titel_feedback      = '';
        $item_upload_feedback           = '';
        $item_upload_aantal_feedback    = '';

		// FORMULIER VERZONDEN

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $fouten                 = 0;

            $item_titel             = trim($_POST['item_titel']);
            $item_url               = trim($_POST['item_url']);
            $item_beschrijving      = trim($_POST['item_beschrijving']);
            $item_audio_titel       = trim($_POST['item_audio_titel']);
            $item_media             = trim($_POST['item_media']);
            $item_upload            = trim($_POST['item_upload']);
            $item_upload_aantal     = trim($_POST['item_upload_aantal']);

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

            if(!empty($item_upload_aantal))
            {
                if(!is_numeric($item_upload_aantal))
                {
                    $fouten++;
                    $item_upload_aantal_feedback = 'Graag een getal invullen';
                }
                else
                {
                    if(empty($item_upload))
                    {
                        $fouten++;
                        $item_upload_feedback = 'Graag invullen';
                    }
                }
            }

            if($fouten == 0)
            {
                // TOEVOEGEN / UPDATEN

                $data = array(
                    'opdracht_titel' => $item_titel,
                    'opdracht_url' => $item_url,
                    'opdracht_beschrijving' => $item_beschrijving,
                    'opdracht_audio_titel' => $item_audio_titel,
                    'opdracht_uploads' => $item_upload,
                    'opdracht_uploads_aantal' => $item_upload_aantal,
                    'opdracht_media_ID' => $item_media,
                );

                if($actie == 'toevoegen')
                {
                    // LES TOEVOEGEN

                    $item_ID = $this->opdrachten_model->insertOpdracht($data);
                }
                else
                {
                    // VERWIJDER MEDIA

                    $this->media_model->verwijderConnecties('opdracht', $item_ID);

                    // LES UPDATEN

                    $q = $this->opdrachten_model->updateOpdracht($item_ID, $data);
                }

                // NIEUWE MEDIA KOPPELEN

                if(!empty($item_media))
                {
                    $media_IDS = explode(',', $item_media);

                    for($i = 0; $i < sizeof($media_IDS); $i++)
                    {
                        if($media_IDS[$i] > 0)
                        {
                            $connectie = array('media_ID' => $media_IDS[$i], 'media_positie' => $i, 'content_type' => 'opdracht', 'content_ID' => $item_ID);
                            $this->media_model->insertMediaConnectie($connectie);
                        }
                    }
                }

                if($actie == 'toevoegen') redirect('opdrachten/opdrachten/');
                else redirect('opdracht/'.$item_url);
            }
        }
        else
        {
            if($actie == 'wijzigen')
            {
                $item = $this->opdrachten_model->getOpdrachtByID($item_ID);
                if($item == null) redirect('opdrachten/opdrachten');

                $item_titel                     = $item->opdracht_titel;
                $item_url                       = $item->opdracht_url;
                $item_beschrijving              = $item->opdracht_beschrijving;
                $item_audio_titel               = $item->opdracht_audio_titel;
                $item_media                     = $item->opdracht_media_ID;
                $item_upload                    = $item->opdracht_uploads;
                $item_upload_aantal             = $item->opdracht_uploads_aantal;
            }
        }

        // MEDIA OPHALEN

        $media = $this->media_model->getMediaByContentID('opdracht', $item_ID);
        $this->data['media'] = $media;

		// PAGINA TONEN

        $this->data['actie'] = $actie;

        $this->data['actie'] = $actie;

        $this->data['item_ID']                  = $item_ID;
        $this->data['item_titel']               = $item_titel;
        $this->data['item_url']                 = $item_url;
        $this->data['item_beschrijving']        = $item_beschrijving;
        $this->data['item_audio_titel']         = $item_audio_titel;
        $this->data['item_media']               = $item_media;
        $this->data['item_upload']              = $item_upload;
        $this->data['item_upload_aantal']       = $item_upload_aantal;

        $this->data['item_titel_feedback']              = $item_titel_feedback;
        $this->data['item_url_feedback']                = $item_url_feedback;
        $this->data['item_beschrijving_feedback']       = $item_beschrijving_feedback;
        $this->data['item_audio_titel_feedback']        = $item_audio_titel_feedback;
        $this->data['item_upload_feedback']             = $item_upload_feedback;
        $this->data['item_upload_aantal_feedback']      = $item_upload_aantal_feedback;

        if(!empty($_POST['item_kopie'])) $this->data['item_kopie'] = $_POST['item_kopie'];

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'opdrachten/opdrachten_wijzigen';
		$this->load->view('opdrachten/template', $pagina);
	}


	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */

	public function verwijderen($item_ID = null, $bevestiging = null)
	{
		if($item_ID == null) redirect('opdrachten/opdrachten');

		$this->load->model('opdrachten_model');
		$item = $this->opdrachten_model->getOpdrachtByID($item_ID);
		if($item == null) redirect('opdrachten/opdrachten');
		$this->data['item'] = $item;

		// ITEM VERWIJDEREN

		if($bevestiging == 'ja')
		{
			$q = $this->opdrachten_model->deleteOpdrachtByID($item_ID);
			if($q) redirect('opdrachten/opdrachten');
			else echo 'De opdracht kon niet worden verwijderd. Probeer het nog eens.';
		}


		// PAGINA TONEN

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'opdrachten/opdrachten_verwijderen';
		$this->load->view('opdrachten/template', $pagina);
	}

    public function archiveren($item_ID = null)
    {
        if($item_ID == null) redirect('opdrachten/opdrachten');
        $this->load->model('opdrachten_model');
        $this->load->model('gebruikers_model');

        $data = array(
            'opdracht_archiveren' => 1
        );

        $this->opdrachten_model->updateOpdracht($item_ID, $data);

        $archief = false;

        $this->index($archief);
    }

    public function dearchiveren($item_ID = null)
    {
        if($item_ID == null) redirect('opdrachten/opdrachten');
        $this->load->model('opdrachten_model');
        $this->load->model('gebruikers_model');

        $data = array(
            'opdracht_archiveren' => 0
        );

        $this->opdrachten_model->updateOpdracht($item_ID, $data);

        $archief = true;

        $this->index($archief);
    }
}
