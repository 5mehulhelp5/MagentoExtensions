<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mehulchaudhari\Daisyconfeed\Controller\Adminhtml\Daisyconfeed;

use Magento\Backend\App\Action;

class Index extends \Mehulchaudhari\Daisyconfeed\Controller\Adminhtml\Daisyconfeed
{
    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        $this->_initAction();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Daisyconfeed'));
        $this->_view->renderLayout();
    }
}
