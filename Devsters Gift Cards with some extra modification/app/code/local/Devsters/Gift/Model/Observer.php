<?php

class Devsters_Gift_Model_Observer
{

    
    function createCouponCode() {
    $chars = "ABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
    srand((double)microtime()*1000000);
    $i = 0;
    $code = '' ;
    while ($i <= 8)
    {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $code = $code . $tmp;
        $i++;
    }
    
    return $code;
    }
    
    public function hookIntoCatalogProductNewAction($observer)
    {
        $product = $observer->getEvent()->getProduct();
        //echo'Inside hookIntoCatalogProductNewAction observer...'; exit;
        //Implement the "catalog_product_new_action" hook
        return $this;    	
    }
    
    public function hookIntoCatalogProductEditAction($observer)
    {
        $product = $observer->getEvent()->getProduct();
        //echo'Inside hookIntoCatalogProductEditAction observer...'; exit;
        //Implement the "catalog_product_edit_action" hook
        return $this;    	
    }    
    
    public function hookIntoCatalogProductPrepareSave($observer)
    {
        $product = $observer->getEvent()->getProduct();
        $event = $observer->getEvent();
        //echo'Inside hookIntoCatalogProductPrepareSave observer...'; exit;
        //Implement the "catalog_product_prepare_save" hook
        return $this;    	
    }

    public function hookIntoSalesOrderItemSaveAfter($observer)
    {
        //$event = $observer->getEvent();
        //echo'Inside hookIntoSalesOrderItemSaveAfter observer...'; exit;
        //Implement the "sales_order_item_save_after" hook
        return $this;    	
    }

    public function hookIntoSalesOrderSaveBefore($observer)
    {
        //$event = $observer->getEvent();
        //echo'Inside hookIntoSalesOrderSaveBefore observer...'; exit;
        //Implement the "sales_order_save_before" hook
        return $this;    	
    }     
    
    public function hookIntoSalesOrderSaveAfter($observer)
    {
        $product = $observer->getEvent()->getProduct();
        $ordercomments = $observer->getEvent()->getOrder()->getComments();
        $orderNumber = $observer->getEvent()->getOrder()->getIncrementId();
        $order = $observer->getEvent()->getOrder();
        $ordersStatus = $order->getStatus();
        $orderItems = $order->getItemsCollection();

        if (($ordersStatus == 'gift_card_created')||($ordersStatus == 'complete')){
         	return $this;  //if gift_card_created gift card has been created or order is already complete hence do nothing
        }
        
	
        foreach ($orderItems as $item){
        
   	 $product_id = $item->product_id;
   	 $product_sku = $item->sku;
   	 $product_name = $item->getName();
   	 $product_price= $item->getPrice();
   	 $product_qty =  (int)$item->getQtyOrdered();
    	 $_product = Mage::getModel('catalog/product')->load($product_id);
    	 $product_type_id = $_product->getTypeId();
	 $cats = $_product->getCategoryIds();
    	 $category_id = $cats[0]; // just grab the first id
    	 $category = Mage::getModel('catalog/category')->load($category_id);
    	 $category_name = $category->getName();
     
   	 if($product_type_id == 'gift')
		{         
		         for ($i = 1; $i <= $product_qty; $i++) {
		         $giftcardnumber = $this->createCouponCode().'-'.$this->createCouponCode().'-'.$this->createCouponCode();
 			 $model = Mage::getModel('gift/gift');
			 $model->setGiftCardNumber($giftcardnumber); 
			 $model->setGiftCardValue($product_price); 
			 $model->setGiftCardBalance($product_price); 
			 $model->setOrderIncrementId($orderNumber); 
                         
                         try {
                                 
        			 $insertId = $model->save()->getId();
        			 $comment = 'Gift Card created with Number : '.$giftcardnumber;
       				 $state = 'processing';
                       		 $status = 'gift_card_created';
                       		 $isCustomerNotified = false;
                        	 $order->setState($state, $status, $comment, $isCustomerNotified);
                        	 $order->save();
			    } catch (Exception $e){
    				 echo $e->getMessage();
    				 exit; 
			    }
		       if ($order->getId()) {
            			try {
                			$translate  = Mage::getSingleton('core/translate');
                			$email = Mage::getModel('core/email_template');
                			$collection =  Mage::getResourceSingleton('core/email_template_collection');
					foreach($collection as $value)
					{
   						 If ($value->getTemplateCode() == "Order Update with Gift Card Number")
   						     $template = $value->getTemplateId();  
					} 
                			Mage::log('About to Send Email : '.$template,null,'events.log');

                			$sender  = array('name' => Mage::getStoreConfig('trans_email/ident_support/name', Mage::app()->getStore()->getId()),'email' => Mage::getStoreConfig('trans_email/ident_support/email', Mage::app()->getStore()->getId()));

               			        Mage::log($sender,null,'events.log');

                			$customerName = $order->getShippingAddress()->getFirstname() . " " . $order->getShippingAddress()->getLastname();
                			$customerEmail = $order->getPayment()->getOrder()->getCustomerEmail();

                		    	$vars = Array( 'order' => $order,
                 		    	               'devstersGiftCardNumber' => $giftcardnumber  );
					Mage::log('About to Pass giftcardnumber : '. $giftcardnumber,null,'events.log');
					
			                $storeId = Mage::app()->getStore()->getId(); 

                			$translate  = Mage::getSingleton('core/translate');
               			 	Mage::getModel('core/email_template')->sendTransactional($template, $sender, $customerEmail, $customerName, $vars, $storeId);
               			 	$translate->setTranslateInline(true);
                			Mage::log('Order successfully sent',null,'events.log');
            				} catch (Exception $e) {
                			Mage::log($e->getMessage(),null,'events.log');
            			}
        			} else {
            			Mage::log('Order not found',null,'events.log');
        			}  
                		
                        }
        	} 
	}    
        //Implement the "sales_order_save_after" hook
         
        return $this;    	
    } 

    public function hookIntoCatalogProductDeleteBefore($observer)
    {
        $product  = $observer->getEvent()->getProduct();
        //echo'Inside hookIntoCatalogProductDeleteBefore observer...'; exit;
        //Implement the "catalog_product_delete_before" hook
        return $this;    	
    }    
    
    public function hookIntoCatalogruleBeforeApply($observer)
    {
        //$event = $observer->getEvent();
        //echo'Inside hookIntoCatalogruleBeforeApply observer...'; exit;
        //Implement the "catalogrule_before_apply" hook
        return $this;    	
    }  

    public function hookIntoCatalogruleAfterApply($observer)
    {
        //$event = $observer->getEvent();
        //echo'Inside hookIntoCatalogruleAfterApply observer...'; exit;
        //Implement the "catalogrule_after_apply" hook
        return $this;    	
    }    
    
    public function hookIntoCatalogProductSaveAfter($observer)
    {
        $product = $observer->getEvent()->getProduct();
        $event = $observer->getEvent();
        //echo'Inside hookIntoCatalogProductSaveAfter observer...'; exit;
        //Implement the "catalog_product_save_after" hook
        return $this;    	
    }   
	
    public function hookIntoCatalogProductStatusUpdate($observer)
    {
        $product = $observer->getEvent()->getProduct();
        $event = $observer->getEvent();
        //echo'Inside hookIntoCatalogProductStatusUpdate observer...'; exit;
        //Implement the "catalog_product_status_update" hook
        return $this;    	
    }

    public function hookIntoCatalogEntityAttributeSaveAfter($observer)
    {
        //$event = $observer->getEvent();
        
        //Implement the "catalog_entity_attribute_save_after" hook
        return $this;    	
    }
    
    public function hookIntoCatalogProductDeleteAfterDone($observer)
    {
        $product = $observer->getEvent()->getProduct();
        $event = $observer->getEvent();
        //echo'Inside hookIntoCatalogProductDeleteAfterDone observer...'; exit;
        //Implement the "catalog_product_delete_after_done" hook
        return $this;    	
    }
    
    public function hookIntoCustomerLogin($observer)
    {
        $event = $observer->getEvent();
        //echo'Inside hookIntoCustomerLogin observer...'; exit;
        //Implement the "customer_login" hook
        return $this;    	
    }       

    public function hookIntoCustomerLogout($observer)
    {
        $event = $observer->getEvent();
        //echo'Inside hookIntoCustomerLogout observer...'; exit;
        //Implement the "customer_logout" hook
        return $this;    	
    }

    public function hookIntoSalesQuoteSaveAfter($observer)
    {
        $event = $observer->getEvent();
        //echo'Inside hookIntoSalesQuoteSaveAfter observer...'; exit;
        //Implement the "sales_quote_save_after" hook
        return $this;    	
    }

    public function hookIntoCatalogProductCollectionLoadAfter($observer)
    {
        $event = $observer->getEvent();
        //echo'Inside hookIntoCatalogProductCollectionLoadAfter observer...'; exit;
        //Implement the "catalog_product_collection_load_after" hook
        return $this;    	
    }
    
}