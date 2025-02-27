<?php

class SelectService extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('serviceForms');
    }

    public function cook()
    {
        $this->view('serviceForms/cook');
    }

    public function cookingService()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_SESSION['num_people'] = $_POST['people'] ?? [];
            $_SESSION['num_meals'] = $_POST['meals'] ?? [];
            $_SESSION['diet'] = $_POST['diet'] ?? [];
            $_SESSION['addons'] = $_POST['addons'] ?? [];

            $_SESSION['total_cost'] = $this->calculateTotal("cook");

        }
    }
    public function maid()
    {
        $this->view('serviceForms/maid');
    }

    public function nanny()
    {
        $this->view('serviceForms/nanny');
    }

    public function cook24()
    {
        $this->view('serviceForms/cook24');
    }

    public function allRounder()
    {
        $this->view('serviceForms/allRounder');
    }

    private function calculateTotal($service)
    {
        // Base pricing in rupees
        if ($service == 'cook') {
            $cost = 0;

            $people_cost = [
                "1-2" => 500,
                "3-5" => 700,
                "5-7" => 850,
                "8-10" => 1000,
            ];

            $addon_cost = [
                "dishwashing" => 500,
                "desserts" => 200,
                "shopping" => 500,
            ];

            if (isset($_SESSION['num_people'])) {
                $cost += $people_cost[$_SESSION['num_people']] ?? 0;
            }

            if (isset($_SESSION['num_meals'])) {
                $cost += count($_SESSION['num_meals']) * $cost;
            }

            if (isset($_SESSION['addons'])) {
                foreach ($_SESSION['addons'] as $addon) {
                    $cost += $addon_cost[$addon] ?? 0;
                }
            }

            if (isset($_SESSION['diet'])) {
                if ($_SESSION['diet'] == 'veg') {
                    $cost -= 150;
                }
            }

            $_SESSION['total_cost'] = $cost;

            header("Content-Type: application/json");
            echo json_encode(["total" => $cost]);
            exit;
        }
    }


}

