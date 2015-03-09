<?php
/**
 * @category	Weboffice
 * @package     Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Price attribute model
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author     Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
class Weboffice_GoogleShoppingApi_Model_Attribute_Price extends Weboffice_GoogleShoppingApi_Model_Attribute_Default
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
        $product->setWebsiteId(Mage::app()->getStore($product->getStoreId())->getWebsiteId());
        $product->setCustomerGroupId(
            Mage::getStoreConfig(Mage_Customer_Model_Group::XML_PATH_DEFAULT_ID, $product->getStoreId())
        );

        $store = Mage::app()->getStore($product->getStoreId());
        $targetCountry = Mage::getSingleton('googleshoppingapi/config')->getTargetCountry($product->getStoreId());
        $isSalePriceAllowed = true;//($targetCountry == 'US');

        // get tax settings
        $taxHelp = Mage::helper('tax');
        $priceDisplayType = $taxHelp->getPriceDisplayType($product->getStoreId());
        $inclTax = ($priceDisplayType == Mage_Tax_Model_Config::DISPLAY_TYPE_INCLUDING_TAX);

        // calculate sale_price attribute value
        $salePriceAttribute = $this->getGroupAttributeSalePrice();
        $salePriceMapValue = null;
        $finalPrice = null;
        if (!is_null($salePriceAttribute)) {
            $salePriceMapValue = $salePriceAttribute->getProductAttributeValue($product);
        }
        if (!is_null($salePriceMapValue) && floatval($salePriceMapValue) > .0001) {
            $finalPrice = $salePriceMapValue;
        
        } else if ($isSalePriceAllowed) {
            $finalPrice = Mage::helper('googleshoppingapi/price')->getCatalogPrice($product, $store, $inclTax);
        }
        
        
        
        if ($product->getTypeId() != Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
            $finalPrice = $taxHelp->getPrice($product, $finalPrice, $inclTax, null, null, null, $product->getStoreId());
        }

        // calculate price attribute value
        $priceMapValue = $this->getProductAttributeValue($product);
        $price = null;
        if (!is_null($priceMapValue) && floatval($priceMapValue) > .0001) {
            $price = $priceMapValue;
        } else if ($isSalePriceAllowed) {
            $price = Mage::helper('googleshoppingapi/price')->getCatalogRegularPrice($product, $store);
        } else {
            $inclTax = ($priceDisplayType != Mage_Tax_Model_Config::DISPLAY_TYPE_EXCLUDING_TAX);
            $price = Mage::helper('googleshoppingapi/price')->getCatalogPrice($product, $store, $inclTax);
        }
        if ($product->getTypeId() != Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
            $price = $taxHelp->getPrice($product, $price, $inclTax, null, null, null, $product->getStoreId());
        }
        
		$shoppingPrice = new Google_Service_ShoppingContent_Price();
		$shoppingPrice->setCurrency($store->getDefaultCurrencyCode());//sprintf('%.2f', $store->roundPrice($price))
        if ($isSalePriceAllowed) {
            // set sale_price and effective dates for it
            if ($price && ($price - $finalPrice) > .0001) {
				$salesPrice = new Google_Service_ShoppingContent_Price();
				$salesPrice->setCurrency($store->getDefaultCurrencyCode());
                $shoppingPrice->setValue(sprintf('%.2f', $store->roundPrice($price)));
                $salesPrice->setValue($finalPrice);
				$shoppingProduct->setSalePrice($salesPrice);
				
                $effectiveDate = $this->getGroupAttributeSalePriceEffectiveDate();
                if (!is_null($effectiveDate)) {
                    $effectiveDate->setGroupAttributeSalePriceEffectiveDateFrom(
                            $this->getGroupAttributeSalePriceEffectiveDateFrom()
                        )
                        ->setGroupAttributeSalePriceEffectiveDateTo($this->getGroupAttributeSalePriceEffectiveDateTo())
                        ->convertAttribute($product, $shoppingProduct);
                }
            } else {
                $shoppingPrice->setValue(sprintf('%.2f', $store->roundPrice($finalPrice)));
            }

            //TODO
            // 2011-03-01T13:00-0800/2011-03-11T15:30-0800
            // salePriceEffectiveDate
            
            // calculate taxes
            $tax = $this->getGroupAttributeTax();
            if (!$inclTax && !is_null($tax)) {
                $tax->convertAttribute($product, $shoppingProduct);
            }
        } else {
            $shoppingPrice->setValue(sprintf('%.2f', $store->roundPrice($price)));
        }
        
        $shoppingProduct->setPrice($shoppingPrice);

        return $shoppingProduct;
    }

}
