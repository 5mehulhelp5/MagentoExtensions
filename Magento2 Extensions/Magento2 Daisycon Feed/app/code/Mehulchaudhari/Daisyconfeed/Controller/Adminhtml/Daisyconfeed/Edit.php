<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mehulchaudhari\Daisyconfeed\Controller\Adminhtml\Daisyconfeed;

class Edit extends \Mehulchaudhari\Daisyconfeed\Controller\Adminhtml\Daisyconfeed
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry)
    {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Edit daisyconfeed
     *
     * @return void
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('daisyconfeed_id');
        $model = $this->_objectManager->create('Mehulchaudhari\Daisyconfeed\Model\Daisyconfeed');

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This daisyconfeed no longer exists.'));
                $this->_redirect('adminhtml/*/');
                return;
            }
        }

        // 3. Set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        // 4. Register model to use later in blocks
        $this->_coreRegistry->register('daisyconfeed_daisyconfeed', $model);

        // 5. Build edit form
        $this->_initAction()->_addBreadcrumb(
            $id ? __('Edit Daisyconfeed') : __('New Daisyconfeed'),
            $id ? __('Edit Daisyconfeed') : __('New Daisyconfeed')
        )->_addContent(
            $this->_view->getLayout()->createBlock('Mehulchaudhari\Daisyconfeed\Block\Adminhtml\Edit')
        );
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Daisyconfeed'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(
            $model->getId() ? $model->getDaisyconfeedFilename() : __('New Daisyconfeed')
        );
        $this->_view->renderLayout();
    }
}
