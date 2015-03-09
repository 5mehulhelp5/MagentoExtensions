<?php
/**
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * GoogleShopping Admin Items Controller
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author      Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
*/
class Weboffice_GoogleShoppingApi_Adminhtml_GoogleShoppingApi_ItemsController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Initialize general settings for action
     *
     * @return  Mage_GoogleShopping_Adminhtml_Googleshopping_ItemsController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('catalog/googleshoppingapi/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Catalog'), Mage::helper('adminhtml')->__('Catalog'))
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Google Content'), Mage::helper('adminhtml')->__('Google Content'));
        return $this;
    }

    /**
     * Manage Items page with two item grids: Magento products and Google Content items
     */
    public function indexAction()
    {

        $this->_title($this->__('Catalog'))
             ->_title($this->__('Google Content'))
             ->_title($this->__('Manage Items'));

        if (0 === (int)$this->getRequest()->getParam('store')) {
            $this->_redirect('*/*/', array('store' => Mage::app()->getAnyStoreView()->getId(), '_current' => true));
            return;
        }
        
        $storeId = $this->_getStoreId();
        
        if($storeId) {
			$service = Mage::getModel('googleshoppingapi/googleShopping');
			$service->getClient($storeId);
        }
        
        $contentBlock = $this->getLayout()->createBlock('googleshoppingapi/adminhtml_items')->setStore($this->_getStore());

        if ($this->getRequest()->getParam('captcha_token') && $this->getRequest()->getParam('captcha_url')) {
            $contentBlock->setGcontentCaptchaToken(
                Mage::helper('core')->urlDecode($this->getRequest()->getParam('captcha_token'))
            );
            $contentBlock->setGcontentCaptchaUrl(
                Mage::helper('core')->urlDecode($this->getRequest()->getParam('captcha_url'))
            );
        }

        if (!$this->_getConfig()->isValidDefaultCurrencyCode($this->_getStore()->getId())) {
            $_countryInfo = $this->_getConfig()->getTargetCountryInfo($this->_getStore()->getId());
            $this->_getSession()->addNotice(
                Mage::helper('googleshoppingapi')->__("The store's currency should be set to %s for %s in system configuration. Otherwise item prices won't be correct in Google Content.", $_countryInfo['currency_name'], $_countryInfo['name'])
            );
        }

        $this->_initAction()
            ->_addBreadcrumb(Mage::helper('googleshoppingapi')->__('Items'), Mage::helper('googleshoppingapi')->__('Items'))
            ->_addContent($contentBlock)
            ->renderLayout();
    }

    /**
     * Grid with Google Content items
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()
                ->createBlock('googleshoppingapi/adminhtml_items_item')
                ->setIndex($this->getRequest()->getParam('index'))
                ->toHtml()
           );
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

    /**
     * Add (export) several products to Google Content
     */
    public function massAddAction()
    {
		$storeId = $this->_getStore()->getId();
		
        $flag = $this->_getFlag();
        if ($flag->isLocked()) {
			$this->_getSession()->addError($this->__('Flag locked!'));
			$this->_redirect('*/*/index', array('store'=>$storeId));
			return;
        }

        session_write_close();
        ignore_user_abort(true);
        set_time_limit(0);

        $productIds = $this->getRequest()->getParam('product', null);
        $notifier = Mage::getModel('adminnotification/inbox');

        try {
            $flag->lock();
            Mage::getModel('googleshoppingapi/massOperations')
                ->setFlag($flag)
                ->addProducts($productIds, $storeId);
        } catch (Exception $e) {
            $flag->unlock();
            $notifier->addMajor(
                Mage::helper('googleshoppingapi')->__('An error has occured while adding products to google shopping account.'),
                $e->getMessage()
            );
            Mage::logException($e);
            $this->_redirect('*/*/index', array('store'=>$storeId));
            return $this;
        }

        $flag->unlock();
        
        $this->_redirect('*/*/index', array('store'=>$storeId));
        return $this;
    }

    /**
     * Delete products from Google Content
     */
    public function massDeleteAction()
    {
		$storeId = $this->_getStore()->getId();
		
        $flag = $this->_getFlag();
        if ($flag->isLocked()) {
            $this->_getSession()->addError($this->__('Flag locked!'));
			$this->_redirect('*/*/index', array('store'=>$storeId));
			return;
        }

        session_write_close();
        ignore_user_abort(true);
        set_time_limit(0);

        $itemIds = $this->getRequest()->getParam('item');
		
        try {
            $flag->lock();
            Mage::getModel('googleshoppingapi/massOperations')
                ->setFlag($flag)
                ->deleteItems($itemIds);
        } catch (Zend_Gdata_App_CaptchaRequiredException $e) {
            // Google requires CAPTCHA for login
            $this->_getSession()->addError(Mage::helper('googleshoppingapi')->__($e->getMessage()));
            $flag->unlock();
            $this->_redirectToCaptcha($e);
            return;
        } catch (Exception $e) {
            $flag->unlock();
            Mage::getModel('adminnotification/inbox')->addMajor(
                Mage::helper('googleshoppingapi')->__('An error has occured while deleting products from google shopping account.'),
                Mage::helper('googleshoppingapi')->__('One or more products were not deleted from google shopping account. Refer to the log file for details.')
            );
            Mage::logException($e);
            $this->_redirect('*/*/index', array('store'=>$storeId));
            return $this;
        }
		$this->_redirect('*/*/index', array('store'=>$storeId));
        $flag->unlock();
        return $this;
        
    }

    /**
     * Update items statistics and remove the items which are not available in Google Content
     */
    public function refreshAction()
    {
		$storeId = $this->_getStore()->getId();
		
        $flag = $this->_getFlag();

        if ($flag->isLocked()) {
			$this->_getSession()->addError($this->__('Flag locked!'));
			$this->_redirect('*/*/index', array('store'=>$storeId));
			return;
        }

        session_write_close();
        ignore_user_abort(true);
        set_time_limit(0);

        $itemIds = $this->getRequest()->getParam('item');

        try {
            $flag->lock();
            Mage::getModel('googleshoppingapi/massOperations')
                ->setFlag($flag)
                ->synchronizeItems($itemIds);
        } catch (Exception $e) {
            $flag->unlock();
            Mage::getModel('adminnotification/inbox')->addMajor(
                Mage::helper('googleshoppingapi')->__('An error has occured while deleting products from google shopping account.'),
                Mage::helper('googleshoppingapi')->__('One or more products were not deleted from google shopping account. Refer to the log file for details.')
            );
            Mage::logException($e);
            Mage::log($e->getMessage());
            return;
        }
        $flag->unlock();
        
        $this->_redirect('*/*/index', array('store'=>$storeId));
        return $this;
    }

    /**
     * Confirm CAPTCHA
     */
    public function confirmCaptchaAction()
    {

        $storeId = $this->_getStore()->getId();
        try {
            Mage::getModel('googleshoppingapi/service')->getClient(
                $storeId,
                Mage::helper('core')->urlDecode($this->getRequest()->getParam('captcha_token')),
                $this->getRequest()->getParam('user_confirm')
            );
            $this->_getSession()->addSuccess($this->__('Captcha has been confirmed.'));

        } catch (Zend_Gdata_App_CaptchaRequiredException $e) {
            $this->_getSession()->addError($this->__('Captcha confirmation error: %s', $e->getMessage()));
            $this->_redirectToCaptcha($e);
            return;
        } catch (Zend_Gdata_App_Exception $e) {
            $this->_getSession()->addError(
                Mage::helper('googleshoppingapi')->parseGdataExceptionMessage($e->getMessage())
            );
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($this->__('Captcha confirmation error.'));
        }

        $this->_redirect('*/*/index', array('store'=>$storeId));
    }

    /**
     * Retrieve background process status
     *
     * @return Zend_Controller_Response_Abstract
     */
    public function statusAction()
    {
        if ($this->getRequest()->isAjax()) {
            $this->getResponse()->setHeader('Content-Type', 'application/json');
            $params = array(
                'is_running' => $this->_getFlag()->isLocked()
            );
            return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($params));
        }
    }

    /**
     * Redirect user to Google Captcha challenge
     *
     * @param Zend_Gdata_App_CaptchaRequiredException $e
     */
    protected function _redirectToCaptcha($e)
    {
        $redirectUrl = $this->getUrl(
            '*/*/index',
            array(
                'store' => $this->_getStore()->getId(),
                'captcha_token' => Mage::helper('core')->urlEncode($e->getCaptchaToken()),
                'captcha_url' => Mage::helper('core')->urlEncode($e->getCaptchaUrl())
            )
        );
        if ($this->getRequest()->isAjax()) {
            $this->getResponse()->setHeader('Content-Type', 'application/json')
                ->setBody(Mage::helper('core')->jsonEncode(array('redirect' => $redirectUrl)));
        } else {
            $this->_redirect($redirectUrl);
        }
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
    
    public function _getStoreId()
    {
        $store = Mage::app()->getStore((int)$this->getRequest()->getParam('store', 0));
        if($store && $store->getId()) {
			return $store->getId();
        }
        return null;
    }

    /**
     * Get Google Shopping config model
     *
     * @return Mage_GoogleShopping_Model_Config
     */
    protected function _getConfig()
    {
        return Mage::getSingleton('googleshoppingapi/config');
    }

    /**
     * Check access to this controller
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/googleshoppingapi/items');
    }
}
