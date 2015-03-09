<?php
/**
 * @category	Weboffice
 * @package     Mage_GoogleShopping
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * GoogleShopping Admin Item Types Controller
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author      Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
*/
class Weboffice_GoogleShoppingApi_Adminhtml_GoogleShoppingApi_TypesController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Dispatches controller_action_postdispatch_adminhtml Event (as not Adminhtml router)
     */
    public function postDispatch()
    {
        parent::postDispatch();
        if ($this->getFlag('', self::FLAG_NO_POST_DISPATCH)) {
            return;
        }
        Mage::dispatchEvent('controller_action_postdispatch_adminhtml', array('controller_action' => $this));
    }

    /**
     * Initialize attribute set mapping object
     *
     * @return Mage_GoogleShopping_Adminhtml_Googleshopping_TypesController
     */
    protected function _initItemType()
    {
        $this->_title($this->__('Catalog'))
             ->_title($this->__('Google Content'))
             ->_title($this->__('Manage Attributes'));

        Mage::register('current_item_type', Mage::getModel('googleshoppingapi/type'));
        $typeId = $this->getRequest()->getParam('id');
        if (!is_null($typeId)) {
            Mage::registry('current_item_type')->load($typeId);
        }
        return $this;
    }

    /**
     * Initialize general settings for action
     *
     * @return  Mage_GoogleShopping_Adminhtml_Googleshopping_ItemsController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('catalog/googleshoppingapi/types')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Catalog'), Mage::helper('adminhtml')->__('Catalog'))
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Google Content'), Mage::helper('adminhtml')->__('Google Content'));
        return $this;
    }

    /**
     * List of all maps (items)
     */
    public function indexAction()
    {
        $this->_title($this->__('Catalog'))
             ->_title($this->__('Google Content'))
             ->_title($this->__('Manage Attributes'));

        $this->_initAction()
            ->_addBreadcrumb(Mage::helper('googleshoppingapi')->__('Attribute Maps'), Mage::helper('googleshoppingapi')->__('Attribute Maps'))
            ->_addContent($this->getLayout()->createBlock('googleshoppingapi/adminhtml_types'))
            ->renderLayout();
    }

    /**
     * Grid for AJAX request
     */
    public function gridAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('googleshoppingapi/adminhtml_types_grid')->toHtml()
        );
    }

    /**
     * Create new attribute set mapping
     */
    public function newAction()
    {
        try {
            $this->_initItemType();

            $this->_title($this->__('New Attribute Mapping'));

            $this->_initAction()
                ->_addBreadcrumb(Mage::helper('googleshoppingapi')->__('New attribute set mapping'), Mage::helper('adminhtml')->__('New attribute set mapping'))
                ->_addContent($this->getLayout()->createBlock('googleshoppingapi/adminhtml_types_edit'))
                ->renderLayout();
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError(Mage::helper('googleshoppingapi')->__("Can't create Attribute Set Mapping."));
            $this->_redirect('*/*/index', array('store' => $this->_getStore()->getId()));
        }
    }

    /**
     * Edit attribute set mapping
     */
    public function editAction()
    {
        $this->_initItemType();
        $typeId = Mage::registry('current_item_type')->getTypeId();

        try {
            $result = array();
            if ($typeId) {
                $collection = Mage::getResourceModel('googleshoppingapi/attribute_collection')
                    ->addTypeFilter($typeId)
                    ->load();
                foreach ($collection as $attribute) {
                    $result[] = $attribute->getData();
                }
            }

            $this->_title($this->__('Edit Attribute Mapping'));
            Mage::register('attributes', $result);

            $breadcrumbLabel = $typeId ? Mage::helper('googleshoppingapi')->__('Edit attribute set mapping') : Mage::helper('googleshoppingapi')->__('New attribute set mapping');
            $this->_initAction()
                ->_addBreadcrumb($breadcrumbLabel, $breadcrumbLabel)
                ->_addContent($this->getLayout()->createBlock('googleshoppingapi/adminhtml_types_edit'))
                ->renderLayout();
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError(Mage::helper('googleshoppingapi')->__("Can't edit Attribute Set Mapping."));
            $this->_redirect('*/*/index');
        }
    }

    /**
     * Save attribute set mapping
     */
    public function saveAction()
    {
        /** @var $typeModel Mage_GoogleShopping_Model_Type */
        $typeModel = Mage::getModel('googleshoppingapi/type');
        $id = $this->getRequest()->getParam('type_id');
        if (!is_null($id)) {
            $typeModel->load($id);
        }

        try {
            $typeModel->setCategory($this->getRequest()->getParam('category'));
            if ($typeModel->getId()) {
                $collection = Mage::getResourceModel('googleshoppingapi/attribute_collection')
                    ->addTypeFilter($typeModel->getId())
                    ->load();
                foreach ($collection as $attribute) {
                    $attribute->delete();
                }
            } else {
                $typeModel->setAttributeSetId($this->getRequest()->getParam('attribute_set_id'))
                    ->setTargetCountry($this->getRequest()->getParam('target_country'));
            }
            $typeModel->save();

            $attributes = $this->getRequest()->getParam('attributes');
            $requiredAttributes = Mage::getSingleton('googleshoppingapi/config')->getRequiredAttributes();
            if (is_array($attributes)) {
                $typeId = $typeModel->getId();
                foreach ($attributes as $attrInfo) {
                    if (isset($attrInfo['delete']) && $attrInfo['delete'] == 1) {
                        continue;
                    }
                    Mage::getModel('googleshoppingapi/attribute')
                        ->setAttributeId($attrInfo['attribute_id'])
                        ->setGcontentAttribute($attrInfo['gcontent_attribute'])
                        ->setTypeId($typeId)
                        ->save();
                    unset($requiredAttributes[$attrInfo['gcontent_attribute']]);
                }
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('googleshoppingapi')->__('The attribute mapping has been saved.'));
            if (!empty($requiredAttributes)) {
                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('googleshoppingapi/category')->getMessage());
            }
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('googleshoppingapi')->__("Can't save Attribute Set Mapping."));
        }
        $this->_redirect('*/*/index', array('store' => $this->_getStore()->getId()));
    }

    /**
     * Delete attribute set mapping
     */
    public function deleteAction()
    {
        try {
            $id = $this->getRequest()->getParam('id');
            $model = Mage::getModel('googleshoppingapi/type');
            $model->load($id);
            if ($model->getTypeId()) {
                $model->delete();
            }
            $this->_getSession()->addSuccess($this->__('Attribute set mapping was deleted'));
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError(Mage::helper('googleshoppingapi')->__("Can't delete Attribute Set Mapping."));
        }
        $this->_redirect('*/*/index', array('store' => $this->_getStore()->getId()));
    }

    /**
     * Get Google Content attributes list
     */
    public function loadAttributesAction()
    {
        try {
            $this->getResponse()->setBody(
            $this->getLayout()->createBlock('googleshoppingapi/adminhtml_types_edit_attributes')
                ->setAttributeSetId($this->getRequest()->getParam('attribute_set_id'))
                ->setTargetCountry($this->getRequest()->getParam('target_country'))
                ->setAttributeSetSelected(true)
                ->toHtml()
            );
        } catch (Exception $e) {
            Mage::logException($e);
            // just need to output text with error
            $this->_getSession()->addError(Mage::helper('googleshoppingapi')->__("Can't load attributes."));
        }
    }

    /**
     * Get available attribute sets
     */
    protected function loadAttributeSetsAction()
    {
        try {
            $this->getResponse()->setBody(
                $this->getLayout()->getBlockSingleton('googleshoppingapi/adminhtml_types_edit_form')
                    ->getAttributeSetsSelectElement($this->getRequest()->getParam('target_country'))
                    ->toHtml()
            );
        } catch (Exception $e) {
            Mage::logException($e);
            // just need to output text with error
            $this->_getSession()->addError(Mage::helper('googleshoppingapi')->__("Can't load attribute sets."));
        }
    }

    /**
     * Get store object, basing on request
     *
     * @return Mage_Core_Model_Store
     */
    public function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        if ($storeId == 0) {
            return Mage::app()->getDefaultStoreView();
        }
        return Mage::app()->getStore($storeId);
    }

    /**
     * Check access to this controller
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/googleshoppingapi/types');
    }
}
