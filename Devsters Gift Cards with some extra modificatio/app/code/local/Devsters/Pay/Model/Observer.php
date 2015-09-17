<?php

class Devsters_Pay_Model_Observer
{ 
  
    public function hookIntoCheckoutOnePageSuccessAction($observer)
    {  
      $orderId = $observer->getEvent()->getOrderIds();
      $order = Mage::getSingleton('sales/order')->load($orderId);      
      $paymentcode = $order->getPayment()->getMethod();
      if (($paymentcode == 'devstersgiftcard')||($order->getGiftCardNo()))
      {
 
       try{   
              if ($paymentcode == 'devstersgiftcard')
              {
              $gcBalance = number_format(($order->getGiftCardValue()- ($order->getSubtotalInclTax()+$order->getShippingInclTax()+$order->getBaseDiscountAmount())),2);
              if ($gcBalance <0 ){ $gcBalance = 0; }
              }else 
              {
               $gcBalance = 0;
              }       
              $gcNumber = $order->getGiftCardNo();
	      $dbWrite= Mage::getSingleton('core/resource')->getConnection('core_write');
	      $gcSQL= "UPDATE ".Mage::getConfig()->getTablePrefix()."devsters_gift_cards SET ".Mage::getConfig()->getTablePrefix()."devsters_gift_cards.gift_card_balance = " .$gcBalance. " WHERE ".Mage::getConfig()->getTablePrefix()."devsters_gift_cards.gift_card_number = '".$gcNumber."'";
              $dbWrite->query($gcSQL);
           } catch (Exception $e){
    		Mage::throwException($e->getMessage());
    	        exit; 
	   } 		
           Mage::log("Gift Card Number: ".$gcNumber." Has a new Balance of: ".$gcBalance);
           return $this;
    }
  }
 
  public function updatePaypalTotal($observer){
		$cart = $observer->getPaypalCart(); 
		$salesEntity = $cart->getSalesEntity();
		$cart->updateTotal(Mage_Paypal_Model_Cart::TOTAL_DISCOUNT,$salesEntity->getGiftCardValue());
		Mage::log("Gift Card Total sent to PayPal: ".$salesEntity->getGiftCardValue());
	}

 
}

    
