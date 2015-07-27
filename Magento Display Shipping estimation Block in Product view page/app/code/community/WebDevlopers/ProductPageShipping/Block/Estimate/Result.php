<?php

class WebDevlopers_ProductPageShipping_Block_Estimate_Result extends WebDevlopers_ProductPageShipping_Block_Estimate_Abstract
{
    
    public function getResult()
    {
        return $this->getEstimate()->getResult();
    }

   
    public function hasResult()
    {
        return $this->getResult() !== null;
    }

   
    public function getCarrierName($code)
    {
        $carrier = Mage::getSingleton('shipping/config')->getCarrierInstance($code);
        if ($carrier) {
            return $carrier->getConfigData('title');
        }

        return null;
    }

  
    public function getShippingPrice($price, $flag)
    {
        return $this->formatPrice(
            $this->helper('tax')->getShippingPrice(
                $price,
                $flag,
                $this->getEstimate()
                    ->getQuote()
                    ->getShippingAddress()
           )
        );
    }

    
    public function formatPrice($price)
    {
        return $this->getEstimate()
            ->getQuote()
            ->getStore()
            ->convertPrice($price, true);
    }
}
