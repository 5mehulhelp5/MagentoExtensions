<?php
class Google_Service_ShoppingContent_DatafeedFormat extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $columnDelimiter;
  public $fileEncoding;
  public $quotingMode;


  public function setColumnDelimiter($columnDelimiter)
  {
    $this->columnDelimiter = $columnDelimiter;
  }
  public function getColumnDelimiter()
  {
    return $this->columnDelimiter;
  }
  public function setFileEncoding($fileEncoding)
  {
    $this->fileEncoding = $fileEncoding;
  }
  public function getFileEncoding()
  {
    return $this->fileEncoding;
  }
  public function setQuotingMode($quotingMode)
  {
    $this->quotingMode = $quotingMode;
  }
  public function getQuotingMode()
  {
    return $this->quotingMode;
  }
}