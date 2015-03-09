<?php
class Google_Service_ShoppingContent_Products_Resource extends Google_Service_Resource
{

  /**
   * Retrieves, inserts, and deletes multiple products in a single request.
   * (products.custombatch)
   *
   * @param Google_ProductsCustomBatchRequest $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool dryRun Flag to run the request in dry-run mode.
   * @return Google_Service_ShoppingContent_ProductsCustomBatchResponse
   */
  public function custombatch(Google_Service_ShoppingContent_ProductsCustomBatchRequest $postBody, $optParams = array())
  {
    $params = array('postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('custombatch', array($params), "Google_Service_ShoppingContent_ProductsCustomBatchResponse");
  }

  /**
   * Deletes a product from your Merchant Center account. (products.delete)
   *
   * @param string $merchantId The ID of the managing account.
   * @param string $productId The ID of the product.
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool dryRun Flag to run the request in dry-run mode.
   */
  public function delete($merchantId, $productId, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'productId' => $productId);
    $params = array_merge($params, $optParams);
    return $this->call('delete', array($params));
  }

  /**
   * Retrieves a product from your Merchant Center account. (products.get)
   *
   * @param string $merchantId The ID of the managing account.
   * @param string $productId The ID of the product.
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_Product
   */
  public function get($merchantId, $productId, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'productId' => $productId);
    $params = array_merge($params, $optParams);
    return $this->call('get', array($params), "Google_Service_ShoppingContent_Product");
  }

  /**
   * Uploads a product to your Merchant Center account. (products.insert)
   *
   * @param string $merchantId The ID of the managing account.
   * @param Google_Product $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool dryRun Flag to run the request in dry-run mode.
   * @return Google_Service_ShoppingContent_Product
   */
  public function insert($merchantId, Google_Service_ShoppingContent_Product $postBody, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('insert', array($params), "Google_Service_ShoppingContent_Product");
  }

  /**
   * Lists the products in your Merchant Center account. (products.listProducts)
   *
   * @param string $merchantId The ID of the managing account.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string pageToken The token returned by the previous request.
   * @opt_param string maxResults The maximum number of products to return in the
   * response, used for paging.
   * @return Google_Service_ShoppingContent_ProductsListResponse
   */
  public function listProducts($merchantId, $optParams = array())
  {
    $params = array('merchantId' => $merchantId);
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_ShoppingContent_ProductsListResponse");
  }
}