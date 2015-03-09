<?php
class Google_Service_ShoppingContent_AccountTax extends Google_Collection
{
  protected $collection_key = 'rules';
  protected $internal_gapi_mappings = array(
  );
  public $accountId;
  public $kind;
  protected $rulesType = 'Google_Service_ShoppingContent_AccountTaxTaxRule';
  protected $rulesDataType = 'array';


  public function setAccountId($accountId)
  {
    $this->accountId = $accountId;
  }
  public function getAccountId()
  {
    return $this->accountId;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  public function setRules($rules)
  {
    $this->rules = $rules;
  }
  public function getRules()
  {
    return $this->rules;
  }
}