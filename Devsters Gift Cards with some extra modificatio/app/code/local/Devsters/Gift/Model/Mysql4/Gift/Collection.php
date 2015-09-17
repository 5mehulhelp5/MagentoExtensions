<?php

class Devsters_Gift_Model_Mysql4_Gift_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('gift/gift');
    }
}