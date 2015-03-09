<?php
class Google_Service_ShoppingContent_AccountShipping extends Google_Collection
{
  protected $collection_key = 'services';
  protected $internal_gapi_mappings = array(
  );
  public $accountId;
  protected $carrierRatesType = 'Google_Service_ShoppingContent_AccountShippingCarrierRate';
  protected $carrierRatesDataType = 'array';
  public $kind;
  protected $locationGroupsType = 'Google_Service_ShoppingContent_AccountShippingLocationGroup';
  protected $locationGroupsDataType = 'array';
  protected $rateTablesType = 'Google_Service_ShoppingContent_AccountShippingRateTable';
  protected $rateTablesDataType = 'array';
  protected $servicesType = 'Google_Service_ShoppingContent_AccountShippingShippingService';
  protected $servicesDataType = 'array';


  public function setAccountId($accountId)
  {
    $this->accountId = $accountId;
  }
  public function getAccountId()
  {
    return $this->accountId;
  }
  public function setCarrierRates($carrierRates)
  {
    $this->carrierRates = $carrierRates;
  }
  public function getCarrierRates()
  {
    return $this->carrierRates;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  public function setLocationGroups($locationGroups)
  {
    $this->locationGroups = $locationGroups;
  }
  public function getLocationGroups()
  {
    return $this->locationGroups;
  }
  public function setRateTables($rateTables)
  {
    $this->rateTables = $rateTables;
  }
  public function getRateTables()
  {
    return $this->rateTables;
  }
  public function setServices($services)
  {
    $this->services = $services;
  }
  public function getServices()
  {
    return $this->services;
  }
}