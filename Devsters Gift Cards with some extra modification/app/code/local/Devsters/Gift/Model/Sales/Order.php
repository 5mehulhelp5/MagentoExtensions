<?php

class Devsters_Gift_Sales_Model_Order extends Mage_Sales_Model_Order
{
    public function getDevstersGiftNumber()
	{
		$order = $this->getOrder();		
		$devstersGiftNumber= $order->getGiftCardNumber();
		return $devstersGiftNumber;
	} 
}