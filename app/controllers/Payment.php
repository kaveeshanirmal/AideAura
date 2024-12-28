<?php

class Payment extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('payment/paymentForm');
    }

    public function paymentDetail()
    {
        $this->view('payment/paymentDetail');
    }
    
    public function paymentComplete()
    {
        $this->view('payment/paymentComplete');
    }

    public function paymentProceed()
    {   
        $this->view('payment/paymentProceed1');
    }
}
