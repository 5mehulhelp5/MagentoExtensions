<?php
class MehulChaudhari_Description_Block_Form extends Mage_Adminhtml_Block_Catalog_Product_Attribute_Edit_Form
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::helper('catalog')->isModuleEnabled('Mage_Cms')
            && Mage::getSingleton('cms/wysiwyg_config')->isEnabled()
        ) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }
}