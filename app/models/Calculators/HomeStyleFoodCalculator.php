<?php

namespace App\Models\Calculators;

use App\Models\BaseCalculator;

class HomeStyleFoodCalculator extends BaseCalculator {
    public function calculateService($params) {
        $cacheKey = $this->generateCacheKey($params);
        
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $result = $this->calculate($params);
        $this->cache[$cacheKey] = $result;
        
        return $result;
    }

    private function calculate($params) {
        // Extract parameters
        $mealType = $params['meal_type'];
        $peopleCount = $params['people_count'];
        $isNonVeg = $params['is_non_veg'];

        // Get base prices and hours from database
        $pricingRule = $this->getPricingRule($mealType);
        $workingHours = $this->getWorkingHours($mealType);
        
        if (!$pricingRule || !$workingHours) {
            return ['monthly_price' => 0, 'working_hours' => 0];
        }

        // Initialize with base values
        $price = $pricingRule->base_price;
        $hours = $workingHours->base_hours;

        // Calculate additional charges based on people count
        if ($peopleCount > 1) {
            if ($peopleCount <= 4) {
                // For 2-4 people: add additional_person_rate_1_5 for each extra person
                $extraPeople = $peopleCount - 1;
                $price += ($extraPeople * $pricingRule->additional_person_rate_1_5);
            } else {
                // For 5-8 people: add additional_person_rate_6_plus
                $price += $pricingRule->additional_person_rate_6_plus;
                
                // Add additional hours based on group size
                if ($peopleCount <= 6) {
                    $hours += $workingHours->additional_hours_5_6;
                } else {
                    $hours += $workingHours->additional_hours_7_8;
                }
            }
        }

        // Add non-veg premium if applicable
        if ($isNonVeg) {
            $price += $pricingRule->non_veg_surcharge;
            $hours += 0.5; // Additional 30 minutes for non-veg
        }

        // Calculate monthly price (multiply by 30 days)
        $monthlyPrice = $price * 30;

        return [
            'monthly_price' => round($monthlyPrice),
            'working_hours' => round($hours, 2)
        ];
    }

    private function getPricingRule($mealType) {
        $query = "SELECT * FROM home_style_food_pricing_rules WHERE meal_type = ?";
        return $this->get_row($query, [$mealType]);
    }

    private function getWorkingHours($mealType) {
        $query = "SELECT * FROM home_style_food_working_hours WHERE meal_type = ?";
        return $this->get_row($query, [$mealType]);
    }

}