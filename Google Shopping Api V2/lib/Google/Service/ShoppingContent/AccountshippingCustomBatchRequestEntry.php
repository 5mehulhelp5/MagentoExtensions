<?php
class Google_Service_ShoppingContent_AccountshippingCustomBatchRequestEntry extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $accountId;
  protected $accountShippingType = 'Google_Service_ShoppingContent_AccountShipping';
  protected $accountShippingDataType = '';
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