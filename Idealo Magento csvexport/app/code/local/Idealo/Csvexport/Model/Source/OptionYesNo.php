<?php

/*
	Idealo, Export-Modul

	(c) Idealo 2013,
	
	Please note that this extension is provided as is and without any warranty. It is recommended to always backup your installation prior to use. Use at your own risk.
	
	Extended by
	
	Christoph Zurek (Idealo Internet GmbH, http://www.idealo.de)
*/





class Idealo_Csvexport_Model_Source_OptionYesNo
 {
 	
 	 
	 public function toOptionArray()
	 { 
	 	
	 	
		 $export_option[] = array('value'=>'0', 'label'=>Mage::helper('csvexport')->__('no')); 
		 $export_option[] = array('value'=>'1', 'label'=>Mage::helper('csvexport')->__('yes'));  

		 return $export_option; 
	 }
 }