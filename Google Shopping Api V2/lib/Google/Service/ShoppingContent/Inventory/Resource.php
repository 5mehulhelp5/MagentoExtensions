<?php
class Google_Service_ShoppingContent_Inventory_Resource extends Google_Service_Resource
{

  /**
   * Updates price and availability for multiple products or stores in a single
   * request. (inventory.custombatch)
   *
   * @param Google_InventoryCustomBatchRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_InventoryCustomBatchResponse
   */
  public function custombatch(Google_Service_ShoppingContent_InventoryCustomBatchRequest $postBody, $optParams = array())
  {
    $params = array('postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('custombatch', array($params), "Google_Service_ShoppingContent_InventoryCustomBatchResponse");
  }

  /**
   * Updates price and availability of a product in your Merchant Center account.
   * (inventory.set)
   *
   * @param string $merchantId The ID of the managing account.
   * @param string $storeCode The code of the store for which to update price and
   * availability. Use online to update price and availability of an online
   * product.
   * @param string $productId The ID of the product for which to update price and
   * availability.
   * @param Google_InventorySetRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_InventorySetResponse
   */
  public function set($merchantId, $storeCode, $productId, Google_Service_ShoppingContent_InventorySetRequest $postBody, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'storeCode' => $storeCode, 'productId' => $productId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('set', array($params), "Google_Service_ShoppingContent_InventorySetResponse");
  }
}
