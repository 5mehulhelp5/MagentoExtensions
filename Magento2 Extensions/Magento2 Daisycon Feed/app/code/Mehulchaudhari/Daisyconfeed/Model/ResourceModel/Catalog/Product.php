<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mehulchaudhari\Daisyconfeed\Model\ResourceModel\Catalog;

use Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator;

/**
 * Daisyconfeed resource product collection model
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Product extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    const NOT_SELECTED_IMAGE = 'no_selection';

    /**
     * Collection Zend Db select
     *
     * @var \Zend_Db_Select
     */
    protected $_select;

    /**
     * Attribute cache
     *
     * @var array
     */
    protected $_attributesCache = [];

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Backend\Media
     */
    protected $_mediaGalleryModel = null;

    /**
     * Init resource model (catalog/category)
     *
     */
    /**
     * Daisyconfeed data
     *
     * @var \Mehulchaudhari\Daisyconfeed\Helper\Data
     */
    protected $_daisyconfeedData = null;
    
    protected $_productCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    protected $_productResource;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_productVisibility;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $_productStatus;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Media
     */
    protected $_mediaAttribute;

    /**
     * @var \Magento\Eav\Model\ConfigFactory
     */
    protected $_eavConfigFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Media\Config
     */
    protected $_mediaConfig;

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Mehulchaudhari\Daisyconfeed\Helper\Data $daisyconfeedData
     * @param \Magento\Catalog\Model\ResourceModel\Product $productResource
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Product\Visibility $productVisibility
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus
     * @param \Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Media $mediaAttribute
     * @param \Magento\Eav\Model\ConfigFactory $eavConfigFactory
     * @param \Magento\Catalog\Model\Product\Media\Config $mediaConfig
     * @param string|null $resourcePrefix
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Mehulchaudhari\Daisyconfeed\Helper\Data $daisyconfeedData,
        \Magento\Catalog\Model\ResourceModel\Product $productResource,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Media $mediaAttribute,
        \Magento\Eav\Model\ConfigFactory $eavConfigFactory,
        \Magento\Catalog\Model\Product\Media\Config $mediaConfig,
        $resourcePrefix = null
    ) {
        $this->_productResource = $productResource;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_storeManager = $storeManager;
        $this->_productVisibility = $productVisibility;
        $this->_productStatus = $productStatus;
        $this->_mediaAttribute = $mediaAttribute;
        $this->_eavConfigFactory = $eavConfigFactory;
        $this->_mediaConfig = $mediaConfig;
        $this->_daisyconfeedData = $daisyconfeedData;
        parent::__construct($context, $resourcePrefix);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('catalog_product_entity', 'entity_id');
    }

    /**
     * Add attribute to filter
     *
     * @param int $storeId
     * @param string $attributeCode
     * @param mixed $value
     * @param string $type
     * @return \Zend_Db_Select|bool
     */
    protected function _addFilter($storeId, $attributeCode, $value, $type = '=')
    {
        if (!$this->_select instanceof \Zend_Db_Select) {
            return false;
        }

        switch ($type) {
            case '=':
                $conditionRule = '=?';
                break;
            case 'in':
                $conditionRule = ' IN(?)';
                break;
            default:
                return false;
                break;
        }

        $attribute = $this->_getAttribute($attributeCode);
        if ($attribute['backend_type'] == 'static') {
            $this->_select->where('e.' . $attributeCode . $conditionRule, $value);
        } else {
            $this->_joinAttribute($storeId, $attributeCode);
            if ($attribute['is_global']) {
                $this->_select->where('t1_' . $attributeCode . '.value' . $conditionRule, $value);
            } else {
                $ifCase = $this->_select->getAdapter()->getCheckSql(
                    't2_' . $attributeCode . '.value_id > 0',
                    't2_' . $attributeCode . '.value',
                    't1_' . $attributeCode . '.value'
                );
                $this->_select->where('(' . $ifCase . ')' . $conditionRule, $value);
            }
        }

        return $this->_select;
    }

    /**
     * Join attribute by code
     *
     * @param int $storeId
     * @param string $attributeCode
     * @return void
     */
    protected function _joinAttribute($storeId, $attributeCode)
    {
        $adapter = $this->getConnection();
        $attribute = $this->_getAttribute($attributeCode);
        $this->_select->joinLeft(
            ['t1_' . $attributeCode => $attribute['table']],
            'e.entity_id = t1_' . $attributeCode . '.entity_id AND ' . $adapter->quoteInto(
                ' t1_' . $attributeCode . '.store_id = ?',
                \Magento\Store\Model\Store::DEFAULT_STORE_ID
            ) . $adapter->quoteInto(
                ' AND t1_' . $attributeCode . '.attribute_id = ?',
                $attribute['attribute_id']
            ),
            []
        );

        if (!$attribute['is_global']) {
            $this->_select->joinLeft(
                ['t2_' . $attributeCode => $attribute['table']],
                $this->getConnection()->quoteInto(
                    't1_' .
                    $attributeCode .
                    '.entity_id = t2_' .
                    $attributeCode .
                    '.entity_id AND t1_' .
                    $attributeCode .
                    '.attribute_id = t2_' .
                    $attributeCode .
                    '.attribute_id AND t2_' .
                    $attributeCode .
                    '.store_id = ?',
                    $storeId
                ),
                []
            );
        }
    }

    /**
     * Get attribute data by attribute code
     *
     * @param string $attributeCode
     * @return array
     */
    protected function _getAttribute($attributeCode)
    {
        if (!isset($this->_attributesCache[$attributeCode])) {
            $attribute = $this->_productResource->getAttribute($attributeCode);

            $this->_attributesCache[$attributeCode] = [
                'entity_type_id' => $attribute->getEntityTypeId(),
                'attribute_id' => $attribute->getId(),
                'table' => $attribute->getBackend()->getTable(),
                'is_global' => $attribute->getIsGlobal() ==
                \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                'backend_type' => $attribute->getBackendType(),
            ];
        }
        return $this->_attributesCache[$attributeCode];
    }

    public function getUrl($storeId)
    {
        $products = [];

        /* @var $store \Magento\Store\Model\Store */
        $store = $this->_storeManager->getStore($storeId);
        if (!$store) {
            return false;
        }

        $adapter = $this->getConnection();

        $this->_select = $adapter->select()->from(
            ['e' => $this->getMainTable()],
            [$this->getIdFieldName(), 'updated_at']
        )->joinInner(
            ['w' => $this->getTable('catalog_product_website')],
            'e.entity_id = w.product_id',
            []
        )->joinLeft(
            ['url_rewrite' => $this->getTable('url_rewrite')],
            'e.entity_id = url_rewrite.entity_id AND url_rewrite.is_autogenerated = 1'
            . $adapter->quoteInto(' AND url_rewrite.store_id = ?', $store->getId())
            . $adapter->quoteInto(' AND url_rewrite.entity_type = ?', ProductUrlRewriteGenerator::ENTITY_TYPE),
            ['url' => 'request_path']
        )->where(
            'w.website_id = ?',
            $store->getWebsiteId()
        );

        $this->_addFilter($store->getId(), 'visibility', $this->_productVisibility->getVisibleInSiteIds(), 'in');
        $this->_addFilter($store->getId(), 'status', $this->_productStatus->getVisibleStatusIds(), 'in');
        
        $query = $adapter->query($this->_select);
        while ($row = $query->fetch()) {
             $id = $row['entity_id'];
             if (empty($row['url'])) {
                  $row['url'] = 'catalog/product/view/id/' . $id;
             }

            $products[$id] = $row['url'];
        }
        return $products;
    }

    public function getCollection($storeId)
    {
        $products = [];

        /* @var $store \Magento\Store\Model\Store */
        $store = $this->_storeManager->getStore($storeId);
        if (!$store) {
            return false;
        }
        
        $feedAttributes = unserialize($this->_daisyconfeedData->getFeedAttributes($store->getId()));
        $attributeToSelect = [];
        foreach($feedAttributes as $feedAttribute){
              $attributeToSelect[] = $feedAttribute['mageattribute']; 
        }            
        $collection = $this->_productCollectionFactory->create()->setStoreId($store->getId());
        $collection
            ->addAttributeToSelect($attributeToSelect)
            ->addFinalPrice()
            ->addTaxPercents()
            ->setVisibility($this->_productVisibility->getVisibleInSiteIds());
        foreach($collection as $productData){
            $id = $productData->getId();
            $data = $productData->getData();
            $Url = $this->getUrl($storeId);
            $data['url'] = $Url[$id];
            $product = $this->_prepareProduct($data, $attributeToSelect, $store->getId());
            $product['product'] = $productData;
            $products[$product['entity_id']] = $product;
        }
        return $products;
    }


    /**
     * Prepare product
     *
     * @param array $productRow
     * @param int $storeId
     * @return \Magento\Framework\Object
     */
    protected function _prepareProduct(array $productRow, $attributeToSelect, $storeId)
    {
        if (empty($productRow['url'])) {
            $productRow['url'] = 'catalog/product/view/id/' . $product->getId();
        }
        return $this->_loadProductImages($productRow, $attributeToSelect, $storeId);
    }

    /**
     * Load product images
     *
     * @param \Magento\Framework\Object $product
     * @param int $storeId
     * @return void
     */
    protected function _loadProductImages($product, $attributeToSelect, $storeId)
    {
        if (in_array("thumbnail", $attributeToSelect)) {
           $product['thumbnail'] = $this->_getMediaConfig()->getBaseMediaUrlAddition() . $product['thumbnail'];
        }else if(in_array("image", $attributeToSelect)){
           $product['image'] = $this->_getMediaConfig()->getBaseMediaUrlAddition() . $product['image'];
        }else if(in_array("small_image", $attributeToSelect)){
            $product['small_image'] = $this->_getMediaConfig()->getBaseMediaUrlAddition() . $product['small_image'];
        }
        return $product;
    }
    

    /**
     * Get media gallery model
     *
     * @return \Magento\Catalog\Model\Product\Attribute\Backend\Media|null
     */
    protected function _getMediaGalleryModel()
    {
        if ($this->_mediaGalleryModel === null) {
            /** @var $eavConfig \Magento\Eav\Model\Config */
            $eavConfig = $this->_eavConfigFactory->create();
            /** @var $eavConfig \Magento\Eav\Model\Attribute */
            $mediaGallery = $eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, 'media_gallery');
            $this->_mediaGalleryModel = $mediaGallery->getBackend();
        }
        return $this->_mediaGalleryModel;
    }

    /**
     * Get media config
     *
     * @return \Magento\Catalog\Model\Product\Media\Config
     */
    protected function _getMediaConfig()
    {
        return $this->_mediaConfig;
    }
}
