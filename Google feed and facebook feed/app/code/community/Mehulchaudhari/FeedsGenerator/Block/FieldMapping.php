<?php
/**
* Mehulchaudhari FeedsGenerator Extension
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
*
* @category   Mehulchaudhari
* @package    Mehulchaudhari_FeedsGenerator
* @author     Mehul Chaudhari
* @copyright  Copyright (c) 2011 ; ;
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
abstract class Mehulchaudhari_FeedsGenerator_Block_FieldMapping extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    /**
     * Label for feed attribute column on admin page
     *
     * @var string
     */
    protected $_feedFieldLabel;

    /**
     * Specifier for model that feed attributes are taken from
     *
     * @var string
     */
    protected $_feedAttributesModelSpecifier;

    /**
     * Map in some of the values not normally visible
     *
     * @var array
     */
    protected $_magentoOptions = array(
        'is_salable'        => 'is_saleable',
        'manufacturer_name' => 'manufacturer_name',
        'final_price'       => 'final_price',
    );

    public function __construct()
    {
        $helper = Mage::helper('adminhtml');

        $this->addColumn('magento', array(
            'label' => $helper->__('Magento product attribute'),
            'size'  => 28,
        ));
        $this->addColumn('xmlfeed', array(
            'label' => $helper->__($this->_feedFieldLabel),
            'size'  => 28
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = $helper->__('Add linked attribute');

        parent::__construct();
        $this->setTemplate('mehulchaudhari/feedsgenerator/system/config/form/field/array_dropdown.phtml');

        // product options
        $eavConfigModel = Mage::getModel('eav/config');
        $attributes = $eavConfigModel->getEntityAttributeCodes('catalog_product');

        foreach ($attributes as $code) {
            $attribute = $eavConfigModel->getAttribute('catalog_product', $code);
            if ($code != '') {
                $this->_magentoOptions[$code] = $code;
            }
        }
        asort($this->_magentoOptions);
    }

    protected function _renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new Exception('Wrong column name specified.');
        }
        $column     = $this->_columns[$columnName];
        $inputName  = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';

        if ($columnName == 'magento') {
            $rendered = '<select name="'.$inputName.'">';
            foreach ($this->_magentoOptions as $code => $name) {
                $rendered .= '<option value="'.$code.'">'.$name.'</option>';
            }
            $rendered .= '</select>';
        } else {
            $rendered = '<select name="' . $inputName . '">';
            $model = Mage::getModel($this->_feedAttributesModelSpecifier);
            foreach ($model->availableFields as $field) {
                $rendered .= '<option value="'.$field.'">'.str_replace("_", " ", $field)."</option>";
            }
            $rendered .= '</select>';
        }

        return $rendered;
    }
}
