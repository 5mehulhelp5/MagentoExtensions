<?php
class MehulChaudhari_Popularity_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getEnable($store){
		         return (int)Mage::getStoreConfig('popularity/general/enable', $store);
	}

	public function getViews($store){
		      return (int)Mage::getStoreConfig('popularity/general/view', $store);
	}

	public function getRating($store){
		  return (int)Mage::getStoreConfig('popularity/general/rating', $store);
	}

	public function getSell($store){
		    return (int)Mage::getStoreConfig('popularity/general/sell', $store);
	}

	public function getDesign($store){
		  return Mage::getStoreConfig('popularity/general/design', $store);
	}
	public function getStep($store){
		  return (int)Mage::getStoreConfig('popularity/general/step', $store);
	}
	public function getCategoryEnable($store){
                  return (int)Mage::getStoreConfig('popularity/general/category_enable', $store);
	}
	public function getCompareEnable($store){
                  return (int)Mage::getStoreConfig('popularity/general/compare_enable', $store);
	}
	public function getFilterEnable($store){
                  return (int)Mage::getStoreConfig('popularity/general/review_filter', $store);
	}
	public function getFilterPercent($store){
                  return (int)Mage::getStoreConfig('popularity/general/percent', $store);
	}
}
	 
