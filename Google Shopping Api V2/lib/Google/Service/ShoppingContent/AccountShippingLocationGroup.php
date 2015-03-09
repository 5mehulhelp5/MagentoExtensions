<?php
class Google_Service_ShoppingContent_AccountShippingLocationGroup extends Google_Collection
{
  protected $collection_key = 'postalCodes';
  protected $internal_gapi_mappings = array(
  );
  public $country;
  public $locationIds;
  public $name;
  protected $postalCodeRangesType = 'Google_Service_ShoppingContent_AccountShippingPostalCodeRange';
  protected $postalCodeRangesDataType = 'array';
  public $postalCodes;


  public function setCountry($country)
  {
    $this->country = $country;
  }
  public function getCountry()
  {
    return $this->country;
  }
  public function setLocationIds($locationIds)
  {
    $this->locationIds = $locationIds;
  }
  public function getLocationIds()
  {
    return $this->locationIds;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setPostalCodeRanges($postalCodeRanges)
  {
    $this->postalCodeRanges = $postalCodeRanges;
  }
  public function getPostalCodeRanges()
  {
    return $this->postalCodeRanges;
  }
  public function setPostalCodes($postalCodes)
  {
    $this->postalCodes = $postalCodes;
  }
  public function getPostalCodes()
  {
    return $this->postalCodes;
  }
}