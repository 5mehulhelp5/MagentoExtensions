<?php
class Google_Service_ShoppingContent_AccountShippingShippingServiceCostRule extends Google_Collection
{
  protected $collection_key = 'children';
  protected $internal_gapi_mappings = array(
  );
  protected $calculationMethodType = 'Google_Service_ShoppingContent_AccountShippingShippingServiceCalculationMethod';
  protected $calculationMethodDataType = '';
  protected $childrenType = 'Google_Service_ShoppingContent_AccountShippingShippingServiceCostRule';
  protected $childrenDataType = 'array';
  protected $conditionType = 'Google_Service_ShoppingContent_AccountShippingCondition';
  protected $conditionDataType = '';


  public function setCalculationMethod(Google_Service_ShoppingContent_AccountShippingShippingServiceCalculationMethod $calculationMethod)
  {
    $this->calculationMethod = $calculationMethod;
  }
  public function getCalculationMethod()
  {
    return $this->calculationMethod;
  }
  public function setChildren($children)
  {
    $this->children = $children;
  }
  public function getChildren()
  {
    return $this->children;
  }
  public function setCondition(Google_Service_ShoppingContent_AccountShippingCondition $condition)
  {
    $this->condition = $condition;
  }
  public function getCondition()
  {
    return $this->condition;
  }
}