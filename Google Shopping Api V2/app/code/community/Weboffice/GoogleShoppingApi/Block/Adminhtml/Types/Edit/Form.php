<?php
/**
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml Google Content types mapping form block
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author      Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */

class Weboffice_GoogleShoppingApi_Block_Adminhtml_Types_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare form before rendering HTML
     *
     * @return Weboffice_GoogleShoppingApi_Block_Adminhtml_Types_Edit_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $itemType = $this->getItemType();

        $fieldset = $form->addFieldset('content_fieldset', array(
            'legend'    => $this->__('Attribute set mapping')
        ));

        if ( !($targetCountry = $itemType->getTargetCountry()) ) {
            $isoKeys = array_keys($this->_getCountriesArray());
            $targetCountry = isset($isoKeys[0]) ? $isoKeys[0] : null;
        }
        $countrySelect = $fieldset->addField('select_target_country', 'select', array(
            'label'     => $this->__('Target Country'),
            'title'     => $this->__('Target Country'),
            'name'      => 'target_country',
            'required'  => true,
            'options'   => $this->_getCountriesArray(),
            'value'     => $targetCountry,
        ));
        if ($itemType->getTargetCountry()) {
            $countrySelect->setDisabled(true);
        }

        $attributeSetsSelect = $this->getAttributeSetsSelectElement($targetCountry)
            ->setValue($itemType->getAttributeSetId());
        if ($itemType->getAttributeSetId()) {
            $attributeSetsSelect->setDisabled(true);
        }

        $fieldset->addField('attribute_set', 'note', array(
            'label'     => $this->__('Attribute Set'),
            'title'     => $this->__('Attribute Set'),
            'required'  => true,
            'text'      => '<div id="attribute_set_select">' . $attributeSetsSelect->toHtml() . '</div>',
        ));

        $attributesBlock = $this->getLayout()
            ->createBlock('googleshoppingapi/adminhtml_types_edit_attributes')
            ->setTargetCountry($targetCountry);
        if ($itemType->getId()) {
            $attributesBlock->setAttributeSetId($itemType->getAttributeSetId())
                ->setAttributeSetSelected(true);
        }

        $attributes = Mage::registry('attributes');
        if (is_array($attributes) && count($attributes) > 0) {
            $attributesBlock->setAttributesData($attributes);
        }

        $fieldset->addField('attributes_box', 'note', array(
            'label'     => $this->__('Attributes Mapping'),
            'text'      => '<div id="attributes_details">' . $attributesBlock->toHtml() . '</div>',
        ));

        $form->addValues($itemType->getData());
        $form->setUseContainer(true);
        $form->setId('edit_form');
        $form->setMethod('post');
        $form->setAction($this->getSaveUrl());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Get Select field with list of available attribute sets for some target country
     *
     * @param  string $targetCountry
     * @return Varien_Data_Form_Element_Select
     */
    public function getAttributeSetsSelectElement($targetCountry)
    {
        $field = new Varien_Data_Form_Element_Select();
        $field->setName('attribute_set_id')
            ->setId('select_attribute_set')
            ->setForm(new Varien_Data_Form())
            ->addClass('required-entry')
            ->setValues($this->_getAttributeSetsArray($targetCountry));
        return $field;
    }

    /**
     * Get allowed country names array
     *
     * @return array
     */
    protected function _getCountriesArray()
    {
        $_allowed = Mage::getSingleton('googleshoppingapi/config')->getAllowedCountries();
        $result = array();
        foreach ($_allowed as $iso => $info) {
            $result[$iso] = $info['name'];
        }
        return $result;
    }

    /**
     * Get array with attribute setes which available for some target country
     *
     * @param  string $targetCountry
     * @return array
     */
    protected function _getAttributeSetsArray($targetCountry)
    {
        $entityType = Mage::getModel('catalog/product')->getResource()->getEntityType();
        $collection = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter($entityType->getId());

        $ids = array();
        $itemType = $this->getItemType();
        if ( !($itemType instanceof Varien_Object && $itemType->getId()) ) {
            $typesCollection = Mage::getResourceModel('googleshoppingapi/type_collection')
                ->addCountryFilter($targetCountry)
                ->load();
            foreach ($typesCollection as $type) {
                $ids[] = $type->getAttributeSetId();
            }
        }

        $result = array('' => '');
        foreach ($collection as $attributeSet) {
            if (!in_array($attributeSet->getId(), $ids)) {
                $result[$attributeSet->getId()] = $attributeSet->getAttributeSetName();
            }
        }
        return $result;
    }

    /**
     * Get current attribute set mapping from register
     *
     * @return Weboffice_GoogleShoppingApi_Model_Type
     */
    public function getItemType()
    {
        return Mage::registry('current_item_type');
    }

    /**
     * Get URL for saving the current map
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save', array('type_id' => $this->getItemType()->getId()));
    }
}
