<?php
/**
 * @category	Weboffice
 * @package     Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Google Content Item Types Model
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author     Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
class Weboffice_GoogleShoppingApi_Model_Service extends Varien_Object
{

    /**
     * Return Google Content Service Instance
     *
     * @param int $storeId
     * @return Weboffice_GoogleShoppingApi_Model_GoogleShopping
     */
    public function getService($storeId = null)
    {
        if (!$this->_service) {
            $this->_service = Mage::getModel('googleshoppingapi/googleShopping');

//             if ($this->getConfig()->getIsDebug($storeId)) {
//                 $this->_service
//                     ->setLogAdapter(Mage::getModel('core/log_adapter', 'googleshoppingapi.log'), 'log')
//                     ->setDebug(true);
//             }
        }
        return $this->_service;
    }

    /**
     * Set Google Content Service Instance
     *
     * @param Weboffice_GoogleShoppingApi_Model_GoogleShopping $service
     * @return Weboffice_GoogleShoppingApi_Model_Service
     */
    public function setService($service)
    {
        $this->_service = $service;
        return $this;
    }

    /**
     * Google Content Config
     *
     * @return Weboffice_GoogleShoppingApi_Model_Config
     */
    public function getConfig()
    {
        return Mage::getSingleton('googleshoppingapi/config');
    }

}
