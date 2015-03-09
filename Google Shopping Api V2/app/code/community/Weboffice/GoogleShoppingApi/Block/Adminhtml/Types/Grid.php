<?php
/**
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml Google Content Item Types Mapping grid
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author      Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
class Weboffice_GoogleShoppingApi_Block_Adminhtml_Types_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('types_grid');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * Prepare grid collection object
     *
     * @return Weboffice_GoogleShoppingApi_Block_Adminhtml_Types_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('googleshoppingapi/type_collection')->addItemsCount();
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * Prepare grid colunms
     *
     * @return Weboffice_GoogleShoppingApi_Block_Adminhtml_Types_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('attribute_set_name',
            array(
                'header'    => $this->__('Attributes Set'),
                'index'     => 'attribute_set_name',
        ));

        $this->addColumn('target_country',
            array(
                'header'    => $this->__('Target Country'),
                'width'     => '150px',
                'index'     => 'target_country',
                'renderer'  => 'googleshoppingapi/adminhtml_types_renderer_country',
                'filter'    => false
        ));

        $this->addColumn('items_total',
            array(
                'header'    => Mage::helper('catalog')->__('Total Qty Content Items'),
                'width'     => '150px',
                'index'     => 'items_total',
                'filter'    => false
        ));

        return parent::_prepareColumns();
    }

    /**
     * Return row url for js event handlers
     *
     * @param Varien_Object
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id'=>$row->getId(), '_current'=>true));
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
}
