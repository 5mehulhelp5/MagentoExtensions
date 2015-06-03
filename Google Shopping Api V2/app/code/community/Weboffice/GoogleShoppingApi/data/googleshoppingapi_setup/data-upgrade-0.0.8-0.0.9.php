<?php

$installer = $this;

/** @var Magento_Db_Adapter_Pdo_Mysql $connection */
$connection = $installer->getConnection();
$Datail = Mage::getModel('googleshoppingapi/attribute_source_googleShoppingCategories')->getDataDetail();
foreach ($Datail as $lang => $taxonomy) {
    $data =  array();
    foreach ($taxonomy as $i => $t) {
        $data[] =  array($i, $lang, $t);
    }

    $connection->insertArray(
        $this->getTable('googleshoppingapi/taxonomies'),
         array('lang_idx', 'lang', 'name'),
        $data
    );
}
