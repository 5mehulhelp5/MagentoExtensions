<?php
class Google_Service_ShoppingContent_InventorySetRequest extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $availability;
  protected $priceType = 'Google_Service_ShoppingContent_Price';
  protected $priceDataType = '';
  public $quantity;
  protected $salePriceType = 'Google_Service_ShoppingContent_Price';
  protected $salePriceDataType = '';
  public $salePriceEffectiveDate;


  public function setAvailability($availability)
  {
    $this->availability = $availability;
  }
  public function getAvailability()
  {
    return $this->availability;
  }
  public function setPrice(Google_Service_ShoppingContent_Price $price)
  {
    $this->price = $price;
  }
  public function getPrice()
  {
    return $this->price;
  }
  public function setQuantity($quantity)
  {
    $this->quantity = $quantity;
  }
  public function getQuantity()
  {
    return $this->quantity;
  }
  public function setSalePrice(Google_Service_ShoppingContent_Price $salePrice)
  {
    $this->salePrice = $salePrice;
  }
  public function getSalePrice()
  {
    return $this->salePrice;
  }
  public function setSalePriceEffectiveDate($salePriceEffectiveDate)
  {
    $this->salePriceEffectiveDate = $salePriceEffectiveDate;
  }
  public function getSalePriceEffectiveDate()
  {
    return $this->salePriceEffectiveDate;
  }
}