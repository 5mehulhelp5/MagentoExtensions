<?php
class Google_Service_ShoppingContent_ProductInstallment extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  protected $amountType = 'Google_Service_ShoppingContent_Price';
  protected $amountDataType = '';
  public $months;


  public function setAmount(Google_Service_ShoppingContent_Price $amount)
  {
    $this->amount = $amount;
  }
  public function getAmount()
  {
    return $this->amount;
  }
  public function setMonths($months)
  {
    $this->months = $months;
  }
  public function getMonths()
  {
    return $this->months;
  }
}