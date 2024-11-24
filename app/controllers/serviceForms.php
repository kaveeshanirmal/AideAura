<?php

class serviceForms extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('forms/cooking');
    }

    public function getForm($service)
{
    switch ($service) {
        case 'home-style-food':
            $this->view('forms/home_style_food');
            break;
        case 'dishwashing':
            $this->view('forms/dishwashing');
            break;
        default:
            $this->view('404');
            break;
    }
}

}


