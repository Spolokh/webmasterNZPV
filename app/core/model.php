<?php

class Model
{
	const TABLE = 'users';

	protected $member = [];
	protected $username;
	protected $password;

	public $isAuthorize;

	public function __construct() 
	{
		$this->logOut();
		$this->isAuthorize = Session::has('username') 
		? Session::get('username') 
		: $this->checkLogin();
	}

	public function checkLogin($k = 'isLogget')
	{
		$this->username = $_POST['username'] ?? Session::get('username');
		$this->password = $_POST['password'] ?? Session::get('username');

		if ( empty($this->username) || empty($this->password) )
		{
			return false;
		}
		
		$member = ORM::forTable('users')->where([ //$where = ['username'=> $this->username, 'password'=> md5($this->password)];
			'username' => $this->username, 
			'password' => md5($this->password)
		]);

		if ($member->findOne())
		{
			Session::set('username', $this->username);
			Session::set('password', $this->password);
			Session::set('isLogget', true);
			return $this->username;
			
		} else {
			return false;
		}
	}

	public function isAuth()
	{
		return Session::has('isLogget');
	}

	public function logOut()
	{
		if (isset($_GET['exit']))
		{
			unset($_SESSION['username'], $_SESSION['password']);
			Session::destroy();
			header('Location: /');
			exit;
		}
	}
}
