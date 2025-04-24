<?php

require_once '../app/core/Helpers.php';


class Controller
{
    public function view($name, $data = [])
    {
        $filename = "../app/views/" . $name . ".view.php";
        
        if (file_exists($filename)) {
            // Extract the data array into variables
            extract($data);
            
            // Include the view file
            require $filename;
        } else {
            // If the view file does not exist, load a 404 page
            require "../app/views/404.view.php";
        }
    }
    public function loadModel($model)
    {
        $filename = "../app/models/" . ucfirst($model) . ".php";
        if(file_exists($filename))
        {
            require_once $filename;
            return new $model;
        }
        return false;
    }

}
