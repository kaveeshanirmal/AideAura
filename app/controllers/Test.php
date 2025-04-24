<?php

class Test extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('orderSummary');
    }

    public function testTime()
    {
        $this->view('testTime');
    }

}