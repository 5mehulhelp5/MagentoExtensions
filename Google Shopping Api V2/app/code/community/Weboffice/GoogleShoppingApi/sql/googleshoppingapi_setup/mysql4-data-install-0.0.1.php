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
if (Mage::helper('googleshopping')->isModuleEnabled('Mage_GoogleShopping')) {
    $typesInsert = $installer->getConnection()
        ->select()
        ->from(
            $installer->getTable('googleshopping/types'),
            array(
                'type_id',
                'attribute_set_id',
                'target_country',
            )
        )
        ->insertFromSelect($installer->getTable('googleshoppingapi/types'));
    $itemsInsert = $installer->getConnection()
        ->select()
        ->from(
            $installer->getTable('googleshopping/items'),
            array(
                'item_id',
                'type_id',
                'product_id',
                'gcontent_item_id',
                'store_id',
                'published',
                'expires'
            )
        )
        ->insertFromSelect($installer->getTable('googleshoppingapi/items'));
    $attributes = '';
    foreach (Mage::getModel('googleshoppingapi/config')->getAttributes() as $destAttribtues) {
        foreach ($destAttribtues as $code => $info) {
            $attributes .= "'$code',";
        }
    }
    $attributes = rtrim($attributes, ',');
    $attributesInsert = $installer->getConnection()
        ->select()
        ->from(
            $installer->getTable('googleshopping/attributes'),
            array(
                'id',
                'attribute_id',
                'gcontent_attribute' => new Zend_Db_Expr("IF(gcontent_attribute IN ($attributes), gcontent_attribute, '')"),
                'type_id',
            )
        )
        ->insertFromSelect($installer->getTable('googleshoppingapi/attributes'));
    $installer->run($typesInsert);
    $installer->run($attributesInsert);
    $installer->run($itemsInsert);
}
