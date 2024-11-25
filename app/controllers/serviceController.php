<?php

namespace App\Controllers;

use App\Models\Calculators\HomeStyleFoodCalculator;
use App\Models\Calculators\DishwashingCalculator;

class ServicesController {
    use \Database;

    private $homeStyleCalculator;
    private $dishwashingCalculator;

    public function __construct() {
        $this->homeStyleCalculator = new HomeStyleFoodCalculator();
        $this->dishwashingCalculator = new DishwashingCalculator();
    }

    public function processHomeStyleFood() {
        if(!$this->validateHomeStyleInput($_POST)) {
            $this->jsonResponse(false, "Invalid input parameters");
            return;
        }

        try {
            $params = [
                'meal_type' => $_POST['meals_type'],
                'people_count' => (int)$_POST['people_count'],
                'is_non_veg' => $_POST['food_preference'] === 'Veg + Non-Veg'
            ];

            $result = $this->homeStyleCalculator->calculateService($params);
            $_SESSION['service_calculation'] = $result;

            $this->jsonResponse(true, $result);
        } catch (\Exception $e) {
            $this->jsonResponse(false, $e->getMessage());
        }
    }

    public function processDishwashing() {
        if(!$this->validateDishwashingInput($_POST)) {
            $this->jsonResponse(false, "Invalid input parameters");
            return;
        }

        try {
            $result = $this->dishwashingCalculator->calculateAdditionals(
                (int)$_POST['people_count'],
                (float)$_SESSION['base_hours'],
                (float)$_SESSION['base_price']
            );
            
            $_SESSION['dishwashing_calculation'] = $result;

            $this->jsonResponse(true, $result);
        } catch (\Exception $e) {
            $this->jsonResponse(false, $e->getMessage());
        }
    }

    private function jsonResponse($success, $data) {
        echo json_encode([
            'success' => $success,
            'data' => $data
        ]);
    }

    private function validateHomeStyleInput($input) {
        return isset($input['meals_type']) && 
               isset($input['people_count']) && 
               isset($input['food_preference']);
    }

    private function validateDishwashingInput($input) {
        return isset($input['people_count']) && 
               isset($_SESSION['base_hours']) && 
               isset($_SESSION['base_price']);
    }

    public function getServiceForm($serviceType) {
        // Fetch pricing data based on service type
        $pricingData = $this->getPricingData($serviceType);
        
        // Pass the data to the view
        $viewPath = "forms/{$serviceType}.view.php";
        include(ROOT_PATH . "/app/views/{$viewPath}");
    }

    private function getPricingData($serviceType) {
        if ($serviceType === 'home-style-food') {
            return [
                'workingHours' => $this->get_all("SELECT * FROM home_style_food_working_hours"),
                'pricingRules' => $this->get_all("SELECT * FROM home_style_food_pricing_rules")
            ];
        } else if ($serviceType === 'dishwashing') {
            return [
                'additionalHours' => $this->get_all("SELECT * FROM dishwashing_additional_hours"),
                'additionalCharges' => $this->get_all("SELECT * FROM dishwashing_additional_charges")
            ];
        }
    }
}