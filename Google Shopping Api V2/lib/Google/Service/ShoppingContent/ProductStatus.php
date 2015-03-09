<?php
class Google_Service_ShoppingContent_ProductStatus extends Google_Collection
{
  protected $collection_key = 'destinationStatuses';
  protected $internal_gapi_mappings = array(
  );
  public $creationDate;
  protected $dataQualityIssuesType = 'Google_Service_ShoppingContent_ProductStatusDataQualityIssue';
  protected $dataQualityIssuesDataType = 'array';
  protected $destinationStatusesType = 'Google_Service_ShoppingContent_ProductStatusDestinationStatus';
  protected $destinationStatusesDataType = 'array';
  public $googleExpirationDate;
  public $kind;
  public $lastUpdateDate;
  public $link;
  public $productId;
  public $title;


  public function setCreationDate($creationDate)
  {
    $this->creationDate = $creationDate;
  }
  public function getCreationDate()
  {
    return $this->creationDate;
  }
  public function setDataQualityIssues($dataQualityIssues)
  {
    $this->dataQualityIssues = $dataQualityIssues;
  }
  public function getDataQualityIssues()
  {
    return $this->dataQualityIssues;
  }
  public function setDestinationStatuses($destinationStatuses)
  {
    $this->destinationStatuses = $destinationStatuses;
  }
  public function getDestinationStatuses()
  {
    return $this->destinationStatuses;
  }
  public function setGoogleExpirationDate($googleExpirationDate)
  {
    $this->googleExpirationDate = $googleExpirationDate;
  }
  public function getGoogleExpirationDate()
  {
    return $this->googleExpirationDate;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  public function setLastUpdateDate($lastUpdateDate)
  {
    $this->lastUpdateDate = $lastUpdateDate;
  }
  public function getLastUpdateDate()
  {
    return $this->lastUpdateDate;
  }
  public function setLink($link)
  {
    $this->link = $link;
  }
  public function getLink()
  {
    return $this->link;
  }
  public function setProductId($productId)
  {
    $this->productId = $productId;
  }
  public function getProductId()
  {
    return $this->productId;
  }
  public function setTitle($title)
  {
    $this->title = $title;
  }
  public function getTitle()
  {
    return $this->title;
  }
}