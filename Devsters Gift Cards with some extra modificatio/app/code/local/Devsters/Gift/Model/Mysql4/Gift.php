<?php
class Devsters_Gift_Model_Mysql4_Gift extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('gift/gift', 'gift_card_id'); 
        
    }
}