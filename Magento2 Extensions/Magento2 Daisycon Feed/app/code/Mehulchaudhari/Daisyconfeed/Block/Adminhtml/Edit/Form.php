<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mehulchaudhari\Daisyconfeed\Block\Adminhtml\Edit;

/**
 * Daisyconfeed edit form
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('daisyconfeed_form');
        $this->setTitle(__('Daisyconfeed Information'));
    }

    /**
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('daisyconfeed_daisyconfeed');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset('add_daisyconfeed_form', ['legend' => __('Daisyconfeed')]);

        if ($model->getId()) {
            $fieldset->addField('daisyconfeed_id', 'hidden', ['name' => 'daisyconfeed_id']);
        }

        $fieldset->addField(
            'daisyconfeed_filename',
            'text',
            [
                'label' => __('Filename'),
                'name' => 'daisyconfeed_filename',
                'required' => true,
                'note' => __('example: daisyconfeed.xml'),
                'value' => $model->getDaisyconfeedFilename()
            ]
        );

        $fieldset->addField(
            'daisyconfeed_path',
            'text',
            [
                'label' => __('Path'),
                'name' => 'daisyconfeed_path',
                'required' => true,
                'note' => __('example: "/daisyconfeed/" or "/" for base path (path must be writeable)'),
                'value' => $model->getDaisyconfeedPath()
            ]
        );

        if (!$this->_storeManager->hasSingleStore()) {
            $field = $fieldset->addField(
                'store_id',
                'select',
                [
                    'label' => __('Store View'),
                    'title' => __('Store View'),
                    'name' => 'store_id',
                    'required' => true,
                    'value' => $model->getStoreId(),
                    'values' => $this->_systemStore->getStoreValuesForForm()
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'store_id',
                'hidden',
                ['name' => 'store_id', 'value' => $this->_storeManager->getStore(true)->getId()]
            );
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
