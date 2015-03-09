<?php
class Google_Service_ShoppingContent_InventoryCustomBatchRequestEntry extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $batchId;
  protected $inventoryType = 'Google_Service_ShoppingContent_Inventory';
  protected $inventoryDataType = '';
  public $merchantId;
  public $productId;
  public $storeCode;


  public function setBatchId($batchId)
  {
    $this->batchId = $batchId;
  }
  public function getBatchId()
  {
    return $this->batchId;
  }
  public function setInventory(Google_Service_ShoppingContent_Inventory $inventory)
  {
    $this->inventory = $inventory;
  }
  public function getInventory()
  {
    return $this->inventory;
  }
  public function setMerchantId($merchantId)
  {
    $this->merchantId = $merchantId;
  }
  public function getMerchantId()
  {
    return $this->merchantId;
  }
  public function setProductId($productId)
  {
    $this->productId = $productId;
  }
  public function getProductId()
  {
    return $this->productId;
  }
  public function setStoreCode($storeCode)
  {
    $this->storeCode = $storeCode;
  }
  public function getStoreCode()
  {
    return $this->storeCode;
  }
}