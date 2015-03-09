<?php
class Google_Service_ShoppingContent_Accountshipping_Resource extends Google_Service_Resource
{

  /**
   * Retrieves and updates the shipping settings of multiple accounts in a single
   * request. (accountshipping.custombatch)
   *
   * @param Google_AccountshippingCustomBatchRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_AccountshippingCustomBatchResponse
   */
  public function custombatch(Google_Service_ShoppingContent_AccountshippingCustomBatchRequest $postBody, $optParams = array())
  {
    $params = array('postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('custombatch', array($params), "Google_Service_ShoppingContent_AccountshippingCustomBatchResponse");
  }

  /**
   * Retrieves the shipping settings of the account. (accountshipping.get)
   *
   * @param string $merchantId The ID of the managing account.
   * @param string $accountId The ID of the account for which to get/update
   * account shipping settings.
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_AccountShipping
   */
  public function get($merchantId, $accountId, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'accountId' => $accountId);
    $params = array_merge($params, $optParams);
    return $this->call('get', array($params), "Google_Service_ShoppingContent_AccountShipping");
  }

  /**
   * Lists the shipping settings of the sub-accounts in your Merchant Center
   * account. (accountshipping.listAccountshipping)
   *
   * @param string $merchantId The ID of the managing account.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string pageToken The token returned by the previous request.
   * @opt_param string maxResults The maximum number of shipping settings to
   * return in the response, used for paging.
   * @return Google_Service_ShoppingContent_AccountshippingListResponse
   */
  public function listAccountshipping($merchantId, $optParams = array())
  {
    $params = array('merchantId' => $merchantId);
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_ShoppingContent_AccountshippingListResponse");
  }

  /**
   * Updates the shipping settings of the account. This method supports patch
   * semantics. (accountshipping.patch)
   *
   * @param string $merchantId The ID of the managing account.
   * @param string $accountId The ID of the account for which to get/update
   * account shipping settings.
   * @param Google_AccountShipping $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_AccountShipping
   */
  public function patch($merchantId, $accountId, Google_Service_ShoppingContent_AccountShipping $postBody, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'accountId' => $accountId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('patch', array($params), "Google_Service_ShoppingContent_AccountShipping");
  }

  /**
   * Updates the shipping settings of the account. (accountshipping.update)
   *
   * @param string $merchantId The ID of the managing account.
   * @param string $accountId The ID of the account for which to get/update
   * account shipping settings.
   * @param Google_AccountShipping $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_AccountShipping
   */
  public function update($merchantId, $accountId, Google_Service_ShoppingContent_AccountShipping $postBody, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'accountId' => $accountId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('update', array($params), "Google_Service_ShoppingContent_AccountShipping");
  }
}