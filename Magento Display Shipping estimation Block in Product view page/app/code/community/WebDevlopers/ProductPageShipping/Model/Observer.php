<?php

class WebDevlopers_ProductPageShipping_Model_Observer
{
    
    protected $_config = null;

    
    public function getConfig()
    {
        if ($this->_config === null) {
            $this->_config = Mage::getSingleton('webdevlopers_productpageshipping/config');
        }

        return $this->_config;
    }

    
    public function observeLayoutHandleInitialization(Varien_Event_Observer $observer)
    {
        /* @var $controllerAction Mage_Core_Controller_Varien_Action */
        $controllerAction = $observer->getEvent()->getAction();
        $fullActionName = $controllerAction->getFullActionName();
        if ($this->getConfig()->isEnabled() && in_array($fullActionName, $this->getConfig()->getControllerActions())) {
            if ($this->getConfig()->getDisplayPosition() === WebDevlopers_ProductPageShipping_Model_Config::DISPLAY_POSITION_LEFT) {
                // Display the form in the left column on the page
                $controllerAction->getLayout()->getUpdate()->addHandle(
                    WebDevlopers_ProductPageShipping_Model_Config::LAYOUT_HANDLE_LEFT
                );
            } elseif ($this->getConfig()->getDisplayPosition() === WebDevlopers_ProductPageShipping_Model_Config::DISPLAY_POSITION_RIGHT) {
                // Display the form in the right column on the page
                $controllerAction->getLayout()->getUpdate()->addHandle(
                    WebDevlopers_ProductPageShipping_Model_Config::LAYOUT_HANDLE_RIGHT
                );
            }
        }
    }
}
