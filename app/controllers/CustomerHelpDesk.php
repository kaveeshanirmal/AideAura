<?php

class customerHelpDesk extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('faq');
    }

    public function operationalHelp()
    {
        $this->view('customerOperationalHelp');
    }

    public function paymentHelp()
    {
        $this->view('customerPaymentHelp');
    }
    
}
