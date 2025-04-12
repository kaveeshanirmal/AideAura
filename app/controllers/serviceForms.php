<?php

class serviceForms extends Controller
{
    private $serviceModel;

    public function __construct() {
        $this->serviceModel = new ServiceModel();
    }

    public function index($a = '', $b = '', $c = '')
    {
        // Get pricing data for both services
        $homeStyleData = $this->serviceModel->getBasePrice('home-style-food');
        $dishwashingData = $this->serviceModel->getBasePrice('dishwashing');
        
        // Prepare data for view
        $data['services'] = [
            'home-style-food' => [
                'basePrice' => $homeStyleData['basePrice'],
                'baseHours' => $homeStyleData['baseHours'],
                'serviceType' => 'home-style-food'
            ],
            'dishwashing' => [
                'basePrice' => $dishwashingData['basePrice'],
                'baseHours' => $dishwashingData['baseHours'],
                'serviceType' => 'dishwashing'
            ]
        ];
        
        $this->view('forms/cooking', $data);
    }

    public function getForm($service)
    {
        try {
            // Always get home-style food data for base values
            $homeStyleData = $this->serviceModel->getBasePrice('home-style-food');
            
            // Prepare data with home-style food values
            $data['services'] = [
                'home-style-food' => [
                    'basePrice' => $homeStyleData['basePrice'],
                    'baseHours' => $homeStyleData['baseHours']
                ]
            ];
            
            // Debug output
            error_log("Home-style food data: " . print_r($homeStyleData, true));

            // Map service names to view files
            $viewMapping = [
                'home-style-food' => 'home_style_food',  // Map to correct view file name
                'dishwashing' => 'dishwashing'
            ];

            if (isset($viewMapping[$service])) {
                $viewFile = $viewMapping[$service];
                $this->view('forms/' . $viewFile, $data);
            } else {
                error_log("Invalid service requested: " . $service);
                $this->view('404');
            }
            
        } catch(Exception $e) {
            error_log("Error in getForm: " . $e->getMessage());
            $this->view('404');
        }
    }

    public function testDatabase() {
        $result = $this->serviceModel->testDatabaseConnection();
        echo "<pre>";
        echo "Controller SearchForWorker Results:\n";
        print_r($result);
        echo "</pre>";
        die();
    }
}



