<?php

class Controller
{
	public $view;
	public $model;
	
	function __construct()
	{
		$this->model = new Model();
		$this->view  = new View();
	}
	
	// действие (action), вызываемое по умолчанию
	public function index()
	{
	
	}
}
