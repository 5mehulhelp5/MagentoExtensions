<?php
class Google_Service_ShoppingContent_Errors extends Google_Collection
{
  protected $collection_key = 'errors';
  protected $internal_gapi_mappings = array(
  );
  public $code;
  protected $errorsType = 'Google_Service_ShoppingContent_Error';
  protected $errorsDataType = 'array';
  public $message;


  public function setCode($code)
  {
    $this->code = $code;
  }
  public function getCode()
  {
    return $this->code;
  }
  public function setErrors($errors)
  {
    $this->errors = $errors;
  }
  public function getErrors()
  {
    return $this->errors;
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