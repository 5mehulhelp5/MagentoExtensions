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
 * Price helper
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author     Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
class Weboffice_GoogleShoppingApi_Helper_Price
{
    /**
     * Tries to return price that looks like price in catalog
     *
     * @param Mage_Catalog_Model_Product $product
     * @param null|Mage_Core_Model_Store $store Store view
     * @return null|float Price
     */
    public function getCatalogPrice(Mage_Catalog_Model_Product $product, $store = null, $inclTax = null)
    {
        switch ($product->getTypeId()) {
            case Mage_Catalog_Model_Product_Type::TYPE_GROUPED:
                // Workaround to avoid loading stock status by admin's website
                if ($store instanceof Mage_Core_Model_Store) {
                    $oldStore = Mage::app()->getStore();
                    Mage::app()->setCurrentStore($store);
                }
                $subProducts = $product->getTypeInstance()->getAssociatedProducts($product);
                if ($store instanceof Mage_Core_Model_Store) {
                    Mage::app()->setCurrentStore($oldStore);
                }
                if (!count($subProducts)) {
                    return null;
                }
                $minPrice = null;
                foreach ($subProducts as $subProduct) {
                    $subProduct->setWebsiteId($product->getWebsiteId())
                        ->setCustomerGroupId($product->getCustomerGroupId());
                    if ($subProduct->isSalable()) {
                        if ($this->getCatalogPrice($subProduct) < $minPrice || $minPrice === null) {
                            $minPrice = $this->getCatalogPrice($subProduct);
                            $product->setTaxClassId($subProduct->getTaxClassId());
                        }
                    }
                }
                return $minPrice;

            case Mage_Catalog_Model_Product_Type::TYPE_BUNDLE:
                if ($store instanceof Mage_Core_Model_Store) {
                    $oldStore = Mage::app()->getStore();
                    Mage::app()->setCurrentStore($store);
                }

                Mage::unregister('rule_data');
                Mage::register('rule_data', new Varien_Object(array(
                    'store_id'          => $product->getStoreId(),
                    'website_id'        => $product->getWebsiteId(),
                    'customer_group_id' => $product->getCustomerGroupId())));

                $minPrice = $product->getPriceModel()->getPricesDependingOnTax($product, 'min', $inclTax);

                if ($store instanceof Mage_Core_Model_Store) {
                    Mage::app()->setCurrentStore($oldStore);
                }
                return $minPrice;

            case 'giftcard':
                return $product->getPriceModel()->getMinAmount($product);

            default:
                return $product->getFinalPrice();
        }
    }

    /**
     * Tries calculate price without discount; if can't returns null
     * @param $product
     * @param $store
     */
    public function getCatalogRegularPrice(Mage_Catalog_Model_Product $product, $store = null)
    {
         switch ($product->getTypeId()) {
            case Mage_Catalog_Model_Product_Type::TYPE_GROUPED:
            case Mage_Catalog_Model_Product_Type::TYPE_BUNDLE:
            case 'giftcard':
                return null;

            default:
                return $product->getPrice();
        }
    }
}
