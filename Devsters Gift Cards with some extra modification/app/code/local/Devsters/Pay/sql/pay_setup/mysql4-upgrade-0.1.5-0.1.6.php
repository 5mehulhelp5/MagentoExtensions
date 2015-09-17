<?php
$installer = $this;
/* @var $installer Mage_Customer_Model_Entity_Setup */

$installer->startSetup();
$installer->run("


ALTER TABLE `{$installer->getTable('sales/quote_payment')}` MODIFY `gift_card_value` decimal(5,2) NOT NULL ;


ALTER TABLE `{$installer->getTable('sales/quote')}` MODIFY `gift_card_value` decimal(5,2) NOT NULL ;


ALTER TABLE `{$installer->getTable('sales/order_payment')}` MODIFY `gift_card_value` decimal(5,2) NOT NULL ;

ALTER TABLE  `".$this->getTable('sales/order')."` MODIFY `gift_card_value` DECIMAL( 5, 2 ) NOT NULL;
ALTER TABLE  `".$this->getTable('sales/quote_address')."` MODIFY `gift_card_value` DECIMAL( 5, 2 ) NOT NULL;


ALTER TABLE  `".$this->getTable('sales/invoice')."` MODIFY `gift_card_value` DECIMAL( 5, 2 ) NOT NULL;

ALTER TABLE  `".$this->getTable('sales/creditmemo')."` MODIFY `gift_card_value` DECIMAL( 5, 2 ) NOT NULL;


");
$installer->endSetup();
