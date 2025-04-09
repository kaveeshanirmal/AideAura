<?php

class App
{
    // Default controller and method
    private $controller = 'Home';
    private $method = 'index';

    // 'admin','hrManager','opManager','financeManager','customer','worker'
    private $roleAccess = [
        'admin' => [
            'Admin' => ['index', 'employees', 'workers', 'workerDetails','assignDynamicRoles', 'workerCertificates', 'workerSchedule', 'customers', 'workerRoles', 'workerRoles1', 'addRole' , 'deleteRoles' , 'paymentRates', 'updatePaymentRates', 'paymentHistory', 'workerInquiries', 'paymentIssues', 'replyComplaint', 'deleteComplaint'],

            'AdminEmployeeAdd' => ['index', 'store'],

            'AdminEmployees' => ['index', 'update', 'delete' , 'search'],

            'AdminRoles1' => ['index', 'edit'],
        ],

        'hrManager' => [
            'HrManager' => ['index', 'workerInfo', 'workerCertificates', 'availabilitySchedule', 'workerSchedules', 'verificationRequests', 'workerInquiries'],
        ],

        'opManager' => [
            'OpManager' => ['index', 'specialRequests', 'workerSchedules'],
        ],
        
        'financeManager' => [
            'FinanceManager' => ['index', 'paymentHistory', 'paymentRates', 'paymentIssues', 'reports'],
        ],

        'customer'=> [
            'BookingHistory' => ['index'],
            'CustomerHelpDesk' => ['index', 'operationalHelp', 'paymentHelp', 'submitComplaint', 'getComplaintPriority', 'getSolution', 'clearSessionMessage'],
            'CustomerProfile'=> ['index', 'personalInfo', 'update', 'bookingHistory', 'paymentHistory', 'faq'],
            'Payment'> ['index', 'paymentDetail', 'paymentComplete', 'paymentProceed'],
            'Home' => ['customerRedirect'],
            'serviceForms' => ['index', 'getForm', 'testDataBase'],
        ],

        'worker' => [
            'WorkerProfile' => ['index', 'personalInfo', 'update', 'workingSchedule', 'faq'],
            'WorkerVerification' => ['index', 'submitVerificationForm', 'editVerificationRequest', 'update', 'deleteVerificationRequest', 'verificationStatus', 'clearSessionMessage'],
            'WorkingSchedule'=> ['index', 'getSchedule', 'saveSchedule', 'deleteSchedule', 'testDatabase'],
            'Home'=> ['workerRedirect'],
        ]
    ];

    private $publicAccess = [
        '-404' => ['index'],
        'About' => ['index'],
        'Contact' => ['index'],
        'Home' => ['index'],
        'ResetPassword' => ['index'],
        'ResetPasswordEnd' => ['index'],
        'Signup' => ['index'],
        'Login' => ['index', 'logout'],
        'Unauthorized' => ['index'],
        'SearchForWorker' => ['index', 'find', 'workerFound'],
        'SelectService' => ['index', 'cook', 'maid', 'nanny', 'cook24', 'allRounder', 'cookingService', 'calculateTotal', 'bookingInfo'],
        'Test' => ['index'],
    ];
    private function splitURL()
    {
        $URL = $_GET['url'] ?? 'home';
        $URL = explode('/', trim($URL, '/'));
        return $URL;
    }

    // Authorization for certain roles to access each controller
    private function checkAccess($controller, $method) {
        // Check if session exists and is logged in
        if (!isset($_SESSION['role']) || !isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
            return false;
        }
    
        $role = $_SESSION['role'];
        
        // Check if the role exists in roleAccess
        if (!isset($this->roleAccess[$role])) {
            return false;
        }
    
        // Find matching controllers (case-insensitive)
        $matchingControllers = array_filter(
            array_keys($this->roleAccess[$role]), 
            function($key) use ($controller) {
                return stripos($key, $controller) !== false;
            }
        );
    
        // Check method access for matching controllers
        foreach ($matchingControllers as $matchController) {
            if (isset($this->roleAccess[$role][$matchController]) && 
                in_array($method, $this->roleAccess[$role][$matchController])) {
                return true;
            }
        }
    
        return false;
    }

    private function isPublic($controller, $method) {
        return isset($this->publicAccess[$controller]) && in_array($method, $this->publicAccess[$controller]);
    }

    public function loadController()
{
    $URL = $this->splitURL();

    // Select controller
    $filename = "../app/controllers/" . ucfirst($URL[0]) . ".php";
    if (file_exists($filename))
    {
        require $filename;
        $this->controller = ucfirst($URL[0]);
    }
    else
    {
        require "../app/controllers/_404.php";
        $this->controller = '_404';
    }

    $controller = new $this->controller;

    // Select method
    if (!empty($URL[1]))
    {
        if (method_exists($controller, $URL[1]))
        {
            $this->method = $URL[1];
        }
    }

    // Remove the controller and method from the $URL array
    $params = array_slice($URL, 2);

    // Check if the controller and method are public or have access
    if ($this->isPublic($this->controller, $this->method) || $this->checkAccess($this->controller, $this->method)) {
        call_user_func_array([$controller, $this->method], $params);
    } else {
        // Redirect to unauthorized access page
        header("Location: /aideAura/public/unauthorized");
        exit();
    }
}
}
