<?php

class PaymentRateModel {
    use Model;

public function getAllPaymentRates(){
  $this->setTable('payment_rates');
  return $this->all();
}

public function updatePayrate($id, $data) {
  try {
      $this->setTable('payment_rates');
      
      // Validate input data
      if (!is_numeric($data['BasePrice']) || !is_numeric($data['BaseHours'])) {
          return false;
      }
      
      $payrateData = [
          'BasePrice' => $data['BasePrice'],
          'BaseHours' => $data['BaseHours'],
      ];
      
      $result = $this->update($id, $payrateData, 'ServiceID');
      return $result ? true : false;
      
  } catch (Exception $e) {
      error_log("Error updating payment rate: " . $e->getMessage());
      return false;
  }
}

}