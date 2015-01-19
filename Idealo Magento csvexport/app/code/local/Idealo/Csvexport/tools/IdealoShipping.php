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

 class Idealo_Universal_Tools_IdealoShipping{
	public $shipping = array('DE' => array('title' => 'DE',
										   'active' => '0',
										   'price' => '',
										   'type' => 'hard',
										   'free' => '',
										   'db' => 'shipping_de'),
							 'AT' => array('title' => 'AT',
										   'active' => '0',
										   'price' => '',
										   'type' => 'hard',
										   'free' => '',
										   'db' => 'shipping_at'),
							 'UK' => array('title' => 'UK',
										   'active' => '0',
										   'price' => '',
										   'type' => 'hard',
										   'free' => '',
										   'db' => 'shipping_uk'),
							 'IT' => array('title' => 'IT',
										   'active' => '0',
										   'price' => '',
										   'type' => 'hard',
										   'free' => '',
										   'db' => 'shipping_it'),
							); 		
	
	
							
	public function __construct($module){
		
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		
		foreach($this->shipping as $ship){
			$path = $module . '/'  . $ship['db'] . '/active';
			$select = "SELECT `value` FROM `" . TABLE_PREFIX . "core_config_data` WHERE `path` LIKE '" . $path . "' LIMIT 1;";
			$value = $connection->fetchAll($select); 
			$this->shipping[$ship['title']]['active'] = $value[0]['value'];
			
			if($this->shipping[$ship['title']]['active'] == '1'){
				$path = $module . '/'  . $ship['db'] . '/costs';
				$select = "SELECT `value` FROM `" . TABLE_PREFIX . "core_config_data` WHERE `path` LIKE '" . $path . "' LIMIT 1;";
				$value = $connection->fetchAll($select); 
				$this->shipping[$ship['title']]['price'] = $value[0]['value'];
				$path = $module . '/'  . $ship['db'] . '/calculation';
				$select = "SELECT `value` FROM `" . TABLE_PREFIX . "core_config_data` WHERE `path` LIKE '" . $path . "' LIMIT 1;";
				$value = $connection->fetchAll($select);
				if($value[0]['value'] == '0'){
					$this->shipping[$ship['title']]['type'] = 'hard';
				}
				
				if($value[0]['value'] == '1'){
					$this->shipping[$ship['title']]['type'] = 'weight';
				}
				
				if($value[0]['value'] == '2'){
					$this->shipping[$ship['title']]['type'] = 'price';
				}
				$path = $module . '/'  . $ship['db'] . '/free_shipping';
				$select = "SELECT `value` FROM `" . TABLE_PREFIX . "core_config_data` WHERE `path` LIKE '" . $path . "' LIMIT 1;";
				$value = $connection->fetchAll($select); 
				$this->shipping[$ship['title']]['free'] = $value[0]['value'];
			}
		}	
	 }

}
?>