<?php

class WebDevlopers_ProductPageShipping_Model_Config_Source_Position
{
   
    public function toOptionArray()
    {
        return array(
            array(
                'value' => WebDevlopers_ProductPageShipping_Model_Config::DISPLAY_POSITION_LEFT,
                'label' => Mage::helper('webdevlopers_productpageshipping')->__('Left Column')
            ),
            array(
                'value' => WebDevlopers_ProductPageShipping_Model_Config::DISPLAY_POSITION_RIGHT,
                'label' => Mage::helper('webdevlopers_productpageshipping')->__('Right Column')
            ),
        );
    }
}
