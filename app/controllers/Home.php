<?php

class Home extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('HeroSection');
    }

    public function customerRedirect()
    {
        if (isset($_SESSION['loggedIn'])) {
            $this->view('EmployeeFindingScreen');
        } else {
            // login page
            header('Location: ' . ROOT . '/public/login');
        }
    }

    public function workerRedirect()
    {
        if (isset($_SESSION['loggedIn'])) {
            // check whether the worker is verified or not
            if ($_SESSION['isVerified'])
                $this->view('workerDashboard');
            else
                // redirect to the worker verification controller
                header('Location: ' . ROOT . '/public/workerVerification');
                
        } else {
            // login page
            header('Location: ' . ROOT . '/public/login');
        }
    }
}

