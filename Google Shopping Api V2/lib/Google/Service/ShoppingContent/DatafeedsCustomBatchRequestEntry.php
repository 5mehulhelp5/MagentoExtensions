<?php
class Google_Service_ShoppingContent_DatafeedsCustomBatchRequestEntry extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $batchId;
  protected $datafeedType = 'Google_Service_ShoppingContent_Datafeed';
  protected $datafeedDataType = '';
  public $datafeedId;
  public $merchantId;
  public $method;


  public function setBatchId($batchId)
  {
    $this->batchId = $batchId;
  }
  public function getBatchId()
  {
    return $this->batchId;
  }
  public function setDatafeed(Google_Service_ShoppingContent_Datafeed $datafeed)
  {
    $this->datafeed = $datafeed;
  }
  public function getDatafeed()
  {
    return $this->datafeed;
  }
  public function setDatafeedId($datafeedId)
  {
    $this->datafeedId = $datafeedId;
  }
  public function getDatafeedId()
  {
    return $this->datafeedId;
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