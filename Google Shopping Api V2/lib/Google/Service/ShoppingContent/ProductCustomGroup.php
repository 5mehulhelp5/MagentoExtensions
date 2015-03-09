<?php

class Google_Service_ShoppingContent_ProductCustomGroup extends Google_Collection
{
  protected $collection_key = 'attributes';
  protected $internal_gapi_mappings = array(
  );
  protected $attributesType = 'Google_Service_ShoppingContent_ProductCustomAttribute';
  protected $attributesDataType = 'array';
  public $name;


  public function setAttributes($attributes)
  {
    $this->attributes = $attributes;
  }
  public function getAttributes()
  {
    return $this->attributes;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
}
