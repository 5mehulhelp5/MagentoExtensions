<?php
$installer = $this;
/* @var $installer Mage_Customer_Model_Entity_Setup */

$installer->startSetup();
$installer->run("

ALTER TABLE `{$installer->getTable('sales/quote_payment')}` ADD `gift_card_no` VARCHAR( 50 ) NOT NULL ;
ALTER TABLE `{$installer->getTable('sales/quote_payment')}` ADD `gift_card_value` decimal(5,2) NOT NULL ;

ALTER TABLE `{$installer->getTable('sales/quote')}` ADD `gift_card_no` VARCHAR( 50 ) NOT NULL ;
ALTER TABLE `{$installer->getTable('sales/quote')}` ADD `gift_card_value` decimal(5,2) NOT NULL ;

ALTER TABLE `{$installer->getTable('sales/order_payment')}` ADD `gift_card_no` VARCHAR( 50 ) NOT NULL ;
ALTER TABLE `{$installer->getTable('sales/order_payment')}` ADD `gift_card_value` decimal(5,2) NOT NULL ;

ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `gift_card_value` DECIMAL( 5, 2 ) NOT NULL;
ALTER TABLE  `".$this->getTable('sales/quote_address')."` ADD  `gift_card_value` DECIMAL( 5, 2 ) NOT NULL;
ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `gift_card_no` VARCHAR( 50 ) NOT NULL;
ALTER TABLE  `".$this->getTable('sales/quote_address')."` ADD  `gift_card_no` VARCHAR( 50 ) NOT NULL;
ALTER TABLE  `".$this->getTable('sales/invoice')."` ADD  `gift_card_value` DECIMAL( 5, 2 ) NOT NULL;
ALTER TABLE  `".$this->getTable('sales/invoice')."` ADD  `gift_card_no` VARCHAR( 50 ) NOT NULL;
ALTER TABLE  `".$this->getTable('sales/creditmemo')."` ADD  `gift_card_value` DECIMAL( 5, 2 ) NOT NULL;
ALTER TABLE  `".$this->getTable('sales/creditmemo')."` ADD  `gift_card_no` VARCHAR( 50 ) NOT NULL;

");
$installer->endSetup();
