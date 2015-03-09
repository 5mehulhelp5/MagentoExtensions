<?php

class Google_Service_ShoppingContent_ProductDestination extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $destinationName;
  public $intention;


  public function setDestinationName($destinationName)
  {
    $this->destinationName = $destinationName;
  }
  public function getDestinationName()
  {
    return $this->destinationName;
  }
  public function setIntention($intention)
  {
    $this->intention = $intention;
  }
  public function getIntention()
  {
    return $this->intention;
  }
}