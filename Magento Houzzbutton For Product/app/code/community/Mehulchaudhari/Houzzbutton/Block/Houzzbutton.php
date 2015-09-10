<?php   
class Mehulchaudhari_Houzzbutton_Block_Houzzbutton extends Mage_Core_Block_Template{   

    protected $_product = null;

    function getProduct()
    {
        if (!$this->_product) {
            $this->_product = Mage::registry('product');
        }
        return $this->_product;
    }



}
