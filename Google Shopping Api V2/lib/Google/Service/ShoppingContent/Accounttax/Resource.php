<?php
class Google_Service_ShoppingContent_Accounttax_Resource extends Google_Service_Resource
{

  /**
   * Retrieves and updates tax settings of multiple accounts in a single request.
   * (accounttax.custombatch)
   *
   * @param Google_AccounttaxCustomBatchRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_AccounttaxCustomBatchResponse
   */
  public function custombatch(Google_Service_ShoppingContent_AccounttaxCustomBatchRequest $postBody, $optParams = array())
  {
    $params = array('postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('custombatch', array($params), "Google_Service_ShoppingContent_AccounttaxCustomBatchResponse");
  }

  /**
   * Retrieves the tax settings of the account. (accounttax.get)
   *
   * @param string $merchantId The ID of the managing account.
   * @param string $accountId The ID of the account for which to get/update
   * account tax settings.
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_AccountTax
   */
  public function get($merchantId, $accountId, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'accountId' => $accountId);
    $params = array_merge($params, $optParams);
    return $this->call('get', array($params), "Google_Service_ShoppingContent_AccountTax");
  }

  /**
   * Lists the tax settings of the sub-accounts in your Merchant Center account.
   * (accounttax.listAccounttax)
   *
   * @param string $merchantId The ID of the managing account.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string pageToken The token returned by the previous request.
   * @opt_param string maxResults The maximum number of tax settings to return in
   * the response, used for paging.
   * @return Google_Service_ShoppingContent_AccounttaxListResponse
   */
  public function listAccounttax($merchantId, $optParams = array())
  {
    $params = array('merchantId' => $merchantId);
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_ShoppingContent_AccounttaxListResponse");
  }

  /**
   * Updates the tax settings of the account. This method supports patch
   * semantics. (accounttax.patch)
   *
   * @param string $merchantId The ID of the managing account.
   * @param string $accountId The ID of the account for which to get/update
   * account tax settings.
   * @param Google_AccountTax $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_AccountTax
   */
  public function patch($merchantId, $accountId, Google_Service_ShoppingContent_AccountTax $postBody, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'accountId' => $accountId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('patch', array($params), "Google_Service_ShoppingContent_AccountTax");
  }

  /**
   * Updates the tax settings of the account. (accounttax.update)
   *
   * @param string $merchantId The ID of the managing account.
   * @param string $accountId The ID of the account for which to get/update
   * account tax settings.
   * @param Google_AccountTax $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_AccountTax
   */
  public function update($merchantId, $accountId, Google_Service_ShoppingContent_AccountTax $postBody, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'accountId' => $accountId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('update', array($params), "Google_Service_ShoppingContent_AccountTax");
  }
}