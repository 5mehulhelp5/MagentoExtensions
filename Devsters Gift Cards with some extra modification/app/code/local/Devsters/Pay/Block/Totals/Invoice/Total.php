<?php
class Devsters_Pay_Block_Totals_Invoice_Total extends Magestore_RewardPoints_Block_Template
{
     public function initTotals() {
        $order = $this->getParentBlock()->getOrder();
        $amount = number_format($order->getGiftCardValue(),2);
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
