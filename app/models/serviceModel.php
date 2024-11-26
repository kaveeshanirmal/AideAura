<?php

class ServiceModel {
    use Model; // Use the Model trait

    public function getBasePrice($service_type) {
        $this->setTable('service_base_price_hour');
        
        try {
            // Get specific service directly
            $query = "SELECT base_price, base_hours FROM " . $this->getTable() . " WHERE service_type = :service_type LIMIT 1";
            $result = $this->get_row($query, ['service_type' => $service_type]);
            
            // Debug log
            error_log("Query Result for $service_type: " . print_r($result, true));
            
            if ($result) {
                return [
                    'basePrice' => floatval($result->base_price),
                    'baseHours' => floatval($result->base_hours)
                ];
            }
            
            return ['basePrice' => 0, 'baseHours' => 0];
            
        } catch(Exception $e) {
            error_log("Database Error: " . $e->getMessage());
            return ['basePrice' => 0, 'baseHours' => 0];
        }
    }

    // Get all service types and their base prices
    public function getAllServices() {
        $this->setTable('service_base_price_hour');
        return $this->all();
    }

    // Update base price for a service
    public function updateBasePrice($service_type, $new_price) {
        $this->setTable('service_base_price_hour');
        
        try {
            $data = [
                'base_price' => $new_price
            ];
            
            return $this->update($service_type, $data, 'service_type');
        } catch(Exception $e) {
            error_log("Error updating base price: " . $e->getMessage());
            return false;
        }
    }

    // Add a new service type
    public function addService($service_type, $base_price) {
        $this->setTable('service_base_price_hour');
        
        try {
            $data = [
                'service_type' => $service_type,
                'base_price' => $base_price
            ];
            
            return $this->insert($data);
        } catch(Exception $e) {
            error_log("Error adding new service: " . $e->getMessage());
            return false;
        }
    }

    // Delete a service type
    public function deleteService($service_type) {
        $this->setTable('service_base_price_hour');
        
        try {
            return $this->delete($service_type, 'service_type');
        } catch(Exception $e) {
            error_log("Error deleting service: " . $e->getMessage());
            return false;
        }
    }

    // Get service details by type
    public function getServiceDetails($service_type) {
        $this->setTable('service_base_price_hour');
        
        try {
            return $this->find($service_type, 'service_type');
        } catch(Exception $e) {
            error_log("Error getting service details: " . $e->getMessage());
            return false;
        }
    }


    
    //test database
    public function testDatabaseConnection() {
        $this->setTable('service_base_price_hour');
        
        try {
            // Test query to get all services
            $query = "SELECT * FROM " . $this->getTable();
            $result = $this->get_all($query);
            
            // Output results directly
            echo "<pre>";
            echo "Database Test Results:\n";
            echo "Table: " . $this->getTable() . "\n";
            echo "Query: " . $query . "\n";
            echo "Results: ";
            print_r($result);
            echo "</pre>";
            
            return $result;
        } catch(Exception $e) {
            echo "<pre>";
            echo "Database Error:\n";
            echo $e->getMessage();
            echo "</pre>";
            return false;
        }
    }
}
//serviceForms controller handles form display and includes pricing data
//ServiceController can be used for other service-related operations
//ServiceModel handles all database operations
//Both controllers can use the same model for data access