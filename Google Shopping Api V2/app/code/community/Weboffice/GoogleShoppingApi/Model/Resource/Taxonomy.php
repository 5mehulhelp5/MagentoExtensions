<?php

class Weboffice_GoogleShoppingApi_Model_Resource_Taxonomy extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('googleshoppingapi/taxonomies', 'id');
    }
}
