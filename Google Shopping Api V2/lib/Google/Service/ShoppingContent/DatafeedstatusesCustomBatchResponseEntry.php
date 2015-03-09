<?php
class Google_Service_ShoppingContent_DatafeedstatusesCustomBatchResponseEntry extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $batchId;
  protected $datafeedStatusType = 'Google_Service_ShoppingContent_DatafeedStatus';
  protected $datafeedStatusDataType = '';
  protected $errorsType = 'Google_Service_ShoppingContent_Errors';
  protected $errorsDataType = '';


  public function setBatchId($batchId)
  {
    $this->batchId = $batchId;
  }
  public function getBatchId()
  {
    return $this->batchId;
  }
  public function setDatafeedStatus(Google_Service_ShoppingContent_DatafeedStatus $datafeedStatus)
  {
    $this->datafeedStatus = $datafeedStatus;
  }
  public function getDatafeedStatus()
  {
    return $this->datafeedStatus;
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