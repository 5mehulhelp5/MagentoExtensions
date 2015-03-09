<?php

class Google_Service_ShoppingContent_ProductShipping extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $country;
  public $locationGroupName;
  public $locationId;
  public $postalCode;
  protected $priceType = 'Google_Service_ShoppingContent_Price';
  protected $priceDataType = '';
  public $region;
  public $service;


  public function setCountry($country)
  {
    $this->country = $country;
  }
  public function getCountry()
  {
    return $this->country;
  }
  public function setLocationGroupName($locationGroupName)
  {
    $this->locationGroupName = $locationGroupName;
  }
  public function getLocationGroupName()
  {
    return $this->locationGroupName;
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
  public function setPrice(Google_Service_ShoppingContent_Price $price)
  {
    $this->price = $price;
  }
  public function getPrice()
  {
    return $this->price;
  }
  public function setRegion($region)
  {
    $this->region = $region;
  }
  public function getRegion()
  {
    return $this->region;
  }
  public function setService($service)
  {
    $this->service = $service;
  }
  public function getService()
  {
    return $this->service;
  }
}