<?php
abstract class WebDevlopers_ProductPageShipping_Block_Estimate_Abstract extends Mage_Catalog_Block_Product_Abstract
{
   
    protected $_estimate = null;



    protected $_config = null;


    
    protected $_session = null;

    
    protected $_carriers = null;

    
    public function getEstimate()
    {
        if ($this->_estimate === null) {
            $this->_estimate = Mage::getSingleton('webdevlopers_productpageshipping/estimate');
        }

        return $this->_estimate;
    }

    
    public function getConfig()
    {
        if ($this->_config === null) {
            $this->_config = Mage::getSingleton('webdevlopers_productpageshipping/config');
        }

        return $this->_config;
    }

    
    public function getSession()
    {
        if ($this->_session === null) {
            $this->_session = Mage::getSingleton('webdevlopers_productpageshipping/session');
        }

        return $this->_session;
    }

   
    public function isEnabled()
    {
        return $this->getConfig()->isEnabled() && $this->getProduct() && !$this->getProduct()->isVirtual();
    }
}
