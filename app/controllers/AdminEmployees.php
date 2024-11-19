<?php

class AdminEmployees extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('adminEmployees');
    }

    public function edit($a = '', $b = '', $c = '')
    {
        $this->view('adminEmployees');
    } 
}