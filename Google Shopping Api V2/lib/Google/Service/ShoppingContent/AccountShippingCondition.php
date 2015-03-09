<?php
class Google_Service_ShoppingContent_AccountShippingCondition extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $deliveryLocationGroup;
  public $deliveryLocationId;
  public $deliveryPostalCode;
  protected $deliveryPostalCodeRangeType = 'Google_Service_ShoppingContent_AccountShippingPostalCodeRange';
  protected $deliveryPostalCodeRangeDataType = '';
  protected $priceMaxType = 'Google_Service_ShoppingContent_Price';
  protected $priceMaxDataType = '';
  public $shippingLabel;
  protected $weightMaxType = 'Google_Service_ShoppingContent_Weight';
  protected $weightMaxDataType = '';


  public function setDeliveryLocationGroup($deliveryLocationGroup)
  {
    $this->deliveryLocationGroup = $deliveryLocationGroup;
  }
  public function getDeliveryLocationGroup()
  {
    return $this->deliveryLocationGroup;
  }
  public function setDeliveryLocationId($deliveryLocationId)
  {
    $this->deliveryLocationId = $deliveryLocationId;
  }
  public function getDeliveryLocationId()
  {
    return $this->deliveryLocationId;
  }
  public function setDeliveryPostalCode($deliveryPostalCode)
  {
    $this->deliveryPostalCode = $deliveryPostalCode;
  }
  public function getDeliveryPostalCode()
  {
    return $this->deliveryPostalCode;
  }
  public function setDeliveryPostalCodeRange(Google_Service_ShoppingContent_AccountShippingPostalCodeRange $deliveryPostalCodeRange)
  {
    $this->deliveryPostalCodeRange = $deliveryPostalCodeRange;
  }
  public function getDeliveryPostalCodeRange()
  {
    return $this->deliveryPostalCodeRange;
  }
  public function setPriceMax(Google_Service_ShoppingContent_Price $priceMax)
  {
    $this->priceMax = $priceMax;
  }
  public function getPriceMax()
  {
    return $this->priceMax;
  }
  public function setShippingLabel($shippingLabel)
  {
    $this->shippingLabel = $shippingLabel;
  }
  public function getShippingLabel()
  {
    return $this->shippingLabel;
  }
  public function setWeightMax(Google_Service_ShoppingContent_Weight $weightMax)
  {
    $this->weightMax = $weightMax;
  }
  public function getWeightMax()
  {
    return $this->weightMax;
  }
}