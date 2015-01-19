<?php

/*
	Idealo, Export-Modul

	(c) Idealo 2013,
	
	Please note that this extension is provided as is and without any warranty. It is recommended to always backup your installation prior to use. Use at your own risk.
	
	Extended by
	
	Christoph Zurek (Idealo Internet GmbH, http://www.idealo.de)
*/


	
	
 

if(file_exists(Mage::getModuleDir('Model', 'Idealo_Csvexport').'/definitions/definition.php')){
    include_once Mage::getModuleDir('Model', 'Idealo_Csvexport').'/definitions/definition.php';  
}

if(file_exists(Mage::getModuleDir('Model', 'Idealo_Realtime').'/definitions/definition.php')){
    include_once Mage::getModuleDir('Model', 'Idealo_Realtime').'/definitions/definition.php';
}

class Idealo_Universal_Tools_IdealoPayment{
	public $payment = array('PREPAID' => array('title' => 'PREPAID',
											  'active' => '0',
											  'exrtafee' => '',
											  'percent' => '',
											  'shipping_incl' => '0',
											  'max_order' => '',
											  'db' => 'moneyorder',
											  'country' => ''),
							'COD' => array('title' => 'COD',
											  'active' => '0',
											  'exrtafee' => '',
											  'percent' => '',
											  'shipping_incl' => '0',
											  'max_order' => '',
											  'db' => 'cc',
											  'country' => ''),
							'PAYPAL' => array('title' => 'PAYPAL',
											  'active' => '0',
											  'exrtafee' => '',
											  'percent' => '',
											  'shipping_incl' => '0',
											  'max_order' => '',
											  'db' => 'paypal',
											  'country' => ''),
							'CREDITCARD' => array('title' => 'CREDITCARD',
											  'active' => '0',
											  'exrtafee' => '',
											  'percent' => '',
											  'shipping_incl' => '0',
											  'max_order' => '',
											  'db' => 'creditcard',
											  'country' => ''),
							'MONEYBOOKERS' => array('title' => 'MONEYBOOKERS',
											  'active' => '0',
											  'exrtafee' => '',
											  'percent' => '',
											  'shipping_incl' => '0',
											  'max_order' => '',
											  'db' => 'moneybookers',
											  'country' => ''),
							'INVOICE' => array('title' => 'INVOICE',
											  'active' => '0',
											  'exrtafee' => '',
											  'percent' => '',
											  'shipping_incl' => '0',
											  'max_order' => '',
											  'db' => 'invoice',
											  'country' => ''),
							'DIRECTDEBIT' => array('title' => 'DIRECTDEBIT',
											  'active' => '0',
											  'exrtafee' => '',
											  'percent' => '',
											  'shipping_incl' => '0',
											  'max_order' => '',
											  'db' => 'directdebit',
											  'country' => ''),
							'SOFORTUEBERWEISUNG' => array('title' => 'SOFORTUEBERWEISUNG',
											  'active' => '0',
											  'exrtafee' => '',
											  'percent' => '',
											  'shipping_incl' => '0',
											  'max_order' => '',
											  'db' => 'sofortueberweisung',
											  'country' => '')			  
							);	
							
		
							
	public function __construct($module){
		
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		
		foreach($this->payment as $pay){
			$path = $module . '/'  . $pay['db'] . '/' . 'active';
			$select = "SELECT `value` FROM `" . TABLE_PREFIX . "core_config_data` WHERE `path` LIKE '" . $path . "' LIMIT 1;";
			$value = $connection->fetchAll($select); 
			$this->payment[$pay['title']]['active'] = $value[0]['value'];
			
			if ($this->payment[$pay['title']]['active'] == '1'){
				$path = $module . '/'  . $pay['db'] . '/extra_charge_fix';
				$select = "SELECT `value` FROM `" . TABLE_PREFIX . "core_config_data` WHERE `path` LIKE '" . $path . "' LIMIT 1;";
				$value = $connection->fetchAll($select); 
				$this->payment[$pay['title']]['exrtafee'] = $value[0]['value'];
				$path = $module . '/'  . $pay['db'] . '/' . 'extra_charge_nofix';
				$select = "SELECT `value` FROM `" . TABLE_PREFIX . "core_config_data` WHERE `path` LIKE '" . $path . "' LIMIT 1;";
				$value = $connection->fetchAll($select); 
				$this->payment[$pay['title']]['percent'] = $value[0]['value'];
				$path = $module . '/'  . $pay['db'] . '/' . 'shipping_incl';
				$select = "SELECT `value` FROM `" . TABLE_PREFIX . "core_config_data` WHERE `path` LIKE '" . $path . "' LIMIT 1;";
				$value = $connection->fetchAll($select); 
				$this->payment[$pay['title']]['shipping_incl'] = $value[0]['value'];
				$path = $module . '/'  . $pay['db'] . '/payment_max';
				$select = "SELECT `value` FROM `" . TABLE_PREFIX . "core_config_data` WHERE `path` LIKE '" . $path . "' LIMIT 1;";
				$value = $connection->fetchAll($select); 
				$this->payment[$pay['title']]['max_order'] = $value[0]['value'];
				$path = $module . '/'  . $pay['db'] . '/countries';
				$select = "SELECT `value` FROM `" . TABLE_PREFIX . "core_config_data` WHERE `path` LIKE '" . $path . "' LIMIT 1;";
				$value = $connection->fetchAll($select);
				if($value[0]['value'] == '0'){
					$this->payment[$pay['title']]['country'] = 'DE';
				}
				
				if($value[0]['value'] == '1'){
					$this->payment[$pay['title']]['country'] = 'AT';
				}
				
				if($value[0]['value'] == '2'){
					$this->payment[$pay['title']]['country'] = 'DE/AT';
				}
				
				if($value[0]['value'] == '3'){
					$this->payment[$pay['title']]['country'] = 'UK';
				}
				
				if($value[0]['value'] == '4'){
					$this->payment[$pay['title']]['country'] = 'DE/AT/UK';
				}
				
				if($value[0]['value'] == '5'){
					$this->payment[$pay['title']]['country'] = 'IT';
				}
				
				if($value[0]['value'] == '6'){
					$this->payment[$pay['title']]['country'] = 'DE/IT';
				}
				
				if($value[0]['value'] == '7'){
					$this->payment[$pay['title']]['country'] = 'AT/IT';
				}
				
				if($value[0]['value'] == '8'){
					$this->payment[$pay['title']]['country'] = 'UK/IT';
				}
				
				if($value[0]['value'] == '9'){
					$this->payment[$pay['title']]['country'] = 'DE/UK';
				}
				
				if($value[0]['value'] == '10'){
					$this->payment[$pay['title']]['country'] = 'AT/UK/IT';
				}
				
				if($value[0]['value'] == '11'){
					$this->payment[$pay['title']]['country'] = 'AT/DE/IT';
				}
				
				if($value[0]['value'] == '12'){
					$this->payment[$pay['title']]['country'] = 'AT/DE/UK/IT';
				}
				
			}
		}
	}	
		
}

?>
