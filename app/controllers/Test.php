<?php

class Test extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('BrowseWorker');
    }

    public function testTime()
    {
        $this->view('testTime');
    }

}