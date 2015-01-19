<?php

/*
	Idealo, Export-Modul

	(c) Idealo 2013,
	
	Please note that this extension is provided as is and without any warranty. It is recommended to always backup your installation prior to use. Use at your own risk.
	
	Extended by
	
	Christoph Zurek (Idealo Internet GmbH, http://www.idealo.de)
*/





class Idealo_Csvexport_Model_Source_OptionFixProc
 {
	 
	 
	 public function toOptionArray()
	 { 
		 $export_option[] = array('value'=>'fix', 'label'=>Mage::helper('csvexport')->__('fixed costs (e.g.: 5.00 or 3 ...)')); 
		 $export_option[] = array('value'=>'proc', 'label'=>Mage::helper('csvexport')->__('% of order value'));  
		 return $export_option; 
	 }
 }