<?php

class HrManager extends Controller
{
    public function index()
    {
        $this->view('hr/workerProfiles');
    }

    public function workerInfo()
    {
        $this->view('hr/workerInfo');
    }

    public function workerCertificates()
    {
        $this->view('hr/workerCertificates');
    }

    public function availabilitySchedule()
    {
        $this->view('hr/availabilitySchedule');
    }

    public function workerSchedules()
    {
        $this->view('hr/workerSchedules');
    }

    public function verificationRequests()
    {
        $this->view('hr/verificationRequests');
    }

    public function workerInquiries()
    {
        $this->view('hr/workerInquiries');
    }
}