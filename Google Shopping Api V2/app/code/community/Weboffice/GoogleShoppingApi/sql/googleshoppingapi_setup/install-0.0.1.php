<?php
/**
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * GoogleShopping install
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author      Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
/** @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;
$installer->startSetup();
$connection = $installer->getConnection();
$table = $connection->newTable($this->getTable('googleshoppingapi/types'))
    ->addColumn('type_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true
        ), 'Type ID')
    ->addColumn('attribute_set_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned' => true,
        'nullable' => false
        ), 'Attribute Set Id')
    ->addColumn('target_country', Varien_Db_Ddl_Table::TYPE_TEXT, 2, array(
        'nullable' => false,
        'default' => 'DE'
        ), 'Target country')
    ->addForeignKey(
        $installer->getFkName(
            'googleshoppingapi/types',
            'attribute_set_id',
            'eav/attribute_set',
            'attribute_set_id'
        ),
        'attribute_set_id',
        $this->getTable('eav/attribute_set'),
        'attribute_set_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addIndex(
        $installer->getIdxName(
            'googleshoppingapi/types',
            array('attribute_set_id', 'target_country'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('attribute_set_id', 'target_country'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->setComment('Google Content Item Types link Attribute Sets');
$installer->getConnection()->createTable($table);
$table = $connection->newTable($this->getTable('googleshoppingapi/items'))
    ->addColumn('item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable' => false,
        'unsigned' => true,
        'primary' => true
        ), 'Item Id')
    ->addColumn('type_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'unsigned' => true,
        'default' => 0
        ), 'Type Id')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'unsigned' => true
        ), 'Product Id')
    ->addColumn('gcontent_item_id', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false
        ), 'Google Content Item Id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'unsigned' => true
        ), 'Store Id')
    ->addColumn('published', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(), 'Published date')
    ->addColumn('expires', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(), 'Expires date')
    ->addForeignKey(
        $installer->getFkName(
            'googleshoppingapi/items',
            'product_id',
            'catalog/product',
            'entity_id'
        ),
        'product_id',
        $this->getTable('catalog/product'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
     )
    ->addForeignKey(
        $installer->getFkName(
            'googleshoppingapi/items',
            'store_id',
            'core/store',
            'store_id'
        ),
        'store_id',
        $this->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
     )
    ->addIndex($installer->getIdxName('googleshoppingapi/items', array('product_id', 'store_id')),
         array('product_id', 'store_id'))
    ->setComment('Google Content Items Products');
$installer->getConnection()->createTable($table);
$table = $connection->newTable($this->getTable('googleshoppingapi/attributes'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'identity'  => true,
        'nullable' => false,
        'unsigned' => true,
        'primary' => true
        ), 'Id')
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'unsigned' => true
        ), 'Attribute Id')
    ->addColumn('gcontent_attribute', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false
        ), 'Google Content Attribute')
    ->addColumn('type_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'unsigned' => true
        ), 'Type Id')
    ->addForeignKey(
        $installer->getFkName(
            'googleshoppingapi/attributes',
            'attribute_id',
            'eav/attribute',
            'attribute_id'
        ),
        'attribute_id',
        $this->getTable('eav/attribute'),
        'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
     )
    ->addForeignKey(
        $installer->getFkName(
            'googleshoppingapi/attributes',
            'type_id',
            'googleshoppingapi/types',
            'type_id'
        ),
        'type_id',
        $this->getTable('googleshoppingapi/types'),
        'type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
     )
     ->setComment('Google Content Attributes link Product Attributes');
$installer->getConnection()->createTable($table);
$catalogEavSetup = Mage::getResourceModel('catalog/eav_mysql4_setup','core_setup');
$attrGoogleShoppingImage = $catalogEavSetup->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'google_shopping_image');
if($attrGoogleShoppingImage === false) {
    $catalogEavSetup->addAttribute('catalog_product','google_shopping_image',
        array (
            'group'             => 'Images',
            'type'              => 'varchar',
            'frontend'          => 'catalog/product_attribute_frontend_image',
            'label'             => 'Google Shopping Image',
            'input'             => 'media_image',
            'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
            'visible'           => true,
            'default'           => '',
            'class'             => '',
            'source'            => ''
        )
    );
}
$catalogEavSetup->addAttribute('catalog_product','google_shopping_category',
    array (
        'group'             => 'Google Shopping',
        'type'              => 'varchar',
        'frontend'          => '',
        'label'             => 'Google Shopping Category',
        'input'             => 'select',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible'           => true,
        'default'           => '',
        'class'             => '',
        'source'            => 'googleshoppingapi/attribute_source_googleShoppingCategories',
        'required'          => false,
        'user_defined'      => true,
    )
);
$installer->endSetup();
