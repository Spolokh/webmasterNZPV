<?php

class IndexController extends Controller
{
	const TITLE = 'Телефоный справочник';

	private $data = [];

	public function __construct()
	{
		$this->model = new IndexModel();
		$this->view  = new View();
	}
	
	public function index()
	{   
		
		//echo $this->model->isAuthorize;	
		//echo Session::get('isLogget')? Session::get('username'): '';
		$this->view->render('index.view.php', 'template.view.php', [
			'title' => self::TITLE,
			'login' => $this->model->isAuthorize 
		]);
	}
}
