<?php
class Mehulchaudhari_Coupon_Model_Observer
{

			public function sendEmail(Varien_Event_Observer $observer)
			{
               //$order = $observer->getEvent()->getOrder();
			   $shipment = $observer->getEvent()->getShipment();
			   $this->log('-------------------shipment-----------------');
			   $this->log($shipment->getData());
			   $order = $shipment->getOrder();
			   $this->log('-------------------order-----------------');
			   $this->log($order->getData());
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
			    $coupon_code = (string)Mage::getStoreConfig('coupon/settings/firstusercode');
			    if($coupon_code != '' || $coupon_code != null){
						$email = $order->getCustomerEmail();
						$name = $order->getCustomerName();
						$mailTemplate = Mage::getModel('core/email_template');
						$mailTemplate->setDesignConfig(array('area' => 'frontend'))
							->sendTransactional(
								Mage::getStoreConfig('coupon/settings/firstuseremail'),
								Mage::getStoreConfig('coupon/settings/sender_email_identity'),
								$email,
								$name,
								array('coupon_code' => $coupon_code,'order' => $order)
							);
						$this->log('-------------------first-----------------');
                        $this->log($mailTemplate->getData());
						if (!$mailTemplate->getSentSuccess()) {
							throw new Exception();
						}
				}		
				return true;
			}
			
			public function sendSecondEmail($order){
			    $coupon_code = (string)Mage::getStoreConfig('coupon/settings/secondusercode');
			    if($coupon_code != '' || $coupon_code != null){
				   $email = $order->getCustomerEmail();
				   $name = $order->getCustomerName();
				   $mailTemplate = Mage::getModel('core/email_template');
					$mailTemplate->setDesignConfig(array('area' => 'frontend'))
						->sendTransactional(
							Mage::getStoreConfig('coupon/settings/seconduseremail'),
							Mage::getStoreConfig('coupon/settings/sender_email_identity'),
							$email,
							$name,
							array('coupon_code' => $coupon_code,'order' => $order)
						);
                    $this->log($mailTemplate);
					if (!$mailTemplate->getSentSuccess()) {
						throw new Exception();
					}
				}	
				return true;
			}
			
			
			public function log($data){
			   Mage::log($data, null, 'coupon.log');
			   return true;
			}
		
}
