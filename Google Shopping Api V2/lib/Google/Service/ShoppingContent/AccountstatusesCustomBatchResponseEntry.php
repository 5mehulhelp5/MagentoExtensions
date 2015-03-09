<?php
class Google_Service_ShoppingContent_AccountstatusesCustomBatchResponseEntry extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  protected $accountStatusType = 'Google_Service_ShoppingContent_AccountStatus';
  protected $accountStatusDataType = '';
  public $batchId;
  protected $errorsType = 'Google_Service_ShoppingContent_Errors';
  protected $errorsDataType = '';


  public function setAccountStatus(Google_Service_ShoppingContent_AccountStatus $accountStatus)
  {
    $this->accountStatus = $accountStatus;
  }
  public function getAccountStatus()
  {
    return $this->accountStatus;
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
}