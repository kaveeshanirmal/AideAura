<?php

class PaymentRateModel {
    use Model;

public function getAllPaymentRates(){
  $this->setTable('payment_rates');
  return $this->all();
}

public function updatePayrate($id, $data){
    $this->setTable('payment_rates');
    
    $payrateData = [
        'BasePrice' => $data->BasePrice,
        'BaseHours' => $data->BaseHours,
     ];

     $result = $this->update($id, $payrateData,'ServiceID');

     if(!$result) {
        return false;
     }
     return true;
}

}