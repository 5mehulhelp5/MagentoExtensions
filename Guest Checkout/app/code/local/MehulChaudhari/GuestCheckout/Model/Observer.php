<?php
class MehulChaudhari_GuestCheckout_Model_Observer
{

			public function checkLogin(Varien_Event_Observer $observer)
			{
				$enable = (boolean)Mage::getStoreConfig('guestcheckout/settings/enable');
				$msg = (string)Mage::getStoreConfig('guestcheckout/settings/msg');
				if($enable){
				        if(!Mage::getSingleton('customer/session')->isLoggedIn()){
						            $returnUrl = Mage::helper('customer')->getLoginUrl();
									Mage::getSingleton('core/session')->addNotice($msg);
						            Mage::app()->getResponse()->setRedirect($returnUrl)->sendResponse();
									exit;
						}
				}
				
			}
		
}
