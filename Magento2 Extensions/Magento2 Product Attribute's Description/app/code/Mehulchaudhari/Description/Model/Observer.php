<?php

namespace Mehulchaudhari\Description\Model;

class Observer
{

    public function addFieldToAttributeEditForm(\Magento\Framework\Event\Observer $observer)
    {
        // Add an extra field to the base fieldset:
        $fieldset = $observer->getForm()->getElement('base_fieldset');
        $fieldset->addField(
            'description',
            'textarea',
            [
                'name' => 'description',
                'label' => __('Description'),
                'title' => __('Description'),
                'style' => 'width: 600px;',
                'required' => false,
            ]
        );
    }
}
