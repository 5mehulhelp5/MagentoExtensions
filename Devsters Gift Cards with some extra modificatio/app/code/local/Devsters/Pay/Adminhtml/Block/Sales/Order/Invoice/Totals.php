<?php

class Devsters_Pay_Adminhtml_Block_Sales_Order_Invoice_Totals extends Mage_Adminhtml_Block_Sales_Order_Totals_Item
{
    /**
     * Initialize order totals array
     *
     * @return Mage_Sales_Block_Order_Totals
     */
    /*protected function _initTotals()
    {
        parent::_initTotals();
        $order = $this->getOrder();
        $amount =    number_format(($order->getGiftCardValue()),2);
        if ($amount) {
            $this->addTotalBefore(new Varien_Object(array(
                'code'      => 'devtersgiftcard',
                'value'     => $amount,
                'base_value'=> $amount,
                'label'     => $this->helper('pay')->__('Devsters Gift Card Purchase'),
            ), array('shipping', 'tax')));
        }

        return $this;
    }*/
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
