<?php

class ContactsController extends Controller
{
	const TITLE  = 'Контакты';
	const MODULE = 'contacts.view.php';
	const LAYOUT = 'template.view.php';
	
	function __construct()
	{
		$this->model = new ContactsModel();	//$this->view  = new View();
	}

	function index()
	{
        (new View())->render(self::MODULE, self::LAYOUT,
        [
            'title' => self::TITLE,
            'login' => (new Model())->isAuthorize
		]);
	}
}
