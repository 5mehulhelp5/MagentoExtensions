<?php
class Google_Service_ShoppingContent_DatafeedStatus extends Google_Collection
{
  protected $collection_key = 'warnings';
  protected $internal_gapi_mappings = array(
  );
  public $datafeedId;
  protected $errorsType = 'Google_Service_ShoppingContent_DatafeedStatusError';
  protected $errorsDataType = 'array';
  public $itemsTotal;
  public $itemsValid;
  public $kind;
  public $processingStatus;
  protected $warningsType = 'Google_Service_ShoppingContent_DatafeedStatusError';
  protected $warningsDataType = 'array';


  public function setDatafeedId($datafeedId)
  {
    $this->datafeedId = $datafeedId;
  }
  public function getDatafeedId()
  {
    return $this->datafeedId;
  }
  public function setErrors($errors)
  {
    $this->errors = $errors;
  }
  public function getErrors()
  {
    return $this->errors;
  }
  public function setItemsTotal($itemsTotal)
  {
    $this->itemsTotal = $itemsTotal;
  }
  public function getItemsTotal()
  {
    return $this->itemsTotal;
  }
  public function setItemsValid($itemsValid)
  {
    $this->itemsValid = $itemsValid;
  }
  public function getItemsValid()
  {
    return $this->itemsValid;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  public function setProcessingStatus($processingStatus)
  {
    $this->processingStatus = $processingStatus;
  }
  public function getProcessingStatus()
  {
    return $this->processingStatus;
  }
  public function setWarnings($warnings)
  {
    $this->warnings = $warnings;
  }
  public function getWarnings()
  {
    return $this->warnings;
  }
}