<?php

class OpManager extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('opm/complaintManagement');
    }

    public function specialRequests()
    {
        $this->view('opm/specialRequests');
    }

    public function workerSchedules()
    {
        $this->view('opm/workerSchedules');
    }
}
