<?php

class Home extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('HeroSection');
    }

    public function findWorkers()
    {
        if (isset($_SESSION['loggedIn'])) {
            $this->view('serviceForms/serviceForms');
        } else {
            // login page
            header('Location: ' . ROOT . '/public/login');
        }
    }

    public function findJobs()
    {
        if (isset($_SESSION['loggedIn'])) {
            // check whether the worker is verified or not
            if ($_SESSION['isVerified'])
                $this->view('workerDashboard');
            else
                // redirect to the worker verification controller
                header('Location: ' . ROOT . '/public/workerVerification/verificationStatus');
                
        } else {
            // login page
            header('Location: ' . ROOT . '/public/login');
        }
    }

}


