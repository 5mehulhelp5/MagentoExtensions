<?php

class WebDevlopers_ProductPageShipping_Block_Estimate_Form extends WebDevlopers_ProductPageShipping_Block_Estimate_Abstract
{
    
    public function isFieldVisible($fieldName)
    {
        if (method_exists($this->getConfig(), 'use' . uc_words($fieldName, ''))) {
            return $this->getConfig()->{'use' . uc_words($fieldName, '')}();
        }

        return true;
    }

    
    public function getFieldValue($fieldName)
    {
        $values = $this->getSession()->getFormValues();
        if (isset($values[$fieldName])) {
            return $values[$fieldName];
        }

        return null;
    }

    
    public function getEstimateUrl()
    {
        return $this->getUrl('webdevlopers_productpageshipping/estimate/estimate', array('_current' => true));
    }

    
    public function getCarriers()
    {
        if ($this->_carriers === null) {
            $this->_carriers = Mage::getModel('shipping/config')->getActiveCarriers();
        }

        return $this->_carriers;
    }

    
    public function isFieldRequired($fieldName)
    {
        $methodMap = array(
            'region' => 'isStateProvinceRequired', 
            'city'   => 'isCityRequired', 
            'postcode' => 'isZipCodeRequired' 
        );

        if (!isset($methodMap[$fieldName])) {
            return false;
        }

        $method = $methodMap[$fieldName];
        foreach ($this->getCarriers() as $carrier) {
            if ($carrier->$method()) {
                return true;
            }
        }

        return false;
    }

    
    public function useShoppingCart()
    {
        if ($this->getSession()->getFormValues() === null ||
            !$this->isFieldVisible('cart')) {
            return $this->getConfig()->useCartDefault();
        }

        return $this->getFieldValue('cart');
    }
}
