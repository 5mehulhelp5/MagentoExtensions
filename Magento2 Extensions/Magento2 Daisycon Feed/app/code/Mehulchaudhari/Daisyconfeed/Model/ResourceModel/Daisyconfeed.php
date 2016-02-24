<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mehulchaudhari\Daisyconfeed\Model\ResourceModel;

/**
 * Daisyconfeed resource model
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Daisyconfeed extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Init resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('daisyconfeed', 'daisyconfeed_id');
    }
}
