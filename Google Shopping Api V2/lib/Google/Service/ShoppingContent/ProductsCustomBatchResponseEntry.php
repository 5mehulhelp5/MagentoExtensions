<?php
class Google_Service_ShoppingContent_ProductsCustomBatchResponseEntry extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $batchId;
  protected $errorsType = 'Google_Service_ShoppingContent_Errors';
  protected $errorsDataType = '';
  public $kind;
  protected $productType = 'Google_Service_ShoppingContent_Product';
  protected $productDataType = '';


  public function setBatchId($batchId)
  {
    $this->batchId = $batchId;
  }
  public function getBatchId()
  {
    return $this->batchId;
  }
  public function setErrors(Google_Service_ShoppingContent_Errors $errors)
  {
    $this->errors = $errors;
  }
  public function getErrors()
  {
    return $this->errors;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  public function setProduct(Google_Service_ShoppingContent_Product $product)
  {
    $this->product = $product;
  }
  public function getProduct()
  {
    return $this->product;
  }
}