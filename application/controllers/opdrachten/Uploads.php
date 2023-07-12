<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uploads extends OpdrachtenController
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
		redirect('opdrachten');
	}



	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */

	public function verwijderen($upload_ID = null, $opdracht_ID = null)
	{
		if($upload_ID == null) redirect('opdrachten');


		// Huiswerk ophalen

		$this->load->model('uploads_model');
		$upload = $this->uploads_model->getUploadByID($upload_ID);
		if($upload == null) redirect('opdrachten');

		// Opdracht ophalen

		$this->load->model('opdrachten_model');
		$opdracht = $this->opdrachten_model->getOpdrachtByID($opdracht_ID);
		$opdracht_url = $opdracht->opdracht_url;

		// Controleren of huiswerk van de huidige gebruiker is

		if($upload->gebruiker_ID == (!empty($this->session->userdata('gebruiker_ID'))) ? ($this->session->userdata('gebruiker_ID')) : ($this->session->userdata('beheerder_ID')))
		{
			// Huiswerk verwijderen

			$verwijderen = $this->uploads_model->deleteUploadByID($upload_ID);

			if($verwijderen)
			{
				unlink('./media/opdrachten/'.$upload->upload_src);

				if($opdracht_ID != null) redirect('opdracht/'.$opdracht_url.'#mijnhuiswerk');
				else redirect('opdrachten');
			}
			else
			{
				redirect('opdrachten');
			}
		}
		else
		{
			redirect('opdrachten');
		}
	}
}