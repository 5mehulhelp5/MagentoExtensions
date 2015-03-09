<?php
class Google_Service_ShoppingContent_AccountShippingShippingService extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $active;
  protected $calculationMethodType = 'Google_Service_ShoppingContent_AccountShippingShippingServiceCalculationMethod';
  protected $calculationMethodDataType = '';
  protected $costRuleTreeType = 'Google_Service_ShoppingContent_AccountShippingShippingServiceCostRule';
  protected $costRuleTreeDataType = '';
  public $name;
  public $saleCountry;


  public function setActive($active)
  {
    $this->active = $active;
  }
  public function getActive()
  {
    return $this->active;
  }
  public function setCalculationMethod(Google_Service_ShoppingContent_AccountShippingShippingServiceCalculationMethod $calculationMethod)
  {
    $this->calculationMethod = $calculationMethod;
  }
  public function getCalculationMethod()
  {
    return $this->calculationMethod;
  }
  public function setCostRuleTree(Google_Service_ShoppingContent_AccountShippingShippingServiceCostRule $costRuleTree)
  {
    $this->costRuleTree = $costRuleTree;
  }
  public function getCostRuleTree()
  {
    return $this->costRuleTree;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setSaleCountry($saleCountry)
  {
    $this->saleCountry = $saleCountry;
  }
  public function getSaleCountry()
  {
    return $this->saleCountry;
  }
}
