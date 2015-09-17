<?php

class Devsters_Pay_Model_Order_Invoice extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    /*public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $order = $invoice->getOrder();        
        $amount =    number_format($order->getGiftCardValue(),2);
        
        if ($amount) {
      		$order->setBaseTotalInvoiced(number_format($order->getBaseTotalPaid(),2));
      		$order->save();
            	Mage::log("Amount after Invoice created: ".$invoice->getTotalPaid());        
        }
        return $this;
    }*/
    
   public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $order = $invoice->getOrder();
        $amount =    number_format($order->getGiftCardValue(),2);
        $invoice->setGrandTotal($invoice->getGrandTotal() - $amount);
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() - $amount);
        return $this;
    }
}
