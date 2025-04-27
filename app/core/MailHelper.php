<?php

class MailHelper
{
    public static function sendMail($to, $subject, $message) {
        // Set the headers
        $headers = "From: no-reply@aideaura.com\r\n";
        return mail($to, $subject, $message, $headers);
    }

    public static function sendBookingConfirmation($to, $bookingDetails, $role) {
        $subject = "Booking Confirmation";

        if ($role == 'worker') {
            $message = "Dear Service Provider,\n\n";
            $message .= "You have a new booking!\n\n";
            $message .= "Booking Details:\n";
            $message .= "Booking ID: " . $bookingDetails->bookingID . "\n";
            $message .= "Date: " . $bookingDetails->bookingDate . "\n";
            $message .= "Location: " . $bookingDetails->location . "\n";
            $message .= "Time: " . $bookingDetails->startTime . "\n";
            $message .= "Booking Amount: LKR " . number_format($bookingDetails->totalCost, 2) . "\n";
            $message .= "Service: " . $bookingDetails->serviceType . "\n";
            $message .= "Thank you for using our service.";
        } elseif ($role == 'customer') {
            $message = "Dear Customer,\n\n";
            $message .= "Your booking has been confirmed.\n\n";
            $message .= "Booking Details:\n";
            $message .= "Booking ID: " . $bookingDetails->bookingID . "\n";
            $message .= "Service Type: " . $bookingDetails->serviceType . "\n";
            $message .= "Date: " . $bookingDetails->bookingDate . "\n";
            $message .= "Time: " . $bookingDetails->startTime . "\n";
            $message .= "Amount Paid: LKR " . number_format($bookingDetails->totalCost, 2) . "\n";
            $message .= "Thank you for using our service.";
        }

        return self::sendMail($to, $subject, $message);
    }

    public static function sendJobRequest($to, $bookingDetails)
    {
        $subject = "You have a new Job Request";
        $message = "Dear Service Provider,\n\n";
        $message .= "You have a new job request!\n\n";
        $message .= "Booking Details:\n";
        $message .= "Service Type: " . $bookingDetails->serviceType . "\n";
        $message .= "Date: " . $bookingDetails->bookingDate . "\n";
        $message .= "Time: " . $bookingDetails->startTime . "\n";
        $message .= "Location: " . $bookingDetails->location . "\n\n";
        $message .= "Thank you for using our service.\n\n";
        $message .= "We kindly request you to accept or reject the job request before it gets auto-rejected.\n";

        return self::sendMail($to, $subject, $message);
    }

    public static function sendInLocationCode($to, $code)
    {
        $subject = "Verification Code";
        $message = "Dear Service Provider,\n\n";
        $message .= "Your verification reference code is: " . $code . "\n\n";
        $message .= "Please use this code along with your verification request.\n\n";
        $message .= "Thank you for using our service.";

        return self::sendMail($to, $subject, $message);

    }

    public static function sendJobCompletionCode($to, $code)
    {
        $subject = "Job Completion Code";
        $message = "Dear Customer,\n\n";
        $message .= "Completion code for your booking is: " . $code . "\n\n";
        $message .= "Please hand this over to your service provider once the job is done.\n\n";
        $message .= "Thank you for using our service.";

        return self::sendMail($to, $subject, $message);
    }
}