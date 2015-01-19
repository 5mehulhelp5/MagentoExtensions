<?php

/*
	Idealo, Export-Modul

	(c) Idealo 2013,
	
	Please note that this extension is provided as is and without any warranty. It is recommended to always backup your installation prior to use. Use at your own risk.
	
	Extended by
	
	Christoph Zurek (Idealo Internet GmbH, http://www.idealo.de)
*/



 
 

 

include_once 'IdealoPayment.php';
include_once 'IdealoShipping.php';
include_once Mage::getModuleDir('Model', 'Idealo_Csvexport').'/definitions/definition.php';

include_once 'IdealoTools.php';

 
class Idealo_Csvexport_Tools_CreateFile{

	public $base_url = '';
	
	public $filename = '';
	
	public $payment = array();
	
	public $shipping = array();
		
	public $seperator;
	
	public $quoting;
	
	public $version_text;
	public $minOrderPrice = '';

	public function __construct($base, $filename, $version_text){
		$this->base_url = $base;
		$this->filename = $filename;
		$this->version_text = $version_text;
		
		$this->getSetting();
		
		$payment = new Idealo_Universal_Tools_IdealoPayment('csvexport');	
	 	$this->payment = $payment->payment;
		$shipping = new Idealo_Universal_Tools_IdealoShipping('csvexport');
		$this->shipping = $shipping->shipping;
		
		$this->createHeader();
		$this->create();
	}
	
	
	public function getSetting(){
		$tools = new Idealo_Universal_Tools_IdealoTools();
		$this->seperator = $tools->getValue('csvexport/file/seperator');
		$this->quoting = $tools->getValue('csvexport/file/quoting');
		$this->minOrderPrice = $tools->getValue('csvexport/smallordervalue/smallordervalue');
	}
	
	
	public function createHeader(){
		$tools = new Idealo_Universal_Tools_IdealoTools();
	 	$schema =	$this->quoting . Mage::helper('csvexport')->__('Article number') . $this->quoting . $this->seperator.
					$this->quoting . Mage::helper('csvexport')->__('Article name') . $this->quoting . $this->seperator .
					$this->quoting . Mage::helper('csvexport')->__('Manufacturer') . $this->quoting . $this->seperator .
					$this->quoting . Mage::helper('csvexport')->__('Category') . $this->quoting . $this->seperator .
					$this->quoting . Mage::helper('csvexport')->__('Short description') . $this->quoting . $this->seperator .
					$this->quoting . Mage::helper('csvexport')->__('Long description') . $this->quoting . $this->seperator .
					$this->quoting . Mage::helper('csvexport')->__('Image(s)') . $this->quoting . $this->seperator .
					$this->quoting . Mage::helper('csvexport')->__('Deeplink') . $this->quoting . $this->seperator .
					$this->quoting . Mage::helper('csvexport')->__('Price') . $this->quoting . $this->seperator .
					$this->quoting . Mage::helper('csvexport')->__('EAN') . $this->quoting . $this->seperator .
					$this->quoting . Mage::helper('csvexport')->__('Article number in shop') . $this->quoting . $this->seperator .	        		   
					$this->quoting . Mage::helper('csvexport')->__('Weight') . $this->quoting . $this->seperator;

	   foreach($this->shipping as $ship){
					if($ship['active'] == '1'){						
						foreach($this->payment as $payment){
							if($payment['active'] == '1' && strpos( $payment['country'], $ship['title'] ) !== false){
								$schema .= $this->quoting . $payment['title'] . '_' . $ship['title'] . $this->quoting . $this->seperator;							
							}						
				    	}					
					}
				}
		
		$schema .=	$this->quoting . Mage::helper('csvexport')->__('portocomment') . $this->quoting . $this->seperator.
		  			$this->quoting . Mage::helper('csvexport')->__('Base price') . $this->quoting . $this->seperator;
		$attribute_list = explode(';', $tools->getValue('csvexport/extra/extra_attributes'));
		
		$schema .= $this->quoting . Mage::helper('csvexport')->__('extraAttributes') . $this->quoting . $this->seperator . 
				   $this->quoting . Mage::helper('csvexport')->__('Delivery time') . $this->quoting . $this->seperator .
				   $this->quoting . Mage::helper('csvexport')->__('Manufacturer Part Number') . $this->quoting . $this->seperator;
		
		if($this->minOrderPrice != ''){
	    	$schema .= $this->quoting . Mage::helper('csvexport')->__('additional costs due to minimum order value') . $this->quoting . $this->seperator;
	   }	  
		
		$schema .= "\n";

		setlocale(LC_ALL, 'de_DE'); 
     	$date = date("d.m.y H:i:s");   

		$schema .= $this->quoting . Mage::helper('csvexport')->__('Feed last generated at') . ' ' . $date . ' ' . Mage::helper('csvexport')->__('hours') . $this->quoting . $this->seperator;
		$schema .= "\n";
		$schema .= $this->quoting . Mage::helper('csvexport')->__('idealo - csv export module v.') . VERSION_NUMBER_IDEALO . ' ' . Mage::helper('csvexport')->__('for Magento 1.4.x - 1.7.0 from') . ' ' . VERSION_DATE . $this->quoting . $this->seperator;
		
		$schema .= "\n";		
		
		$path = substr(__FILE__, 0, -52);

		$fp = fopen($path . 'export/' . $this->filename, "w+");
        fputs($fp, $schema);
        fclose($fp);
	}
	
	public function create(){
		$path = substr(__FILE__, 0, -52);

		$filename = explode('.', $this->filename);
		$filename = $filename[0];
		
		$part = 0;
		$file = $path . 'export/' . $filename . '_' . $part . '.csv';
 		$fp = fopen($path . 'export/' . $this->filename, "a");
 		while(file_exists($file)){
 			$content = file_get_contents($file);
 		   	unlink($file);
          	fwrite($fp, $content);
          	
          	$part++;
          	$file = $path . 'export/' . $filename . '_' . $part . '.csv';
        }

        fclose($fp);
	}
 	
}
 
?>