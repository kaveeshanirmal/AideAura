<?php

class App
{
    // Default controller and method
    private $controller = 'Home';
    private $method = 'index';

    private function splitURL()
    {
        $URL = $_GET['url'] ?? 'home';
        $URL = explode('/', trim($URL, '/'));
        return $URL;
    }

    // Authorization for certain roles to access each controller


    public function loadController()
    {
        $URL = $this->splitURL();

        // Select controller
        $filename = "../app/controllers/" . ucfirst($URL[0]) . ".php";
        if (file_exists($filename))
        {
            require $filename;
            $this->controller = ucfirst($URL[0]);
        }
        else
        {
            require "../app/controllers/_404.php";
            $this->controller = '_404';
        }

        $controller = new $this->controller;

        // Select method
        if (!empty($URL[1]))
        {
            if (method_exists($controller, $URL[1]))
            {
                $this->method = $URL[1];
            }
        }

        // Remove the controller and method from the $URL array
        $params = array_slice($URL, 2);

        // Call the method with the parameters
        call_user_func_array([$controller, $this->method], $params);
    }
}
