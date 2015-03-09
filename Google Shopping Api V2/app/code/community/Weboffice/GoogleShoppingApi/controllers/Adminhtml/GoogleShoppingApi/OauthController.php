<?php
/**
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * GoogleShopping Admin OAuth2 Controller
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
*/
class Weboffice_GoogleShoppingApi_Adminhtml_GoogleShoppingApi_OauthController extends Mage_Adminhtml_Controller_Action
{ 
	/**
     * Google Content Config
     *
     * @return Weboffice_GoogleShoppingApi_Model_Config
     */
    public function getConfig()
    {
        return Mage::getSingleton('googleshoppingapi/config');
    }
    
    public function preDispatch()
	{ 
		if ($this->getRequest()->getActionName() == 'auth') Mage::getSingleton('adminhtml/url')->turnOffSecretKey();
		parent::preDispatch();
	}
    
    /**
     * Get store object, basing on request
     *
     * @return Mage_Core_Model_Store
     * @throws Mage_Core_Exception
     */
    public function _getStore()
    {
        $store = Mage::app()->getStore((int)$this->getRequest()->getParam('store', 0));
        if ((!$store) || 0 == $store->getId()) {
            Mage::throwException($this->__('Unable to select a Store View.'));
        }
        return $store;
    }
    
    /**
     * Retrieve synchronization process mutex
     *
     * @return Mage_GoogleShopping_Model_Flag
     */
    protected function _getFlag()
    {
        return Mage::getSingleton('googleshoppingapi/flag')->loadSelf();
    }
	
	public function authAction() {
		$url = Mage::getUrl("adminhtml/googleShoppingApi_oauth/auth");
		// /index.php/admin/googleShoppingApi_oauth/auth/
		
		$storeId = $this->getRequest()->getParam('store_id');
		
		$state = $this->getRequest()->getParam('state');
		if($state) {
			$params = json_decode(base64_decode(urldecode($state)));
			$storeId = $params->store_id;
		} else {
			$state = urlencode(base64_encode(json_encode(array('store_id' => $storeId))));
		}
		
 		

		$clientId = $this->getConfig()->getConfigData('client_id',$storeId);
		$clientSecret = $this->getConfig()->getConfigData('client_secret',$storeId);
		
		$adminSession = Mage::getSingleton('admin/session');
		$service = Mage::getModel('googleshoppingapi/googleShopping');
		$client = new Google_Client();
		$client->setApplicationName(Weboffice_GoogleShoppingApi_Model_GoogleShopping::APPNAME);
		$client->setClientId($clientId);
		$client->setClientSecret($clientSecret);
		$client->setRedirectUri($url);
		$client->setScopes('https://www.googleapis.com/auth/content');
		$client->setState($state);
		
		$accessTokens = $adminSession->getGoogleOAuth2Token();
		if(!is_array($accessTokens)) {
			$accessTokens = array();
		}
		
		$code = $this->getRequest()->getParam('code');
		if($code) {
			$accessToken = $client->authenticate($code);
			$accessTokens[$storeId] = $accessToken;
			$adminSession->setGoogleOAuth2Token($accessTokens);
			// unlock flag after successfull authentication
			$flag = $this->_getFlag();
			$flag->unlock();
			$this->_redirect('*/googleShoppingApi_items/index', array('store'=>$storeId));
			return $this;
		}
		
		header('Location: ' . $client->createAuthUrl());
		exit;
	}
	
}