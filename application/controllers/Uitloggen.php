<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uitloggen extends CI_Controller
{
	public function index()
	{
		$this->session->sess_destroy();
		redirect('');
	}
}