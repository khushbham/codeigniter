<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beoordelingen extends CI_Controller
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
        $this->load->model('lessen_model');
        $beoordelingen = $this->lessen_model->getBeoordelingenCompleet();
        $recente_beoordelingen = $this->lessen_model->getRecenteBeoordelingen();

		// PAGINA TONEN
		
		$this->data['beoordelingen'] = $beoordelingen;
		$this->data['recente_beoordelingen'] = $recente_beoordelingen;

		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/beoordelingen';
		$this->load->view('cms/template', $pagina);
	}

    public function detail($ID)
    {
        if(empty($ID)) {
            redirect('cms/beoordelingen');
        }

        $this->load->model('lessen_model');
        $beoordeling = $this->lessen_model->getBeoordelingByID($ID);

        // PAGINA TONEN

        $this->data['beoordeling'] = $beoordeling[0];

        $pagina['data'] = $this->data;
        $pagina['pagina'] = 'cms/beoordeling';
        $this->load->view('cms/template', $pagina);
    }
}