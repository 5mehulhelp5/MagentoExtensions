<?php
class Google_Service_ShoppingContent_AccounttaxCustomBatchRequestEntry extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $accountId;
  protected $accountTaxType = 'Google_Service_ShoppingContent_AccountTax';
  protected $accountTaxDataType = '';
  public $batchId;
  public $merchantId;
  public $method;


  public function setAccountId($accountId)
  {
    $this->accountId = $accountId;
  }
  public function getAccountId()
  {
    return $this->accountId;
  }
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
  public function setMerchantId($merchantId)
  {
    $this->merchantId = $merchantId;
  }
  public function getMerchantId()
  {
    return $this->merchantId;
  }
  public function setMethod($method)
  {
    $this->method = $method;
  }
  public function getMethod()
  {
    return $this->method;
  }
}