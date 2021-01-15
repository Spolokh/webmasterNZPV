<?php

class UsersModel extends Model
{
  	const OFFSET = 0;
	const NUMBER = 7;

	public function __construct ()
	{
		parent:: __construct();
	}
	
	public function query() // Здесь реальные данные.
	{
		return ORM::forTable('books')->orderByDesc('id')->offset(self::OFFSET)->limit(self::NUMBER);
	}

	public function user()
	{
		return ORM::forTable('books');
	}
}
