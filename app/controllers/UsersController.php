<?php

class usersController extends Controller
{
	const TITLE = 'Телефонная книга';

	public $view;
	public $model;
	public $login;

	public function __construct()
	{
		$this->model = new usersModel();
		$this->view  = new View();
	}

	public function index()
	{
		$this->view->render('users.view.php', 'template.view.php',
		[
			'title' => self::TITLE,
			'query' => $this->model->query(),
			'login' => $this->model->isAuthorize
		]);
	}

	public function user()
	{
		$id = $_GET['user'] ?? null;

		$this->view->render('user.view.php', 'template.view.php',
		[
			'title' => self::TITLE,
			'query' => $this->model->user()->findOne($id),
			'login' => $this->model->isAuthorize
		]);
	}
}
