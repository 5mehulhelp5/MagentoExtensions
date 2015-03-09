<?php
class Google_Service_ShoppingContent_AccountShippingRateTableCell extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  protected $conditionType = 'Google_Service_ShoppingContent_AccountShippingCondition';
  protected $conditionDataType = '';
  protected $rateType = 'Google_Service_ShoppingContent_Price';
  protected $rateDataType = '';


  public function setCondition(Google_Service_ShoppingContent_AccountShippingCondition $condition)
  {
    $this->condition = $condition;
  }
  public function getCondition()
  {
    return $this->condition;
  }
  public function setRate(Google_Service_ShoppingContent_Price $rate)
  {
    $this->rate = $rate;
  }
  public function getRate()
  {
    return $this->rate;
  }
}