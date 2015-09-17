<?php

$installer = $this;
/* @var $installer Devsters_Gift_Mysql4_Setup */

$installer->startSetup();


$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('devsters_gift_cards')} (
  `gift_card_id` int(11) unsigned NOT NULL auto_increment,
  `gift_card_number`  varchar(50) NOT NULL,
  `gift_card_value`  decimal(5,2) NOT NULL,
  `gift_card_balance`  decimal(5,2) NOT NULL,
  `order_increment_id`  varchar(50) NOT NULL,
   PRIMARY KEY  (`gift_card_id`),
   UNIQUE KEY  (`gift_card_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$fieldList = array(
    'price',
    'tax_class_id'
);

// make these attributes applicable to downloadable products
foreach ($fieldList as $field) {
    $applyTo = split(',', $installer->getAttribute('catalog_product', $field, 'apply_to'));
    if (!in_array('gift', $applyTo)) {
        $applyTo[] = 'gift';
        $installer->updateAttribute('catalog_product', $field, 'apply_to', join(',', $applyTo));
    }
}

$installer->endSetup();