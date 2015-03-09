<?php
/**
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Google Shopping Items
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author      Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
class Weboffice_GoogleShoppingApi_Block_Adminhtml_Items_Item extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('items');
        $this->setUseAjax(true);
    }

    /**
     * Prepare grid collection object
     *
     * @return Weboffice_GoogleShoppingApi_Block_Adminhtml_Items_Item
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('googleshoppingapi/item_collection');
        $store = $this->_getStore();
        $collection->addStoreFilter($store->getId());
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * Prepare grid columns
     *
     * @return Weboffice_GoogleShoppingApi_Block_Adminhtml_Items_Item
     */
    protected function _prepareColumns()
    {
        $this->addColumn('name',
            array(
                'header'    => $this->__('Product Name'),
                'width'     => '30%',
                'index'     => 'name',
        ));

        $this->addColumn('expires',
            array(
                'header'    => $this->__('Expires'),
                'type'      => 'datetime',
                'width'     => '100px',
                'index'     => 'expires',
        ));

        return parent::_prepareColumns();
    }

    /**
     * Prepare grid massaction actions
     *
     * @return Weboffice_GoogleShoppingApi_Block_Adminhtml_Items_Item
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('item_id');
        $this->getMassactionBlock()->setFormFieldName('item');
        $this->setNoFilterMassactionColumn(true);

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => $this->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete', array('_current'=>true)),
             'confirm'  => $this->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('refresh', array(
             'label'    => $this->__('Synchronize'),
             'url'      => $this->getUrl('*/*/refresh', array('_current'=>true)),
             'confirm'  => $this->__('This action will update items attributes and remove the items which are not available in Google Content. If an attributes was deleted from mapping, it will be deleted from Google too. Continue?')
        ));
        return $this;
    }

    /**
     * Grid url getter
     *
     * @return string current grid url
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * Get store model by request param
     *
     * @return Mage_Core_Model_Store
     */
    protected function _getStore()
    {
        return Mage::app()->getStore($this->getRequest()->getParam('store'));
    }
}
