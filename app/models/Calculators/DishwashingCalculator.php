<?php

namespace App\Models\Calculators;

use App\Models\BaseCalculator;

class DishwashingCalculator extends BaseCalculator {
    public function calculateAdditionals($peopleCount, $baseHours, $basePrice) {
        $cacheKey = "dishwashing_{$peopleCount}_{$baseHours}_{$basePrice}";
        
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $result = $this->calculate($peopleCount, $baseHours, $basePrice);
        $this->cache[$cacheKey] = $result;
        
        return $result;
    }

    private function calculate($peopleCount, $baseHours, $basePrice) {
        // Get additional charges and hours from database
        $additionalHours = $this->getAdditionalHours($peopleCount);
        $additionalCharge = $this->getAdditionalCharge($peopleCount);

        // Calculate total hours
        $totalHours = $baseHours;
        if ($additionalHours && isset($additionalHours->additional_minutes)) {
            $totalHours += floatval($additionalHours->additional_minutes);
        }

        // Calculate monthly price (base price + (additional charge * 30 days))
        $monthlyPrice = $basePrice;
        if ($additionalCharge && isset($additionalCharge->additional_charge)) {
            // For people count 2-4: multiply by (people_count - 1)
            if (in_array($peopleCount, ['2', '3', '4'])) {
                $extraPeople = intval($peopleCount) - 1;
                $monthlyPrice += (floatval($additionalCharge->additional_charge) * $extraPeople * 30);
            } 
            // For people count 5-8: add flat additional charge
            else if (in_array($peopleCount, ['5-6', '7-8'])) {
                $monthlyPrice += (floatval($additionalCharge->additional_charge) * 30);
            }
        }

        return [
            'monthly_price' => round($monthlyPrice),
            'working_hours' => round($totalHours, 2)
        ];
    }

    private function getAdditionalHours($peopleCount) {
        $query = "SELECT additional_minutes FROM dishwashing_additional_hours WHERE people_count = ?";
        return $this->get_row($query, [$peopleCount]);
    }

    private function getAdditionalCharge($peopleCount) {
        $query = "SELECT additional_charge FROM dishwashing_additional_charges WHERE people_count = ?";
        return $this->get_row($query, [$peopleCount]);
    }

    public function testDatabaseConnection() {
        echo "<pre>";
        echo "Testing Dishwashing Database Connection:\n\n";
        
        echo "Additional Hours:\n";
        $query = "SELECT * FROM dishwashing_additional_hours LIMIT 1";
        var_dump($this->get_row($query));
        
        echo "\nAdditional Charges:\n";
        $query = "SELECT * FROM dishwashing_additional_charges LIMIT 1";
        var_dump($this->get_row($query));
        
        echo "</pre>";
    }
}