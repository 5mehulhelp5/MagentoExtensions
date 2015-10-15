<?php
namespace Mehulchaudhari\Facebookconversion\Block;

class Facebookconversion extends \Magento\Catalog\Block\Product\AbstractProduct
{

   protected $_storeManager;
   protected $checkoutSession;
   protected $customerSession;

   public function __construct(
   \Magento\Catalog\Block\Product\Context $context,
   \Magento\Store\Model\StoreManagerInterface $storeManager,
   \Magento\Checkout\Model\Session $checkoutSession,
   \Magento\Customer\Model\Session $customerSession,
   array $data = []
  ) {
    $this->_storeManager = $storeManager;
    $this->checkoutSession = $checkoutSession;
    $this->customerSession = $customerSession;
    parent::__construct($context, $data);
  }

   public function getEnable($store = null){
      return (bool)$this->_scopeConfig->getValue('facebookconversion/general/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
   }
   
   public function getPixelId($store = null){
      return (string)$this->_scopeConfig->getValue('facebookconversion/general/pixelid', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
   }
   public function getCustom($store = null){
      return (bool)$this->_scopeConfig->getValue('facebookconversion/general/custom', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
   }
   
   public function getCurrencyCode(){
	return $this->_storeManager->getStore()->getCurrentCurrencyCode();
   }
   
   public function getProductData(){
         return $this->_coreRegistry->registry('current_product');
   }
   
   public function getCategoryData(){
        return $this->_coreRegistry->registry('current_category');
   }
  
   public function getOrderTotal()
   {
       $order = $this->checkoutSession->getLastRealOrder();
       return $order->getGrandTotal();
   }
   
}
