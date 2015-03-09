<?php
class Google_Service_ShoppingContent_AccountshippingCustomBatchResponseEntry extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  protected $accountShippingType = 'Google_Service_ShoppingContent_AccountShipping';
  protected $accountShippingDataType = '';
  public $batchId;
  protected $errorsType = 'Google_Service_ShoppingContent_Errors';
  protected $errorsDataType = '';
  public $kind;


  public function setAccountShipping(Google_Service_ShoppingContent_AccountShipping $accountShipping)
  {
    $this->accountShipping = $accountShipping;
  }
  public function getAccountShipping()
  {
    return $this->accountShipping;
  }
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
}