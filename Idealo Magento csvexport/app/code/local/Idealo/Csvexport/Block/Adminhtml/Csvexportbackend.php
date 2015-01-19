<?php

/*
	Idealo, Export-Modul

	(c) Idealo 2013,
	
	Please note that this extension is provided as is and without any warranty. It is recommended to always backup your installation prior to use. Use at your own risk.
	
	Extended by
	
	Christoph Zurek (Idealo Internet GmbH, http://www.idealo.de)
*/

  


 
 

include  Mage::getModuleDir('Model', 'Idealo_Csvexport').'/tools/Idealo.php';
include  Mage::getModuleDir('Model', 'Idealo_Csvexport').'/tools/CreateFile.php';
include_once  Mage::getModuleDir('Model', 'Idealo_Csvexport').'/definitions/definition.php';

class Idealo_Csvexport_Block_Adminhtml_Csvexportbackend extends Mage_Adminhtml_Block_Template {

	public $filename = '';
		
	public $url = '';
	
	private $newVersion = '';
   	private $downloadSite = '';

	public function __construct(){	
    	$this->assign('imageUrlPath', $this->imageUrlPath());
    	$this->assign('rightColumn', $this->col03());
    	    	
    	$writeable = $this->checkFolderWriteable();
    	$this->assign('writeable', $writeable);
    	
    	if($writeable === true){
    		$errorSettings = $this->checkSettings();
    		$this->assign('errorSettings', $errorSettings);
    		if($errorSettings === true){
    			$form = $this->createForm();
    			$this->assign('form', $form);
    		}
    	}
        
    }


	public function checkFolderWriteable(){
		$path = __FILE__;

		if(defined('COMPILER_INCLUDE_PATH')){
			$path = substr($path, 0 , -67) . '/export/';
        }else{
            $path = substr($path, 0 , -68) . 'export/';
        }

		if(is_writable($path) === false){
			return false;
		}

		return true;
	}

	public function checkSettings(){
		$tools = new Idealo_Universal_Tools_IdealoTools();
		$file = $tools->getValue('csvexport/file/name');
		$seperator = $tools->getValue('csvexport/file/seperator');
		
		$not_set = array();
   		if($file == ''){
   			$not_set[] = 'Please enter a feed name!';
   		}

   		if($seperator == ''){
   			$not_set[] = 'Please define a column dividing value!';
   		}elseif(strlen($seperator) > 1){
   			$not_set[] = 'The column dividing value can only consist of one character!';
   		}
   		$idealo_shipping = new Idealo_Universal_Tools_IdealoShipping('csvexport');
	 	$shipping = $idealo_shipping->shipping;

		$shipping_active = false;
				
	 	foreach($shipping as $ship){
			if($ship['active'] == '1'){
				$shipping_active = true;
				if($ship['price'] == ''){
					$not_set[] = $ship['db'];
				}else{
					if($ship['type'] == 'hard'){
			   			if(!is_numeric($ship['price'])){
			   				$not_set[] = 'Wrong shipping costs format ' . $ship['title']. '!';
			   			}
			   		}else{
		   				$shippingCostsDE = explode(";", $ship['price']);
			   			if(count($shippingCostsDE) <= 1){
			   				$not_set[]= 'Wrong shipping costs format for ' . $ship['title']. '!';
			   			}else{
			   				foreach($shippingCostsDE as $costs){
				   				$costs = explode(":", $costs);
				   				if(count($costs) <= 1){
									$not_set[] = 'Wrong shipping costs format ' . $ship['title']. '!';
									break;
								}	
							}
				   		}
	   				}
				}
			}
	 	}

   		if(!$shipping_active){
   			$not_set[] = 'Please activate shipping costs for at least one country!';
   		}
   		$idealo_payment = new Idealo_Universal_Tools_IdealoPayment('csvexport');
	 	$payment = $idealo_payment->payment;
	 	
	 	$payment_active = false;

	 	foreach($payment as $pay){	 		
			if($pay['active'] == '1'){
				$payment_active = true;
				break;
			}
		}

	 	if(!$payment_active){
   			$not_set[] = 'At least one payment method must be activated for each activated shipping country!';
   		}
   		
   		if(count($not_set) > 0){
   			return $not_set;
   		}
		
		return true;
	}
	
	
	public function createForm(){
		return $this->col02();
	}

	
	public function imageUrlPath(){
		$url = Mage::app()->getStore()->getBaseUrl();
		$url = substr($url, 0, -11);
		$url = str_replace("/index", "", $url);
		
		return $url;
	}

	
	public function col02(){
		$from = Mage::helper('core/url')->getCurrentUrl();
		$path = __FILE__;
		$base_url = Mage::app()->getStore()->getBaseUrl();
		$base_url = substr($base_url, 0, -11);
		$base_url = str_replace("/index", "", $base_url);
			
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');	

		$select = $connection->select('value')
							 ->from(TABLE_PREFIX . 'core_config_data')
							 ->where("path LIKE 'csvexport/file/name'"); 
		$setting = $connection->fetchAll($select);
		$this->filename = $setting[0]['value'];
		
		$art_number = $connection->fetchALL("SELECT count(*) FROM " . TABLE_PREFIX . "catalog_product_entity;");
		$art_number = $art_number[0]['count(*)'];
		$select = $connection->select('value')
							 ->from(TABLE_PREFIX . 'core_config_data')
							 ->where("path LIKE 'csvexport/step/interval'"); 
		$steps = $connection->fetchAll($select);
		$interval = $steps[0]['value'];

		if($interval == '' || $interval <= 0 || $art_number < $interval){
			$interval = $art_number;
		}
				
		$begin = 1;

		$part = 0;
		
		$result = array();
		$result['baseUrl'] = $base_url;
		$result['version'] = VERSION_NUMBER_IDEALO;
		$result['versionDate'] = VERSION_DATE;
		if(isset($_GET['export'])){	   
			$result['export'] = true;
	    	if(($_GET['part'] + 1) * $interval > ($art_number + $interval)){
	    		$create_file = new Idealo_Csvexport_Tools_CreateFile($base_url, $this->filename, MODULE_VERSION_TEXT);
	    		$result['makeFile'] = true;	
	    	}else{
	    		$result['makeFile'] = false;	    		
	    		$tools = new Idealo_Csvexport_Tools_Idealo(($_GET['part'] * $interval),  $interval, $_GET['part']);
	    		$to_export = $art_number - (($_GET['part'] -1) * $interval);
	    		$result['toExport']	= $to_export;
	    		if(($_GET['part']-1) <= 0){
	    			$to_export = $art_number;
	    			if(isset($_SESSION['idealo_csv_separatorArray'])){
			    		unset($_SESSION['idealo_csv_separatorArray']);
			    	}
			    	if(isset($_SESSION['idealo_csv_separatorWarning'])){
			    		unset($_SESSION['idealo_csv_separatorWarning']);
			    	}
			    	if(isset($_SESSION['idealo_csv_separatorInt'])){
			    		unset($_SESSION['idealo_csv_separatorInt']);
			    	}
	    		}
	    		
	    		if($to_export <= $interval){
	    			$result['makeFile2'] = true;
	    		}else{
	    			$result['makeFile2'] = false;
	    			$result['toExport'] = $to_export - $interval;
	    		}
	    		
	    		$result['part'] = $_GET['part'] + 1;
		    	}
		}else{		    
			$result['export'] = false;
			if(!isset($_GET['end'])){
				$result['end'] = false;
				$result['checkVersion'] = $this->checkVersion();
				if($result['checkVersion'] === true){
					$result['newVersion'] = $this->newVersion;	
					$result['downloadSite']	= $this->downloadSite;
				}
				
				$result['toExport']	= $art_number;
				$result['from']	= $from;
			}else{
				$result['end'] = true;
			}
			if (isset($_GET['end'])){
				$result['end'] = true;
				$result['fileName'] = $this->filename;
				
				if(isset($_SESSION['idealo_csv_separatorInt'])){
					$select = $connection->select('value')
										 ->from(TABLE_PREFIX . 'core_config_data')
										 ->where("path LIKE 'csvexport/file/seperator'");
					$setting = $connection->fetchAll($select);
					$result['seperator'] = $setting[0]['value'];
					
					if($_SESSION['idealo_csv_separatorInt'] > 0){
			    		$result['separatorInt'] = false;
			    		foreach($_SESSION['idealo_csv_separatorArray'] as $separ){
			    			if($separ['comes'] == 0){
			    				$result['separatorInt'] = true;
			    			}
			    		}
					}
				}				
			}else{			    
				$result['end'] = false;
			}
		}
		return $result;
	}

	
	public function col03(){
		$url = Mage::app()->getStore()->getBaseUrl();
		$url = substr($url, 0, -11);
		$html = '';
		return $html;
	}

	

	
	 public function checkVersion(){
	$new_idealo_version_text = '';

	if(@file_get_contents(VERSION_LOCATION_IDEALO) !== false){
		$xml_idealo = simplexml_load_file(VERSION_LOCATION_IDEALO);
		$version_idealo = (string)$xml_idealo->csv_export->magento;
	
		$idealo_module_download = (string)$xml_idealo->download->url;
	
		$old_version_idealo = explode('.', VERSION_NUMBER_IDEALO);
		$new_version_idealo = explode('.', $version_idealo);
	   
		if(count($old_version_idealo) == count($new_version_idealo)){
				if (
   						($old_version_idealo[0] < $new_version_idealo[0])
   						or
   						(
   								$old_version_idealo[0] == $new_version_idealo[0]
   								and
   								$old_version_idealo[1] < $new_version_idealo [1]
   						)
   						or
   						(
   								$old_version_idealo[0] == $new_version_idealo[0]
   								and
   								$old_version_idealo[1] == $new_version_idealo[1]
   								and
   								$old_version_idealo[2] < $new_version_idealo[2]
   									
   						)
   				){	   					
   					$this->newVersion = $version_idealo;
   					$this->downloadSite = (string)$xml_idealo->download->url;
					return true;
	 			}
	 		}
		}
		
		return false;
	}

}