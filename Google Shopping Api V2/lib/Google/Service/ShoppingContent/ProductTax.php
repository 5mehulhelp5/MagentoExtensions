<?php
class Google_Service_ShoppingContent_ProductTax extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $country;
  public $locationId;
  public $postalCode;
  public $rate;
  public $region;
  public $taxShip;


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
  public function setPostalCode($postalCode)
  {
    $this->postalCode = $postalCode;
  }
  public function getPostalCode()
  {
    return $this->postalCode;
  }
  public function setRate($rate)
  {
    $this->rate = $rate;
  }
  public function getRate()
  {
    return $this->rate;
  }
  public function setRegion($region)
  {
    $this->region = $region;
  }
  public function getRegion()
  {
    return $this->region;
  }
  public function setTaxShip($taxShip)
  {
    $this->taxShip = $taxShip;
  }
  public function getTaxShip()
  {
    return $this->taxShip;
  }
}