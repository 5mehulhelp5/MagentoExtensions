<?php
class Google_Service_ShoppingContent_Productstatuses_Resource extends Google_Service_Resource
{

  /**
   * Gets the statuses of multiple products in a single request.
   * (productstatuses.custombatch)
   *
   * @param Google_ProductstatusesCustomBatchRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_ProductstatusesCustomBatchResponse
   */
  public function custombatch(Google_Service_ShoppingContent_ProductstatusesCustomBatchRequest $postBody, $optParams = array())
  {
    $params = array('postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('custombatch', array($params), "Google_Service_ShoppingContent_ProductstatusesCustomBatchResponse");
  }

  /**
   * Gets the status of a product from your Merchant Center account.
   * (productstatuses.get)
   *
   * @param string $merchantId The ID of the managing account.
   * @param string $productId The ID of the product.
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_ProductStatus
   */
  public function get($merchantId, $productId, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'productId' => $productId);
    $params = array_merge($params, $optParams);
    return $this->call('get', array($params), "Google_Service_ShoppingContent_ProductStatus");
  }

  /**
   * Lists the statuses of the products in your Merchant Center account.
   * (productstatuses.listProductstatuses)
   *
   * @param string $merchantId The ID of the managing account.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string pageToken The token returned by the previous request.
   * @opt_param string maxResults The maximum number of product statuses to return
   * in the response, used for paging.
   * @return Google_Service_ShoppingContent_ProductstatusesListResponse
   */
  public function listProductstatuses($merchantId, $optParams = array())
  {
    $params = array('merchantId' => $merchantId);
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_ShoppingContent_ProductstatusesListResponse");
  }
}