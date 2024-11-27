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
        $this->view('admin/adminWorkerProfile');
    }

    public function worker1()
    {
        $this->view('admin/adminWorkerProfile1');
    }
    public function worker2()
    {
        $this->view('admin/adminWorkerProfile2');
    }
    public function workerSchedule()
    {
        $this->view('admin/adminWorkerProfileSchedule');
    }

    public function customers()
    {
        $this->view('admin/customerProfiles');
    }

    public function workerRoles()
    {
        $this->view('admin/adminRoles');
    }

    public function workerRoles1()
    {
        $this->view('admin/adminRoles1');
    }

    public function paymentRates()
    {
        $this->view('admin/adminPayrate');
    }

    public function paymentHistory()
    {
        $this->view('admin/adminPaymentHistory');
    }

    public function workerInquiries()
    {

        $this->view('admin/adminWorkerInquiries');
    }

    public function paymentIssues()
    {
        $this->view('admin/adminpaymentIssues');
    }
}
