<?php

class Devsters_Pay_Adminhtml_Block_Sales_Order_Totals extends Mage_Adminhtml_Block_Sales_Order_Totals_Item
{
    public function initTotals() {
        $order = $this->getParentBlock()->getOrder();
        $amount = number_format($this->getOrder()->getGiftCardValue(),2);
        if ($amount != 0) {
            $this->getParentBlock()->addTotal(new Varien_Object(array(
                'code' => 'devstersgiftcard',
                'value' => -$amount,
                'base_value' => -$amount,
                'label' => $this->helper('pay')->__('Devsters Gift Card Purchase'),
                    )), 'shipping');
        }
    }

}
