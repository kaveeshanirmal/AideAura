<?php

class PricingModel {
    use Model;
    public function getServicePricing($roleName) {
        $sql = "SELECT c.categoryName, c.description, d.detailName, d.price, d.description, d.updatedAt
                FROM price_categories c
                JOIN price_details d ON c.categoryID = d.categoryID
                WHERE c.roleID = (SELECT roleID FROM jobroles j WHERE j.name = ?)";

        return $this->get_all($sql, [$roleName]);
    }

    public function getPriceForDetail($roleName, $categoryName, $detailName) {
        $sql = "SELECT d.price FROM price_details d
                JOIN price_categories c ON d.categoryID = c.categoryID
                JOIN jobroles j ON c.roleID = j.roleID
                WHERE j.name = ? AND c.categoryName = ? AND d.detailName = ?";
        $result = $this->get_row($sql, [$roleName, $categoryName, $detailName]);
        return $result->price ?? 0;
    }

    public function calculateServiceTotal($roleName, $formData): array
    {
        $base_price = 0;
        $addon_price = 0;

        if ($roleName == 'Cook') {
            // Get base price for number of people
            $people_cost = $this->getPriceForDetail($roleName, 'people_cost', $formData['num_people'] ?? '');
            // Apply base price based on people count
            $base_price = $people_cost;

            // Multiply by number of meals if applicable
            if (isset($formData['num_meals']) && is_array($formData['num_meals'])) {
                $meal_count = count($formData['num_meals']);
                $base_price *= ($meal_count > 0) ? $meal_count : 1;
            }

            // Calculate non-veg cost if applicable
            if (isset($formData['diet']) && $formData['diet'] == 'nonveg') {
                $nonveg_cost = $this->getPriceForDetail($roleName, 'diet', 'non-veg');
                $base_price += $nonveg_cost;
            }

            // Calculate addon costs
            if (isset($formData['addons']) && is_array($formData['addons'])) {
                foreach ($formData['addons'] as $addon) {
                    $addon_cost = $this->getPriceForDetail($roleName, 'addon_cost', $addon);
                    $addon_price += $addon_cost;
                }
            }

            // Calculate total price at the end
            $total_price = $base_price + $addon_price;

            // Return the prices
            return [
                'total_price' => $total_price,
                'base_price' => $base_price,
                'addon_price' => $addon_price
            ];
        }

        elseif ($roleName == 'Maid' || $roleName == 'Nanny') {
            // For all formData element, fetch price using a for loop
            foreach ($formData as $key => $value) {
                if ($key == 'addons') {
                    // Calculate addon costs
                    if (is_array($value)) {
                        foreach ($value as $addon) {
                            $addon_cost = $this->getPriceForDetail($roleName, 'addons', $addon);
                            $addon_price += $addon_cost;
                        }
                    }
                    continue;
                }

                // Handle array values (like services[])
                if (is_array($value)) {
                    foreach ($value as $detail) {
                        $base_price += $this->getPriceForDetail($roleName, $key, $detail);
                    }
                } else {
                    // Handle single values (like property-size)
                    $base_price += $this->getPriceForDetail($roleName, $key, $value);
                }
            }
            return [
                'total_price' => $base_price+$addon_price,
                'base_price' => $base_price,
                'addon_price' => $addon_price
            ];
        }

        elseif ($roleName == 'Cook 24-hour Live in') {
            return [
                'total_price' => 0,
                'base_price' => 0,
                'addon_price' => 0
            ];
        }

        return [
            'total_price' => $base_price + $addon_price,
            'base_price' => $base_price,
            'addon_price' => $addon_price
        ];
    }




public function getAllPriceDetails()
{
    $this->setTable('price_details');

    $sql = "SELECT 
                pd.*, 
                pc.categoryName, 
                pc.description AS categoryDescription, 
                pc.displayName AS categoryDisplayName,
                pc.roleID,
                wr.name AS roleName
            FROM price_details pd
            LEFT JOIN price_categories pc ON pd.categoryID = pc.categoryID
            LEFT JOIN jobroles wr ON pc.roleID = wr.roleID";

    return $this->get_all($sql, []);
}


    
    public function getPriceCategoryDetailsFromCategoryID($categoryID){
        $this->setTable('price_categories');
        $CategoryData = $this->find($categoryID,'categoryID');
        if($CategoryData){
            return $CategoryData;
        } else{
            return false;
        }
    }

    // function to update price details
    public function updatePriceDetails($detailID,$data)
    {
    $this->setTable('price_details');
    $result = $this->update($detailID, $data, 'detailID');

    if (!$result) {
        return false;
    }
    return true; // Update failed
}

//     public function updateCategoryDetails($categoryID,$data)
//     {
//     $this->setTable('price_categories');
//     $result = $this->update($categoryID, $data, 'categoryID');

//     if (!$result) {
//         return false;
//     }
//     return true; // Update failed
// }

}