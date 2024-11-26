<?php

class workerVerification extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('workerVerification/employeeVerificationForm');
    }

    public function submitVerificationForm()
{
    // Ensure that the request method is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Read the raw POST data
        $inputData = file_get_contents('php://input');
        
        // Decode the incoming JSON data into a PHP associative array
        $formData = json_decode($inputData, true);

        // Check if the data was successfully decoded
        if ($formData) {
            // Return the data as JSON
            echo json_encode([
                'status' => 'success',
                'message' => 'Form data received successfully.',
                'data' => $formData,
            ]);
            // Ensure there's no extra output here
        } else {
            // If JSON decoding fails, return an error message
            http_response_code(400); // Bad Request
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid JSON data received.',
            ]);
        }
    } else {
        // If the method is not POST, return method not allowed
        http_response_code(405); // Method Not Allowed
        echo json_encode([
            'status' => 'error',
            'message' => 'Method Not Allowed. Please use POST.',
        ]);
    }
}

}



