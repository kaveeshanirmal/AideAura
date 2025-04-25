<?php

class Test extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('orderSummary');
    }

    public function testTime()
    {
        $this->view('testTime');
    }

    //test mailHelper
    public function mailHelper()
    {
        $to = "test@example.com";
        $subject = "Test Email via MailHog";
        $message = "This is a test email sent from PHP using the mail() function!";
        $headers = "From: no-reply@example.com";

        if (MailHelper::sendMail($to, $subject, $message, $headers)) {
            echo "Mail sent successfully.";
        } else {
            echo "Mail failed.";
        }
    }

}