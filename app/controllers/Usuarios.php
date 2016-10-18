<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->timeden = 50;
		$this->data = array();
		}

	public function index()
	{
		$data = array();
		$logueado = $this->session->userdata('logueado');
		if($logueado)
		{
			return $this->loggedin();
		}

		$denegados = $this->session->userdata('denegados');
		if($denegados>=3)
		{
			$denegadostime = $this->session->userdata('denegado-fecha');
			$now = time();
			$diff = $now - $denegadostime;
			if($diff < $this->timeden)
			{
				return $this->denegado();			
			}
			$this->session->set_userdata('denegados', 0);
			$this->session->set_userdata('denegado-fecha', 0);
		}

		if($this->input->post('mail'))
		{

			$this->load->model('usermodel', 'model');

			$mail = $this->input->post('mail', true);
			$this->load->helper('email');
			$pass = $this->input->post('pass');
						// Validar user y pass
			$validMail = valid_email($mail);

			if($validMail)
			{
				$validate = $this->model->ValidateUser($mail, $pass);

				if($validate)
				{
					$this->session->set_userdata('logueado', true);
					$this->session->set_userdata('mail', $mail);
					return $this->loggedin();
				}
				else
				{
					$denegados = $this->session->userdata('denegados');
				 	$denegados++;
					$this->session->set_userdata('denegados', $denegados);
					$this->session->set_userdata('denegado-fecha', time());
					$data['error'] = "Acceso denegado";
				}
			}
			else
			{
				$data['error'] = "E-mail inválido";
			}

		}

		$this->load->view('usuarios/acceso', $data);

	}

	public function denegado()
	{
	
		$data = array();
		$denegados = $this->session->userdata('denegados');

		if($denegados<3)
		{
			return redirect("Usuarios");
		}

		$denegadostime = $this->session->userdata('denegado-fecha');
		$now = time();
		$diff = $now - $denegadostime;
		$data['segundos'] = $this->timeden - $diff;

		$this->load->view('usuarios/denegado', $data);		
	}

	public function logout()
	{
		$this->session->set_userdata('logueado', false);
		redirect('Usuarios');
	}

	public function loggedin()
	{

		$logueado = $this->session->userdata('logueado');
		$mail = $this->session->userdata('mail');

		if(!$logueado)
		{
			return redirect('Usuarios');
		}

		echo "logueado " . $mail . "<br><a href='" . base_url() . "Usuarios/logout'>Cerrar sesión</a>";
	}

  private function validateRegistro()
  {

		if(!$this->input->post()) 
			return false;
 		
 		# validar datos
		if(!$this->input->post('mail') || !$this->input->post('password'))
		{
 			$this->data['error'] = 'Completa todos los campos';
			return false;
		}

		if(strlen($this->input->post('password')) < 6)
		{
 			$this->data['error'] = 'Contrasena invalida';
			return false;
		}

		$this->load->helper('email');
		if( ! valid_email($this->input->post('mail')))
		{
 			$this->data['error'] = 'E-mail invalido';
			return false;
		}
		
		$this->load->model('usermodel', 'usuariosfran');
		$registrado = $this->usuariosfran->MailRegistrado( $this->input->post('mail') );
		if($registrado)
		{
 			$this->data['error'] = 'El email ya esta registrado';
			return false;
		}

 		# Registro correcto 
 		#    Guarda los datos en la base de datos
 		$id = $this->usuariosfran->SaveUser($this->input->post('mail'), $this->input->post('password'));
 		#    Crea la session
		$this->session->set_userdata('id', $id);
		$this->session->set_userdata('logueado', true);
		$this->session->set_userdata('mail', $this->input->post('mail'));
 		#    Te lleva al logued in
 		redirect('Usuarios');
 		#Registro incorrecto -> sigue el circuito y muestra error
 		return true;

  }

  public function registro()
  {

		$logueado = $this->session->userdata('logueado');

		if($logueado)
		{
			return redirect('Usuarios');
		}

  	if( $this->validateRegistro() )
  		return;

    $this->load->view('usuarios/registro', $this->data);

  }

}
