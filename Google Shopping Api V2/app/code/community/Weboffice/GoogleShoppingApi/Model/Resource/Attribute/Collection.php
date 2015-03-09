<?php
/**
 * @category	Weboffice
 * @package     Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * GoogleShopping Attributes collection
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author     Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
class Weboffice_GoogleShoppingApi_Model_Resource_Attribute_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Whether to join attribute_set_id to attributes or not
     *
     * @var bool
     */
    protected $_joinAttributeSetFlag = true;

    protected function _construct()
    {
        $this->_init('googleshoppingapi/attribute');
    }

    /**
     * Add attribute set filter
     *
     * @param int $attributeSetId
     * @param string $targetCountry two words ISO format
     * @return Weboffice_GoogleShoppingApi_Model_Mysql4_Attribute_Collection
     */
    public function addAttributeSetFilter($attributeSetId, $targetCountry)
    {
        if (!$this->getJoinAttributeSetFlag()) {
            return $this;
        }
        $this->getSelect()->where('attribute_set_id = ?', $attributeSetId);
        $this->getSelect()->where('target_country = ?', $targetCountry);
        return $this;
    }

    /**
     * Add type filter
     *
     * @param int $type_id
     * @return Weboffice_GoogleShoppingApi_Model_Mysql4_Attribute_Collection
     */
    public function addTypeFilter($type_id)
    {
        $this->getSelect()->where('main_table.type_id = ?', $type_id);
        return $this;
    }

    /**
     * Load collection data
     *
     * @param bool $printQuery
     * @param bool $logQuery
     * @return  Weboffice_GoogleShoppingApi_Model_Mysql4_Attribute_Collection
     */
    public function load($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }
        if ($this->getJoinAttributeSetFlag()) {
            $this->_joinAttributeSet();
        }
        parent::load($printQuery, $logQuery);
        return $this;
    }

    /**
     * Join attribute sets data to select
     *
     * @return  Weboffice_GoogleShoppingApi_Model_Mysql4_Attribute_Collection
     */
    protected function _joinAttributeSet()
    {
        $this->getSelect()
            ->joinInner(
                array('types'=>$this->getTable('googleshoppingapi/types')),
                'main_table.type_id=types.type_id',
                array('attribute_set_id' => 'types.attribute_set_id', 'target_country' => 'types.target_country'));
        return $this;
    }

    /**
     * Get flag - whether to join attribute_set_id to attributes or not
     *
     * @return bool
     */
    public function getJoinAttributeSetFlag()
    {
        return $this->_joinAttributeSetFlag;
    }

    /**
     * Set flag - whether to join attribute_set_id to attributes or not
     *
     * @param bool $flag
     * @return bool
     */
    public function setJoinAttributeSetFlag($flag)
    {
        return $this->_joinAttributeSetFlag = (bool)$flag;
    }
}
