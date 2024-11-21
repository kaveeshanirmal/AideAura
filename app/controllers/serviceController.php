<?php

class ServiceController {

    public function HomeStyleCook() {
        require_once 'view/services/home_style_food.php';
    }

    public function submitServiceForm() {
        // Just handling validation for now
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $people = isset($_POST['people']) ? $_POST['people'] : '';
            $meals = isset($_POST['meals']) ? $_POST['meals'] : '';
            $veg = isset($_POST['veg']) ? $_POST['veg'] : '';

            if (!$people || !$meals || !$veg) {
                $error = 'All fields are required!';
            } else {
                $success = 'Form submitted successfully!';
            }
        }

        require_once 'view/services/home_style_food.php';
    }
}
