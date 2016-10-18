<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserModel extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->key_md5 = "fran";
	}

	public function ValidateUser( $user = '', $pass = '' )
	{
		
		$sql = "select * from user where mail = '{$user}'";
		$row = $this->db->query($sql)->row();

		if(!$row)
		{
			return false;
		}

		if( $row->password != md5( $pass . $this->key_md5 ) )
		{
			return false;
		}

		if(!$row->active)
		{
			return false;
		}

		return true;

	}

	public function CreateUsersTest()
	{
		for($i=0;$i<10;$i++)
		{
			$this->SaveUser("mail-{$i}@gmail.com", "password{$i}");
		}
	}

	public function SaveUser( $mail = '', $password = '' )
  {
  	$password = md5( $password . $this->key_md5 );
  	$this->db->insert('user', array('mail' => $mail, 'password' => $password, 'active' => 1));
  	return $this->db->insert_id();
  }

  public function MailRegistrado( $mail = '' )
  {
  	$sql = "select * from user where mail = '{$mail}'";
  	$row = $this->db->query($sql)->row();
  	if($row)
  	{
  	  return true;
  	}
  	else
  	{
  	  return false;
  	}
  }


}