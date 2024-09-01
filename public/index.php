<?php
    
session_start();

require "../app/core/init.php";

$app = new App();   //instantiation of the App class in prder to call load controller method
$app->loadController(); //calling the load controller method
