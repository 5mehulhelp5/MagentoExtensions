<?php

class Devsters_Pay_Model_Order_Creditmemo extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();
        $amount =    number_format($order->getGiftCardValue(),2);
        if ($amount) {
            if (($creditmemo->getGrandTotal() - $amount)<0){
                $creditmemo->setGrandTotal(number_format(0.00));
            	$creditmemo->setBaseGrandTotal(number_format(0.00));
             }else{
                $creditmemo->setGrandTotal($creditmemo->getGrandTotal() - $amount);
            	$creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() - $amount);
            }
        }

        return $this;
    }
}