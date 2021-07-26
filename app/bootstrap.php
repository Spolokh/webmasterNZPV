<?php

session_start();

define('root', dirname(__DIR__));
define('rootpath', dirname(__FILE__));
define('VENDOR', root. '/libs');
define('UPLOADS', root. '/uploads');
define('VIEW_PATH', rootpath.'/views');
define('MODEL_PATH', rootpath.'/models');
define('CONTR_PATH', rootpath.'/controllers');

$config = include_once ('core/config.php');

define('DBHOST', $config['dbhost']);
define('DBUSER', $config['dbuser']);
define('DBPASS', $config['dbpass']);
define('DBNAME', $config['dbname']);

require_once 'core/idiorm.php';
require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';
require_once 'core/route.php';

ORM::configure('mysql:host='.DBHOST.';dbname='.DBNAME);
ORM::configure('username', DBUSER);
ORM::configure('password', DBPASS);

$db = ORM::getDb();
/*
$db->exec("CREATE TABLE IF NOT EXISTS users (
        id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        date int(11) DEFAULT '0',
        username varchar(50) NOT NULL UNIQUE,
        password varchar(255) NOT NULL,
        mail varchar(50) NOT NULL UNIQUE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$db->exec("CREATE TABLE IF NOT EXISTS books (
        id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        mail varchar(50) NOT NULL,
        phone varchar(255) NOT NULL,
        image varchar(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
*/

Route::loader();
Route::start();

function getConfig($k)
{
    global $config;
    return $config[$k] ?? false;
}
