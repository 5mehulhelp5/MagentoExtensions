<?php
class Google_Service_ShoppingContent_AccountstatusesListResponse extends Google_Collection
{
  protected $collection_key = 'resources';
  protected $internal_gapi_mappings = array(
  );
  public $kind;
  public $nextPageToken;
  protected $resourcesType = 'Google_Service_ShoppingContent_AccountStatus';
  protected $resourcesDataType = 'array';


  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  public function setNextPageToken($nextPageToken)
  {
    $this->nextPageToken = $nextPageToken;
  }
  public function getNextPageToken()
  {
    return $this->nextPageToken;
  }
  public function setResources($resources)
  {
    $this->resources = $resources;
  }
  public function getResources()
  {
    return $this->resources;
  }
}