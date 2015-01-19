<?php

/*
	Idealo, Export-Modul

	(c) Idealo 2013,
	
	Please note that this extension is provided as is and without any warranty. It is recommended to always backup your installation prior to use. Use at your own risk.
	
	Extended by
	
	Christoph Zurek (Idealo Internet GmbH, http://www.idealo.de)
*/



	
 

include_once  Mage::getModuleDir ( 'Model', 'Idealo_Csvexport' ) . '/definitions/definition.php';
include_once  Mage::getModuleDir ( 'Model', 'Idealo_Csvexport' ) . '/tools/IdealoTools.php';
include_once  Mage::getModuleDir ( 'Model', 'Idealo_Csvexport' ) . '/tools/CreateFile.php';
include_once  Mage::getModuleDir ( 'Model', 'Idealo_Csvexport' ) . '/tools/Idealo.php';

class Idealo_Csvexport_Model_Observer
{
	
	public function runExport(){
		$tools = new Idealo_Universal_Tools_IdealoTools();

		if($tools->getValue('csvexport/cron/cron') == '1'){
			$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
			
			$filename = $tools->getValue('csvexport/file/name');
			$select = "SELECT count(*) FROM `" . TABLE_PREFIX . "catalog_product_entity`;";
			$art_number = $connection->fetchAll($select); 
			$art_number = $art_number[0]['count(*)'];
			$interval = $tools->getValue('csvexport/step/interval');
			if($interval == '' || $interval <= 0  || $art_number < $interval){
				$interval = $art_number;
			}
			
			$part = 0;
			
			while(($part * $interval) <= ($art_number + $interval)){
				$export = new Idealo_Csvexport_Tools_Idealo(($part * $interval),  $interval, $part);
				$part++;
			}
		
			$base = substr(__FILE__, 0, -51);
			$create_file = new Idealo_Csvexport_Tools_CreateFile( $base, $filename, MODULE_VERSION_TEXT );
		}
	}
	
	
}
