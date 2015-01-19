<?php

/*
	Idealo, Export-Modul

	(c) Idealo 2013,
	
	Please note that this extension is provided as is and without any warranty. It is recommended to always backup your installation prior to use. Use at your own risk.
	
	Extended by
	
	Christoph Zurek (Idealo Internet GmbH, http://www.idealo.de)
*/





define('VERSION_NUMBER_IDEALO', '2.20.0');
define('VERSION_DATE', '04.03.2014');
define('TEXT_IDEALO_CSV_MODIFIED', 'no');
define('VERSION_LOCATION_IDEALO', 'http://ftp.idealo.de/software/modules/version.xml');

define('COMPAIGN', '?ref=94511215');
define('PRODUCT_NAME_TABEL', 'catalog_product_entity_varchar');
define('DESRIPTION_TABLE', 'catalog_product_entity_text');
define('IMAGE_TABLE', 'catalog_product_entity_media_gallery');
define('DECIMAL', 'catalog_product_entity_decimal');

$prefix = Mage::getConfig()->getTablePrefix();
define('TABLE_PREFIX', $prefix[0]);

class Idealo_Csvexport_Definitions_Definition{
	
}
?>
