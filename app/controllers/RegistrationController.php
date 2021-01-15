<?php

class RegistrationController extends Controller
{
	const TITLE  = 'Регистрация';
	const MODULE = 'registration.view.php';
	const LAYOUT = 'template.view.php';
	
	function __construct()
	{
		$this->model = new RegistrationModel();
	}

	function index()
	{
		(new View())->render(self::MODULE, self::LAYOUT, [
			'title' => self::TITLE,
			'login' => $this->model->isAuthorise,
		]);
	}
}
