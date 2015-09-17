<?php
class Devsters_Pay_Model_Pdf_Total extends Mage_Sales_Model_Order_Pdf_Total_Default
{
    public function getTotalsForDisplay()
    {
        $amount = $this->getOrder()->formatPriceTxt($this->getAmount());
        if ($this->getAmountPrefix()) {
            $amount = $this->getAmountPrefix().$amount;
        }
        $label = Mage::helper('pay')->__($this->getTitle()) . ':';
        $fontSize = $this->getFontSize() ? $this->getFontSize() : 7;
        $total = array(
            'amount'    => $amount,
            'label'     => $label,
            'font_size' => $fontSize
        );
        return array($total);
    }
    
    /**
     * Get Total amount from source
     *
     * @return float
     */
    public function getAmount()
    {
        return -number_format($this->getOrder()->getGiftCardValue(),2);
    }
}
