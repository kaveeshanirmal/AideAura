<?php

class Controller
{
    public function view($name)
    {
        $filename = "../views".$name.".view.php";
        
        if(file_exists($filename))
        {
            require $filename;
        }
        else
        {
            require "../views/404.view.php";
        }
    }

}
