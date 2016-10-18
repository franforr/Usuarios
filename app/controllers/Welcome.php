<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index2()
	{

		$peticion = $this->uri->segment(1);
		$peticionesValidas = array('wel','come', 'welcome');

		if($peticion == 'jsonx')
		{
			return $this->json();
		}

		if($peticion == 'welc')
		{
			return redirect('productos/detalle/123');
		}

		if(!in_array($peticion, $peticionesValidas))
		{
			#echo 'Error';
			$this->json();
			return;
		}

		#echo "Carlos";
		#$this->load->view('welcome_message');
		$this->load->helper('date');
		var_dump( days_in_month(2, 1990) );
	}

	public function prueba()
	{
		#echo "prueba";
		echo now();
		#$this->load->view('welcome_message');
	}
	
	public function json()
	{
		#echo "json prueba";
		$this->load->library('session');
		echo $this->session->userdata('usuario') ;
		$count = $this->session->userdata('visitas') ;
		$count++;
		$this->session->set_userdata('visitas', $count);
		var_dump($count);

		#$this->load->view('welcome_message');
	}

	public function productos()
	{
		echo "listado productos";
		#$this->load->view('welcome_message');
	}
}
