<?php
class Google_Service_ShoppingContent_LoyaltyPoints extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $name;
  public $pointsValue;
  public $ratio;


  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setPointsValue($pointsValue)
  {
    $this->pointsValue = $pointsValue;
  }
  public function getPointsValue()
  {
    return $this->pointsValue;
  }
  public function setRatio($ratio)
  {
    $this->ratio = $ratio;
  }
  public function getRatio()
  {
    return $this->ratio;
  }
}