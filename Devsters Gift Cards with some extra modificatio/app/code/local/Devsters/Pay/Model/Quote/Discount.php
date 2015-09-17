<?php
class Devsters_Pay_Model_Quote_Discount extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    protected $_totaldiscountpay; 
    protected $_rulesItemTotalspay = array();

    public function __construct()
    {
        $this->setCode('devstersgiftcard');
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return Mage::helper('pay')->__('Devsters Gift Card');
    }

    /**
     * Collect totals information about insurance
     *
     * @param   Mage_Sales_Model_Quote_Address $address
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);
        if (($address->getAddressType() == 'billing')) {
            return $this;
        }
        
        $amount =   number_format($address->getQuote()->getGiftCardValue(),2);

        $this->_totaldiscountpay = $amount;
		
	$items = $this->_getAddressItems($address);
		if (!count($items)) {
		    return $this;
        }
        $this->initTotals($items, $address);
      
         foreach ($items as $item) {
      	   if ($item->getParentItemId()) {
                        continue;
           }
      	   if ($item->getHasChildren() && $item->isChildrenCalculated()) {
                    foreach ($item->getChildren() as $child) {
                         $this->process($child);
                         $this->_aggregateItemDiscount($child);
                    }
           } else {
                     $this->process($item);
                     $this->_aggregateItemDiscount($item);
           }
		     
	}
        $address->setBaseGrandTotal($address->getBaseGrandTotal() - $amount);
        $address->setGrandTotal($address->getGrandTotal() - $amount);
		return $this;
    }
    
    

    
   /**
     * Aggregate item discount information to address data and related properties
     *
     * @param   $amount
     * @return  Mage_SalesRule_Model_Quote_Discount
     */
    protected function _aggregateItemDiscount($item)
    {
        $this->_addAmount(-$item->getDiscountAmount());
        $this->_addBaseAmount(-$item->getBaseDiscountAmount());
        return $this;
    }
    
    public function process(Mage_Sales_Model_Quote_Item_Abstract $item)
    {
    	  $item->setDiscountAmount(0);
        $item->setBaseDiscountAmount(0);
        $item->setDiscountPercent(0);
        $quote      = $item->getQuote();
        $address    = $this->_getAddress($item);
        
        $qty = $item->getTotalQty();
        $itemPrice              = $this->_getItemPrice($item);
        $baseItemPrice          = $this->_getItemBasePrice($item);
        $itemOriginalPrice      = $this->_getItemOriginalPrice($item);
        $baseItemOriginalPrice  = $this->_getItemBaseOriginalPrice($item);

        if ($itemPrice < 0) {
            return $this;
        }
             
              if ($this->_totaldiscountpay > 0) {
                    if ($this->_rulesItemTotalspay['giftcardpay']['items_count'] <= 1) {
                            $quoteAmount = $quote->getStore()->convertPrice($this->_totaldiscountpay);
                            $baseDiscountAmount = min($baseItemPrice * $qty, $this->_totaldiscountpay);
                    } else {
                    	       
                            $discountRate = $baseItemPrice * $qty /
                                          $this->_rulesItemTotalspay['giftcardpay']['base_items_price'];
                            $maximumItemDiscount = $this->_totaldiscountpay * $discountRate;
                            $quoteAmount = $quote->getStore()->convertPrice($maximumItemDiscount);

                            $baseDiscountAmount = min($baseItemPrice * $qty, $maximumItemDiscount);
                            $this->_rulesItemTotalspay['giftcardpay']['items_count']--;
                    }

                        $discountAmount = min($itemPrice * $qty, $quoteAmount);
                        $discountAmount = $quote->getStore()->roundPrice($discountAmount);
                        $baseDiscountAmount = $quote->getStore()->roundPrice($baseDiscountAmount);

                        //get discount for original price
                        $originalDiscountAmount = min($itemOriginalPrice * $qty, $quoteAmount);
                        $baseOriginalDiscountAmount = $quote->getStore()->roundPrice($baseItemOriginalPrice);
                        $this->_totaldiscountpay -= $baseDiscountAmount;
           }    	        
    	        
    	    $itemDiscountAmount = $item->getDiscountAmount();
            $itemBaseDiscountAmount = $item->getBaseDiscountAmount();

            $discountAmount     = min($itemDiscountAmount + $discountAmount, $itemPrice * $qty);
            $baseDiscountAmount = min($itemBaseDiscountAmount + $baseDiscountAmount, $baseItemPrice * $qty);
            
            $item->getGiftCardEarn($discountAmount);
            $item->getGiftCardBaseEarn($discountAmount);

            $baseTaxableAmount = $item->getBaseTaxableAmount();
            $taxableAmount = $item->getTaxableAmount();

            $item->setBaseTaxableAmount(max(0, $baseTaxableAmount - $discountAmount));
            $item->setTaxableAmount(max(0, $taxableAmount - $discountAmount));

    	    return $this;  
    }
    
    
    public function initTotals($items, Mage_Sales_Model_Quote_Address $address)
    {
        if (!$items) {
            return $this;
        }
        $ruleTotalItemsPrice = 0;
        $ruleTotalBaseItemsPrice = 0;
        $validItemsCount = 0;

                foreach ($items as $item) {
                    //Skipping child items to avoid double calculations
                    if ($item->getParentItemId()) {
                        continue;
                    }
                    $qty = $item->getTotalQty();
                    $ruleTotalItemsPrice += $this->_getItemPrice($item) * $qty;
                    $ruleTotalBaseItemsPrice += $this->_getItemBasePrice($item) * $qty;
                    $validItemsCount++;
                }

                $this->_rulesItemTotalspay['giftcardpay'] = array(
                    'items_price' => $ruleTotalItemsPrice,
                    'base_items_price' => $ruleTotalBaseItemsPrice,
                    'items_count' => $validItemsCount,
                );
       
        return $this;
    }

    protected function _getItemPrice($item)
    {
        $price = $item->getDiscountCalculationPrice();
        $calcPrice = $item->getCalculationPrice();
        return ($price !== null) ? $price : $calcPrice;
    }
    
    protected function _getItemBasePrice($item)
    {
        $price = $item->getDiscountCalculationPrice();
        return ($price !== null) ? $item->getBaseDiscountCalculationPrice() : $item->getBaseCalculationPrice();
    }

    protected function _getItemOriginalPrice($item)
    {
        return Mage::helper('tax')->getPrice($item, $item->getOriginalPrice(), true);
    }
    
    protected function _getItemBaseOriginalPrice($item)
    {
        return Mage::helper('tax')->getPrice($item, $item->getBaseOriginalPrice(), true);
    }

    /**
     * Add giftcard totals information to address object
     *
     * @param   Mage_Sales_Model_Quote_Address $address
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        if (($address->getAddressType() == 'billing')) {
            $amount =   number_format($address->getQuote()->getGiftCardValue(),2);
            if ($amount != 0) {
                $address->addTotal(array(
                    'code'  => $this->getCode(),
                    'title' => $this->getLabel(),
                    'value' => -$amount
                ));
            }
        }

        return $this;
    }
}
