<?php

class PROFILE extends Controller
{
    public function index()
    {
        $this->view('profile');
    }

    public function personalInfo()
    {
        $this->view('personalInfo');
    }
}
