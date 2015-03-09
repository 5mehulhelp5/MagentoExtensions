<?php
/**
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Content attribute's model
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author      Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
class Weboffice_GoogleShoppingApi_Model_Attribute_Content extends Weboffice_GoogleShoppingApi_Model_Attribute_Default
{
    /**
     * Set current attribute to entry (for specified product)
     *
     * @param Mage_Catalog_Model_Product $product
     * @param Google_Service_ShoppingContent_Product $shoppingProduct
     * @return Google_Service_ShoppingContent_Product
     */
    public function convertAttribute($product, $shoppingProduct)
    {
        $mapValue = $this->getProductAttributeValue($product);
        $description = $this->getGroupAttributeDescription();
        if (!is_null($description)) {
            $mapValue = $description->getProductAttributeValue($product);
        }

        if (!is_null($mapValue)) {
            $descrText = $mapValue;
        } elseif ($product->getDescription()) {
            $descrText = $product->getDescription();
        } else {
            $descrText = 'no description';
        }
        
        $processor = Mage::helper('cms')->getBlockTemplateProcessor();
        $descrText = strip_tags($processor->filter($descrText));
        
        $descrText = Mage::helper('googleshoppingapi')->cleanAtomAttribute($descrText);
        $descrText = html_entity_decode($descrText,null,"UTF-8");
        //$descrText = mb_convert_encoding($descrText,"UTF-8");
       $shoppingProduct->setDescription($descrText);

        return $shoppingProduct;
    }
}
