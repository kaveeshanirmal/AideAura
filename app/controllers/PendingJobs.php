<?php

class PendingJobs extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('pendingjobs');
    }

    public function edit($a = '', $b = '', $c = '')
    {
        $this->view('pendingjobs');
    } 
}