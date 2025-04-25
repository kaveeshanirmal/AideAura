<?php

spl_autoload_register(function($class){
    require "../app/models/".ucfirst($class).'.php';
});
require 'config.php';
require 'functions.php';
require 'Database.php';
require 'Model.php';
require 'Controller.php';
require 'App.php';
require 'loadenv.php';
require 'HelperFunctions.php';
require 'MailHelper.php';

loadEnv(dirname(__DIR__, 2) . '/.env');

// Set Timezone
date_default_timezone_set('Asia/Colombo');