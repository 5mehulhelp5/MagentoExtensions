<?php

/*
	Idealo, Export-Modul

	(c) Idealo 2013,
	
	Please note that this extension is provided as is and without any warranty. It is recommended to always backup your installation prior to use. Use at your own risk.
	
	Extended by
	
	Christoph Zurek (Idealo Internet GmbH, http://www.idealo.de)
*/









include_once  'IdealoPayment.php';
include_once  'IdealoShipping.php';
include_once  Mage::getModuleDir('Model', 'Idealo_Csvexport').'/definitions/definition.php';
include_once  'IdealoTools.php';

class Idealo_Csvexport_Tools_Idealo extends Idealo_Universal_Tools_IdealoTools
{
	
	public $filename;
		    
	public $seperator;
	
	public $quoting;
		
	public $begin_export;
	
	public $end_export;	
	
	public $part;		
	
	public function __construct($begin, $end, $part){	
		$this->begin_export = $begin;
		$this->end_export = $end;
		$this->part = $part;
		$payment = new Idealo_Universal_Tools_IdealoPayment('csvexport');	
	 	$this->payment = $payment->payment;
		$shipping = new Idealo_Universal_Tools_IdealoShipping('csvexport');
		$this->shipping = $shipping->shipping;
		
		$this->AllNeeded();
		$this->runExport();
	}
	
	
	
	 public function getCsvUrl(){
	 	$url = Mage::app()->getStore()->getBaseUrl();
		$url = substr($url, 0, -11);
		$url .= '/export/' . $this->filename;
	 	
	 	return $url;
	 }
	
	
	 public function getArticle(){
	 	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$select = 'SELECT `entity_id` FROM `' . TABLE_PREFIX . 'catalog_product_entity` LIMIT ' . $this->begin_export . ', ' . $this->end_export . ';';
		$article = $connection->fetchAll($select); 

		return $article;
	 }

	 
	
	 
	 
	public function runExport(){
		$schema ='';
		$use_store = $this->getValue('web/url/use_store');
		$export_shop = $this->getValue('csvexport/store/store');
	 	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');	 	
	 	
	 	$store_webside = 'default';
	 	
	 	if ( $export_shop != 'no_multi' ){
	 	    $select = $connection->select('website_id')
            	 	    ->from(TABLE_PREFIX . 'core_store_group')
            	 	    ->where('default_store_id = ' . $export_shop);

	 	    $store_webside = $connection->fetchAll($select); 
			$store_webside = $store_webside[0]['website_id'];
	 	}
	 	 
		$article = $this->getArticle();
		$attribute_list = explode(';', $this->getValue('csvexport/extra/extra_attributes'));
		if($store_webside=='default'){
			$website_id = '1';
		}else{
			$website_id = $store_webside;	
		}
		
		$shopUrl = $this->getShopUrl($website_id, $use_store, $export_shop);

		$storeCode = Mage::app()->getStore($website_id)->getCode() . '/';
		
		$rootcat = Mage::app()->getStore($website_id)->getRootCategoryId();
		
		foreach($article as $art){
		    $product = Mage::getModel('catalog/product')->setStoreId($website_id)->load($art['entity_id']);

			$webside = '';
			
			if($store_webside != 'default'){
				$select = 'SELECT * FROM `' . TABLE_PREFIX . 'catalog_product_website` WHERE `product_id` = ' . $art['entity_id'] . ';';
				$webside = $connection->fetchAll($select); 
				$webside = $webside[0]['website_id'];	
			}
			
			$stock_status = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getIsInStock();
			$idealo_listing = '';
			
			if($this->checkAttributeExists('idealo')){
				$idealo_listing = Mage::getModel('catalog/product')->load($product->getId())->getAttributeText('idealo');
			}
				
			$url = '';
			$categoryIds = $product->getCategoryIds();
			
			$category = '';
			
			if(!empty($categoryIds)){
				$category = Mage::getModel('catalog/category')->setStoreId($website_id)->load($categoryIds[0]);
				$url = $this->getProductUrl($product, $shopUrl, $store_webside, $storeCode, $website_id, $category);
			}
			$name = $product->getName();
			
			$product_parent = '';
			$price = $this->getPrice($product, $product->getEntityId());

			$parentIdArray = $this->getParentArray($product->getId());
				
			if(count($parentIdArray) > 0){													
				$product_parent = Mage::getModel('catalog/product')->setStoreId($website_id)->load($parentIdArray[0]);
				$price = $this->getPrice($product_parent, $product->getEntityId(), $parentIdArray[0]);
				if($price == 0){
					$price = $this->getPrice($product, $product->getEntityId());
				}
				
				if($name == ''){
					$name = $product_parent->getName();
				}
				
				$categoryIds = $product_parent->getCategoryIds();
			
				if(!empty($categoryIds)){
					$category = Mage::getModel('catalog/category')->setStoreId($website_id)->load($categoryIds[0]);
					$url = $this->getProductUrl($product_parent, $shopUrl, $store_webside, $storeCode, $website_id, $category);
				}
			}else{
				$price = $this->getPrice($product, $product->getEntityId());
			}
				
			if(!empty($categoryIds)){
				if(($webside == $store_webside || $store_webside == 'default') 
					&& $product->getStatus() == '1' 
					&& $stock_status == '1' 
					&& !$product->getTypeInstance(true)->hasOptions($product) 
					&& $product->getTypeId() == 'simple'
					&& $idealo_listing != 'no' 
					&& $category->getIsActive() != '0'
					&& $name != ''
				  ){        
					$category = $this->getCategoryPath($categoryIds, $rootcat);
						
					$replaceArray = array("index.php/", "admin/", "default-category/");
			        $url = str_replace($replaceArray, "", $url);
			
					if($this->compaign != 'no'){
						$url .= COMPAIGN;
					}
	
					$schema .= $this->quoting . $art['entity_id'] . $this->quoting . $this->seperator;
					$attribute_array = array();
					
					$spaceToReplace = array("$", ".");
					
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
				 	if(count($attribute_array) > 0){
				 		foreach($attribute_array as $attr_array){
					 		$name .= ', ' . $attr_array[0] . ': ' . $attr_array[1];
					 	}
				 	}		
				 	
					$schema .= 	$this->quoting . $this->checkSeparator($this->cleanText($name, 200), $this->seperator) . $this->quoting . $this->seperator;
					$manufacturer = '';
					
					if($this->manufacturer != '' && $this->checkAttributeExists($this->manufacturer)){
						$manufacturer = Mage::getModel('catalog/product')->load($product->getId())->getAttributeText($this->manufacturer);
					}
					
				 	$schema .=$this->quoting . $this->checkSeparator($manufacturer, $this->seperator) . $this->quoting . $this->seperator;			 	
					$schema .= $this->quoting . $this->checkSeparator($category, $this->seperator)  . $this->quoting . $this->seperator;
					$schema .= $this->quoting . $this->checkSeparator($this->cleanText($product->getShortDescription(), 100), $this->seperator) . $this->quoting . $this->seperator;
					$schema .= $this->quoting . $this->checkSeparator($this->cleanText($product->getDescription(), 1000), $this->seperator) . $this->quoting . $this->seperator;
					
					$images_separator = ';';
		            
		            if($this->seperator == ';'){
		            	$images_separator = '$';
		            }
					
		            $img = '';
		             
		            if($product->image != 'no_selection'){
		            	$img .= substr(Mage::app()->getStore()->getBaseUrl(), 0, -10) . 'media/catalog/product' . $product->image . $images_separator;
		            }
		            foreach($product->getMediaGalleryImages() as $image) {
	                    $img .= $image->getUrl() . $images_separator;
	                }  
	
					if($product_parent != ''){
						if ($product_parent->image != 'no_selection'){
			            	$img .=  substr(Mage::app()->getStore()->getBaseUrl() , 0, -10) . 'media/catalog/product' . $product_parent->image . $images_separator;
			            }
			            foreach($product_parent->getMediaGalleryImages() as $image) {
		                    $img .= $image->getUrl() . $images_separator;
		                }  
					}
						
					$img = substr($img, 0, -1);
					$img = str_replace("index.", "", $img);
					
					$schema .= $this->quoting . $this->checkSeparator($img, $this->seperator) . $this->quoting . $this->seperator;
					$ean = '';
	
					if($this->ean != ''){
						$var_name_ean = 'get' . $this->ean;
						$ean = $product->$var_name_ean();			
						
					}
					
					$schema .= $this->quoting . $url . $this->quoting . $this->seperator;
					
					$schema .= $this->quoting . $price . $this->quoting . $this->seperator.
							   $this->quoting . $ean . $this->quoting . $this->seperator .
							   $this->quoting . $product->getSku() . $this->quoting . $this->seperator .
							   $this->quoting . number_format($product->getWeight() , 3, '.', '') . $this->quoting . $this->seperator;

					foreach($this->shipping as $ship){
						if($ship['active'] == '1'){	
							$shipping = 0.00;
							if(!$this->checkFreeShipping($manufacturer, $category, $ship['title'])){
								$shipping = number_format($this->getShippingCosts($price, $product->getWeight(), $ship), 2, '.', '') ;
							}	
							foreach($this->payment as $payment){
								if($payment['active'] == '1' && strpos($payment['country'], $ship['title']) !== false){
									 $schema .= $this->quoting . $this->getPaymentCosts($payment, $ship['title'], $price, $shipping) . $this->quoting . $this->seperator;								
								}						
					       }					
						}
					}
						
					$portocoment = $this->portocomment;

			      	if($this->checkMinOrder($price)){
			      		$portocoment = Mage::helper('csvexport')->__('Minimum order value') . ' ' . $this->minOrder . ' ' . Mage::helper('csvexport')->__('currency');
			      	}

			      	if($this->minOrderPrice != ''){		     	
				     	if($this->checkMinExtraPrice($price)){
				     		$portocoment = $this->minOrderPrice . ' ' . Mage::helper('csvexport')->__('GBP minimum value surcharge below') . ' ' . $this->idealoMinorderBorder .  ' ' . Mage::helper('csvexport')->__('GBP order value');
				     	}
		      		}

					$schema .=  $this->quoting . $this->checkSeparator($portocoment, $this->seperator) . $this->quoting . $this->seperator;
					$baseprice = $this->getBaseprice($product, $price);
					
					if($baseprice == ''){
						$schema .= $this->quoting . '' . $this->quoting . $this->seperator;
					}else{
						$schema .= $this->quoting . number_format($baseprice[1], 2, '.', '') . ' ' . 
							  	   Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getName() .
							   	   ' / ' . $baseprice[2] . ' ' . $baseprice[0] . $this->quoting . $this->seperator;
					}
						
					$attr_separator = '$';
					if($attr_separator == $this->seperator){
						$attr_separator = '|';
					}
					
					$attributes = '';
						
					if(count($attribute_array) > 0){		
				 		foreach($attribute_array as $attr_array){ 	
				 			$attributes .= $attr_array[0] . ':' . $attr_array[1] . $attr_separator;
					 	}
					 		
					 	$attributes  = substr($attributes, 0, -1);
				 	}

					$schema .= $this->quoting . $this->cleanText($attributes, 200) . $this->quoting . $this->seperator .
							   $this->quoting . $this->getDelivery($product) . $this->quoting . $this->seperator;
					$m_number = '';
	
					if($this->manufacturer_number != ''){
						$var_name_em_number = 'get' . $this->manufacturer_number;
						$m_number = $product->$var_name_em_number();			
					}
					
					$schema .= $this->quoting . $this->checkSeparator($m_number, $this->seperator) . $this->quoting . $this->seperator;
					
					if($this->minOrderPrice != ''){
				     	if($this->checkMinExtraPrice($price)){
				     		$schema .= $this->quoting . number_format($this->minOrderPrice, 2, '.', '') . $this->quoting . $this->seperator;
				     	}else{
				     		$schema .= $this->quoting . '0.00' . $this->quoting . $this->seperator;
				     	}
				     }
	
					$schema .= "\n";
				}
			}
		}
		
		$this->saveSeperatorCheck($this->separatorArray,$this->separatorWarning,$this->separatorInt);
		$path = __FILE__;
		
		$path = substr($path, 0 , -48);
		$path = $path.'export/';
		
		$file = explode('.', $this->filename);
		
        $fp = fopen($path . $file[0] . '_' . $this->part . '.csv', "w+");
        fputs($fp, $schema);
        fclose($fp);
	}


	
    public function getPaymentCosts($payment, $country, $price, $shipping){
		$costs = $shipping;
		if($payment['exrtafee'] != ''){
			$costs = $costs + $payment['exrtafee'];
		}
		
		if($payment['percent'] != ''){
			if($payment['shipping_incl'] == '1'){
				$costs = $costs + (($price + $costs) * $payment['percent'] / 100);
			}else{
				$costs = $costs + ($price * $payment['percent'] / 100);
			}
		}
				
		return number_format($costs , 2, '.', '');
    }
	
	
	
	public function AllNeeded(){
		$this->filename = $this->getValue('csvexport/file/name');
		$this->seperator = $this->getValue('csvexport/file/seperator');
		$this->quoting = $this->getValue('csvexport/file/quoting');
		$this->portocomment = $this->getValue('csvexport/portocomment/value');
		
		if($this->getValue('csvexport/campaign/active') == '1.3.1'){
			$this->compaign = 'yes';
		}else{
			$this->compaign = 'no';
		}
		
		$this->manufacturer = $this->getValue('csvexport/extra/manufacturer_attribute');
		$this->del = $this->getValue('csvexport/extra/delivery_attribute');
		$this->ean = $this->getValue('csvexport/extra/ean_attribute');
		$this->free_brand_de = $this->getValue('csvexport/shipping_de/free_brands');
		$this->free_cat_de = $this->getValue('csvexport/shipping_de/free_cats');
		$this->free_brand_at = $this->getValue('csvexport/shipping_at/free_brands');
		$this->free_cat_at = $this->getValue('csvexport/shipping_at/free_cats');
		$this->free_brand_uk = $this->getValue('csvexport/shipping_uk/free_brands');
		$this->free_cat_uk = $this->getValue('csvexport/shipping_uk/free_cats');
		$this->manufacturer_number = $this->getValue('csvexport/extra/manufacturer_number_attribute');
		 	
		$this->minOrder = $this->getValue('csvexport/minimumordervalue/minimumordervalue');
		$this->minOrderPrice = $this->getValue('csvexport/smallordervalue/smallordervalue');
	 	$this->idealoMinorderBorder = $this->getValue('csvexport/smallordervalue/upperlimit');
	 			
	}

	public function saveSeperatorCheck($separatorArray, $separatorWarning, $separatorInt){
		if($separatorWarning === true){
			$separatorWarning = '1';
		}else{
			$separatorWarning = '0';
		}

		if(isset($_SESSION['idealo_csv_separatorArray'])){
			$oldSeparatorArray = $_SESSION['idealo_csv_separatorArray'];
			for($i = 0; $i < count($separatorArray); $i++){
				$separatorArray[$i]['comes'] += $oldSeparatorArray[$i]['comes'];
			}
			
			$separatorInt += $_SESSION['idealo_csv_separatorInt'];	
		}
		
		$_SESSION['idealo_csv_separatorArray'] = $separatorArray;
		$_SESSION['idealo_csv_separatorWarning'] = $separatorWarning;
		$_SESSION['idealo_csv_separatorInt'] = $separatorInt;
	}

}
?>