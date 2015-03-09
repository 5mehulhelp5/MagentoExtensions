<?php
class Google_Service_ShoppingContent_AccountShippingRateTable extends Google_Collection
{
  protected $collection_key = 'content';
  protected $internal_gapi_mappings = array(
  );
  protected $contentType = 'Google_Service_ShoppingContent_AccountShippingRateTableCell';
  protected $contentDataType = 'array';
  public $name;
  public $saleCountry;


  public function setContent($content)
  {
    $this->content = $content;
  }
  public function getContent()
  {
    return $this->content;
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
}