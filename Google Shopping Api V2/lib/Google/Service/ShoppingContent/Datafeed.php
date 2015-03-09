<?php
class Google_Service_ShoppingContent_Datafeed extends Google_Collection
{
  protected $collection_key = 'intendedDestinations';
  protected $internal_gapi_mappings = array(
  );
  public $attributeLanguage;
  public $contentLanguage;
  public $contentType;
  protected $fetchScheduleType = 'Google_Service_ShoppingContent_DatafeedFetchSchedule';
  protected $fetchScheduleDataType = '';
  public $fileName;
  protected $formatType = 'Google_Service_ShoppingContent_DatafeedFormat';
  protected $formatDataType = '';
  public $id;
  public $intendedDestinations;
  public $kind;
  public $name;
  public $targetCountry;


  public function setAttributeLanguage($attributeLanguage)
  {
    $this->attributeLanguage = $attributeLanguage;
  }
  public function getAttributeLanguage()
  {
    return $this->attributeLanguage;
  }
  public function setContentLanguage($contentLanguage)
  {
    $this->contentLanguage = $contentLanguage;
  }
  public function getContentLanguage()
  {
    return $this->contentLanguage;
  }
  public function setContentType($contentType)
  {
    $this->contentType = $contentType;
  }
  public function getContentType()
  {
    return $this->contentType;
  }
  public function setFetchSchedule(Google_Service_ShoppingContent_DatafeedFetchSchedule $fetchSchedule)
  {
    $this->fetchSchedule = $fetchSchedule;
  }
  public function getFetchSchedule()
  {
    return $this->fetchSchedule;
  }
  public function setFileName($fileName)
  {
    $this->fileName = $fileName;
  }
  public function getFileName()
  {
    return $this->fileName;
  }
  public function setFormat(Google_Service_ShoppingContent_DatafeedFormat $format)
  {
    $this->format = $format;
  }
  public function getFormat()
  {
    return $this->format;
  }
  public function setId($id)
  {
    $this->id = $id;
  }
  public function getId()
  {
    return $this->id;
  }
  public function setIntendedDestinations($intendedDestinations)
  {
    $this->intendedDestinations = $intendedDestinations;
  }
  public function getIntendedDestinations()
  {
    return $this->intendedDestinations;
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
  public function setTargetCountry($targetCountry)
  {
    $this->targetCountry = $targetCountry;
  }
  public function getTargetCountry()
  {
    return $this->targetCountry;
  }
}