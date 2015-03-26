<?php
/**
 * @category	Weboffice
 * @package     Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Google Shopping Observer
 *
 * @category	Weboffice
 * @package     Weboffice_GoogleShoppingApi
 * @author      Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
class Weboffice_GoogleShoppingApi_Model_Observer
{
    /**
     * Update product item in Google Content
     *
     * @param Varien_Object $observer
     * @return Weboffice_GoogleShoppingApi_Model_Observer
     */
    public function saveProductItem($observer)
    {
        $product = $observer->getEvent()->getProduct();
        $items = $this->_getItemsCollection($product);

        try {
            Mage::getModel('googleshoppingapi/massOperations')
                ->synchronizeItems($items);
        } catch (Zend_Gdata_App_CaptchaRequiredException $e) {
            Mage::getSingleton('adminhtml/session')
                ->addError('Cannot update Google Content Item. Google requires CAPTCHA.');
        }

        return $this;
    }

    /**
     * Delete product item from Google Content
     *
     * @param Varien_Object $observer
     * @return Weboffice_GoogleShoppingApi_Model_Observer
     */
    public function deleteProductItem($observer)
    {
        $product = $observer->getEvent()->getProduct();
        $items = $this->_getItemsCollection($product);

        try {
            Mage::getModel('googleshoppingapi/massOperations')
                ->deleteItems($items);
        } catch (Zend_Gdata_App_CaptchaRequiredException $e) {
            Mage::getSingleton('adminhtml/session')
                ->addError('Cannot delete Google Content Item. Google requires CAPTCHA.');
        }

        return $this;
    }

    /**
     * Get items which are available for update/delete when product is saved
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Weboffice_GoogleShoppingApi_Model_Mysql4_Item_Collection
     */
    protected function _getItemsCollection($product)
    {
        $items = Mage::getResourceModel('googleshoppingapi/item_collection')
            ->addProductFilterId($product->getId());
        if ($product->getStoreId()) {
            $items->addStoreFilter($product->getStoreId());
        }
        $clientAuthenticated = true;
        foreach ($items as $item) {
            if (!Mage::getStoreConfigFlag('bvt_googleshoppingapi_config/settings/observed', $item->getStoreId())) {
                $items->removeItemByKey($item->getId());
            }else {
				$service = Mage::getModel('googleshoppingapi/googleShopping');
				if(!$service->isAuthenticated($item->getStoreId())) {
					$items->removeItemByKey($item->getId());
					$clientAuthenticated =false;
				}
			}
			
		}
		if(!$clientAuthenticated) {
			Mage::getSingleton('adminhtml/session')->addWarning(
				Mage::helper('googleshoppingapi')->__('Product was not updated on GoogleShopping for at least one store. Please authenticate and save the product again or update manually.')
			); 
		}

        return $items;
    }

    /**
     * Check if synchronize process is finished and generate notification message
     *
     * @param  Varien_Event_Observer $observer
     * @return Weboffice_GoogleShoppingApi_Model_Observer
     */
    public function checkSynchronizationOperations(Varien_Event_Observer $observer)
    {
        $flag = Mage::getSingleton('googleshoppingapi/flag')->loadSelf();
        if ($flag->isExpired()) {
            Mage::getModel('adminnotification/inbox')->addMajor(
                Mage::helper('googleshoppingapi')->__('Google Shopping operation has expired.'),
                Mage::helper('googleshoppingapi')->__('One or more google shopping synchronization operations failed because of timeout.')
            );
            $flag->unlock();
        }
        return $this;
    }
}
