<?php

/*
	Idealo, Export-Modul

	(c) Idealo 2013,
	
	Please note that this extension is provided as is and without any warranty. It is recommended to always backup your installation prior to use. Use at your own risk.
	
	Extended by
	
	Christoph Zurek (Idealo Internet GmbH, http://www.idealo.de)
*/





class Idealo_Csvexport_Model_Source_OptionCountries
 {
 	
 	 
	 public function toOptionArray()
	 { 
		 $export_option[] = array('value'=>'0', 'label'=>'DE'); 
		 $export_option[] = array('value'=>'1', 'label'=>'AT');
		 $export_option[] = array('value'=>'2', 'label'=>'DE/AT'); 
		 $export_option[] = array('value'=>'3', 'label'=>'UK'); 
		 $export_option[] = array('value'=>'4', 'label'=>'DE/AT/UK');
		 $export_option[] = array('value'=>'5', 'label'=>'IT');
		 $export_option[] = array('value'=>'6', 'label'=>'DE/IT');
		 $export_option[] = array('value'=>'7', 'label'=>'AT/IT');
		 $export_option[] = array('value'=>'8', 'label'=>'UK/IT');
		 $export_option[] = array('value'=>'9', 'label'=>'DE/UK');
		 $export_option[] = array('value'=>'10', 'label'=>'AT/UK/IT');
		 $export_option[] = array('value'=>'11', 'label'=>'AT/DE/IT');
		 $export_option[] = array('value'=>'12', 'label'=>'AT/DE/UK/IT');
		 
		 return $export_option; 
	 }
 }