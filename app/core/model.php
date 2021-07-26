<?php

class Model
{
	protected $username;
	protected $password;

	public $isAuthorize;

	public function __construct() 
	{
		$this->logOut();
		$this->isAuthorize = Session::has('username') ?
		Session::get('username') : $this->checkLogin();
	}

	public function checkLogin($k = 'isLogget')
	{
		$this->username = $_POST['username'] ?? Session::get('username');
		$this->password = $_POST['password'] ?? Session::get('password');

		if ( empty($this->username) || empty($this->password) )
		{
			return false;
		}
		
		$member = ORM::forTable('users')->select(['id', 'mail', 'username'])->where([
			'username' => $this->username, 'password' => md5($this->password)
		]);

		if ($member->findOne())
		{
			Session::set('username', $this->username);
			Session::set('isLogget', true);
			return true;
			
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
	
	/**
	* Запись ошибок
	*/

	protected function logWrite($message, $write = false)
	{
		if ($write === false)
		{
		    return;
		}

		$output = date('d.m.Y H:i:s') . PHP_EOL . $message . PHP_EOL . '-------------------------' . PHP_EOL;
		file_put_contents(root . '/logs.txt', $output, FILE_APPEND | LOCK_EX);
    }
}
