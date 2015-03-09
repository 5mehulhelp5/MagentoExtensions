<?php
class Google_Service_ShoppingContent_AccountsCustomBatchRequestEntry extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  protected $accountType = 'Google_Service_ShoppingContent_Account';
  protected $accountDataType = '';
  public $accountId;
  public $batchId;
  public $merchantId;
  public $method;


  public function setAccount(Google_Service_ShoppingContent_Account $account)
  {
    $this->account = $account;
  }
  public function getAccount()
  {
    return $this->account;
  }
  public function setAccountId($accountId)
  {
    $this->accountId = $accountId;
  }
  public function getAccountId()
  {
    return $this->accountId;
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