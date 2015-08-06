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

abstract class Mehulchaudhari_FeedsGenerator_Model_FeedCronBase
{
    protected $CHILD_SCRIPT = 'child_run.php';
    protected $BATCH_SIZE = 100;

    /**
     * @var bool
     */
    public $generateCategories = false;

    /**
     * @var array
     */
    protected $_defaultFieldSettings = array(
        'strip_tags' => true,
        'strip_newlines' => true,
        'normalise_whitespace' => false,
    );

    /**
     * @var string
     */
    protected $_configPath = null;

    /**
     * The class to be instantiated by $this->CHILD_SCRIPT
     *
     * @var string
     */
    protected $_childClass = "feedsgenerator/child";

    /**
     * @var array
     */
    protected $_accumulator = array();

    protected $_productCategoryAccumulator;

    /**
     * @var Mage_Core_Model_Store
     */
    protected $_store;

    /**
     * @var array
     */
    protected $_requiredFields = array();

    /**
     * @var bool
     */
    protected $_generateProductCategory = false;

    /**
     * @var array
     */
    protected $_attributes = array();

    /**
     * @return string
     */
    protected function getPath()
    {
        $outputPath = Mage::getStoreConfig(
            'mehulchaudhari_feedsgenerator/'.$this->_configPath.'/output',
            $this->info('store_id')
        );

        if (substr($outputPath, 0, 1) == "/") {
            $path = $outputPath . '/';
        } else {
            $path = $this->info("base_dir") . '/' . $outputPath . '/';
        }

        return str_replace('//', '/', $path);
    }

    public function generateFeedViaMagentoCron()
    {
        $useMagentoCron = Mage::getStoreConfigFlag('mehulchaudhari_feedsgenerator/general/use_magento_cron');

        if ($useMagentoCron === true) {
            $this->generateFeed();
        }
    }

    public function generateFeed()
    {
        /** @var $stores Mage_Core_Model_Store[] */
        $stores = Mage::app()->getStores();

        $this->log("Config element: {$this->_configPath}");
        $this->log("Found " . count($stores) . " stores - starting to process.");

        foreach ($stores as $this->_store) {
            if (!Mage::getStoreConfig('mehulchaudhari_feedsgenerator/'.$this->_configPath.'/active', $this->_store->getId())) {
                $this->log("Store: {$this->_store->getName()} disabled, not processing");
            } else {
                // get and store attribute mapping
                $this->setupAppData();
                $this->_attributes = $this->collectAttributeMapping();

                $this->log("Processing store: {$this->_store->getName()}");
                if ($this->_generateProductCategory) {
                    // Create the entire products xml file
                    $this->batchProcessStore();
                    // Create for each leaf category, their products xml file
                    $categories = $this->getCategoriesXml();
                    foreach ($categories['link_ids'] as $categoryId) {
                        $this->log('Generating Product Category XML File: ' . $categoryId);
                        $this->batchProcessStore($categoryId);
                    }
                } else {
                    $this->batchProcessStore();
                }
            }
        }

        $this->finaliseAppData();
        $this->log('Finished update function');
    }

    /**
     * @param int $categoryId
     * @return Mage_Catalog_Model_Product[]|Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
    protected function getProductCollection($categoryId)
    {
        $product = Mage::getModel('catalog/product');
        $collection = $product->getCollection();
        $collection->setStoreId($this->_store);
        $collection->addStoreFilter();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);
        $collection->addAttributeToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);

        if ($categoryId != -1 && $categoryId) {
            $collection->getSelect()->where("e.entity_id IN (
                SELECT product_id FROM catalog_category_product WHERE category_id = ?
                )", $categoryId);
            $collection->getSelect()->order('product_id');
        }

        return $collection;
    }

    /**
     * @param int|null $catId
     */
    private function batchProcessStore($catId = null)
    {
        $this->setupStoreData();
        $productCollection = $this->getProductCollection($catId);

        $skipQuery = false;

        $includeAll = Mage::getStoreConfig(
            'mehulchaudhari_feedsgenerator/'.$this->_configPath.'/include_all_products',
            $this->info('store_id')
        );
        $overrideAttributeCode = Mage::getStoreConfig(
            'mehulchaudhari_feedsgenerator/'.$this->_configPath.'/custom_filter_attribute',
            $this->info('store_id')
        );

        if ($overrideAttributeCode != '0') {
            if ($includeAll) {
                // Include all with exceptions - test for exclusion by requiring a 'null' value
                $this->log("Include all products, but exclude where $overrideAttributeCode = true");
                $productCollection->addAttributeToFilter($overrideAttributeCode, array('in' => array(0, '0', '', false, 'no')));
            } else {
                // Exclude all with exceptions - test for inclusion by requiring something other than a 'null' value
                $this->log("Exclude all products, but include where $overrideAttributeCode = true");
                $productCollection->addAttributeToFilter($overrideAttributeCode, array('nin' => array(0, '0', '', false, 'no')));
            }
        } else {
            if ($includeAll) {
                // Include all with no exceptions - the simple case
                $this->log("Include all products");
            } else {
                // Exclude all with no exceptions - no results
                $this->log("Exclude all products");
                $skipQuery = true;
            }
        }

        if (!$skipQuery) {
            /** @var $iterator Mage_Core_Model_Resource_Iterator */
            $iterator = Mage::getSingleton("core/resource_iterator");
            $iterator->walk($productCollection->getSelect(), array(array($this, "productBatchCallback")));

            $this->log('Iterating: ' . $productCollection->getSize() . ' products...');

            // Finish off anything left in the array (if we didn't process a multiple of BATCH_SIZE)
            $this->collectDataForAccumulatedEntities();
        }

        $this->finaliseStoreData($catId);
    }

    /**
     * @param array $args
     */
    public function productBatchCallback($args)
    {
        $this->_accumulator[] = $args['row']['entity_id'];
        if (count($this->_accumulator) >= $this->BATCH_SIZE) {
            $this->collectDataForAccumulatedEntities();
        }
    }

    private function collectDataForAccumulatedEntities()
    {
        // Build file descriptor list for talking to child process
        $stdErr = Mage::getBaseDir('log') . '/mehulchaudhari_feedsgenerator_subprocess.txt';
        $descriptorSpec = array(
            0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
            1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
            2 => array("file", $stdErr, "a") // stderr is a file to write to
        );
        $pipes = array();

        // Build child path from current file location and Magento path delimiter
        $execPath = escapeshellarg(dirname(__FILE__) . DS . $this->CHILD_SCRIPT);

        // Open child process with proc_open
        //$this->log("Opening child: ".$exec_path);
        $process = proc_open('php '.$execPath, $descriptorSpec, $pipes);

        if (!is_resource($process)) {
            $this->log("Error opening child process.");
            fclose($pipes[0]);
        } else {
            // Write entity id/attribute info to pipe
            $data = array(
                "magento_path" => $this->info("base_dir"),
                "store_id" => $this->info("store_id"),
                "child_class" => Mage::getConfig()->getModelClassName($this->_childClass),
                "entity_ids" => $this->_accumulator,
                "attributes" => $this->_attributes,
                "generate_categories" => $this->generateCategories,
                "config_path" => $this->_configPath,
            );
            $this->_accumulator = array();

            fwrite($pipes[0], json_encode($data));
            fclose($pipes[0]);

            // Read child's output until it finishes or child is no longer running
            $result = '';
            do {
                $result .= fgets($pipes[1]);
                $state = proc_get_status($process);
            } while (!feof($pipes[1]) && $state['running']);

            // read JSON-encoded data from child process
            $batchData = json_decode($result, true);

            if (is_array($batchData)) {
                $this->populateFeedWithBatchData($batchData);
            } else {
                $this->log("Could not unserialize returned data from child to array:");
                $this->log($result);
                $this->log($batchData);
            }

           // Close child process if it's still open
           $status = proc_close($process);
        }

        // Close remaining file handles
        @fclose($pipes[1]);
    }

    protected function collectLinkedAttributes()
    {
        $result = unserialize($this->getConfig('m_to_xml_attributes'));

        if (is_array($result)) {
            return $result;
        } else {
            return array();
        }
    }

    protected function collectAttributeMapping()
    {
        // Initialise attribute mapping from list of required fields
        $fields = array();
        foreach ($this->_requiredFields as $requiredField) {
            $fields[$requiredField['feed']] = $requiredField;
        }

        // Merge in the extra mapped attributes
        foreach ($this->collectLinkedAttributes() as $fieldData) {
            $fields[$fieldData['xmlfeed']] = array(
                'magento' => $fieldData['magento'],
                'feed' => $fieldData['xmlfeed'],
                'type' => 'product_attribute',
            );
        }

        // Set defaults for any field settings that weren't specified explicitly
        foreach ($fields as &$field) {
            foreach ($this->_defaultFieldSettings as $key => $value) {
                if (!isset($field[$key])) {
                    $field[$key] = $value;
                }
            }
        }

        return $fields;
    }

    /**
     * For feeds generating a single file, set it up here
     */
    protected function setupAppData() {}

    /**
     * For feeds generating a single file, set it up here
     */
    protected function setupStoreData() {}

    /**
     * Data returned from a child process at the batch level
     *
     * @param array $batchData
     */
    protected function populateFeedWithBatchData($batchData) {}

    /**
     * @param string $feedTag
     * @param mixed $value
     * @return mixed
     */
    protected function processBatchField($feedTag, $value)
    {
        return $value;
    }

    /**
     * Process returned data at the store level
     */
    protected function finaliseStoreData() {}

    /**
     * Process returned data at the application level
     */
    protected function finaliseAppData() {}

    /**
     * @param string $key
     * @return mixed
     */
    protected function getConfig($key)
    {
        if (isset($this->_store)) {
            $storeId = $this->_store->getId();
        } else {
            $storeId = null;
        }
        return Mage::getStoreConfig('mehulchaudhari_feedsgenerator/' . $this->_configPath . '/' . $key, $storeId);
    }

    /**
     * @param string $type
     * @return string|null
     */
    protected function info($type)
    {
        $store = $this->_store;
        $coreDate = Mage::getModel('core/date')->timestamp(time());

        $info = null;
        switch ($type) {
            case "store_id":
                $info = $store->getId();
                break;
            case "store_url":
                $info = $store->getBaseUrl();
                break;
            case "shop_name":
                $info = $store->getName();
                break;
            case "date":
                $info = date("d-m-Y", $coreDate);
                break;
            case "time":
                $info = date("h:i:s", $coreDate);
                break;
            case "clean_store_name":
                $info = str_replace('+', '-', strtolower(urlencode($store->getName())));
                break;
            case "base_dir":
                $info = Mage::getBaseDir();
                break;
        }
        return $info;
    }

    /**
     * @param Mage_Core_Model_Store $store
     */
    public function setStore($store)
    {
        $this->_store = $store;
    }

    /**
     * Convenience wrapper for Mage::log
     *
     * @param string $msg   Text to log
     */
    public function log($msg)
    {
        Mage::log(
            get_class($this).": ".$msg,
            Zend_Log::INFO,
            'mehulchaudhari_feedsgenerator.log'
        );
    }
}
