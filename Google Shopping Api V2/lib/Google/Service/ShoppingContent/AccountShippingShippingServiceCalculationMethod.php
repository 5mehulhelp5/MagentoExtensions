<?php
class Google_Service_ShoppingContent_AccountShippingShippingServiceCalculationMethod extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $carrierRate;
  public $excluded;
  protected $flatRateType = 'Google_Service_ShoppingContent_Price';
  protected $flatRateDataType = '';
  public $percentageRate;
  public $rateTable;


  public function setCarrierRate($carrierRate)
  {
    $this->carrierRate = $carrierRate;
  }
  public function getCarrierRate()
  {
    return $this->carrierRate;
  }
  public function setExcluded($excluded)
  {
    $this->excluded = $excluded;
  }
  public function getExcluded()
  {
    return $this->excluded;
  }
  public function setFlatRate(Google_Service_ShoppingContent_Price $flatRate)
  {
    $this->flatRate = $flatRate;
  }
  public function getFlatRate()
  {
    return $this->flatRate;
  }
  public function setPercentageRate($percentageRate)
  {
    $this->percentageRate = $percentageRate;
  }
  public function getPercentageRate()
  {
    return $this->percentageRate;
  }
  public function setRateTable($rateTable)
  {
    $this->rateTable = $rateTable;
  }
  public function getRateTable()
  {
    return $this->rateTable;
  }
}