<?php
class Google_Service_ShoppingContent_AccountStatusExampleItem extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $itemId;
  public $link;
  public $submittedValue;
  public $title;
  public $valueOnLandingPage;


  public function setItemId($itemId)
  {
    $this->itemId = $itemId;
  }
  public function getItemId()
  {
    return $this->itemId;
  }
  public function setLink($link)
  {
    $this->link = $link;
  }
  public function getLink()
  {
    return $this->link;
  }
  public function setSubmittedValue($submittedValue)
  {
    $this->submittedValue = $submittedValue;
  }
  public function getSubmittedValue()
  {
    return $this->submittedValue;
  }
  public function setTitle($title)
  {
    $this->title = $title;
  }
  public function getTitle()
  {
    return $this->title;
  }
  public function setValueOnLandingPage($valueOnLandingPage)
  {
    $this->valueOnLandingPage = $valueOnLandingPage;
  }
  public function getValueOnLandingPage()
  {
    return $this->valueOnLandingPage;
  }
}