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
class Weboffice_GoogleShoppingApi_Model_Item extends Mage_Core_Model_Abstract
{
    /**
     * Regestry keys for caching attributes and types
     *
     * @var string
     */
    const TYPES_REGISTRY_KEY = 'gcontent_types_registry';

    /**
     * Service Item Instance
     *
     * @var Weboffice_GoogleShoppingApi_Model_Service_Item
     */
    protected $_serviceItem = null;

    protected function _construct()
    {
        parent::_construct();
        $this->_init('googleshoppingapi/item');
    }

    /**
     * Return Service Item Instance
     *
     * @return Weboffice_GoogleShoppingApi_Model_Service_Item
     */
    public function getServiceItem()
    {
        if (is_null($this->_serviceItem)) {
            $this->_serviceItem = Mage::getModel('googleshoppingapi/service_item')
                ->setStoreId($this->getStoreId());
        }
        return $this->_serviceItem;
    }

    /**
     * Set Service Item Instance
     *
     * @param Weboffice_GoogleShoppingApi_Model_Service_Item $service
     * @return Weboffice_GoogleShoppingApi_Model_Item
     */
    public function setServiceItem($service)
    {
        $this->_serviceItem = $service;
        return $this;
    }

    /**
     * Target Country
     *
     * @return string Two-letters country ISO code
     */
    public function getTargetCountry()
    {
        return Mage::getSingleton('googleshoppingapi/config')->getTargetCountry($this->getStoreId());
    }

    /**
     * Save item to Google Content
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Weboffice_GoogleShoppingApi_Model_Item
     */
    public function insertItem(Mage_Catalog_Model_Product $product)
    {
        $this->setProduct($product);
        $this->getServiceItem()
            ->insert($this);
        $this->setTypeId($this->getType()->getTypeId());

        return $this;
    }

    /**
     * Update Item data
     *
     * @return Weboffice_GoogleShoppingApi_Model_Item
     */
    public function updateItem()
    {
        if ($this->getId()) {
            $this->getServiceItem()
                ->update($this);
        }
        return $this;
    }

    /**
     * Delete Item from Google Content
     *
     * @return Weboffice_GoogleShoppingApi_Model_Item
     */
    public function deleteItem()
    {
        $this->getServiceItem()->delete($this);
        return $this;
    }

    /**
     * Load Item Model by Product
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Weboffice_GoogleShoppingApi_Model_Item
     */
    public function loadByProduct($product)
    {
        $this->setProduct($product);
        $this->getResource()->loadByProduct($this);
        return $this;
    }

    /**
     * Return Google Content Item Type Model for current Item
     *
     * @return Weboffice_GoogleShoppingApi_Model_Type
     */
    public function getType()
    {
        $attributeSetId = $this->getProduct()->getAttributeSetId();
        $targetCountry = $this->getTargetCountry();

        $registry = Mage::registry(self::TYPES_REGISTRY_KEY);
        if (is_array($registry) && isset($registry[$attributeSetId][$targetCountry])) {
            return $registry[$attributeSetId][$targetCountry];
        }

        $type = Mage::getModel('googleshoppingapi/type')
            ->loadByAttributeSetId($attributeSetId, $targetCountry);

        $registry[$attributeSetId][$targetCountry] = $type;
        Mage::unregister(self::TYPES_REGISTRY_KEY);
        Mage::register(self::TYPES_REGISTRY_KEY, $registry);

        return $type;
    }
    
    /**
     *	@return string
     */
    public function getGoogleShoppingItemId() {
		$tmpId = urldecode($this->getGcontentItemId());
		Mage::log($tmpId);
		return preg_replace('/.*\//','',$tmpId);
    }

    /**
     * Product Getter. Load product if not exist.
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        if (is_null($this->getData('product')) && !is_null($this->getProductId())) {
            $product = Mage::getModel('catalog/product')
                ->setStoreId($this->getStoreId())
                ->load($this->getProductId());
            $this->setData('product', $product);
        }

        return $this->getData('product');
    }

    /**
     * Product Setter.
     *
     * @param Mage_Catalog_Model_Product
     * @return Weboffice_GoogleShoppingApi_Model_Item
     */
    public function setProduct(Mage_Catalog_Model_Product $product)
    {
        $this->setData('product', $product);
        $this->setProductId($product->getId());
        $this->setStoreId($product->getStoreId());

        return $this;
    }
}
