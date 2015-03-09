<?php
/**
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Adminhtml Google Shopping Item Id Renderer
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author      Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
class Weboffice_GoogleShoppingApi_Block_Adminhtml_Items_Renderer_Id
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renders Google Shopping Item Id
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        $baseUrl = 'http://www.google.com/merchants/view?docId=';

        $itemUrl = $row->getData($this->getColumn()->getIndex());
        $urlParts = parse_url($itemUrl);
        if (isset($urlParts['path'])) {
            $pathParts = explode('/', $urlParts['path']);
            $itemId = $pathParts[count($pathParts) - 1];
        } else {
            $itemId = $itemUrl;
        }
        $title = $this->__('View Item in Google Content');

        return sprintf('<a href="%s" alt="%s" title="%s" target="_blank">%s</a>', $baseUrl . $itemId, $title, $title, $itemId);
    }
}
