<?php


session_start();
require "../app/core/init.php";

// Debugging line to check session status
// var_dump($_SESSION);

$app = new App();
$app->loadController();
