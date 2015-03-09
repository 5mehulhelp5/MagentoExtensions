<?php
class Google_Service_ShoppingContent_AccountShippingCarrierRate extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $carrier;
  public $carrierService;
  protected $modifierFlatRateType = 'Google_Service_ShoppingContent_Price';
  protected $modifierFlatRateDataType = '';
  public $modifierPercent;
  public $name;
  public $saleCountry;
  public $shippingOrigin;


  public function setCarrier($carrier)
  {
    $this->carrier = $carrier;
  }
  public function getCarrier()
  {
    return $this->carrier;
  }
  public function setCarrierService($carrierService)
  {
    $this->carrierService = $carrierService;
  }
  public function getCarrierService()
  {
    return $this->carrierService;
  }
  public function setModifierFlatRate(Google_Service_ShoppingContent_Price $modifierFlatRate)
  {
    $this->modifierFlatRate = $modifierFlatRate;
  }
  public function getModifierFlatRate()
  {
    return $this->modifierFlatRate;
  }
  public function setModifierPercent($modifierPercent)
  {
    $this->modifierPercent = $modifierPercent;
  }
  public function getModifierPercent()
  {
    return $this->modifierPercent;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setSaleCountry($saleCountry)
  {
    $this->saleCountry = $saleCountry;
  }
  public function getSaleCountry()
  {
    return $this->saleCountry;
  }
  public function setShippingOrigin($shippingOrigin)
  {
    $this->shippingOrigin = $shippingOrigin;
  }
  public function getShippingOrigin()
  {
    return $this->shippingOrigin;
  }
}