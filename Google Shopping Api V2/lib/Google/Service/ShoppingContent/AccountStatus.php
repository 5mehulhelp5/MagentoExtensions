<?php
class Google_Service_ShoppingContent_AccountStatus extends Google_Collection
{
  protected $collection_key = 'dataQualityIssues';
  protected $internal_gapi_mappings = array(
  );
  public $accountId;
  protected $dataQualityIssuesType = 'Google_Service_ShoppingContent_AccountStatusDataQualityIssue';
  protected $dataQualityIssuesDataType = 'array';
  public $kind;


  public function setAccountId($accountId)
  {
    $this->accountId = $accountId;
  }
  public function getAccountId()
  {
    return $this->accountId;
  }
  public function setDataQualityIssues($dataQualityIssues)
  {
    $this->dataQualityIssues = $dataQualityIssues;
  }
  public function getDataQualityIssues()
  {
    return $this->dataQualityIssues;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
}