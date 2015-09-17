<?php
class Devsters_Pay_Model_Method_Pay extends Mage_Payment_Model_Method_Abstract
{
     	const PAYMENT_METHOD_DEVSTERSGIFTCARD_CODE = 'devstersgiftcard';

    /**
     * Payment method code
     *
     * @var string
     */
    	protected $_code = self::PAYMENT_METHOD_DEVSTERSGIFTCARD_CODE;
	protected $_formBlockType = 'pay/form_pay';
	protected $_infoBlockType = 'pay/info_pay';
     
     
	public function assignData($data)
	{
		if (!($data instanceof Varien_Object)) {
			$data = new Varien_Object($data);
		}
		$info = $this->getInfoInstance();
		$info->setGiftCardNo($data->getGiftCardNo());
		return $this;
	}

        
        public function assignGiftCardValueToQuote($gcNumber)
        {
        
           $errorMsg = 'Success!!';
           
        	   try {
			$dbRead= Mage::getSingleton('core/resource')->getConnection('core_read');
	                $gcSQL= "SELECT gift_card_number,gift_card_balance FROM ".Mage::getConfig()->getTablePrefix()."devsters_gift_cards WHERE gift_card_number = '".$gcNumber."'";
			$qryResult=$dbRead->query($gcSQL);
			$giftCard= $qryResult->fetchAll();
			} catch (Exception $e){
    				 Mage::throwException($e->getMessage());
    				 exit; 
		        } 		
			if(empty($giftCard[0])){
				$errorCode = 'invalid_data';
				$errorMsg = $this->_getHelper()->__('Gift Card Number does not exist: '.$gcNumber);
			}else {					           
		           $info = $this->getInfoInstance();
		           $info->setGiftCardValue(number_format(($giftCard[0]['gift_card_balance']),2));
		           $info->setGiftCardNo($gcNumber);
		           $quote = $info->getQuote();
		           if ($quote){
		           $info->getQuote()->setGiftCardValue(number_format(($giftCard[0]['gift_card_balance']),2));
		           $info->getQuote()->setGiftCardNo($gcNumber);
		           $info->getQuote()->save();	
		           }	           			
		        }
		           
			if($errorMsg!='Success!!'){
				Mage::throwException($errorMsg);
                        }
                  return $this;      
        }
        
        public function assignGiftCardValueToOrder($gcNumber)
        {
        
           $errorMsg = 'Success!!';
           
        	   try {
			$dbRead= Mage::getSingleton('core/resource')->getConnection('core_read');
	                $gcSQL= "SELECT gift_card_number,gift_card_balance FROM ".Mage::getConfig()->getTablePrefix()."devsters_gift_cards WHERE gift_card_number = '".$gcNumber."'";
			$qryResult=$dbRead->query($gcSQL);
			$giftCard= $qryResult->fetchAll();
			} catch (Exception $e){
    				 Mage::throwException($e->getMessage());
    				 exit; 
		        } 		
			if(empty($giftCard[0])){
				$errorCode = 'invalid_data';
				$errorMsg = $this->_getHelper()->__('Gift Card Number does not exist: '.$gcNumber);
			}else {					           
		           $order = Mage::getSingleton('sales/order');
		           $order->save();		           			
		        }
		           
			if($errorMsg!='Success!!'){
				Mage::throwException($errorMsg);
                        }
                  return $this;      
        }
        
	public function validate()
	{
		parent::validate();
                $errorMsg = 'Success!!';  
		$info = $this->getInfoInstance();
		$quote = $info->getQuote();
		$no = $info->getGiftCardNo();
		if(empty($quote)){
		   $quoteAfter = Mage::getSingleton('checkout/session')->getQuote()->getPayment();
		   $no = $quoteAfter->getGiftCardNo();
		   $this->assignGiftCardValueToOrder($no);
		   $gcValue = $quoteAfter->getGiftCardValue();
		   $value = Mage::getSingleton('checkout/session')->getQuote()->getGrandTotal();
		 }else if ($quote){
	           $this->assignGiftCardValueToQuote($no);
		   $gcValue = $quote->getGiftCardValue();
		   $value = $quote->getGrandTotal();
		}
		if(empty($no)||empty ($gcValue)){
			$errorCode = 'invalid_data';
			$errorMsg = $this->_getHelper()->__('Gift Card Number is a required field');
		} else {		     
		          if ($gcValue < $value)
		          { 		          
		             $errorCode = 'invalid_data';
		             $errorMsg = $this->_getHelper()->__('Gift Card funds: '.$gcValue. ' have been applied, You will need an additional mode of payment to cover for the remaining cost.');
		           }else if ($gcValue > $value)
		           {
		            $successMsg = $this->_getHelper()->__('Gift Card funds: '.$gcValue. ' have been applied to the total, Your new gift card balance will be '.($gcValue - $value) );

		           }
		   
		}			
                
		if($errorMsg!='Success!!'){
		         Mage::log($errorMsg);
		         Mage::getSingleton('core/session')->addSuccess($errorMsg);		      
		         Header('Location: '.$_SERVER['/checkout/onepage/']);
			 Exit(); 
		}else if ($successMsg)
		{
		 if ($value > 0)
		  {
		    Mage::log($successMsg);
		    Mage::getSingleton('core/session')->addSuccess($successMsg);
		  }
		  return $this;
		}

		
	}



}
?>
