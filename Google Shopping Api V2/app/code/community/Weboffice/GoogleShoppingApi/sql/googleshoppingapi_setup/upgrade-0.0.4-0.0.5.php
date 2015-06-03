<?php
$installer = $this;
$installer->startSetup();

/** @var Magento_Db_Adapter_Pdo_Mysql $connection */
$connection = $installer->getConnection();

$table = $connection->newTable($this->getTable('googleshoppingapi/taxonomies'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true
    ), 'ID')
    ->addColumn('lang_idx', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false
    ), 'Internal Language Index')
    ->addColumn('lang', Varien_Db_Ddl_Table::TYPE_TEXT, 5, array(
        'nullable' => false,
        'default'  => ''
    ), 'Language')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
        'default'  => ''
    ), 'Name')
    ->addIndex(
        $installer->getIdxName(
            'googleshoppingapi/types',
            array('lang_idx', 'lang'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('lang_idx', 'lang'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->setComment('Google Taxonomies');
$installer->getConnection()->createTable($table);

$installer->endSetup();

