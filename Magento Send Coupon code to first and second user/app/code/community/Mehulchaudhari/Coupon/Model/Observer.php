<?php
class Mehulchaudhari_Coupon_Model_Observer
{

			public function sendEmail(Varien_Event_Observer $observer)
			{
               $order = $observer->getEvent()->getOrder();
			   $customerid = $order->getCustomerId();
			   $customeremail = $order->getCustomerEmail();
			   if($customerid != '' || $customerid != null){
			            $orders = Mage::getResourceModel('sales/order_collection')->addFieldToSelect('*')->addFieldToFilter('customer_id',$customerid);
			   }else{
			            $orders = Mage::getResourceModel('sales/order_collection')->addFieldToSelect('*')->addFieldToFilter('customer_email',$customeremail);   
			   }
			   
			   $count = $orders->count();
			   switch($count){
			        
					case 1:
					    $this->sendFirstEmail($order);
					break;
					
					case 2:
					    $this->sendSecondEmail($order);
					break;
			         
			   }
			   
			}
			
			
			public function sendFirstEmail($order){
			    $email = $order->getCustomerEmail();
			    $name = $order->getCustomerName();
				$postObject = array('coupon_code'=>Mage::getStoreConfig('coupon/settings/firstusercode'),'customer_name'=>$name);
				$mailTemplate = Mage::getModel('core/email_template');
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->sendTransactional(
                        Mage::getStoreConfig('coupon/settings/firstuseremail'),
                        Mage::getStoreConfig('coupon/settings/sender_email_identity'),
                        $email,
                        $name,
                        array('data' => $postObject,'order' => $order)
                    );

                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }

                $translate->setTranslateInline(true);
				
			}
			
			public function sendSecondEmail($order){
			   $email = $order->getCustomerEmail();
			   $name = $order->getCustomerName();
			   $postObject = array('coupon_code'=>Mage::getStoreConfig('coupon/settings/secondusercode'),'customer_name'=>$name);
			   $mailTemplate = Mage::getModel('core/email_template');
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->sendTransactional(
                        Mage::getStoreConfig('coupon/settings/seconduseremail'),
                        Mage::getStoreConfig('coupon/settings/sender_email_identity'),
                        $email,
                        $name,
                        array('data' => $postObject,'order' => $order)
                    );

                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }

                $translate->setTranslateInline(true);
			}
		
}
