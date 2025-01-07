<?php

class FinanceManager extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('fm/accountantPaymentHistory');
    }

    public function paymentHistory()
    {
        $this->view('fm/paymentHistory');
    }

    public function paymentIssues()
    {
        $this->view('fm/paymentIssues');
    }

    public function paymentRates()
    {
        $this->view('fm/paymentRates');
    }

    public function reports()
    {
        $this->view('fm/reports');
    }
}
