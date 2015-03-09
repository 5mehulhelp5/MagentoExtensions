<?php
class Google_Service_ShoppingContent_DatafeedFetchSchedule extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $dayOfMonth;
  public $fetchUrl;
  public $hour;
  public $password;
  public $timeZone;
  public $username;
  public $weekday;


  public function setDayOfMonth($dayOfMonth)
  {
    $this->dayOfMonth = $dayOfMonth;
  }
  public function getDayOfMonth()
  {
    return $this->dayOfMonth;
  }
  public function setFetchUrl($fetchUrl)
  {
    $this->fetchUrl = $fetchUrl;
  }
  public function getFetchUrl()
  {
    return $this->fetchUrl;
  }
  public function setHour($hour)
  {
    $this->hour = $hour;
  }
  public function getHour()
  {
    return $this->hour;
  }
  public function setPassword($password)
  {
    $this->password = $password;
  }
  public function getPassword()
  {
    return $this->password;
  }
  public function setTimeZone($timeZone)
  {
    $this->timeZone = $timeZone;
  }
  public function getTimeZone()
  {
    return $this->timeZone;
  }
  public function setUsername($username)
  {
    $this->username = $username;
  }
  public function getUsername()
  {
    return $this->username;
  }
  public function setWeekday($weekday)
  {
    $this->weekday = $weekday;
  }
  public function getWeekday()
  {
    return $this->weekday;
  }
}