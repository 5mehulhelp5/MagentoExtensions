<?php

class Devsters_Gift_Model_Gift extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('gift/gift'); // this is location of the resource file.
    }
}