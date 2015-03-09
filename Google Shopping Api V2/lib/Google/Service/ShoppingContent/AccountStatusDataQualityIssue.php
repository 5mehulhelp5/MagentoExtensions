<?php
class Google_Service_ShoppingContent_AccountStatusDataQualityIssue extends Google_Collection
{
  protected $collection_key = 'exampleItems';
  protected $internal_gapi_mappings = array(
  );
  public $country;
  public $displayedValue;
  protected $exampleItemsType = 'Google_Service_ShoppingContent_AccountStatusExampleItem';
  protected $exampleItemsDataType = 'array';
  public $id;
  public $lastChecked;
  public $numItems;
  public $severity;
  public $submittedValue;


  public function setCountry($country)
  {
    $this->country = $country;
  }
  public function getCountry()
  {
    return $this->country;
  }
  public function setDisplayedValue($displayedValue)
  {
    $this->displayedValue = $displayedValue;
  }
  public function getDisplayedValue()
  {
    return $this->displayedValue;
  }
  public function setExampleItems($exampleItems)
  {
    $this->exampleItems = $exampleItems;
  }
  public function getExampleItems()
  {
    return $this->exampleItems;
  }
  public function setId($id)
  {
    $this->id = $id;
  }
  public function getId()
  {
    return $this->id;
  }
  public function setLastChecked($lastChecked)
  {
    $this->lastChecked = $lastChecked;
  }
  public function getLastChecked()
  {
    return $this->lastChecked;
  }
  public function setNumItems($numItems)
  {
    $this->numItems = $numItems;
  }
  public function getNumItems()
  {
    return $this->numItems;
  }
  public function setSeverity($severity)
  {
    $this->severity = $severity;
  }
  public function getSeverity()
  {
    return $this->severity;
  }
  public function setSubmittedValue($submittedValue)
  {
    $this->submittedValue = $submittedValue;
  }
  public function getSubmittedValue()
  {
    return $this->submittedValue;
  }
}