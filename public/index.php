<?php
session_start();
require "../app/core/init.php";

// Debugging line to check session status
// var_dump($_SESSION);

$app = new App();
$app->loadController();

//Enable Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);