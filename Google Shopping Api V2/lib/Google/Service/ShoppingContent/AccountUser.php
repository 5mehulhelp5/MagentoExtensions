<?php
class Google_Service_ShoppingContent_AccountUser extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $admin;
  public $emailAddress;


  public function setAdmin($admin)
  {
    $this->admin = $admin;
  }
  public function getAdmin()
  {
    return $this->admin;
  }
  public function setEmailAddress($emailAddress)
  {
    $this->emailAddress = $emailAddress;
  }
  public function getEmailAddress()
  {
    return $this->emailAddress;
  }
}
