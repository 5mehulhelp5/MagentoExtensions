<?php
/**
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml Google Contyent Item Types Grid
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author      Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */

class Weboffice_GoogleShoppingApi_Block_Adminhtml_Types extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'googleshoppingapi';
        $this->_controller = 'adminhtml_types';
        $this->_addButtonLabel = Mage::helper('googleshoppingapi')->__('Add Attribute Mapping');
        $this->_headerText = Mage::helper('googleshoppingapi')->__('Manage Attribute Mapping');
        parent::__construct();
    }
}
