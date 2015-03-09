<?php
/**
 * @category	Weboffice
 * @package     Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Google Content items collection
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author     Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
class Weboffice_GoogleShoppingApi_Model_Resource_Item_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('googleshoppingapi/item');
    }

    /**
     * Init collection select
     *
     * @return Weboffice_GoogleShoppingApi_Model_Mysql4_Item_Collection
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->_joinTables();
        return $this;
    }

    /**
     * Filter collection by specified store ids
     *
     * @param array|int $storeIds
     * @return Weboffice_GoogleShoppingApi_Model_Mysql4_Item_Collection
     */
    public function addStoreFilter($storeIds)
    {
        $this->getSelect()->where('main_table.store_id IN (?)', $storeIds);
        return $this;
    }

    /**
     * Filter collection by specified product id
     *
     * @param int $productId
     * @return Weboffice_GoogleShoppingApi_Model_Mysql4_Item_Collection
     */
    public function addProductFilterId($productId)
    {
        $this->getSelect()->where('main_table.product_id=?', $productId);
        return $this;
    }

    /**
     * Add field filter to collection
     *
     * @see self::_getConditionSql for $condition
     * @param string $field
     * @param null|string|array $condition
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function addFieldToFilter($field, $condition=null)
    {
        if ($field == 'name') {
            $conditionSql = $this->_getConditionSql(
                $this->getConnection()->getIfNullSql('p.value', 'p_d.value'), $condition
            );
            $this->getSelect()->where($conditionSql, null, Varien_Db_Select::TYPE_CONDITION);
            return $this;
        } else {
            return parent::addFieldToFilter($field, $condition);
        }
    }

    /**
     * Join product and type data
     *
     * @return Weboffice_GoogleShoppingApi_Model_Mysql4_Item_Collection
     */
    protected function _joinTables()
    {
        $entityType = Mage::getSingleton('eav/config')->getEntityType('catalog_product');
        $attribute = Mage::getSingleton('eav/config')->getAttribute($entityType->getEntityTypeId(),'name');

        $joinConditionDefault =
            sprintf("p_d.attribute_id=%d AND p_d.store_id='0' AND main_table.product_id=p_d.entity_id",
                $attribute->getAttributeId()
            );
        $joinCondition =
            sprintf("p.attribute_id=%d AND p.store_id=main_table.store_id AND main_table.product_id=p.entity_id",
                $attribute->getAttributeId()
            );

        $this->getSelect()
            ->joinLeft(
                array('p_d' => $attribute->getBackend()->getTable()),
                $joinConditionDefault,
                array());

        $this->getSelect()
            ->joinLeft(
                array('p' => $attribute->getBackend()->getTable()),
                $joinCondition,
                array('name' => $this->getConnection()->getIfNullSql('p.value', 'p_d.value')));

        $this->getSelect()
            ->joinLeft(
                array('types' => $this->getTable('googleshoppingapi/types')),
                'main_table.type_id=types.type_id');

        return $this;
    }
}
