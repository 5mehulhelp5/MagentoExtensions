<?php
class Google_Service_ShoppingContent_Price extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $currency;
  public $value;


  public function setCurrency($currency)
  {
    $this->currency = $currency;
  }
  public function getCurrency()
  {
    return $this->currency;
  }
  public function setValue($value)
  {
    $this->value = $value;
  }
  public function getValue()
  {
    return $this->value;
  }
}