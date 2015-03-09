<?php
class Google_Service_ShoppingContent_ProductsCustomBatchRequestEntry extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $batchId;
  public $merchantId;
  public $method;
  protected $productType = 'Google_Service_ShoppingContent_Product';
  protected $productDataType = '';
  public $productId;


  public function setBatchId($batchId)
  {
    $this->batchId = $batchId;
  }
  public function getBatchId()
  {
    return $this->batchId;
  }
  public function setMerchantId($merchantId)
  {
    $this->merchantId = $merchantId;
  }
  public function getMerchantId()
  {
    return $this->merchantId;
  }
  public function setMethod($method)
  {
    $this->method = $method;
  }
  public function getMethod()
  {
    return $this->method;
  }
  public function setProduct(Google_Service_ShoppingContent_Product $product)
  {
    $this->product = $product;
  }
  public function getProduct()
  {
    return $this->product;
  }
  public function setProductId($productId)
  {
    $this->productId = $productId;
  }
  public function getProductId()
  {
    return $this->productId;
  }
}