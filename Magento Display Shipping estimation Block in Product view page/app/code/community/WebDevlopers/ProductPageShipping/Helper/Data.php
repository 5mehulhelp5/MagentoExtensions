<?php

class WebDevlopers_ProductPageShipping_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getTitle(){
	   return Mage::getStoreConfig('webdevlopers_productpageshipping/message/blocktitle');
	}

	public function getDes(){
	   return Mage::getStoreConfig('webdevlopers_productpageshipping/message/blockdec');
	}
	
	public function getShiptitle(){
	   return Mage::getStoreConfig('webdevlopers_productpageshipping/message/shippingblocktitle');
	}
	
	public function getButton(){
	   return Mage::getStoreConfig('webdevlopers_productpageshipping/message/button');
	}
	
	public function getResult(){
	   return Mage::getStoreConfig('webdevlopers_productpageshipping/message/result');
	}

}
