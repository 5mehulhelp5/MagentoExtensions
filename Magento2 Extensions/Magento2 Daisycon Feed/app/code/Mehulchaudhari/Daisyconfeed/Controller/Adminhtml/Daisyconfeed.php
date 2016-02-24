<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mehulchaudhari\Daisyconfeed\Controller\Adminhtml;

/**
 * XML Daisyconfeed controller
 */
abstract class Daisyconfeed extends \Magento\Backend\App\Action
{
    /**
     * Init actions
     *
     * @return $this
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        $this->_view->loadLayout();
        $this->_setActiveMenu(
            'Mehulchaudhari_Daisyconfeed::catalog_daisyconfeed'
        )->_addBreadcrumb(
            __('Catalog'),
            __('Catalog')
        )->_addBreadcrumb(
            __('XML Daisyconfeed'),
            __('XML Daisyconfeed')
        );
        return $this;
    }

    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mehulchaudhari_Daisyconfeed::daisyconfeed');
    }
}
