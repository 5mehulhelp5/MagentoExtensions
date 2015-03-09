<?php

class Google_Service_ShoppingContent_ProductStatusDataQualityIssue extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $detail;
  public $fetchStatus;
  public $id;
  public $location;
  public $severity;
  public $timestamp;
  public $valueOnLandingPage;
  public $valueProvided;


  public function setDetail($detail)
  {
    $this->detail = $detail;
  }
  public function getDetail()
  {
    return $this->detail;
  }
  public function setFetchStatus($fetchStatus)
  {
    $this->fetchStatus = $fetchStatus;
  }
  public function getFetchStatus()
  {
    return $this->fetchStatus;
  }
  public function setId($id)
  {
    $this->id = $id;
  }
  public function getId()
  {
    return $this->id;
  }
  public function setLocation($location)
  {
    $this->location = $location;
  }
  public function getLocation()
  {
    return $this->location;
  }
  public function setSeverity($severity)
  {
    $this->severity = $severity;
  }
  public function getSeverity()
  {
    return $this->severity;
  }
  public function setTimestamp($timestamp)
  {
    $this->timestamp = $timestamp;
  }
  public function getTimestamp()
  {
    return $this->timestamp;
  }
  public function setValueOnLandingPage($valueOnLandingPage)
  {
    $this->valueOnLandingPage = $valueOnLandingPage;
  }
  public function getValueOnLandingPage()
  {
    return $this->valueOnLandingPage;
  }
  public function setValueProvided($valueProvided)
  {
    $this->valueProvided = $valueProvided;
  }
  public function getValueProvided()
  {
    return $this->valueProvided;
  }
}
