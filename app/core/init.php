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

loadEnv(dirname(__DIR__, 2) . '/.env');