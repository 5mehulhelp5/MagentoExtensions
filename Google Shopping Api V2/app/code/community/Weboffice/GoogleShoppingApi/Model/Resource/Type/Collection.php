<?php
/**
 * @category	Weboffice
 * @package     Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * GoogleShopping Item Types collection
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author     Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
class Weboffice_GoogleShoppingApi_Model_Resource_Type_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    protected function _construct()
    {
        $this->_init('googleshoppingapi/type');
    }

    /**
     * Init collection select
     *
     * @return Weboffice_GoogleShoppingApi_Model_Mysql4_Type_Collection
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->_joinAttributeSet();
        return $this;
    }

   /**
    * Get SQL for get record count
    *
    * @return Varien_Db_Select
    */
   public function getSelectCountSql()
   {
       $this->_renderFilters();
       $paginatorAdapter = new Zend_Paginator_Adapter_DbSelect($this->getSelect());
       return $paginatorAdapter->getCountSelect();
   }

    /**
     * Add total count of Items for each type
     *
     * @return Weboffice_GoogleShoppingApi_Model_Mysql4_Type_Collection
     */
    public function addItemsCount()
    {
        $this->getSelect()
            ->joinLeft(
                array('items'=>$this->getTable('googleshoppingapi/items')),
                'main_table.type_id=items.type_id',
                array('items_total' => new Zend_Db_Expr('COUNT(items.item_id)')))
            ->group('main_table.type_id');
        return $this;
    }

    /**
     * Add country ISO filter to collection
     *
     * @param string $iso Two-letter country ISO code
     * @return Weboffice_GoogleShoppingApi_Model_Mysql4_Type_Collection
     */
    public function addCountryFilter($iso)
    {
        $this->getSelect()->where('target_country=?', $iso);
        return $this;
    }

    /**
     * Join Attribute Set data
     *
     * @return Weboffice_GoogleShoppingApi_Model_Mysql4_Type_Collection
     */
    protected function _joinAttributeSet()
    {
        $this->getSelect()
            ->join(
                array('set'=>$this->getTable('eav/attribute_set')),
                'main_table.attribute_set_id=set.attribute_set_id',
                array('attribute_set_name' => 'set.attribute_set_name'));
        return $this;
    }
}
