<?php
namespace Mehulchaudhari\Houzzbutton\Block;
use Magento\Catalog\Api\CategoryRepositoryInterface;

class Houzzbutton extends \Magento\Catalog\Block\Product\AbstractProduct
{

   public function __construct(
   \Magento\Catalog\Block\Product\Context $context,
   array $data = []
  ) {
    parent::__construct($context, $data);
  }

   public function getEnable($store = null){
      return (bool)$this->_scopeConfig->getValue('houzzbutton/general/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
   }
   
   public function getName($store = null){
      return (string)$this->_scopeConfig->getValue('houzzbutton/general/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
   }
   public function getHzid($store = null){
      return (bool)$this->_scopeConfig->getValue('houzzbutton/general/hzid', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
   }
   public function getCount($store = null){
      return (bool)$this->_scopeConfig->getValue('houzzbutton/general/count', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
   }
   public function getProductData(){
         return $this->_coreRegistry->registry('current_product');
   }
   public function getCategoryName(){
          if($this->_coreRegistry->registry('current_category')){
               return $this->_coreRegistry->registry('current_category')->getName();
          }else{
               return null;
          }
   }
}
