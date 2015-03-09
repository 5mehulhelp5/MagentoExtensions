<?php
/**
 * Magento Module Weboffice_GoogleShoppingApi
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Product helper
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author     Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
class Weboffice_GoogleShoppingApi_Helper_Product extends Mage_Core_Helper_Abstract
{
    /**
     * Product attributes cache
     *
     * @var array
     */
    protected $_productAttributes;

    /**
     * Attribute labels by store ID
     *
     * @var array
     */
    protected $_attributeLabels;

    /**
     * Return Product attribute by attribute's ID
     *
     * @param Mage_Catalog_Model_Product $product
     * @param int $attributeId
     * @return null|Mage_Catalog_Model_Entity_Attribute Product's attribute
     */
    public function getProductAttribute(Mage_Catalog_Model_Product $product, $attributeId)
    {
        if (!isset($this->_productAttributes[$product->getId()])) {
            $attributes = $product->getAttributes();
            foreach ($attributes as $attribute) {
                $this->_productAttributes[$product->getId()][$attribute->getAttributeId()] = $attribute;
            }
        }

        return isset($this->_productAttributes[$product->getId()][$attributeId])
            ? $this->_productAttributes[$product->getId()][$attributeId]
            : null;
    }

    /**
     * Return Product Attribute Store Label
     * Set attribute name like frontend lable for custom attributes (which wasn't defined by Google)
     *
     * @param Mage_Catalog_Model_Resource_Eav_Attribute $attribute
     * @param int $storeId Store View Id
     * @return string Attribute Store View Label or Attribute code
     */
    public function getAttributeLabel($attribute, $storeId)
    {
        $attributeId = $attribute->getId();
        $frontendLabel = $attribute->getFrontend()->getLabel();

        if (is_array($frontendLabel)) {
            $frontendLabel = array_shift($frontendLabel);
        }
        if (!isset($this->_attributeLabels[$attributeId])) {
            $this->_attributeLabels[$attributeId] = $attribute->getStoreLabels();
        }

        if (isset($this->_attributeLabels[$attributeId][$storeId])) {
            return $this->_attributeLabels[$attributeId][$storeId];
        } else if (!empty($frontendLabel)) {
            return $frontendLabel;
        } else {
            return $attribute->getAttributeCode();
        }
    }
}
