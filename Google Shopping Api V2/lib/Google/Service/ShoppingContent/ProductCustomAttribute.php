<?php
class Google_Service_ShoppingContent_ProductCustomAttribute extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $name;
  public $type;
  public $unit;
  public $value;


  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setType($type)
  {
    $this->type = $type;
  }
  public function getType()
  {
    return $this->type;
  }
  public function setUnit($unit)
  {
    $this->unit = $unit;
  }
  public function getUnit()
  {
    return $this->unit;
  }
  public function setValue($value)
  {
    $this->value = $value;
  }
  public function getValue()
  {
    return $this->value;
  }
}