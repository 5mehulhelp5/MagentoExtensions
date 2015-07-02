<?php
/* @var $this Mage_Eav_Model_Entity_Setup */

// Add an extra column to the catalog_eav_attribute-table:
$this->getConnection()->addColumn(
    $this->getTable('catalog/eav_attribute'),
    'description',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'Description'
    )
);
/*$this->getConnection()->addColumn(
    $this->getTable('eav/attribute'),
    'description',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'Description'
    )
);*/