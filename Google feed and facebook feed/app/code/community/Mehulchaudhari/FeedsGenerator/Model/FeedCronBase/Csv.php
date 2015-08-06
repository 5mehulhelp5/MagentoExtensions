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

abstract class Mehulchaudhari_FeedsGenerator_Model_FeedCronBase_Csv extends Mehulchaudhari_FeedsGenerator_Model_FeedCronBase
{
    /**
     * @var string
     */
    public $_configPath = 'shoptabfeed';

    /**
     * @var bool
     */
    public $generateCategories = false;

    /**
     * Separator to use in CSV output - can be overridden
     *
     * @var string
     */
    protected $_separator = ",";

    /**
     * @var array
     */
    protected $_rows;

    protected function setupStoreData()
    {
        // Add header row to CSV data
        $row = array();
        foreach ($this->_attributes as $attribute) {
            $row[] = $attribute['feed'];
        }
        $this->_rows[] = $row;
    }

    /**
     * Data returned from a child process at the batch level
     *
     * @param array $batchData
     */
    protected function populateFeedWithBatchData($batchData)
    {
        foreach ($batchData as $productData) {
            $row = array();
            foreach ($productData as $feedTag => $value) {
                $value = $this->processBatchField($feedTag, $value);
                $row[] = $value;
            }
            $this->_rows[] = $row;
        }
    }

    protected function finaliseStoreData()
    {
        // Write CSV data to temp file
        $memoryLimit = 16 * 1024 * 1024;
        $fp = fopen("php://temp/maxmemory:$memoryLimit", 'r+');
        foreach ($this->_rows as $row) {
            fputcsv($fp, $row, $this->_separator);
        }
        rewind($fp);

        // Write temp file data to file
        $cleanStoreName = str_replace('+', '-', strtolower(urlencode($this->_store->getName())));
        $filename = $cleanStoreName . '-products.csv';
        $io = new Varien_Io_File();
        $io->setAllowCreateFolders(true);
        $io->open(array('path' => $this->getPath()));
        $io->write($filename, $fp);
        $io->close();

        fclose($fp);
    }
}
