<?php
/**
 * Mehulchaudhari FeedsGenerator Extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Mehulchaudhari
 * @package    Mehulchaudhari_FeedsGenerator
 * @author     Thai Phan
 * @copyright  Copyright (c) 2014 ; ;
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/** @var $installer Mage_Eav_Model_Entity_Setup */

const PROCESS_ID = 'google_taxonomy';

$indexProcess = new Mage_Index_Model_Process();
$indexProcess->setId(PROCESS_ID);

if (!$indexProcess->isLocked()) {
    $indexProcess->lockAndBlock();

    $installer = $this;

    $installer->startSetup();

    // Create (or re-create) the table containing all of the Google taxonomy information. This is a list
    // of available taxonomy values.
    $installer->run("
        DROP TABLE IF EXISTS {$this->getTable('feedsgenerator_taxonomy')};
        CREATE TABLE {$this->getTable('feedsgenerator_taxonomy')} (
          `taxonomy_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `store_lang` varchar(255) NOT NULL,
          `taxonomy_name` varchar(255) NOT NULL,
          PRIMARY KEY (`taxonomy_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");

    // Fill the taxonomy table with values provided by Google. Values are inserted in batches of
    // 1000 to increase processing speed.
	
		$connection = $installer->getConnection();
		$Datail = Mage::getModel('feedsgenerator/googleproducts_taxonomy')->getDataDetail();
		
		foreach ($Datail as $lang => $taxonomy) {
			$data =  array();
			foreach ($taxonomy as $i => $t) {
				$data[] =  array($lang, $t);
			}

			$connection->insertArray(
				$this->getTable('feedsgenerator_taxonomy'),
				 array('store_lang', 'taxonomy_name'),
				$data
			);
		}
		
	/*foreach ($allStores as $_eachStoreId => $val) 
	{
	            $locael = Mage::getStoreConfig('general/locale/code', $_eachStoreId);
				$filename = __DIR__ . '/taxonomy.'.$locael.'.txt';
				echo '<pre>'; print_r($filename); exit; die;
				if (file_exists($filename) && is_readable($filename)) {
					$taxonomies = file($filename);

					// Remove the first line as it's a comment
					array_shift($taxonomies);

					$values = array();
					$i = 0;

					foreach ($taxonomies as $taxonomy) {
						$values[] = "('" . addslashes(trim($taxonomy)) . "')";

						// Process the file in batches
						if($i++ % 1000 == 0) {
							$insertValues = implode(',', $values);
							$insertStoreId = $_eachStoreId;
							$installer->run("INSERT INTO {$this->getTable('google_taxonomy')} (`taxonomy_name, store_id`) VALUES {$insertValues}, {$insertStoreId};");
							$values = array();
						}
					}

					// Process any remaining values
					if(count($values)) {
						$insertValues = implode(',', $values);
						$installer->run("INSERT INTO {$this->getTable('google_taxonomy')} (`taxonomy_name, store_id`) VALUES {$insertValues}, {$insertStoreId};");
					}
				}
    }*/
    // Add a new category attribute to allow setting the taxonomy, to be displayed on the General Information
    // tab underneath the usual core attributes
    $installer->addAttribute('catalog_category', 'google_product_category', array(
        'group'         => 'General Information',
        'input'         => 'select',
        'label'         => 'Google Product Category',
        'source'        => 'feedsgenerator/googleproducts_source_taxonomy',
        'sort_order'    => 15,
        'type'          => 'int',
    ));

    $installer->endSetup();

    $indexProcess->unlock();
}
