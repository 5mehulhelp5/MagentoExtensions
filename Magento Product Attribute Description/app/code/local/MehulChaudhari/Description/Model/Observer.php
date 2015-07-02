<?php

class MehulChaudhari_Description_Model_Observer
{
    /**
     * Hook that allows us to edit the form that is used to create and/or edit attributes.
     * @param Varien_Event_Observer $observer
     */
    public function addFieldToAttributeEditForm($observer)
    {
        // Add an extra field to the base fieldset:
        $fieldset = $observer->getForm()->getElement('base_fieldset');
		
        $fieldset->addField('description', 'editor', array(
            'name' => 'description',
            'label' => Mage::helper('core')->__('Description'),
            'title' => Mage::helper('core')->__('Description'),
			'wysiwyg'   => true,
            'required'  => false,
			'style'     => 'width: 600px;'
            //'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig()
        ));
    }
}