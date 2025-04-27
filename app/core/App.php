<?php

class App
{
    // Default controller and method
    private $controller = 'Home';
    private $method = 'index';

    // 'admin','hrManager','opManager','financeManager','customer','worker'
    private $roleAccess = [
        'admin' => [
            'Admin' => ['index', 'employees', 'workers', 'workerDetails', 'getAvailabilitySchedule', 'generateScheduleView', 'getScheduleView','assignDynamicRoles','updateVerificationStatus', 'workerCertificates', 'workerSchedule', 'customers', 'searchCustomers',  'workerRoles', 'workerRoles1', 'addRole' , 'updateRole', 'deleteRoles', 'priceData', 'bookingDetails','searchBookingDetails', 'storePriceDetails', 'priceCategoryDetails', 'updatePriceDetails', 'updatePaymentRates', 'paymentDetails', 'workerInquiries', 'workerComplaints', 'replyComplaint', 'deleteComplaint','bookingReports'],

            'AdminEmployeeAdd' => ['index', 'store'],

            'AdminEmployees' => ['index', 'update', 'delete' , 'search' , 'all'],

            // 'AdminRoles1' => ['index', 'edit'],

            'Complaint' => ['adminIndex', 'details', 'chat', 'respond', 'resolve', 'delete', 'filter'],

            'workerComplaint' => ['adminIndex', 'details', 'chat', 'respond', 'resolve', 'delete', 'filter'],

            'BookingReports' => ['roleIndex', '', 'getTotalRevenue', 'getServiceTypeRevenue', 'getDailyRevenue'],
        ],

        'hrManager' => [
            'HrManager' => ['index', 'assignDynamicRoles', 'workerDetails','workerProfiles', 'updateVerificationStatus', 'getScheduleView', 'getScheduleViewOfWorker','findWorkerUserID', 'workerCertificates', 'getAvailabilitySchedule', 'workerSchedules', 'generateScheduleView', 'verificationRequests', 'workerMatching', 'workerComplaints','managePhysicalVerifications'],

            //'Complaint' => ['hrIndex', 'details', 'chat', 'respond', 'resolve', 'delete', 'filter'],

            'workerComplaint' => ['hrIndex', 'details', 'chat', 'respond', 'resolve', 'delete', 'filter'],
        ],

        'opManager' => [
            'OpManager' => ['index', 'customers', 'searchCustomers', 'bookingDetails', 'searchBookingDetails', 'workerSchedules', 'getScheduleView', 'workerMatching', 'workerInquiries'],

            'Complaint' => ['opIndex', 'details', 'chat', 'respond', 'resolve', 'delete', 'filter'],
        ],

        'financeManager' => [
            'FinanceManager' => ['index', 'paymentDetails','priceData','updatePriceDetails', 'cancelledBookings', 'updatePaymentRates', 'workerInquiries', 'bookingReports', 'workerComplaints'],

            'Complaint' => ['financeIndex', 'details', 'chat', 'respond', 'resolve', 'delete', 'filter'],

            'workerComplaint' => ['financeIndex', 'details', 'chat', 'respond', 'resolve', 'delete', 'filter'],

            'BookingReports' => ['roleIndex', '', 'getTotalRevenue', 'getServiceTypeRevenue', 'getDailyRevenue'],
        ],

        'customer'=> [
            'BookingHistory' => ['index'],
            'CustomerHelpDesk' => ['index', 'operationalHelp', 'paymentHelp', 'submitComplaint', 'getComplaintPriority', 'getSolution', 'clearSessionMessage', 'getConversation', 'submitReply'],
            'CustomerProfile'=> ['index', 'personalInfo', 'update', 'bookingHistory', 'cancellingBooking','paymentHistory', 'faq'],
            'SearchForWorker' => ['index', 'find', 'searchResults', 'processing', 'browseWorkers', 'waitingForResponse', 'noWorkersFound', 'noAlternativesFound', 'findExactMatch', 'findAlternatives'],
            'Payment' => ['authorize', 'success', 'cancel', 'notify', 'jsonResponse', 'clearSessionMessage'],
            'Home' => ['findWorkers'],
            'SelectService' => ['index', 'cook', 'maid', 'nanny', 'cook24', 'allRounder', 'bookingInfo', 'bookingSummary', 'submitBookingInfo', 'cookPricing', 'maidPricing', 'nannyPricing', 'cook24Pricing', 'allRounderPricing'],
            'Booking' => ['index', 'bookWorker', 'getBooking', 'getBookingState', 'noResponse', 'orderSummary', 'acceptanceTimeout', 'workerRejected', 'orderTimeout', 'cancelBooking'],
            'BookingReview' => ['index', 'checkPendingReviews', 'submitReview', 'getWorkerName', 'getPendingReviews', 'markAsShown', 'resetReviewShownFlag'],        ],

        'worker' => [
            'WorkerProfile' => ['index', 'personalInfo', 'update', 'workingSchedule', 'faqworker'],
            'WorkerVerification' => ['index', 'submitVerificationForm', 'editVerificationRequest', 'update', 'deleteVerificationRequest', 'verificationStatus', 'clearSessionMessage'],
            'WorkingSchedule'=> ['index', 'getSchedule', 'saveSchedule', 'deleteSchedule', 'testDatabase'],
            'Home'=> ['findJobs'],
            'Dashboard' => ['index', 'availability', 'getJobRequests', 'updateLocation', 'getLatestBookings'],
            'Booking' => ['getBooking', 'accept', 'reject', 'getBookingState', 'completeBooking'],
            'WorkerHelpDesk' => ['index', 'operationalHelp', 'paymentHelp', 'submitComplaint', 'getComplaintPriority', 'getSolution', 'clearSessionMessage', 'getConversation', 'submitReply'],
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
        'Login' => ['index', 'logout', 'restoreBookingSession', 'checkForExpiredBookings', 'checkForUncompletedBookings'],
        'Unauthorized' => ['index'],
        'SearchForWorker' => ['index', 'find', 'workerFound'],
        'Notifications' => ['index', 'poll', 'markAsRead', 'markAllAsRead', 'renderItem'],
        'Test' => ['index', 'testTime', 'mailHelper'],
    ];

    private $bookingFlowRestrictions = [
        'SelectService' => ['index', 'cook', 'maid', 'nanny', 'cook24', 'allRounder', 'bookingInfo', 'bookingSummary', 'submitBookingInfo'],
        'SearchForWorker' => ['index', 'find', 'searchResults', 'browseWorkers'],
    ];

    private function splitURL()
    {
        $URL = $_GET['url'] ?? 'home';
        $URL = explode('/', trim($URL, '/'));
        return $URL;
    }

    // Check if user has an active booking that should prevent accessing certain pages
    private function hasActiveBookingRestriction($controller, $method) {
        // Only apply to customers
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
            return false;
        }

        // Check if this controller/method is in the restricted list
        $isRestrictedPage = false;
        foreach ($this->bookingFlowRestrictions as $restrictedController => $restrictedMethods) {
            if (strcasecmp($controller, $restrictedController) === 0 && in_array($method, $restrictedMethods)) {
                $isRestrictedPage = true;
                break;
            }
        }

        if (!$isRestrictedPage) {
            return false;
        }

        // Check if there's an active booking in session
        if (isset($_SESSION['booking']) &&
            isset($_SESSION['booking']['status']) &&
            ($_SESSION['booking']['status'] === 'pending' || $_SESSION['booking']['status'] === 'accepted')) {

            // User has active booking - should not access booking flow pages
            return true;
        }

        return false;
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

        // Check if user is trying to access booking flow with active booking
        if ($this->hasActiveBookingRestriction($this->controller, $this->method)) {
            // Redirect to appropriate page based on booking status
            if (isset($_SESSION['booking']['status']) && ($_SESSION['booking']['status'] === 'pending'|| $_SESSION['booking']['status'] === 'accepted')) {
                header("Location: " . ROOT . "/public/searchForWorker/waitingForResponse");
                exit();
            }
        }

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