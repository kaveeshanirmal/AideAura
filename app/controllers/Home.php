<?php

class Home extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('HeroSection');
    }

    public function isSignedIn()
    {
        if (isset($_SESSION['loggedIn'])) {
            if ($_SESSION['role'] == "customer") {
                // worker finding page
                // header('Location: ' . ROOT . '/public/home');
                $this->view('EmployeeFindingScreen');
            } else if ($_SESSION['role'] == "worker") {
                // worker dashboard
                // header('Location: ' . ROOT . '/public/home');
                $this->view('EmployeeFindingScreen');
            } else {
                // admin dashboard
                header('Location: ' . ROOT . '/public/home');
            }
        } else {
            // login page
            header('Location: ' . ROOT . '/public/login');
        }
    }
}

