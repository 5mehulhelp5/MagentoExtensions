<?php
class Google_Service_ShoppingContent_AccountTaxTaxRule extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $country;
  public $locationId;
  public $ratePercent;
  public $shippingTaxed;
  public $useGlobalRate;


  public function setCountry($country)
  {
    $this->country = $country;
  }
  public function getCountry()
  {
    return $this->country;
  }
  public function setLocationId($locationId)
  {
    $this->locationId = $locationId;
  }
  public function getLocationId()
  {
    return $this->locationId;
  }
  public function setRatePercent($ratePercent)
  {
    $this->ratePercent = $ratePercent;
  }
  public function getRatePercent()
  {
    return $this->ratePercent;
  }
  public function setShippingTaxed($shippingTaxed)
  {
    $this->shippingTaxed = $shippingTaxed;
  }
  public function getShippingTaxed()
  {
    return $this->shippingTaxed;
  }
  public function setUseGlobalRate($useGlobalRate)
  {
    $this->useGlobalRate = $useGlobalRate;
  }
  public function getUseGlobalRate()
  {
    return $this->useGlobalRate;
  }
}