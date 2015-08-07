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

class Mehulchaudhari_FeedsGenerator_Model_Child
{
    /**
     * @var array
     */
    protected $category_paths = array();

    /**
     * @var array
     */
    protected $category_names = array();

    /**
     * @var Mage_Catalog_Helper_Output
     */
    protected $catalogOutputHelper = null;

    public function __construct($config)
    {
        $this->config = $config;
        $this->catalogOutputHelper = Mage::helper("catalog/output");
    }

    /**
     * Returns a hierarchical array of categories a product appears in. If a product
     * product is in multiple branches of the category tree, the longest branch is used.
     *
     * @param Mage_Catalog_Model_Product $product
     * @param array $exclude_cats
     * @return array
     */
    public function getCategoryPath(Mage_Catalog_Model_Product $product, array $exclude_cats)
    {
        $path = $this->getCategoryPathIds($product, $exclude_cats);

        $category_path = array();
        $size = sizeof($path);
        for ($i = 2; $i < $size; $i++) {
            if (!isset($this->category_names[$path[$i]])) {
                $this->category_names[$path[$i]] = Mage::getModel('catalog/category')->load($path[$i])->getName();
            }
            $category_path[] = $this->category_names[$path[$i]];
        }

        return $category_path;
    }

    /**
     * Returns the longest category path ID for a product.
     *
     * @param Mage_Catalog_Model_Product $product
     * @param array $exclude_cats
     * @return array
     */
    public function getCategoryPathIds(Mage_Catalog_Model_Product $product, array $exclude_cats)
    {
        $categories = $product->getCategoryIds();
        $path = array();
        foreach ($categories as $category_id) {
            if (!isset($this->category_paths[$category_id])) {
                $category = Mage::getModel('catalog/category')->load($category_id);
                $this->category_paths[$category_id] = explode("/", $category->getPath());
            }

            // Only include categories with paths longer than the default
            // category and with a top-level parent not in the excluded list.
            if (count($this->category_paths[$category_id]) >= 3
                    && !in_array($this->category_paths[$category_id][2], $exclude_cats)) {

                $current_path = $this->category_paths[$category_id];
                if (sizeof($current_path) > sizeof($path)) {
                    $path = $current_path;
                }
            }
        }
        return $path;
    }

    function log($msg)
    {
        Mage::log("Child: " . $msg, null, 'mehulchaudhari_feedsgenerator_child.log');
    }

    public function produceBatch()
    {
        $products = array();
        foreach ($this->config->entity_ids as $entity_id) {
            $this->log("Processing $entity_id");
            $product = Mage::getModel('catalog/product')->load($entity_id);
            $products = array_merge($products, $this->processProduct($product));
        }
        fwrite(STDOUT, json_encode($products));
    }

    /**
     * Remove "child_run.php/" from product URLs. This gets around some weirdness in
     * core Magento that adds SCRIPT_NAME to generated URLs.
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    public function getCleanProductUrl(Mage_Catalog_Model_Product $product)
    {
        return str_replace("child_run.php/", "", $product->getProductUrl());
    }

    /**
     * Performs the various value cleanups, if they're enabled for the
     * provided attribute.
     *
     * @param $attribute
     * @param mixed $value
     * @return mixed
     */
    public function cleanValue($attribute, $value)
    {
        // strip tags from the attribute value
        if (isset($attribute->strip_tags) && $attribute->strip_tags) {
            $value = strip_tags($value);
        }

        // replace newlines with spaces
        if (isset($attribute->strip_newlines) && $attribute->strip_newlines) {
            $value = str_replace("\n", ' ', $value);
        }

        // replace strings of whitespace characters with a single
        // space character - not enabled by default
        if (isset($attribute->normalise_whitespace) && $attribute->normalise_whitespace) {
            $value = preg_replace('/\s+/', ' ', $value);
        }

        return $value;
    }

    /**
     * By default output the requested attributes for all products. If special
     * handling is needed for some product types (i.e. configurable products),
     * this function can be overridden. Returns an array of products, which
     * can be used if something like a configurable product is translated into
     * multiple child products.
     *
     * @var Mage_Catalog_Model_Product $product
     * @return array
     */
    public function processProduct(Mage_Catalog_Model_Product $product)
    {
        return array($this->getProductData($product));
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @param array $additionalAttributes
     * @return array
     */
    public function getProductData(Mage_Catalog_Model_Product $product, $additionalAttributes = array())
    {
        $product_data = array();
        $attributes = array_merge((array)$this->config->attributes, $additionalAttributes);

        foreach ($attributes as $attribute) {
            if ($attribute->type == 'constant') {
                $value = $attribute->value;
            } elseif ($attribute->type == 'product_attribute') {
                // if this is a normal product attribute, retrieve it's frontend
                // representation, or the default value if it doesn't have a value
                // for this attribute
                if ($product->getData($attribute->magento) === null) {
                    if (isset($attribute->default)) {
                        $value = $attribute->default;
                    } else {
                        $value = '';
                    }
                } else {
                    /** @var $attributeObj Mage_Catalog_Model_Resource_Eav_Attribute */
                    $attributeObj = $product->getResource()->getAttribute($attribute->magento);
                    $value = $attributeObj->getFrontend()->getValue($product);
                    // The output helper performs this check as well, but we don't want to unnecessarily send off
                    // every single attribute to this helper if we can avoid it.
                    if ($attributeObj->getIsWysiwygEnabled()) {
                        $value = $this->catalogOutputHelper->productAttribute($product, $value, $attribute->magento);
                    }
                }
            } elseif ($attribute->type == 'stock_attribute') {
                $value = $product->getStockItem()->getData($attribute->magento);
                if ($value === null) {
                    if (isset($attribute->default)) {
                        $value = $attribute->default;
                    } else {
                        $value = '';
                    }
                }
            } elseif ($attribute->type == 'computed') {
                // if this is a computed attribute, handle it depending on its code
                switch ($attribute->magento) {
                    case 'final_price':
                        $value = sprintf('%.2f', (float)(Mage::helper('tax')->getPrice($product, $product->getFinalPrice(), true)));
						$value .= ' '.Mage::getStoreConfig('currency/options/default',$product->getStoreId());
                        break;
						
					case 'special_price':
                        $value = sprintf('%.2f', (float)(Mage::helper('tax')->getPrice($product, $product->getSpecialPrice(), true)));
						$value .= ' '.Mage::getStoreConfig('currency/options/default',$product->getStoreId());
                        break;
						
					case 'price':
                        $value = sprintf('%.2f', (float)(Mage::helper('tax')->getPrice($product, $product->getPrice(), true)));
						$value .= ' '.Mage::getStoreConfig('currency/options/default',$product->getStoreId());
                        break;	

                    case 'product_link':
                        $value = $this->getCleanProductUrl($product);
                        break;

                    case 'image_url':
                        $value = (string)Mage::helper('catalog/image')->init($product, 'image');
                        break;

                    case 'instock_y_n':
                        $value = $product->isSaleable() ? "Y" : "N"; // myshopping
                        break;

                    case 'availability_yes_no':
                        $value = $product->isSaleable() ? "yes" : "no";
                        break;

                    case 'availability_google':
                        $value = $product->isSaleable() ? 'in stock' : 'out of stock';
                        break;

                    case 'currency':
                        $value = Mage::getStoreConfig('mehulchaudhari_feedsgenerator/' . $this->config->config_path . '/currency');
                        break;

                    case 'google_taxonomy':
                        if (!isset($attribute->exclude_cats)) {
                            $attribute->exclude_cats = array();
                        }
                        $categoryIds = $this->getCategoryPathIds($product, $attribute->exclude_cats);

                        if (count($categoryIds) > 0) {
                            $categoryId = $categoryIds[count($categoryIds) - 1];
                            $category = Mage::getModel('catalog/category')->load($categoryId);
                            $value = $category->getResource()->getAttribute('google_product_category')->getFrontend()->getValue($category);
                        } else {
                            $value = '';
                        }
                        break;

                    case 'category_path':
                    case 'category_last':
                        if (!isset($attribute->exclude_cats)) {
                            $attribute->exclude_cats = array();
                        }
                        $value = $this->getCategoryPath($product, $attribute->exclude_cats);

                        if ($attribute->magento == 'category_last') {
                            if (count($value) > 0) {
                                $value = $value[count($value) - 1];
                            } else {
                                $value = utf8_encode(Mage::getStoreConfig('mehulchaudhari_feedsgenerator/'. $this->config->config_path .'/defaultcategory'));
                            }
                        }
                        break;

                    case 'child_skus':
                        $value = array();
                        if ($product->getTypeId() == 'configurable') {
                            foreach ($product->getTypeInstance()->getUsedProducts() as $child) {
                                $value[] = $child->getSku();
                            }
                        }
                        break;

                    default:
                        $this->log("Unknown computed attribute code: {$attribute->magento}");
                        $value = 'UNKNOWN_COMPUTED_ATTRIBUTE_CODE';
                }
            } else {
                $this->log("Unknown attribute type: {$attribute->type}");
                $value = 'UNKNOWN_ATTRIBUTE_TYPE';
            }

            // Clean up the value, or each of its elements if it's an array
            if (is_array($value)) {
                foreach ($value as &$element) {
                    $element = $this->cleanValue($attribute, $element);
                }
                unset($element);
            } else {
                $value = $this->cleanValue($attribute, $value);
            }

            $product_data[$attribute->feed] = $value;
        }
        return $product_data;
    }
}
