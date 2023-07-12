<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Huiswerk extends CursistenController
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
		redirect('cursistenmodule');
	}
	
	
	
	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */
	
	public function verwijderen($item_ID = null, $les_ID = null)
	{
		if($item_ID == null) redirect('cursistenmodule');
		
		
		// Huiswerk ophalen
		
		$this->load->model('huiswerk_model');
		$huiswerk = $this->huiswerk_model->getHuiswerkByID($item_ID);
		if($huiswerk == null) redirect('cursistenmodule');
		
		// Controleren of huiswerk van de huidige gebruiker is
		
		if($huiswerk->gebruiker_ID == $this->session->userdata('gebruiker_ID'))
		{
			// Huiswerk verwijderen
			
			$verwijderen = $this->huiswerk_model->deleteHuiswerkByID($item_ID);
			
			if($verwijderen)
			{
				unlink('./media/huiswerk/'.$huiswerk->huiswerk_src);
				
				if($les_ID != null) redirect('cursistenmodule/lessen/'.$les_ID.'#mijnhuiswerk');
				else redirect('cursistenmodule/lessen');
			}
			else
			{
				redirect('cursistenmodule');
			}
		}
		else
		{
			redirect('cursistenmodule');
		}
	}
}