<?php 

class Usuarios extended CI_Controller{


  # usuarios/category/23/negocios

  public function __construct()
  {

  	parent::__construct();
  	#tus otras cosas
  	$this->load->model('carpeta/UsuariosModelo', 'userM', array(123, 'test'));

  	$this->userM->funcionDelModelo();

  	$temp = (object) array('carlos' => $this->_privateCategories);

  	$this->prueba = $temp;
  	$this->data = array();
  	$this->data['title'] = 'Titulo por defecto';

  }
  
  public function category()
  {
  	$this->data['title'] = 'Listado de categorias';
  	$this->data['list'] = array('asda1','asda2');
	$this->prueba->carlos();
  } 

  private function _privateCategories()
  {
    $this->load->view('vista', $this->data);
  } 

  public function registro()
  {

  	if($this->input->post())
 	{

 		# validar datos
 		$valido = true;
		if(!$this->input->post('mail') || !$this->input->post('password'))
		{
 			$this->data['error'] = 'Completa todos los campos';
			$valido = false;
		}
		$this->load->helper('email');
		if(!valid_email($this->input->post('mail')))
		{
 			$this->data['error'] = 'E-mail invalido';
			$valido = false;
		}
		if(strlen($this->input->post('password')) < 6)
		{
 			$this->data['error'] = 'Contrasena invalida';
			$valido = false;
		}

		$registrado = $this->userM->MailRegistrado($this->input->post('mail'));

		if($registrado)
		{
 			$this->data['error'] = 'El email ya esta registrado';
			$valido = false;
		}

		if($valido)
		{
	 		# Registro correcto 
	 		#    Guarda los datos en la base de datos
	 		$id = $this->userM->SaveUser($this->input->post('mail'), $this->input->post('password'));
	 		#    Crea la session
	 		$this->session->set_userdata('user', $this->input->post('mail'));
	 		#    Te lleva al logued in
	 		return $this->loguedin();
		}
 		#Registro incorrecto -> sigue el circuito y muestra error


 	}


    $this->load->view('usuarios/registro', $this->data);
  }

  #dentro del modelo
  public function SaveUser( $mail = '', $password = '' )
  {
  	$this->db->insert('user', array('mail' => $mail, 'password' => $password, 'active' => 1));
  	return $this->db->inserted_id();
  }

  public function MailRegistrado( $mail = '' )
  {
  	$sql = "select mail from user where mail = '{$mail}'";
  	$row = $this->db->query($sql)->row();
  	if($row)
  	  return true;
  	else
  	  return false;
  }
  
}