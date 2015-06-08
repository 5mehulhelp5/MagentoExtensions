<?php
class MehulChaudhari_Shippingcart_Model_Observer
{

			public function Applyshipping(Varien_Event_Observer $observer)
			{
				
				if (Mage::getStoreConfig('shipping/origin/shippingmethod') != '')
					{
							$country = Mage::getStoreConfig('shipping/origin/country_id');
							$postcode = Mage::getStoreConfig('shipping/origin/postcode');
							$city = Mage::getStoreConfig('shipping/origin/city');
							$regionId = Mage::getStoreConfig('shipping/origin/region_id');
							$region = '';
							$code = Mage::getStoreConfig('shipping/origin/shippingmethod');
					
								try {
									if (!empty($code)) {
										$shippingAddress = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress();
										$groups = $shippingAddress->getGroupedAllShippingRates();
													   
										if(!$shippingAddress->getShippingMethod())
										{
											$shippingAddress
											->setCountryId($country)
											->setCity($city)
											->setPostcode($postcode)
											->setRegionId($regionId)
											->setRegion($region)
											->setShippingMethod($code)
											->setCollectShippingRates(true);
											Mage::getSingleton('checkout/session')->getQuote()->save();
											Mage::getSingleton('checkout/session')->getQuote()->getPayment()->setMethod('');
											Mage::getSingleton('checkout/session')->getQuote()->save();
										}
									} else {
										Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()
											->setCountryId($country)
											->setCity($city)
											->setPostcode($postcode)
											->setRegionId($regionId)
											->setRegion($region)
											->setCollectShippingRates(true);
											Mage::getSingleton('checkout/session')->getQuote()->save();
									}
					
									Mage::getSingleton('checkout/session')->resetCheckout();
							
								}
								catch (Mage_Core_Exception $e) {
									Mage::getSingleton('checkout/session')->addError($e->getMessage());
								}
								catch (Exception $e) {
									Mage::getSingleton('checkout/session')->addException(
										$e,
										Mage::helper('checkout')->__('Load customer quote error')
									);
								}
					
						  return $this;
					}
			}
		
}
