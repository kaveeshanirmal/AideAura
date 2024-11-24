<?php

class AdminEmployees extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('admin/adminEmployees');
    }

    public function edit($a = '', $b = '', $c = '')
    {
        $this->view('admin/adminEmployees');
    } 
}