<?php
/**
 * @category	Weboffice
 * @package     Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Google Content Item Model
 *
 * @category	Weboffice
 * @package     Weboffice_GoogleShoppingApi
 * @author      Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
class Weboffice_GoogleShoppingApi_Model_Service_Item extends Weboffice_GoogleShoppingApi_Model_Service
{
    /**
     * Return Store level Service Instance
     *
     * @param int $storeId
     * @return Weboffice_GoogleShoppingApi_Model_GoogleShopping
     */
    public function getService($storeId = null)
    {
        if ($storeId === null) {
            $storeId = $this->getStoreId();
        }
        return parent::getService($storeId);
    }

    /**
     * Insert Item into Google Content
     *
     * @param Weboffice_GoogleShoppingApi_Model_Item $item
     * @return Weboffice_GoogleShoppingApi_Model_Service_Item
     */
    public function insert($item)
    {

        $service = Mage::getModel('googleshoppingapi/googleShopping');
        
        $product = $item->getType()->convertAttributes($item->getProduct());

        $shoppingProduct = $service->insertProduct($product,$item->getStoreId());
        $published = now();

        $item->setGcontentItemId($shoppingProduct->getId())
            ->setPublished($published);
            
        $expires = $shoppingProduct->getExpirationDate();
        
        if ($expires) {
            $expires = $this->convertContentDateToTimestamp($expires);
            $item->setExpires($expires);
        }
        return $this;
    }

    /**
     * Update Item data in Google Content
     *
     * @param Weboffice_GoogleShoppingApi_Model_Item $item
     * @return Weboffice_GoogleShoppingApi_Model_Service_Item
     */
    public function update($item)
    {
		$service = Mage::getModel('googleshoppingapi/googleShopping');
		
		$gItemId = $item->getGoogleShoppingItemId();

		// get product from google shopping
		//$product = $service->getProduct($gItemId,$item->getStoreId());
		
		$product = $item->getType()->convertAttributes($item->getProduct());
		
		$shoppingProduct = $service->updateProduct($product,$item->getStoreId());

		$expires = $shoppingProduct->getExpirationDate();
        
        if ($expires) {
            $expires = $this->convertContentDateToTimestamp($expires);
            $item->setExpires($expires);
        }
        
        return $this;
    }

    /**
     * Delete Item from Google Content
     *
     * @param Weboffice_GoogleShoppingApi_Model_Item $item
     * @return Weboffice_GoogleShoppingApi_Model_Service_Item
     */
    public function delete($item)
    {
        $gItemId = $item->getGoogleShoppingItemId();
        $service = Mage::getModel('googleshoppingapi/googleShopping');
        $service->deleteProduct($gItemId,$item->getStoreId());

        return $this;
    }

    /**
     * Convert Google Content date format to unix timestamp
     * Ex. 2008-12-08T16:57:23Z -> 2008-12-08 16:57:23
     *
     * @param string Google Content datetime
     * @return int
     */
    public function convertContentDateToTimestamp($gContentDate)
    {
        return Mage::getSingleton('core/date')->date(null, $gContentDate);
    }
}
