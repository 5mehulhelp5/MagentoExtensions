<?php
class Google_Service_ShoppingContent_Account extends Google_Collection
{
  protected $collection_key = 'users';
  protected $internal_gapi_mappings = array(
  );
  public $adultContent;
  protected $adwordsLinksType = 'Google_Service_ShoppingContent_AccountAdwordsLink';
  protected $adwordsLinksDataType = 'array';
  public $id;
  public $kind;
  public $name;
  public $reviewsUrl;
  public $sellerId;
  protected $usersType = 'Google_Service_ShoppingContent_AccountUser';
  protected $usersDataType = 'array';
  public $websiteUrl;


  public function setAdultContent($adultContent)
  {
    $this->adultContent = $adultContent;
  }
  public function getAdultContent()
  {
    return $this->adultContent;
  }
  public function setAdwordsLinks($adwordsLinks)
  {
    $this->adwordsLinks = $adwordsLinks;
  }
  public function getAdwordsLinks()
  {
    return $this->adwordsLinks;
  }
  public function setId($id)
  {
    $this->id = $id;
  }
  public function getId()
  {
    return $this->id;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setReviewsUrl($reviewsUrl)
  {
    $this->reviewsUrl = $reviewsUrl;
  }
  public function getReviewsUrl()
  {
    return $this->reviewsUrl;
  }
  public function setSellerId($sellerId)
  {
    $this->sellerId = $sellerId;
  }
  public function getSellerId()
  {
    return $this->sellerId;
  }
  public function setUsers($users)
  {
    $this->users = $users;
  }
  public function getUsers()
  {
    return $this->users;
  }
  public function setWebsiteUrl($websiteUrl)
  {
    $this->websiteUrl = $websiteUrl;
  }
  public function getWebsiteUrl()
  {
    return $this->websiteUrl;
  }
}