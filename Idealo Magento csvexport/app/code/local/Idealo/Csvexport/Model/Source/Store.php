<?php

/*
	Idealo, Export-Modul

	(c) Idealo 2013,
	
	Please note that this extension is provided as is and without any warranty. It is recommended to always backup your installation prior to use. Use at your own risk.
	
	Extended by
	
	Christoph Zurek (Idealo Internet GmbH, http://www.idealo.de)
*/





class Idealo_Csvexport_Model_Source_Store
 {
	 
	 
	 public function toOptionArray()
	 { 
	    $prefix = Mage::getConfig()->getTablePrefix();
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$select = 'SELECT * FROM `' . $prefix[0] . 'core_store_group`;';
		$stores = $connection->fetchAll($select); 
		
		$export_option = array();
		$export_option[] = array('value'=>'no_multi', 'label'=>Mage::helper('csvexport')->__('no multi shop'));
		
		foreach($stores as $store){
			$export_option[] = array('value'=>$store['default_store_id'], 'label'=>$store['name']); 	
		}

		 return $export_option; 
	 }
 }