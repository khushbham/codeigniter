<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inzendingen extends OpdrachtenController
{
	public function __construct()
	{
		parent::__construct();
	}

	/* ============= */
	/* = OVERZICHT = */
	/* ============= */

	public function index()
	{
		// Models laden

        $this->load->model('uploads_model');
        $this->load->model('opdrachten_model');

        $inzendingen = '';
        $uploads = '';

        $inzendingen = $this->opdrachten_model->getVoltooideOpdrachten();

        if(!empty($inzendingen)) {
            foreach($inzendingen as $inzending) {
                $uploads = $this->uploads_model->getUploads($inzending->gebruiker_ID, $inzending->opdracht_ID);
				$inzending->uploads = $uploads;
            }
        }

        $this->data['inzendingen']  = $inzendingen;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'opdrachten/inzendingen';

		$this->load->view('opdrachten/template', $pagina);
	}



	/* ======= */
	/* = ZIP = */
	/* ======= */

	public function download($opdracht_ID = null, $gebruiker_ID = null){
		if($gebruiker_ID == null || $opdracht_ID == null) redirect('opdrachten/inzendingen');

		$this->load->library('zip');

		$this->load->model('uploads_model');
		$uploads = $this->uploads_model->getUploads($gebruiker_ID, $opdracht_ID);

		// Read files from directory

		if($this->input->post('createZip') != NULL){

			// Get Zipstream
			require_once(APPPATH.'libraries/vendor/autoload.php');

			// enable output of HTTP headers
			$options = new ZipStream\Option\Archive();
			$options->setSendHttpHeaders(true);
			$options->setFlushOutput(true);

			// File name
			$this->load->model('gebruikers_model');
			$gebruiker = $this->gebruikers_model->getGebruikerByID($gebruiker_ID);
			$gebruiker_geslacht = (!empty($gebruiker->gebruiker_geslacht)) ? ($gebruiker->gebruiker_geslacht) : 'nnb';
			$gebruiker_geboortedatum = (!empty($gebruiker->gebruiker_geboortedatum)) ? ($gebruiker->gebruiker_geboortedatum) : 'nnb';
			$filename = $gebruiker->gebruiker_ID."-".$gebruiker_geslacht."-".$gebruiker_geboortedatum."-".date("Y-m-d");

			// create a new zipstream object
			$zip = new ZipStream\ZipStream($filename.'.zip', $options);

			// Get files to upload
			foreach ($uploads as $upload) {
				// Add files to zip
				$zip->addFileFromPath($upload->upload_src, 'media/opdrachten/'.$upload->upload_src);
			}

			// finish the zip stream
			$zip->finish();
		}

		// Load view
		$this->load->view('index_view');
	}



	/* ============== */
	/* = BEOORDELEN = */
	/* ============== */
	public function beoordelen($item_ID = null)
	{
		if($item_ID == null) redirect('opdrachten/inzendingen');

		$this->load->model('opdrachten_model');
        $this->load->model('uploads_model');
		$inzending = $this->opdrachten_model->getVoltooideOpdrachtByID($item_ID);
		if($inzending == null) redirect('opdrachten/inzendingen');
		$this->data['inzending'] = $inzending;

		$uploads = $this->uploads_model->getUploads($inzending->gebruiker_ID, $inzending->opdracht_ID);
		$this->data['uploads'] = $uploads;

		$item_beoordeling			            = '';
		$item_beoordeling_feedback	            = '';

		$item_beoordeling_error	                = '';
		$item_beoordeling_feedback_error		= '';


		// FORMULIER VERZONDEN

		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{

			$fouten = 0;

			$item_beoordeling		        = trim($_POST['item_beoordeling']);
			$item_beoordeling_feedback 		= trim($_POST['item_beoordeling_feedback']);

			if($fouten == 0) {

				$data = array(
					'opdracht_beoordeling' 			=> $item_beoordeling,
					'opdracht_beoordeling_feedback' => $item_beoordeling_feedback,
				);

				// UPDATEN
				$updaten = $this->opdrachten_model->updateInzending($inzending->gebruiker_ID, $inzending->opdracht_ID, $data);
			}
		}



		// PAGINA TONEN

		$this->data['item_beoordeling'] 		            = $item_beoordeling;
		$this->data['item_beoordeling_feedback'] 		    = $item_beoordeling_feedback;

		$this->data['item_beoordeling_error'] 			    = $item_beoordeling_error;
		$this->data['item_beoordeling_feedback_error'] 		= $item_beoordeling_feedback_error;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'opdrachten/inzending_beoordelen';
		$this->load->view('opdrachten/template', $pagina);

	}
}
