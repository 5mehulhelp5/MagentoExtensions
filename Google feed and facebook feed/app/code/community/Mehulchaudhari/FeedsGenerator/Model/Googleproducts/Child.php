<?php
/**
 * Mehulchaudhari FeedsGenerator Extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Mehulchaudhari
 * @package    Mehulchaudhari_FeedsGenerator
 * @author     Mehul Chaudhari
 * @copyright  Copyright (c) 2014 ; ;
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mehulchaudhari_FeedsGenerator_Model_Googleproducts_Child extends Mehulchaudhari_FeedsGenerator_Model_Child
{
    protected $useVariantConfigurables;

    /**
     * @var array
     */
    protected $linkAttributes = array();

    public function __construct($config)
    {
        parent::__construct($config);

        $this->useVariantConfigurables = Mage::getStoreConfig(
            'mehulchaudhari_feedsgenerator/googleproductsfeed/variant_configurables',
            $this->config->store_id
        );

        // load the Magento to Google variant attributes array
        $linkAttributesSetting = unserialize(
            Mage::getStoreConfig('mehulchaudhari_feedsgenerator/googleproductsfeed/link_attributes', $this->config->store_id)
        );

        if ($linkAttributesSetting) {
            foreach ($linkAttributesSetting as $linkAttribute) {
                $this->linkAttributes[$linkAttribute['magento']] = $linkAttribute['xmlfeed'];
            }
        }
    }

    /**
     * Don't generate an entry for configurable products; instead, generate
     * an entry for each of their child products, adding on the
     * g:item_group_id field containing the parent product's SKU to each.
     *
     * @var Mage_Catalog_Model_Product $product
     * @return array
     */
    public function processProduct(Mage_Catalog_Model_Product $parentProduct)
    {
        if ($this->useVariantConfigurables && $parentProduct->getTypeId() == 'configurable') {
            // Get all configurable attributes from the parent product and see
            // if mappings have been provided for them from Magento to
            // Google's variant attributes. If a mapping is found, add a new
            // atttribute specifier object to the list that's processed by
            // getProductData().
            $variantAttributes = array();
            $isGoogleVariant = true;
            foreach ($parentProduct->getTypeInstance()->getUsedProductAttributes($parentProduct) as $attribute) {
                if (isset($this->linkAttributes[$attribute->getAttributeCode()])) {
                    $variantAttributes[$this->linkAttributes[$attribute->getAttributeCode()]] = (object)array(
                        'magento' => $attribute->getAttributeCode(),
                        'feed' => $this->linkAttributes[$attribute->getAttributeCode()],
                        'type' => 'product_attribute',
                    );
                }
            }

            // If the product doesn't vary by any of the Google-supported variant attributes,
            // we can't tell Google that it is a variant product. If we pass the g:item_group_id
            // field but no variant attribute fields, Google will reject it.
            if (empty($variantAttributes)) {
                $isGoogleVariant = false;
            }

            // Use the parent's link rather than the child's, as the child
            // won't normally be directly accessible.
            $variantAttributes['product_link'] = (object)array(
                'value' => $this->getCleanProductUrl($parentProduct),
                'feed' => 'g:link',
                'type' => 'constant',
            );

            $products = array();
            foreach ($parentProduct->getTypeInstance()->getUsedProducts(null, $parentProduct) as $child) {
                $childData = $this->getProductData($child, $variantAttributes);
                if ($isGoogleVariant === true) {
                    $childData['g:item_group_id'] = $parentProduct->getSku();
                }
                $products[] = $childData;
            }
        } else {
            $products = array($this->getProductData($parentProduct));
        }

        return $products;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @param array $additionalAttributes
     * @return array
     */
    public function getProductData(Mage_Catalog_Model_Product $product, $additionalAttributes = array())
    {
        $productData = parent::getProductData($product, $additionalAttributes);

        $identifierAttributes = Mage::getModel('feedsgenerator/googleproducts_config_feedAttributes')->identifierFields;
        $hasIdentifier = false;
        foreach ($identifierAttributes as $identifier) {
            if (!empty($productData[$identifier])) {
                $hasIdentifier = true;
                break;
            }
        }
        $productData['g:identifier_exists'] = $hasIdentifier === true ? "true" : "false";

        return $productData;
    }
}
