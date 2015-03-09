<?php
class Google_Service_ShoppingContent_Accounts_Resource extends Google_Service_Resource
{

  /**
   * Retrieves, inserts, updates, and deletes multiple Merchant Center
   * (sub-)accounts in a single request. (accounts.custombatch)
   *
   * @param Google_AccountsCustomBatchRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_AccountsCustomBatchResponse
   */
  public function custombatch(Google_Service_ShoppingContent_AccountsCustomBatchRequest $postBody, $optParams = array())
  {
    $params = array('postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('custombatch', array($params), "Google_Service_ShoppingContent_AccountsCustomBatchResponse");
  }

  /**
   * Deletes a Merchant Center sub-account. (accounts.delete)
   *
   * @param string $merchantId The ID of the managing account.
   * @param string $accountId The ID of the account.
   * @param array $optParams Optional parameters.
   */
  public function delete($merchantId, $accountId, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'accountId' => $accountId);
    $params = array_merge($params, $optParams);
    return $this->call('delete', array($params));
  }

  /**
   * Retrieves a Merchant Center account. (accounts.get)
   *
   * @param string $merchantId The ID of the managing account.
   * @param string $accountId The ID of the account.
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_Account
   */
  public function get($merchantId, $accountId, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'accountId' => $accountId);
    $params = array_merge($params, $optParams);
    return $this->call('get', array($params), "Google_Service_ShoppingContent_Account");
  }

  /**
   * Creates a Merchant Center sub-account. (accounts.insert)
   *
   * @param string $merchantId The ID of the managing account.
   * @param Google_Account $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_Account
   */
  public function insert($merchantId, Google_Service_ShoppingContent_Account $postBody, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('insert', array($params), "Google_Service_ShoppingContent_Account");
  }

  /**
   * Lists the sub-accounts in your Merchant Center account.
   * (accounts.listAccounts)
   *
   * @param string $merchantId The ID of the managing account.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string pageToken The token returned by the previous request.
   * @opt_param string maxResults The maximum number of accounts to return in the
   * response, used for paging.
   * @return Google_Service_ShoppingContent_AccountsListResponse
   */
  public function listAccounts($merchantId, $optParams = array())
  {
    $params = array('merchantId' => $merchantId);
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_ShoppingContent_AccountsListResponse");
  }

  /**
   * Updates a Merchant Center account. This method supports patch semantics.
   * (accounts.patch)
   *
   * @param string $merchantId The ID of the managing account.
   * @param string $accountId The ID of the account.
   * @param Google_Account $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_Account
   */
  public function patch($merchantId, $accountId, Google_Service_ShoppingContent_Account $postBody, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'accountId' => $accountId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('patch', array($params), "Google_Service_ShoppingContent_Account");
  }

  /**
   * Updates a Merchant Center account. (accounts.update)
   *
   * @param string $merchantId The ID of the managing account.
   * @param string $accountId The ID of the account.
   * @param Google_Account $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_ShoppingContent_Account
   */
  public function update($merchantId, $accountId, Google_Service_ShoppingContent_Account $postBody, $optParams = array())
  {
    $params = array('merchantId' => $merchantId, 'accountId' => $accountId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('update', array($params), "Google_Service_ShoppingContent_Account");
  }
}