<?php
class Google_Service_ShoppingContent_AccounttaxCustomBatchResponseEntry extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  protected $accountTaxType = 'Google_Service_ShoppingContent_AccountTax';
  protected $accountTaxDataType = '';
  public $batchId;
  protected $errorsType = 'Google_Service_ShoppingContent_Errors';
  protected $errorsDataType = '';
  public $kind;


  public function setAccountTax(Google_Service_ShoppingContent_AccountTax $accountTax)
  {
    $this->accountTax = $accountTax;
  }
  public function getAccountTax()
  {
    return $this->accountTax;
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