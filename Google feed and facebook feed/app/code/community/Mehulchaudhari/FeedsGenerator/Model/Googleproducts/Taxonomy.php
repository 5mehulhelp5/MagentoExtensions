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
 * @author     Mehul Chaudhari
 * @copyright  Copyright (c) 2014 ; ;
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mehulchaudhari_FeedsGenerator_Model_Googleproducts_Taxonomy
{
	const TAXONOMY_FILE_PATH = "/var/taxonomy/";
	
    public function insert($langRaw, $storelang)
    {
        
		$taxonomyPath = Mage::getBaseDir(). self::TAXONOMY_FILE_PATH;
	
        $filename = $taxonomyPath.'taxonomy.'.$langRaw.'.txt';
		
		if(!file_exists($filename)) {
			$filename = $taxonomyPath . "taxonomy.en-US.txt";
			$storelang = 'US';
        }
		
		if(($fh = fopen($filename,"r")) !== false) {
                $line = 0;
                while (($category = fgets($fh)) !== false) {
                    if($line === 0) {$line++;continue;} // skip first line
                    $line++;
						$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
						$connection->beginTransaction();
						$fields = array();
						$fields['store_lang'] = $storelang;
						$fields['taxonomy_name'] = (string)trim($category);
						$connection->insert('feedsgenerator_taxonomy', $fields);
						$connection->commit();
                }
        }
        return true;
    }
	
	public function toOptionArray()
    {
        $options = array();
        // Set the default value
        $options[] = array('value' => 0, 'label' => '');

        /** @var Mage_Core_Model_Resource $resource */
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');

		$langRaw = Mage::getStoreConfig('general/locale/code',Mage::app()->getRequest()->getParam('store'));
		$langRaw = str_replace("_","-",$langRaw);
		$storelang = explode("-",$langRaw)[1];
		
		$sql = "SELECT * FROM feedsgenerator_taxonomy WHERE store_lang = '".$storelang."'";
        $taxonomies = $read->query($sql)->fetchAll();
		if(!count($taxonomies)){
		    $this->insert($langRaw, $storelang);
		}
		$taxonomies = $read->query($sql)->fetchAll();
        foreach ($taxonomies as $taxonomy) {
            $id = $taxonomy['taxonomy_id'];
            $name = $taxonomy['taxonomy_name'];
            $options[] = array('value' => $id, 'label' => $name);
        }

        return $options;
    }
	
	public function getDataDetail()
    {
        $taxonomyPath = Mage::getBaseDir() . self::TAXONOMY_FILE_PATH;
        
		$langs = array('en-US','de-DE','en-AU','en-GB','nl-NL');
		if (is_null($this->_optionsdata) || count($this->_optionsdata) < 1) {
		     foreach($langs as $lang){
					   $taxonomyFile = $taxonomyPath . "taxonomy.".$lang.".txt";
				
					    $store_lang = explode("-",$lang)[1];
						if(($fh = fopen($taxonomyFile,"r")) !== false) {
							$line = 0;
							//$this->_optionsdata = array();
							while (($category = fgets($fh)) !== false) {
								if($line === 0) {$line++;continue;} // skip first line
								$line++;
								$this->_optionsdata[$store_lang][] = trim($category);
							}
						}
					}
		}
        return $this->_optionsdata;
    }
}
