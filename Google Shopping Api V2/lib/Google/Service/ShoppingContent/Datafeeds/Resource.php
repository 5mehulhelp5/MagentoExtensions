<?php
class Google_Service_ShoppingContent_Datafeeds_Resource extends Google_Service_Resource
{

  /**
   * (datafeeds.custombatch)
   *
   * @param Google_DatafeedsCustomBatchRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_DatafeedsCustomBatchResponse
   */
  public function custombatch(Google_Service_ShoppingContent_DatafeedsCustomBatchRequest $postBody, $optParams = array())
  {
    $params = array('postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('custombatch', array($params), "Google_Service_ShoppingContent_DatafeedsCustomBatchResponse");
  }

  /**
   * Deletes a datafeed from your Merchant Center account. (datafeeds.delete)
   *
   * @param string $merchantId
   * @param string $datafeedId
   * @param array $optParams Optional parameters.
   */
  public function delete($merchantId, $datafeedId, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'datafeedId' => $datafeedId);
    $params = array_merge($params, $optParams);
    return $this->call('delete', array($params));
  }

  /**
   * Retrieves a datafeed from your Merchant Center account. (datafeeds.get)
   *
   * @param string $merchantId
   * @param string $datafeedId
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_Datafeed
   */
  public function get($merchantId, $datafeedId, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'datafeedId' => $datafeedId);
    $params = array_merge($params, $optParams);
    return $this->call('get', array($params), "Google_Service_ShoppingContent_Datafeed");
  }

  /**
   * Registers a datafeed with your Merchant Center account. (datafeeds.insert)
   *
   * @param string $merchantId
   * @param Google_Datafeed $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_Datafeed
   */
  public function insert($merchantId, Google_Service_ShoppingContent_Datafeed $postBody, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('insert', array($params), "Google_Service_ShoppingContent_Datafeed");
  }

  /**
   * Lists the datafeeds in your Merchant Center account.
   * (datafeeds.listDatafeeds)
   *
   * @param string $merchantId The ID of the managing account.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string pageToken The token returned by the previous request.
   * @opt_param string maxResults The maximum number of products to return in the
   * response, used for paging.
   * @return Google_Service_ShoppingContent_DatafeedsListResponse
   */
  public function listDatafeeds($merchantId, $optParams = array())
  {
    $params = array('merchantId' => $merchantId);
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_ShoppingContent_DatafeedsListResponse");
  }

  /**
   * Updates a datafeed of your Merchant Center account. This method supports
   * patch semantics. (datafeeds.patch)
   *
   * @param string $merchantId
   * @param string $datafeedId
   * @param Google_Datafeed $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_Datafeed
   */
  public function patch($merchantId, $datafeedId, Google_Service_ShoppingContent_Datafeed $postBody, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'datafeedId' => $datafeedId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('patch', array($params), "Google_Service_ShoppingContent_Datafeed");
  }

  /**
   * Updates a datafeed of your Merchant Center account. (datafeeds.update)
   *
   * @param string $merchantId
   * @param string $datafeedId
   * @param Google_Datafeed $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_Datafeed
   */
  public function update($merchantId, $datafeedId, Google_Service_ShoppingContent_Datafeed $postBody, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'datafeedId' => $datafeedId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('update', array($params), "Google_Service_ShoppingContent_Datafeed");
  }
}