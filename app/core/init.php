<?php

define('ROOT', 'http://localhost/AideAura/public');

spl_autoload_register(function($class){
    require "../models/".ucfirst($class).'.php';
});

require 'config.php';
require 'functions.php';
require 'Database.php';
require 'Model.php';
require 'Controller.php';
require 'App.php';
