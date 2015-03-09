<?php
/*
 * Copyright 2010 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

/**
 * Service definition for ShoppingContent (v2).
 *
 * <p>
 * Manage product items, inventory, and Merchant Center accounts for Google
 * Shopping.</p>
 *
 * <p>
 * For more information about this service, see the API
 * <a href="https://developers.google.com/shopping-content/v2/" target="_blank">Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class Google_Service_ShoppingContent extends Google_Service
{
  /** Manage your product listings and accounts for Google Shopping. */
  const CONTENT =
      "https://www.googleapis.com/auth/content";

  public $accounts;
  public $accountshipping;
  public $accountstatuses;
  public $accounttax;
  public $datafeeds;
  public $datafeedstatuses;
  public $inventory;
  public $products;
  public $productstatuses;
  

  /**
   * Constructs the internal representation of the ShoppingContent service.
   *
   * @param Google_Client $client
   */
  public function __construct(Google_Client $client)
  {
    parent::__construct($client);
    $this->servicePath = 'content/v2/';
    $this->version = 'v2';
    $this->serviceName = 'content';

    $this->accounts = new Google_Service_ShoppingContent_Accounts_Resource(
        $this,
        $this->serviceName,
        'accounts',
        array(
          'methods' => array(
            'custombatch' => array(
              'path' => 'accounts/batch',
              'httpMethod' => 'POST',
              'parameters' => array(),
            ),'delete' => array(
              'path' => '{merchantId}/accounts/{accountId}',
              'httpMethod' => 'DELETE',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'accountId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'get' => array(
              'path' => '{merchantId}/accounts/{accountId}',
              'httpMethod' => 'GET',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'accountId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'insert' => array(
              'path' => '{merchantId}/accounts',
              'httpMethod' => 'POST',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'list' => array(
              'path' => '{merchantId}/accounts',
              'httpMethod' => 'GET',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'pageToken' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
                'maxResults' => array(
                  'location' => 'query',
                  'type' => 'integer',
                ),
              ),
            ),'patch' => array(
              'path' => '{merchantId}/accounts/{accountId}',
              'httpMethod' => 'PATCH',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'accountId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'update' => array(
              'path' => '{merchantId}/accounts/{accountId}',
              'httpMethod' => 'PUT',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'accountId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),
          )
        )
    );
    $this->accountshipping = new Google_Service_ShoppingContent_Accountshipping_Resource(
        $this,
        $this->serviceName,
        'accountshipping',
        array(
          'methods' => array(
            'custombatch' => array(
              'path' => 'accountshipping/batch',
              'httpMethod' => 'POST',
              'parameters' => array(),
            ),'get' => array(
              'path' => '{merchantId}/accountshipping/{accountId}',
              'httpMethod' => 'GET',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'accountId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'list' => array(
              'path' => '{merchantId}/accountshipping',
              'httpMethod' => 'GET',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'pageToken' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
                'maxResults' => array(
                  'location' => 'query',
                  'type' => 'integer',
                ),
              ),
            ),'patch' => array(
              'path' => '{merchantId}/accountshipping/{accountId}',
              'httpMethod' => 'PATCH',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'accountId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'update' => array(
              'path' => '{merchantId}/accountshipping/{accountId}',
              'httpMethod' => 'PUT',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'accountId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),
          )
        )
    );
    $this->accountstatuses = new Google_Service_ShoppingContent_Accountstatuses_Resource(
        $this,
        $this->serviceName,
        'accountstatuses',
        array(
          'methods' => array(
            'custombatch' => array(
              'path' => 'accountstatuses/batch',
              'httpMethod' => 'POST',
              'parameters' => array(),
            ),'get' => array(
              'path' => '{merchantId}/accountstatuses/{accountId}',
              'httpMethod' => 'GET',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'accountId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'list' => array(
              'path' => '{merchantId}/accountstatuses',
              'httpMethod' => 'GET',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'pageToken' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
                'maxResults' => array(
                  'location' => 'query',
                  'type' => 'integer',
                ),
              ),
            ),
          )
        )
    );
    $this->accounttax = new Google_Service_ShoppingContent_Accounttax_Resource(
        $this,
        $this->serviceName,
        'accounttax',
        array(
          'methods' => array(
            'custombatch' => array(
              'path' => 'accounttax/batch',
              'httpMethod' => 'POST',
              'parameters' => array(),
            ),'get' => array(
              'path' => '{merchantId}/accounttax/{accountId}',
              'httpMethod' => 'GET',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'accountId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'list' => array(
              'path' => '{merchantId}/accounttax',
              'httpMethod' => 'GET',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'pageToken' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
                'maxResults' => array(
                  'location' => 'query',
                  'type' => 'integer',
                ),
              ),
            ),'patch' => array(
              'path' => '{merchantId}/accounttax/{accountId}',
              'httpMethod' => 'PATCH',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'accountId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'update' => array(
              'path' => '{merchantId}/accounttax/{accountId}',
              'httpMethod' => 'PUT',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'accountId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),
          )
        )
    );
    $this->datafeeds = new Google_Service_ShoppingContent_Datafeeds_Resource(
        $this,
        $this->serviceName,
        'datafeeds',
        array(
          'methods' => array(
            'custombatch' => array(
              'path' => 'datafeeds/batch',
              'httpMethod' => 'POST',
              'parameters' => array(),
            ),'delete' => array(
              'path' => '{merchantId}/datafeeds/{datafeedId}',
              'httpMethod' => 'DELETE',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'datafeedId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'get' => array(
              'path' => '{merchantId}/datafeeds/{datafeedId}',
              'httpMethod' => 'GET',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'datafeedId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'insert' => array(
              'path' => '{merchantId}/datafeeds',
              'httpMethod' => 'POST',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'list' => array(
              'path' => '{merchantId}/datafeeds',
              'httpMethod' => 'GET',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'pageToken' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
                'maxResults' => array(
                  'location' => 'query',
                  'type' => 'integer',
                ),
              ),
            ),'patch' => array(
              'path' => '{merchantId}/datafeeds/{datafeedId}',
              'httpMethod' => 'PATCH',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'datafeedId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'update' => array(
              'path' => '{merchantId}/datafeeds/{datafeedId}',
              'httpMethod' => 'PUT',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'datafeedId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),
          )
        )
    );
    $this->datafeedstatuses = new Google_Service_ShoppingContent_Datafeedstatuses_Resource(
        $this,
        $this->serviceName,
        'datafeedstatuses',
        array(
          'methods' => array(
            'custombatch' => array(
              'path' => 'datafeedstatuses/batch',
              'httpMethod' => 'POST',
              'parameters' => array(),
            ),'get' => array(
              'path' => '{merchantId}/datafeedstatuses/{datafeedId}',
              'httpMethod' => 'GET',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'datafeedId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'list' => array(
              'path' => '{merchantId}/datafeedstatuses',
              'httpMethod' => 'GET',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'pageToken' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
                'maxResults' => array(
                  'location' => 'query',
                  'type' => 'integer',
                ),
              ),
            ),
          )
        )
    );
    $this->inventory = new Google_Service_ShoppingContent_Inventory_Resource(
        $this,
        $this->serviceName,
        'inventory',
        array(
          'methods' => array(
            'custombatch' => array(
              'path' => 'inventory/batch',
              'httpMethod' => 'POST',
              'parameters' => array(),
            ),'set' => array(
              'path' => '{merchantId}/inventory/{storeCode}/products/{productId}',
              'httpMethod' => 'POST',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'storeCode' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'productId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),
          )
        )
    );
    $this->products = new Google_Service_ShoppingContent_Products_Resource(
        $this,
        $this->serviceName,
        'products',
        array(
          'methods' => array(
            'custombatch' => array(
              'path' => 'products/batch',
              'httpMethod' => 'POST',
              'parameters' => array(
                'dryRun' => array(
                  'location' => 'query',
                  'type' => 'boolean',
                ),
              ),
            ),'delete' => array(
              'path' => '{merchantId}/products/{productId}',
              'httpMethod' => 'DELETE',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'productId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'dryRun' => array(
                  'location' => 'query',
                  'type' => 'boolean',
                ),
              ),
            ),'get' => array(
              'path' => '{merchantId}/products/{productId}',
              'httpMethod' => 'GET',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'productId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'insert' => array(
              'path' => '{merchantId}/products',
              'httpMethod' => 'POST',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'dryRun' => array(
                  'location' => 'query',
                  'type' => 'boolean',
                ),
              ),
            ),'list' => array(
              'path' => '{merchantId}/products',
              'httpMethod' => 'GET',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'pageToken' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
                'maxResults' => array(
                  'location' => 'query',
                  'type' => 'integer',
                ),
              ),
            ),
          )
        )
    );
    $this->productstatuses = new Google_Service_ShoppingContent_Productstatuses_Resource(
        $this,
        $this->serviceName,
        'productstatuses',
        array(
          'methods' => array(
            'custombatch' => array(
              'path' => 'productstatuses/batch',
              'httpMethod' => 'POST',
              'parameters' => array(),
            ),'get' => array(
              'path' => '{merchantId}/productstatuses/{productId}',
              'httpMethod' => 'GET',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'productId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'list' => array(
              'path' => '{merchantId}/productstatuses',
              'httpMethod' => 'GET',
              'parameters' => array(
                'merchantId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'pageToken' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
                'maxResults' => array(
                  'location' => 'query',
                  'type' => 'integer',
                ),
              ),
            ),
          )
        )
    );
  }
}