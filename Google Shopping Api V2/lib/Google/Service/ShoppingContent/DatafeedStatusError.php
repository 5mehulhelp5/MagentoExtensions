<?php
class Google_Service_ShoppingContent_DatafeedStatusError extends Google_Collection
{
  protected $collection_key = 'examples';
  protected $internal_gapi_mappings = array(
  );
  public $code;
  public $count;
  protected $examplesType = 'Google_Service_ShoppingContent_DatafeedStatusExample';
  protected $examplesDataType = 'array';
  public $message;


  public function setCode($code)
  {
    $this->code = $code;
  }
  public function getCode()
  {
    return $this->code;
  }
  public function setCount($count)
  {
    $this->count = $count;
  }
  public function getCount()
  {
    return $this->count;
  }
  public function setExamples($examples)
  {
    $this->examples = $examples;
  }
  public function getExamples()
  {
    return $this->examples;
  }
  public function setMessage($message)
  {
    $this->message = $message;
  }
  public function getMessage()
  {
    return $this->message;
  }
}
