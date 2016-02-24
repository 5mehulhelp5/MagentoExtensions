<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mehulchaudhari\Daisyconfeed\Block\Adminhtml;

/**
 * Adminhtml catalog (google) sitemaps block
 */
class Daisyconfeed extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Block constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_daisyconfeed';
        $this->_blockGroup = 'Mehulchaudhari_Daisyconfeed';
        $this->_headerText = __('XML Daisyconfeed');
        $this->_addButtonLabel = __('Add Daisyconfeed');
        parent::_construct();
    }
}
