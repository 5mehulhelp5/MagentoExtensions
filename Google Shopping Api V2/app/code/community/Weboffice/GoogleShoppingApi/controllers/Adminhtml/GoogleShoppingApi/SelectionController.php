<?php
/**
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * GoogleShopping Products selection grid controller
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author      Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
class Weboffice_GoogleShoppingApi_Adminhtml_GoogleShoppingApi_SelectionController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Search result grid with available products for Google Content
     */
    public function searchAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()
                ->createBlock('googleshoppingapi/adminhtml_items_product')
                ->setIndex($this->getRequest()->getParam('index'))
                ->setFirstShow(true)
                ->toHtml()
           );
    }

    /**
     * Grid with available products for Google Content
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()
                ->createBlock('googleshoppingapi/adminhtml_items_product')
                ->setIndex($this->getRequest()->getParam('index'))
                ->toHtml()
           );
    }
}
