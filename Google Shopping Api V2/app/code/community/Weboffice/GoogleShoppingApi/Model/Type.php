<?php
/**
 * @category	Weboffice
 * @package     Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Google Content Item Types Model
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author     Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
class Weboffice_GoogleShoppingApi_Model_Type extends Mage_Core_Model_Abstract
{
    /**
     * Mapping attributes collection
     *
     * @var Weboffice_GoogleShoppingApi_Model_Mysql4_Attribute_Collection
     */
    protected $_attributesCollection;

    protected function _construct()
    {
        $this->_init('googleshoppingapi/type');
    }

    /**
     * Load type model by Attribute Set Id and Target Country
     *
     * @param int $attributeSetId Attribute Set
     * @param string $targetCountry Two-letters country ISO code
     * @return Weboffice_GoogleShoppingApi_Model_Type
     */
    public function loadByAttributeSetId($attributeSetId, $targetCountry)
    {
        return $this->getResource()
            ->loadByAttributeSetIdAndTargetCountry($this, $attributeSetId, $targetCountry);
    }
    
    public function convertAttributes($product) {
    
		$newShoppingProduct = new Google_Service_ShoppingContent_Product();
		$map = $this->_getAttributesMapByProduct($product);
        $base = $this->_getBaseAttributes();
        $attributes = array_merge($base, $map);
        
        foreach ($attributes as $name => $attribute) {
            $attribute->convertAttribute($product, $newShoppingProduct);
        }
        
        return $newShoppingProduct;
    }

    /**
     * Return Product attribute values array
     *
     * @param Mage_Catalog_Model_Product $product
     * @return array Product attribute values
     */
    protected function _getAttributesMapByProduct(Mage_Catalog_Model_Product $product)
    {
        $result = array();
        $group = Mage::getSingleton('googleshoppingapi/config')->getAttributeGroupsFlat();
        foreach ($this->_getAttributesCollection() as $attribute) {
            $productAttribute = Mage::helper('googleshoppingapi/product')
                ->getProductAttribute($product, $attribute->getAttributeId());

            if (!is_null($productAttribute)) {
                // define final attribute name
                if ($attribute->getGcontentAttribute()) {
                    $name = $attribute->getGcontentAttribute();
                } else {
                    $name = Mage::helper('googleshoppingapi/product')->getAttributeLabel($productAttribute, $product->getStoreId());
                }

                if (!is_null($name)) {
                    $name = Mage::helper('googleshoppingapi')->normalizeName($name);
                    if (isset($group[$name])) {
                        // if attribute is in the group
                        if (!isset($result[$group[$name]])) {
                            $result[$group[$name]] = $this->_createAttribute($group[$name]);
                        }
                        // add group attribute to parent attribute
                        $result[$group[$name]]->addData(array(
                            'group_attribute_' . $name => $this->_createAttribute($name)->addData($attribute->getData())
                        ));
                        unset($group[$name]);
                    } else {
                        if (!isset($result[$name])) {
                            $result[$name] = $this->_createAttribute($name);
                        }
                        $result[$name]->addData($attribute->getData());
                    }
                }
            }
        }

        return $this->_initGroupAttributes($result);
    }

    /**
     * Retrun array with base attributes
     *
     * @return array
     */
    protected function _getBaseAttributes()
    {
        $names = Mage::getSingleton('googleshoppingapi/config')->getBaseAttributes();
        $attributes = array();
        foreach ($names as $name) {
            $attributes[$name] = $this->_createAttribute($name);
        }

        return $this->_initGroupAttributes($attributes);
    }

    /**
     * Append to attributes array subattribute's models
     *
     * @param array $attributes
     * @return array
     */
    protected function _initGroupAttributes($attributes)
    {
        $group = Mage::getSingleton('googleshoppingapi/config')->getAttributeGroupsFlat();
        foreach ($group as $child => $parent) {
            if (isset($attributes[$parent]) &&
                !isset($attributes[$parent]['group_attribute_' . $child])) {
                    $attributes[$parent]->addData(
                        array('group_attribute_' . $child => $this->_createAttribute($child))
                    );
            }
        }

        return $attributes;
    }

    /**
     * Prepare Google Content attribute model name
     *
     * @param string Attribute name
     * @return string Normalized attribute name
     */
    protected function _prepareModelName($string)
    {
        $string = Mage::helper('googleshoppingapi')->normalizeName($string);
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }

    /**
     * Create attribute instance using attribute's name
     *
     * @param string $name
     * @return Weboffice_GoogleShoppingApi_Model_Attribute
     */
    protected function _createAttribute($name)
    {
        $modelName = 'googleshoppingapi/attribute_' . $this->_prepareModelName($name);
        $useDefault = false;
        try {
            $attributeModel = Mage::getModel($modelName);
            $useDefault = !$attributeModel;
        } catch (Exception $e) {
            $useDefault = true;
        }
        if ($useDefault) {
            $attributeModel = Mage::getModel('googleshoppingapi/attribute_default');
        }
        $attributeModel->setName($name);

        return $attributeModel;
    }

    /**
     * Retrieve type's attributes collection
     * It is protected, because only Type knowns about its attributes
     *
     * @return Weboffice_GoogleShoppingApi_Model_Mysql4_Attribute_Collection
     */
    protected function _getAttributesCollection()
    {
        if (is_null($this->_attributesCollection)) {
            $this->_attributesCollection = Mage::getResourceModel('googleshoppingapi/attribute_collection')
                ->addAttributeSetFilter($this->getAttributeSetId(), $this->getTargetCountry());
        }
        return $this->_attributesCollection;
    }

}
