<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mehulchaudhari\Daisyconfeed\Model\ResourceModel\Daisyconfeed;

/**
 * Daisyconfeed resource model collection
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Init collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Mehulchaudhari\Daisyconfeed\Model\Daisyconfeed', 'Mehulchaudhari\Daisyconfeed\Model\ResourceModel\Daisyconfeed');
    }

    /**
     * Filter collection by specified store ids
     *
     * @param array|int[] $storeIds
     * @return $this
     */
    public function addStoreFilter($storeIds)
    {
        $this->getSelect()->where('main_table.store_id IN (?)', $storeIds);
        return $this;
    }
}
