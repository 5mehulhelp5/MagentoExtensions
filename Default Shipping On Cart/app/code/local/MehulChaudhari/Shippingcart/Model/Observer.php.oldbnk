<?php
class MehulChaudhari_Shippingcart_Model_Observer
{

			public function Applyshipping(Varien_Event_Observer $observer)
			{
				if (Mage::getStoreConfig('shipping/origin/shippingmethod') == '')
					return $this;
		        
				$quote = $observer->getEvent()->getQuote();
				$shippingAddress = $quote->getShippingAddress();
				$billingAddress = $quote->getBillingAddress();
				$saveQuote = false;
				if (!$shippingAddress->getCountryId()) {
					$country = Mage::getStoreConfig('shipping/origin/country_id');
					$state = Mage::getStoreConfig('shipping/origin/region_id');
					$postcode = Mage::getStoreConfig('shipping/origin/postcode');
					$method = Mage::getStoreConfig('shipping/origin/shippingmethod');
					
					$shippingAddress
						->setCountryId($country)
						->setShippingMethod($method)
						->setCollectShippingRates(true);
					$shippingAddress->save();
					$saveQuote = true;
				}
				if ($saveQuote)
					$quote->save();
				return $this;
			}
		
}
