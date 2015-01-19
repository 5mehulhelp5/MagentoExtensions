<?php

/*
	Idealo, Export-Modul

	(c) Idealo 2013,
	
	Please note that this extension is provided as is and without any warranty. It is recommended to always backup your installation prior to use. Use at your own risk.
	
	Extended by
	
	Christoph Zurek (Idealo Internet GmbH, http://www.idealo.de)
*/



	
 
include_once  'idealo_universal.php';

if(file_exists(Mage::getModuleDir('Model', 'Idealo_Csvexport').'/definitions/definition.php')){
    include_once Mage::getModuleDir('Model', 'Idealo_Csvexport').'/definitions/definition.php';  
}

if(file_exists(Mage::getModuleDir('Model', 'Idealo_Realtime').'/definitions/definition.php')){
    include_once Mage::getModuleDir('Model', 'Idealo_Realtime').'/definitions/definition.php';
}
 
class Idealo_Universal_Tools_IdealoTools extends idealo_universal{
	
		
	public $payment = array();
	
	public $shipping = array();
		
	public $portocomment;
	
	public $compaign;	
	
	public $manufacturer = '';
	
	public $del = '';
	public $free_brand_de = '';
	public $free_cat_de = '';
	public $free_brand_at = '';
	public $free_cat_at = '';
	public $free_brand_uk = '';
	public $free_cat_uk = '';
	public $baseprice = array(	'G' 	=> array('KG' 	=> 1000),
								'KG' 	=> array('G' 	=> 0.001),
								'L' 	=> array('ML' 	=> 1000),
								'ML' 	=> array('L' 	=> 0.001),
								'M' 	=> array('CM' 	=> 100),
								'CM' 	=> array('M' 	=> 0.01),
							 );				
	
	
	
	 public function getShopUrl($webside, $use_store, $export_shop){
	 	
	 	if ($webside == Null){
			$webside_url = Mage::getUrl();return $this->cleanUrl($webside_url);
		}
	 	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');	
	 	
	 	$select = "SELECT * FROM `" . TABLE_PREFIX . "core_config_data` WHERE `scope_id` = " . $webside . " and `scope` LIKE 'websites' and `path` LIKE 'web/unsecure/base_url';";
		$webside_url = $connection->fetchAll($select); 
		
		$webside_url = $webside_url[0]['value'];
		
		$use_url = '';
		if($use_store == '1'){	
			$select = '	SELECT
							`code`
						FROM
							`core_store`
						WHERE
							`store_id` = ' . $export_shop . '
						;';
						
			$use_url = $connection->fetchAll($select); 
			$use_url =  $use_url [0] [ 'code' ]  . '/';	
		}
		
		if($webside_url == ''){
			$webside_url = Mage::getUrl();
		}
		
		$webside_url = $webside_url . $use_url;
		
		return $this->cleanUrl( $webside_url );				
	 }
	
	
	
	 public function getProductUrl ( $product, $webside_url, $store_webside, $storeCode, $website_id, $category ){

 		$useStoreCode = $this->getValue('web/url/use_store');
 		
 		if($useStoreCode == '0'){
 			$storeCode = '';
 		}
 		
 		$useCats = $this->getValue('catalog/seo/product_use_categories');
 		$urlPath=$product->getUrlPath();

		if(empty($urlPath)){
			$urlPath = $product->getUrlKey();
		} 
		
		if($store_webside != 'default'){
			if($useCats == '0'){
				return $webside_url .$storeCode . $urlPath;
			}else{	
				return $webside_url . $storeCode . trim(Mage::getModel('catalog/product_url')->getUrlPath($product,$category),"/");
			}	
		}else{
			if($useCats == '0'){
				return Mage::getUrl() . $storeCode . $urlPath;
			}else{		
				return Mage::getBaseUrl() . $storeCode . trim(Mage::getModel('catalog/product_url')->getUrlPath($product,$category),"/");			
			}		
		}	 	
	 }
	
	
	public function cleanUrl($webside_url){
		$stringToReplace = array("admin/", "admin");
		$webside_url = str_replace($stringToReplace, "", $webside_url);

		return $webside_url;
	}
	
	
 	 public function getEmail(){
		if(!$generalEmail = Mage::getSingleton('core/config_data')->getCollection()->getItemByColumnValue('path', 'trans_email/ident_general/email')){
	        $conf = Mage::getSingleton( 'core/config' )->init()->getXpath( '/config/default/trans_email/ident_general/email' );
	        return array_shift($conf);
	    }else{
	        return $generalEmail->getValue();
	    }
 	 }	
	
	
	
	 public function getParentArray($id){
	 	$parentIdArray = Mage::getModel('catalog/product_type_grouped')->getParentIdsByChild($id);
			  	
	  	if(count($parentIdArray) == 0){
	  		$parentIdArray = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($id);
	  	}
	  	
	  	if(count($parentIdArray) == 0){
	  		$parentIdArray = Mage::getModel('bundle/product_type')->getParentIdsByChild($id);
	  	}
	  	
	  	return $parentIdArray;
	 }
	 
	 
	 public function getAttributesArray($product, $attribute_list){
		$attribute_array = array ();
		$spaceToReplace = array( "$", "." );
		
		if(count($attribute_list) > 0){
			foreach($attribute_list as $attr){
				if($attr != '' && $this->checkAttributeExists($attr)){
					$attribute_value = Mage::getModel('catalog/product')->load($product->getId())->getAttributeText($attr);

					if($attribute_value != ''){
						$attribute_name = str_replace($spaceToReplace, " ", $product->getResource()->getAttribute($attr)->getFrontendLabel());
						$attribute_array[] = array($attribute_name, $attribute_value);
					}
				}
			}
		}

		return $attribute_array;
	 }	 			 			 	
	 	 
	 	 
	 public function getAttributesValues($attribute_array){ 
	 	 $attributeName = '';
	 	if(count($attribute_array) > 0){
	 		foreach($attribute_array as $attr_array){
	 			if(is_array($attr_array[1])){
	 				if(!empty($attr_array[1])){
	 					$attributeName .= ', ' . $attr_array[0] . ': ';
	 					foreach($attr_array[1] as $arr){
	 						$attributeName .= $arr . ' ';
	 					}
	 				}	
				}else{
					$attributeName .= ', ' . $attr_array[0] . ': ' . $attr_array[1];
				}
		 	}
	 	}
	 	
	 	return $attributeName;
	 }
	 
	 
	 public function checkProduct($product){	 	
	 	$result = array();
	 	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
	 	
	 	$result['idealo_listing'] = 'yes';
	 	
	 	if($this->checkAttributeExists('idealo')){
			$result['idealo_listing'] = Mage::getModel('catalog/product')->load($product->getId())->getAttributeText('idealo');
		}
		$select = 'SELECT * FROM `' . TABLE_PREFIX . 'catalog_product_website` WHERE `product_id` = ' . $product->getId() . ';';
		$webside = $connection->fetchAll($select); 
	
		$result['webside'] = $webside[0]['website_id'];		
		$result['stock_status'] = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getIsInStock();
			
	 	return $result;
	 }
	
	
	
	public function getValue($path){
	 	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$select = "SELECT `value` FROM `" . TABLE_PREFIX . "core_config_data` WHERE `path` LIKE '" . $path . "';";
		$text = $connection->fetchAll($select); 
		
	 	return $text[0]['value']; 	
   }
	
	
	
	 public function getSuperAttributePricing($product_id, $price){
	 	$table_array = array ( 	'catalog_product_super_attribute_pricing', 
								'catalog_product_super_link', 
								'catalog_product_super_attribute', 
								'catalog_product_entity_int');
	 	
	 	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		foreach($table_array as $table){
			$sql = "SHOW tables LIKE '" . $table . "';";
			$tables = $connection->fetchAll($sql);
			
			if(count($tables) == 0){
				return $price;
			}
		}
		$select = "SELECT count(*) FROM `" . TABLE_PREFIX . "catalog_product_super_attribute_pricing`;";
		$value = $connection->fetchAll( $select );
		
		if((integer) $value[0]['count(*)'] == 0){
			return $price;
		}
		$sql = "SELECT `parent_id` FROM `" . TABLE_PREFIX . "catalog_product_super_link` WHERE `product_id` = " . $product_id. ";";
		$catalog_product_super_link_parent_id = $connection->fetchAll( $sql );
		if(!isset($catalog_product_super_link_parent_id[0]['parent_id'])){
			return $price;
		}		
		
		$sql = "SELECT
					cpsap.`pricing_value`,
					cpsap.`is_percent`
				FROM
					`" . TABLE_PREFIX . "catalog_product_super_attribute` cpsa,
					`" . TABLE_PREFIX . "catalog_product_entity_int` cpei,
					`" . TABLE_PREFIX . "catalog_product_super_attribute_pricing` cpsap
				WHERE
					cpei.`entity_id` = " . $product_id . " AND
					cpsa.`product_id` = " . $catalog_product_super_link_parent_id[0]['parent_id'] . " AND
					cpei.`value` = cpsap.`value_index` AND
					cpei.`attribute_id` = cpsa.`attribute_id` AND
					cpsa.`product_super_attribute_id` = cpsap.`product_super_attribute_id`
					;";
		
		$value = $connection->fetchAll($sql);

		if(count($value) > 0){
			foreach($value as $va){
				if(isset($va['pricing_value'])){
					if($va['is_percent'] == '1'){
						$price = $price * (1 + (float)$va['pricing_value'] / 100);
					}else{
						$price = $price + (float)$va['pricing_value'];
					}
				}	
			}
		}
		
		return $price;
	 }
	 
	 
	 
	public function getPrice($product, $product_id, $parent_id = ''){
		if($parent_id != ''){
			return number_format(Mage::helper('tax')->getPrice($product, $this->getSuperAttributePricing($product_id, $product->getFinalPrice()), true), 2, '.', '');
		}else{
			return number_format(Mage::helper('tax')->getPrice($product, $product->getFinalPrice(), true), 2, '.', '');
		}
	}
	
	
	
	public function getProductCount(){	
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');

		$select = "SELECT count(*) FROM `" . TABLE_PREFIX . "catalog_product_entity`;";
		$value = $connection->fetchAll($select); 
		
		return $value[0]['count(*)'];		
	}
	
	
	
	  public function checkFreeShipping($manufacturer, $category, $country){
	  	if($country == 'DE'){
	  		if(($manufacturer != '' && $this->free_brand_de != '' && strpos($manufacturer, $this->free_brand_de) !== false) 
	  			|| ($category != '' && $this->free_cat_de != '' && strpos($category, $this->free_cat_de) !== false)
	  		){	
	  			return true;
	  		}
	  	}
	  	
	  	if($country == 'AT'){
	  		if(( $manufacturer != '' && $this->free_brand_at != '' && strpos($manufacturer, $this->free_brand_at) !== false)
	  			|| ( $category != '' && $this->free_cat_at != '' && strpos($category, $this->free_cat_at) !== false)
	  		){	
	  			return true;
	  		}
	  	}
	  	
	  	if($country == 'UKT'){
	  		if(( $manufacturer != '' && $this->free_brand_uk != '' && strpos($manufacturer, $this->free_brand_uk) !== false)
	  			|| ( $category != '' && $this->free_cat_uk != '' && strpos($category, $this->free_cat_uk) !== false)
	  		){	
	  			return true;
	  		}
	  	}
	  	
	  	return false;
	  }
	
	 
	 
	 public function getAttributeType($attribute){
	 	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
	 	
		$select = "SELECT `frontend_input` FROM `" . TABLE_PREFIX . "eav_attribute` WHERE `attribute_code` LIKE '" . $attribute . "';";
		$type = $connection->fetchAll($select);
		
		return $type[0]['frontend_input'];
	 }
	 
	 
	 
	 public function checkAttributeExists($attribute){
	 	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$select = "SELECT `attribute_code` FROM `" . TABLE_PREFIX . "eav_attribute` WHERE `attribute_code` LIKE '" . $attribute . "';";
		$text = $connection->fetchAll($select); 
		
		if(count($text) > 0){
			return true;
		}else{
			return false;
		}
	 }	 
	 
	 
    
     public function setValue($path, $value){
 	   	try{
		 	$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
			$write = "UPDATE `core_config_data` SET `value` = '" . $value . "' WHERE `path` LIKE '" . $path . "';";
			$connection->query($write);
 	   	} catch ( Exception $e ){}	
     }
	
	
	 public function checkTableExists($table){
	 	$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
		$sql = "SHOW tables LIKE '" . $table . "';";
		$tables = $connection->fetchAll($sql);
		
		if(count($tables) > 0){
			return true;
		}
		
		return false;
	 }
	 
	 
	 
	 public function getShippingCosts($price, $weight, $ship){
	 	if($ship['free'] != ''){
	 		if((float)$price >= (float)$ship['free']){
	 			return 0;
	 		}
	 		
	 	}
	 	if($ship['type'] == 'hard'){
	 		return $ship ['price'];
	 	}
	 
	 	$costs = explode(';', $ship['price']);
	 	$value = '';
	 	
	 	if($ship['type'] == 'weight'){
	 		$value = $weight;
	 	}else{
	 		$value = $price;
	 	}
	 	for($i = 0; $i < count($costs); $i++){
	 		$co = explode(':', $costs[$i]);
	 		if((count($costs) - 1) == $i){	
	 			return $co [1];
	 		}
	 		if((float)$value <= (float)$co[0]){	
	 			return $co[1];
	 		}
	 	}
	 }
	
    
    
     public function getCategoryPath($cats, $rootcat){
     	$category = '';
     	$path = array();
     	foreach($cats as $category_id):
	        $newPath = explode ('/', Mage::getModel('catalog/category')->load($category_id)->getPath());

			if ($newPath[1] == $rootcat
				&& count ($path) < count ($newPath)
			){
				$path = $newPath;
			}	              
	    endforeach;

	    array_splice($path, 0, 2);

	    foreach($path as $catId){
	    	$category .= ' -> ' . $this->cleanText(Mage::getModel('catalog/category')->load($catId)->getName(), 50);		
	    }

	    return substr($category, 4);
     }
	 
	 
	 public function getDelivery($product){
	 	$delivery_text = '';
	 	if($this->del != '' && $this->checkAttributeExists($this->del)){
			if($this->getAttributeType($this->del) == 'text'){
				$var_name_del = 'get' . $this->del;
				$delivery_text = $product->$var_name_del();
			}elseif($this->getAttributeType($this->del) == 'select'){
				$delivery_text = Mage::getModel('catalog/product')->load($product->getId())->getAttributeText($this->del);
			}
		}
		if($delivery_text == ''){
			$delivery_text = 'Auf Lager';
		}
		
		return $delivery_text;
	 }
	 
	 
	
	 public function getBaseprice($product, $productPrice){
	 	if(!($productAmount = $product->getBasePriceAmount())) return '';
		if(!($referenceAmount = $product->getBasePriceBaseAmount()))return '';
		if(!is_numeric($productAmount) || !is_numeric($referenceAmount)) return '';
		
		$baseUnit = $product->getBasePriceBaseUnit();
		$unit = $product->getBasePriceUnit();
		$rate = '';
		
		if($unit == $baseUnit){	
			$rate = 1;
		}else{
			$rate = $this->baseprice[$unit][$baseUnit];
		}

		$basePrice = ($productPrice / $productAmount / $rate  * $referenceAmount);

		return array($baseUnit, $basePrice, $referenceAmount);	 	
	 }
	 
	 
	 
	 public function getCategoryInPath($use_store, $cats){
	 	if($use_store == '0'){
	 		return '';
	 	}
	 	
	 	$category = $this->getCategoryPath($cats);
	 }	
	
}
?>