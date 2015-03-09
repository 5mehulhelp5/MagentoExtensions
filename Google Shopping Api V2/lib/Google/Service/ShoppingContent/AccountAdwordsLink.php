<?php
class Google_Service_ShoppingContent_AccountAdwordsLink extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $adwordsId;
  public $status;


  public function setAdwordsId($adwordsId)
  {
    $this->adwordsId = $adwordsId;
  }
  public function getAdwordsId()
  {
    return $this->adwordsId;
  }
  public function setStatus($status)
  {
    $this->status = $status;
  }
  public function getStatus()
  {
    return $this->status;
  }
}