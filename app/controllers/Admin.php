<?php

class Admin extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('admin/adminReports');
    }

    public function employees($a = '', $b = '', $c = '')
    {
        $this->view('admin/adminEmployees');
    }

    public function workers()
    {
        $this->view('admin/workerProfiles');
    }

    public function customers()
    {
        $this->view('admin/customerProfiles');
    }

    public function workerRoles()
    {
        $this->view('admin/adminRoles');
    }

    public function paymentRates()
    {
        $this->view('admin/adminPaymentRates');
    }

    public function paymentHistory()
    {
        $this->view('admin/adminPaymentHistory');
    }

    public function workerInquiries()
    {
        $this->view('admin/adminWorkerInquiries');
    }

    public function customerInquiries()
    {
        $this->view('admin/adminCustomerInquiries');
    }
}
