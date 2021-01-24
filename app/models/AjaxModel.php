<?php

use Upload\Upload;
use PHPMailer\PHPMailer;

class AjaxModel extends Model
{
	protected $action;
	protected $upload;
	protected $header;
	protected $mailto;
	protected $errors = [];
	protected $charset;

	public function __construct()
	{
		parent::__construct();

		$this->action = $_POST['action'] ?? null;
		$this->header = $_SERVER['HTTP_X_REQUESTED_WITH'];

		if ( !isset($this->action) or (!$this->header or strtolower($this->header) != 'xmlhttprequest') )
		{
			header("HTTP/1.1 500 Internal Server Error"); 
			exit;
		}
		
		$this->mailto = getConfig('admin_mail');
		$this->charset = getConfig('charset');
	}
	
	public function contact() 
	{
		if ($this->action != 'contact')
		{
			header("HTTP/1.1 500 Internal Server Error");
			exit;
		}

		foreach ($_POST as $k => $v)
		{
			$$k = trim(htmlspecialchars($v));
		}

		if (!isset($name, $mail, $sessid, $message) or $sessid !== session_id())
		{
			header("HTTP/1.1 500 Internal Server Error");
			exit;
		}

		if (empty($name))
		{
			$this->errors[] = 'Введите ваше Имя!';
		}

		if (empty($mail) or !filter_var($mail, FILTER_VALIDATE_EMAIL))
		{
			$this->errors[] = 'Введите вашу почту корректно!';
		}

		if ( reset($this->errors) )
		{
			header('HTTP/1.1 500 Internal Server Error');
			echo join ('<li>', array_values($this->errors));
			exit;
		}
		
		try {
			$mailer = new PHPMailer;
			$mailer->SetFrom ($mail, $name);
			$mailer->CharSet  = $this->charset;
			//$mailer->Subject  = $subject;
			$mailer->Body     = $message;
			$mailer->AddAddress($this->mailto, $subject);
			$mailer->AddReplyTo($mail, $name);
			$mailer->IsHTML(true);
			$result = $mailer->Send() ? 'Ваше сообщение успешно отправленно!' : $mailer->ErrorInfo; 
			$mailer->ClearAddresses(); 
			$mailer->ClearAttachments();
			
		} catch (\Exception $e) {
			$result = $e->getMessage();
		}
		exit($result);
	}
	
	public function setPhone($id = 0, array $json = [], string $result = '', $jsonUnicode = JSON_UNESCAPED_UNICODE) : string
	{
		if ($this->action != 'addbook')
		{
			header("HTTP/1.1 500 Internal Server Error");
			exit ('Action None!');
		}

		foreach ($_POST as $k => $v)
		{
			$$k = trim(htmlspecialchars($v));
		}

		if (!isset($name, $family, $mail, $phone))
		{
			header("HTTP/1.1 500 Internal Server Error");
			exit('Post None!');
		}

		if ( !filter_var($mail, FILTER_VALIDATE_EMAIL) )
		{ 
			$this->errors[] = 'Ведите вашу почту корректно!';
		}

		if ( !preg_match('/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/', $phone) )
        	{
            		$this->errors[] = 'Укажите корректный телефон.';
		}
		
		if ( reset($this->errors) )
		{
			header('HTTP/1.1 500 Internal Server Error');
			echo join ("\n", array_values($this->errors));
			exit;
		}

		if ($_FILES['icon']['name'] || !$_FILES['icon']['error'])
		{
			$this->upload = new Upload($_FILES['icon']);

			if ($this->upload->uploaded)
			{
				$this->upload->file_new_name_body = uniqid('photo_');
				$this->upload->file_max_size = '6291456'; //1048576
				$this->upload->image_ratio_crop = true;
				$this->upload->forbidden  = ['application/*'];
				$this->upload->allowed    = ['image/jpeg', 'image/jpg', 'image/png']; 
				$this->upload->process(root.'/img');
		
				if ($this->upload->processed) { 				
					$icon = $this->upload->file_dst_name;		
				} 
			}

			$this->upload->Clean();
		}

		$result = ORM::forTable('books')->create();
		$result->name = $name .' '. $family;
		$result->mail = $mail;
		$result->icon = $icon ? $icon : 'default.png';
		$result->phone = $phone;
		$result->save();

		$id = $result->id(); 
		
		//$row = ORM::forTable('books')
		//	->query('SELECT * FROM `books` WHERE `id` =:id', ['id' => $id])
		//  ->findOne();

		foreach( ORM::forTable('books')->orderByDesc('id')->findArray() as $row )
		{
			$json [] = $row;
		}

		echo json_encode($json, $jsonUnicode);
		exit;
	}

	public function setRegistration()
	{
		foreach ($_POST as $k => $v) {
			$$k = trim(htmlspecialchars($v));
		}

		if (!isset($username, $password, $mail) or $action != 'registration')
		{
			header("HTTP/1.1 500 Internal Server Error");
			exit ('Post None!');
		}

		if (!preg_match('/^[A-Za-z0-9_\-]{3,16}$/i', $username))
		{
			$this->errors[] = 'Введите ваш логин корректно!';
		}

		if (empty($password) or $password !== $password2)
		{
			$this->errors[] = 'Веденные ваши пароли не совпaдают!';
		}

		if( !preg_match("/([a-z]+)/", $password) or 
			!preg_match("/([A-Z]+)/", $password) or 
			!preg_match("/([0-9]+)/", $password)) 
		{ 
			$this->errors[] = 'Пароль доджен содержать тщ 6 до 12 символов, быть в разных регистрах!';
		} 

		if (!filter_var($mail, FILTER_VALIDATE_EMAIL))
		{ 
			$this->errors[] = 'Введите вашу почту корректно.';
		}

		if (ORM::forTable('users')->select('mail')->where(['mail' => $mail])->findOne())
		{
			$this->errors[] = 'Ваш email уже кто то использует!';
		}

		if (ORM::forTable('users')->select('username')->where(['username' => $username])->findOne())
		{
			$this->errors[] = 'Ваш логин уже кто то использует!';
		}

		if (reset($this->errors))
		{
			header('HTTP/1.1 500 Internal Server Error'); //403
			echo join ('<br/>', array_values($this->errors)) ;
			exit;
		}

		$user = ORM::forTable('users')->create();
		$user->username = $username;
		$user->password = md5($password);
		$user->date = time();
		$user->mail = $mail;
		$user->save();

		printf ('<p>%s, Вы зарегистрированны в системе.</p>', $username);
		exit;
	}
	  
	public function deleteBook()
	{
		if (!$this->isAuthorize)
		{
			header('HTTP/1.1 500 Internal Server Error');
			exit;
		}

		if ($this->action != 'delete')
		{
			header("HTTP/1.1 500 Internal Server Error");
			exit;
		}

		if (empty($_POST['item']))
		{
			header('HTTP/1.1 500 Internal Server Error');
			exit ('Вы никого не выбрали.');
		}
		
		foreach ($_POST['item'] as $k => $v)
		{
			$query = ORM::forTable('books')->findOne($v);

			if ( $query->icon and file_exists(root.'/img/'.$query->icon) )
			{
				unlink(root.'/img/'.$query->icon);
			}

			$query->delete();
		}
		exit;
	}
}
