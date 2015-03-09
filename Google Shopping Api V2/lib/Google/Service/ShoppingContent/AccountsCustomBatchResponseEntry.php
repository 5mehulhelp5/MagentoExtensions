<?php
class Google_Service_ShoppingContent_AccountsCustomBatchResponseEntry extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  protected $accountType = 'Google_Service_ShoppingContent_Account';
  protected $accountDataType = '';
  public $batchId;
  protected $errorsType = 'Google_Service_ShoppingContent_Errors';
  protected $errorsDataType = '';
  public $kind;


  public function setAccount(Google_Service_ShoppingContent_Account $account)
  {
    $this->account = $account;
  }
  public function getAccount()
  {
    return $this->account;
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