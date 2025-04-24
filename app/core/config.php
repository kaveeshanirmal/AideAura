<?php

//define('ROOT', 'http://localhost/AideAura');
//define('ROOT', 'http://192.168.1.127:8000/AideAura');
define('ROOT', "http://" . $_SERVER['SERVER_NAME'] . "/AideAura");

//define('ROOT_PATH', dirname(__DIR__, 2));
$path = $_SERVER['DOCUMENT_ROOT'] . "/AideAura";
define("ROOT_PATH", $path);

//database config
define('DBNAME', 'aideaura');
define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', ''); 