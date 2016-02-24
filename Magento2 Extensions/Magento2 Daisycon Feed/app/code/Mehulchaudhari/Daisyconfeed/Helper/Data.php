<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Daisyconfeed data helper
 *
 */
namespace Mehulchaudhari\Daisyconfeed\Helper;

use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Config path to daisyconfeed valid paths
     */
    const XML_PATH_BESLIST_VALID_PATHS = 'daisyconfeed/file/valid_paths';

    /**
     * Config path to valid file paths
     */
    const XML_PATH_PUBLIC_FILES_VALID_PATHS = 'general/file/public_files_valid_paths';

    /**#@+
     * Limits xpath config settings
     */
    const XML_PATH_MAX_LINES = 'daisyconfeed/limit/max_lines';

    const XML_PATH_MAX_FILE_SIZE = 'daisyconfeed/limit/max_file_size';

    /**#@-*/

    /**#@+
     * Search Engine Submission Settings
     */
    const XML_PATH_SUBMISSION_ROBOTS = 'daisyconfeed/search_engines/submission_robots';

    /**#@-*/
    const XML_PATH_PRODUCT_IMAGES_INCLUDE = 'daisyconfeed/product/image_include';

    /**
     * Get maximum daisyconfeed.xml URLs number
     *
     * @param int $storeId
     * @return int
     */
    public function getMaximumLinesNumber($storeId)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_MAX_LINES,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get maximum daisyconfeed.xml file size in bytes
     *
     * @param int $storeId
     * @return int
     */
    public function getMaximumFileSize($storeId)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_MAX_FILE_SIZE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get enable Submission to Robots.txt
     *
     * @param int $storeId
     * @return int
     */
    public function getEnableSubmissionRobots($storeId)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SUBMISSION_ROBOTS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get product image include policy
     *
     * @param int $storeId
     * @return string
     */
    public function getProductImageIncludePolicy($storeId)
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_PRODUCT_IMAGES_INCLUDE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get list valid paths for generate a daisyconfeed XML file
     *
     * @return string[]
     */
    public function getValidPaths()
    {
        return array_merge(
            [$this->scopeConfig->getValue(self::XML_PATH_BESLIST_VALID_PATHS, ScopeInterface::SCOPE_STORE)],
            $this->scopeConfig->getValue(self::XML_PATH_PUBLIC_FILES_VALID_PATHS, ScopeInterface::SCOPE_STORE)
        );
    }
    
    public function getFeedAttributes($storeId)
    {
        return $this->scopeConfig->getValue(
            'daisyconfeed/product/feedattribute',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
    
    public function getDefaultsAttributesValue($storeId)
    {
        $defaultvalues = unserialize($this->scopeConfig->getValue(
            'daisyconfeed/product/feeddefaultattribute',
            ScopeInterface::SCOPE_STORE,
            $storeId
        ));
        $ArrayValues = [];
        foreach($defaultvalues as $defaultvalue){
               $ArrayValues[$defaultvalue['magedefaultattribute']] = $defaultvalue['value'];
        }
        return $ArrayValues;
    }
	
	public function getTitle($storeId)
    {
        return $this->scopeConfig->getValue(
            'daisyconfeed/product/title',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
	
	public function getAuthor($storeId)
    {
        return $this->scopeConfig->getValue(
            'daisyconfeed/product/author',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
	
	public function getCurrency($storeId)
	{
	     return $this->scopeConfig->getValue(
            'currency/options/default',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
	}
	
	public function getWeightUnit($storeId)
	{
	     return $this->scopeConfig->getValue(
            'daisyconfeed/product/weightunit',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
	}
}
