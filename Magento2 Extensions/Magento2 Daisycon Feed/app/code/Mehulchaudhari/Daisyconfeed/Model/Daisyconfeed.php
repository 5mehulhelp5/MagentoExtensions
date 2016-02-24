<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Mehulchaudhari\Daisyconfeed\Model;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Daisyconfeed model
 *
 * @method \Mehulchaudhari\Daisyconfeed\Model\ResourceModel\Daisyconfeed _getResource()
 * @method \Mehulchaudhari\Daisyconfeed\Model\ResourceModel\Daisyconfeed getResource()
 * @method string getDaisyconfeedType()
 * @method \Mehulchaudhari\Daisyconfeed\Model\Daisyconfeed setDaisyconfeedType(string $value)
 * @method string getDaisyconfeedFilename()
 * @method \Mehulchaudhari\Daisyconfeed\Model\Daisyconfeed setDaisyconfeedFilename(string $value)
 * @method string getDaisyconfeedPath()
 * @method \Mehulchaudhari\Daisyconfeed\Model\Daisyconfeed setDaisyconfeedPath(string $value)
 * @method string getDaisyconfeedTime()
 * @method \Mehulchaudhari\Daisyconfeed\Model\Daisyconfeed setDaisyconfeedTime(string $value)
 * @method int getStoreId()
 * @method \Mehulchaudhari\Daisyconfeed\Model\Daisyconfeed setStoreId(int $value)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Daisyconfeed extends \Magento\Framework\Model\AbstractModel
{
    const OPEN_TAG_KEY = 'start';

    const CLOSE_TAG_KEY = 'end';

    const INDEX_FILE_PREFIX = 'daisyconfeed';

    const TYPE_INDEX = 'daisyconfeed';

    const TYPE_URL = 'url';

    /**
     * Real file path
     *
     * @var string
     */
    protected $_filePath;

    /**
     * Daisyconfeed items
     *
     * @var array
     */
    protected $_daisyconfeedItems = [];

    /**
     * Current daisyconfeed increment
     *
     * @var int
     */
    protected $_daisyconfeedIncrement = 0;

    /**
     * Daisyconfeed start and end tags
     *
     * @var array
     */
    protected $_tags = [];

    /**
     * Number of lines in daisyconfeed
     *
     * @var int
     */
    protected $_lineCount = 0;

    /**
     * Current daisyconfeed file size
     *
     * @var int
     */
    protected $_fileSize = 0;

    /**
     * New line possible symbols
     *
     * @var array
     */
    private $_crlf = ["win" => "\r\n", "unix" => "\n", "mac" => "\r"];

    /**
     * @var \Magento\Framework\Filesystem\Directory\Write
     */
    protected $_directory;

    /**
     * @var \Magento\Framework\Filesystem\File\Write
     */
    protected $_stream;

    /**
     * Daisyconfeed data
     *
     * @var \Mehulchaudhari\Daisyconfeed\Helper\Data
     */
    protected $_daisyconfeedData;
    
    protected $_catalogHelper;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $_escaper;


    /**
     * @var \Mehulchaudhari\Daisyconfeed\Model\ResourceModel\Catalog\ProductFactory
     */
    protected $_productFactory;


    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_dateModel;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $dateTime;
	
	/**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;
    
	/**
     * @var \Magento\Catalog\Model\Product\Media\Config
     */
    protected $_mediaConfig;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Escaper $escaper
     * @param \Mehulchaudhari\Daisyconfeed\Helper\Data $daisyconfeedData
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Mehulchaudhari\Daisyconfeed\Model\ResourceModel\Catalog\ProductFactory $productFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $modelDate
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Escaper $escaper,
        \Mehulchaudhari\Daisyconfeed\Helper\Data $daisyconfeedData,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Catalog\Helper\Data $catalogHelper,
        \Mehulchaudhari\Daisyconfeed\Model\ResourceModel\Catalog\ProductFactory $productFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $modelDate,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Stdlib\DateTime $dateTime,
		\Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
		\Magento\Catalog\Model\Product\Media\Config $mediaConfig,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_escaper = $escaper;
        $this->_daisyconfeedData = $daisyconfeedData;
        $this->_directory = $filesystem->getDirectoryWrite(DirectoryList::ROOT);
        $this->_productFactory = $productFactory;
        $this->_dateModel = $modelDate;
        $this->_catalogHelper = $catalogHelper;
        $this->_storeManager = $storeManager;
        $this->_request = $request;
        $this->dateTime = $dateTime;
		$this->_mediaConfig = $mediaConfig;
		$this->categoryRepository = $categoryRepository;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Init model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mehulchaudhari\Daisyconfeed\Model\ResourceModel\Daisyconfeed');
    }

    /**
     * Get file handler
     *
     * @return \Magento\Framework\Filesystem\File\WriteInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getStream()
    {
        if ($this->_stream) {
            return $this->_stream;
        } else {
            throw new \Magento\Framework\Exception\LocalizedException(__('File handler unreachable'));
        }
    }

    /**
     * Initialize daisyconfeed items
     *
     * @return void
     */
    protected function _initDaisyconfeedItems()
    {
        /** @var $helper \Mehulchaudhari\Daisyconfeed\Helper\Data */
        $helper = $this->_daisyconfeedData;
        $storeId = $this->getStoreId();

        $this->_daisyconfeedItems[] = new \Magento\Framework\DataObject(
            [
                'collection' => $this->_productFactory->create()->getCollection($storeId),
            ]
        );
        $date = new \Zend_Date();
        $this->_tags = [
            self::TYPE_INDEX => [
                self::OPEN_TAG_KEY => '<?xml version="1.0" encoding="UTF-8"?>' .
                PHP_EOL .
                '<products>'.
                PHP_EOL,
                self::CLOSE_TAG_KEY => '</products>',
            ],
            self::TYPE_URL => [
                self::OPEN_TAG_KEY => '<?xml version="1.0" encoding="UTF-8"?>' .
                PHP_EOL .
                '<products>'.
                PHP_EOL,
                self::CLOSE_TAG_KEY => '</products>',
            ],
        ];
    }

    /**
     * Check daisyconfeed file location and permissions
     *
     * @return \Magento\Framework\Model\AbstractModel
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave()
    {
        $path = $this->getDaisyconfeedPath();

        /**
         * Check path is allow
         */
        if ($path && preg_match('#\.\.[\\\/]#', $path)) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Please define a correct path.'));
        }
        /**
         * Check exists and writable path
         */
        if (!$this->_directory->isExist($path)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __(
                    'Please create the specified folder "%1" before saving the daisyconfeed.',
                    $this->_escaper->escapeHtml($this->getDaisyconfeedPath())
                )
            );
        }

        if (!$this->_directory->isWritable($path)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Please make sure that "%1" is writable by the web-server.', $this->getDaisyconfeedPath())
            );
        }
        /**
         * Check allow filename
         */
        if (!preg_match('#^[a-zA-Z0-9_\.]+$#', $this->getDaisyconfeedFilename())) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __(
                    'Please use only letters (a-z or A-Z), numbers (0-9) or underscores (_) in the filename. No spaces or other characters are allowed.'
                )
            );
        }
        if (!preg_match('#\.xml$#', $this->getDaisyconfeedFilename())) {
            $this->setDaisyconfeedFilename($this->getDaisyconfeedFilename() . '.xml');
        }

        $this->setDaisyconfeedPath(rtrim(str_replace(str_replace('\\', '/', $this->_getBaseDir()), '', $path), '/') . '/');

        return parent::beforeSave();
    }

    /**
     * Generate XML file
     *
     * @see http://www.daisyconfeeds.org/protocol.html
     *
     * @return $this
     */
    public function generateXml()
    {
        $this->_initDaisyconfeedItems();
        /** @var $daisyconfeedItem \Magento\Framework\Object */
        foreach ($this->_daisyconfeedItems as $daisyconfeedItem) {
            foreach ($daisyconfeedItem->getCollection() as $item) {
                $xml = $this->_getDaisyconfeedRow(
                    $item
                );
                if ($this->_isSplitRequired($xml) && $this->_daisyconfeedIncrement > 0) {
                    $this->_finalizeDaisyconfeed();
                }
                if (!$this->_fileSize) {
                    $this->_createDaisyconfeed();
                }
                $this->_writeDaisyconfeedRow($xml);
                // Increase counters
                $this->_lineCount++;
                $this->_fileSize += strlen($xml);
            }
        }
        $this->_finalizeDaisyconfeed();

        if ($this->_daisyconfeedIncrement == 1) {
            // In case when only one increment file was created use it as default daisyconfeed
            $path = rtrim(
                $this->getDaisyconfeedPath(),
                '/'
            ) . '/' . $this->_getCurrentDaisyconfeedFilename(
                $this->_daisyconfeedIncrement
            );
            $destination = rtrim($this->getDaisyconfeedPath(), '/') . '/' . $this->getDaisyconfeedFilename();

            $this->_directory->renameFile($path, $destination);
        } else {
            // Otherwise create index file with list of generated daisyconfeeds
            $this->_createDaisyconfeedIndex();
        }

        // Push daisyconfeed to robots.txt
        if ($this->_isEnabledSubmissionRobots()) {
            $this->_addDaisyconfeedToRobotsTxt($this->getDaisyconfeedFilename());
        }

        $this->setDaisyconfeedTime($this->_dateModel->gmtDate('Y-m-d H:i:s'));
        $this->save();

        return $this;
    }

    /**
     * Generate daisyconfeed index XML file
     *
     * @return void
     */
    protected function _createDaisyconfeedIndex()
    {
        $this->_createDaisyconfeed($this->getDaisyconfeedFilename(), self::TYPE_INDEX);
        for ($i = 1; $i <= $this->_daisyconfeedIncrement; $i++) {
            $xml = $this->_getDaisyconfeedIndexRow($this->_getCurrentDaisyconfeedFilename($i), $this->_getCurrentDateTime());
            $this->_writeDaisyconfeedRow($xml);
        }
        $this->_finalizeDaisyconfeed(self::TYPE_INDEX);
    }

    /**
     * Get current date time
     *
     * @return string
     */
    protected function _getCurrentDateTime()
    {
        return (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
    }

    /**
     * Check is split required
     *
     * @param string $row
     * @return bool
     */
    protected function _isSplitRequired($row)
    {
        /** @var $helper \Mehulchaudhari\Daisyconfeed\Helper\Data */
        $helper = $this->_daisyconfeedData;
        $storeId = $this->getStoreId();
        if ($this->_lineCount + 1 > $helper->getMaximumLinesNumber($storeId)) {
            return true;
        }

        if ($this->_fileSize + strlen($row) > $helper->getMaximumFileSize($storeId)) {
            return true;
        }

        return false;
    }

    /**
     * Get daisyconfeed row
     *
     * @param string $url
     * @param null|string $lastmod
     * @param null|array $images
     * @return string
     * Daisyconfeed images
     * @see http://support.google.com/webmasters/bin/answer.py?hl=en&answer=178636
     *
     * Daisyconfeed PageMap
     * @see http://support.google.com/customsearch/bin/answer.py?hl=en&answer=1628213
     */
    protected function _getDaisyconfeedRow($item)
    {
        $helper = $this->_daisyconfeedData;
        $storeId = $this->getStoreId();
		$priceDataArray = $categories = [];
		$product = $item['product'];
        $FeedAttributes = unserialize($helper->getFeedAttributes($storeId));
        $DefaultAttributeValues = $helper->getDefaultsAttributesValue($storeId);
        $url = $this->_getUrl($item['url']);
		$priceDataArray['minimum_price'] = 0;
        $row = '<link><![CDATA[' . htmlspecialchars($url) . ']]></link>';
        foreach($FeedAttributes as $FeedAttribute){
           $mage = $FeedAttribute['mageattribute'];
           $feeds = $FeedAttribute['feedattribute'];
		   if(!isset($item[$mage])){
		        if(isset($DefaultAttributeValues[$mage])){
				    $item[$mage] = $DefaultAttributeValues[$mage];
				}else{
				    $item[$mage] = null;
				}
		   }
		   $value = strip_tags($item[$mage]);
		   $safeString = null;
           switch ($feeds) {
				
				case 'maximum_price':
				     $safeString = sprintf('%.2f', $this->_catalogHelper->getTaxPrice($product, $value));
					 $priceDataArray['maximum_price'] = $safeString;
				break;
				
				case 'stock':
				     $safeString = $product->isSaleable() ? 'yes' : 'no';
				break;
				
				case 'priority':
				     if($value && $value != ''){
					        $safeString = $value;
					 }else{
					        $safeString = 0;
					 }
				break;
				
				case 'img_large':
		        case 'img_medium':
				case 'img_small':
				      if($value && $value != '' && $value != 'no_selection'){
							  $productMediaPath = $this->_getMediaConfig()->getBaseMediaUrlAddition();
							  if(strpos($value,$productMediaPath)!== false){
									 $safeString = htmlspecialchars($this->_getMediaUrl($value));
							  }else{
									 $safeString = htmlspecialchars($this->_getMediaUrl($productMediaPath.$value));
							  }
					  }
				break;
				
				case 'minimum_price':
					   if($value && $value != ''){
						       $safeString = sprintf('%.2f', $this->_catalogHelper->getTaxPrice($product, $value));
							   $priceDataArray['minimum_price'] = $safeString;
					   }
				break;
						
				default:
                        // Google doesn't like HTML tags in the feed
					   $safeString = strip_tags($value);
					   if(!$safeString || $safeString == '')$safeString = null;	
                 break;
		   }
		   if ($safeString !== null && ($feeds !== 'minimum_price')) {
					$row .= '<'.$feeds.'><![CDATA['.strip_tags($safeString).']]></'.$feeds.'>';
		   }
		 }
		 
		 if($priceDataArray['minimum_price'] !== 0){
		        $row .= '<minimum_price><![CDATA['.strip_tags($priceDataArray['minimum_price']).']]></minimum_price>';
		 }else{
		       $row .= '<minimum_price><![CDATA['.strip_tags($priceDataArray['maximum_price']).']]></minimum_price>';
		 }
		 $categories = $this->getCategories($product, $storeId);
		 foreach($categories as $feedTag=>$feedvalue){
		       $row .= '<'.$feedTag.'><![CDATA['.strip_tags($feedvalue).']]></'.$feedTag.'>';
		 }
		 
        return '<product>' . $row . '</product>';
    }
	
	public function getCategories($product, $storeId)
    {
        $categoriesId = array(@end($product->getCategoryIds()));
		$aCategories = [];
		foreach($categoriesId as $categoryId){
		     $category = $this->categoryRepository->get($categoryId, $storeId);
			 if($category->getLevel() > 2){
			       if($category->getParentId()){
				      $aCategories['category'] = $this->categoryRepository->get($category->getParentId(), $storeId)->getName();
					}else{
					  $aCategories['category'] = $category->getName();
					}
					$aCategories['sub_category'] = $category->getName();
			 }else{
			        $aCategories['category'] = $category->getName();
					$aCategories['sub_category'] = $category->getName();
			 }
		}
		return $aCategories;
    }
    
	/**
     * Get media config
     *
     * @return \Magento\Catalog\Model\Product\Media\Config
     */
    protected function _getMediaConfig()
    {
        return $this->_mediaConfig;
    }
    /**
     * Get daisyconfeed index row
     *
     * @param string $daisyconfeedFilename
     * @param null|string $lastmod
     * @return string
     */
    protected function _getDaisyconfeedIndexRow($daisyconfeedFilename, $lastmod = null)
    {
        $url = $this->getDaisyconfeedUrl($this->getDaisyconfeedPath(), $daisyconfeedFilename);
        $row = '<loc>' . htmlspecialchars($url) . '</loc>';
        if ($lastmod) {
            $row .= '<lastmod>' . $this->_getFormattedLastmodDate($lastmod) . '</lastmod>';
        }

        return '<daisyconfeed>' . $row . '</daisyconfeed>';
    }

    /**
     * Create new daisyconfeed file
     *
     * @param null|string $fileName
     * @param string $type
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _createDaisyconfeed($fileName = null, $type = self::TYPE_URL)
    {
        if (!$fileName) {
            $this->_daisyconfeedIncrement++;
            $fileName = $this->_getCurrentDaisyconfeedFilename($this->_daisyconfeedIncrement);
        }

        $path = rtrim($this->getDaisyconfeedPath(), '/') . '/' . $fileName;
        $this->_stream = $this->_directory->openFile($path);

        $fileHeader = sprintf($this->_tags[$type][self::OPEN_TAG_KEY], $type);
        $this->_stream->write($fileHeader);
        $this->_fileSize = strlen($fileHeader . sprintf($this->_tags[$type][self::CLOSE_TAG_KEY], $type));
    }

    /**
     * Write daisyconfeed row
     *
     * @param string $row
     * @return void
     */
    protected function _writeDaisyconfeedRow($row)
    {
        $this->_getStream()->write($row . PHP_EOL);
    }

    /**
     * Write closing tag and close stream
     *
     * @param string $type
     * @return void
     */
    protected function _finalizeDaisyconfeed($type = self::TYPE_URL)
    {
        if ($this->_stream) {
            $this->_stream->write(sprintf($this->_tags[$type][self::CLOSE_TAG_KEY], $type));
            $this->_stream->close();
        }

        // Reset all counters
        $this->_lineCount = 0;
        $this->_fileSize = 0;
    }

    /**
     * Get current daisyconfeed filename
     *
     * @param int $index
     * @return string
     */
    protected function _getCurrentDaisyconfeedFilename($index)
    {
        return self::INDEX_FILE_PREFIX . '-' . $this->getStoreId() . '-' . $index . '.xml';
    }

    /**
     * Get base dir
     *
     * @return string
     */
    protected function _getBaseDir()
    {
        return $this->_directory->getAbsolutePath();
    }

    /**
     * Get store base url
     *
     * @param string $type
     * @return string
     */
    protected function _getStoreBaseUrl($type = \Magento\Framework\UrlInterface::URL_TYPE_LINK)
    {
        return rtrim($this->_storeManager->getStore($this->getStoreId())->getBaseUrl($type), '/') . '/';
    }

    /**
     * Get url
     *
     * @param string $url
     * @param string $type
     * @return string
     */
    protected function _getUrl($url, $type = \Magento\Framework\UrlInterface::URL_TYPE_LINK)
    {
        return $this->_getStoreBaseUrl($type) . ltrim($url, '/');
    }

    /**
     * Get media url
     *
     * @param string $url
     * @return string
     */
    protected function _getMediaUrl($url)
    {
        return $this->_getUrl($url, \Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * Get date in correct format applicable for lastmod attribute
     *
     * @param string $date
     * @return string
     */
    protected function _getFormattedLastmodDate($date)
    {
        return date('c', strtotime($date));
    }

    /**
     * Get Document root of Magento instance
     *
     * @return string
     */
    protected function _getDocumentRoot()
    {
        return $this->_request->getServer('DOCUMENT_ROOT');
    }

    /**
     * Get domain from store base url
     *
     * @return string
     */
    protected function _getStoreBaseDomain()
    {
        $storeParsedUrl = parse_url($this->_getStoreBaseUrl());
        $url = $storeParsedUrl['scheme'] . '://' . $storeParsedUrl['host'];

        $documentRoot = trim(str_replace('\\', '/', $this->_getDocumentRoot()), '/');
        $baseDir = trim(str_replace('\\', '/', $this->_getBaseDir()), '/');

        if (strpos($baseDir, $documentRoot) === 0) {
            //case when basedir is in document root
            $installationFolder = trim(str_replace($documentRoot, '', $baseDir), '/');
            $storeDomain = rtrim($url . '/' . $installationFolder, '/');
        } else {
            //case when documentRoot contains symlink to basedir
            $url = $this->_getStoreBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
            $storeDomain = rtrim($url, '/');
        }

        return $storeDomain;
    }

    /**
     * Get daisyconfeed.xml URL according to all config options
     *
     * @param string $daisyconfeedPath
     * @param string $daisyconfeedFileName
     * @return string
     */
    public function getDaisyconfeedUrl($daisyconfeedPath, $daisyconfeedFileName)
    {
        return $this->_getStoreBaseDomain() . str_replace('//', '/', $daisyconfeedPath . '/' . $daisyconfeedFileName);
    }

    /**
     * Check is enabled submission to robots.txt
     *
     * @return bool
     */
    protected function _isEnabledSubmissionRobots()
    {
        /** @var $helper \Mehulchaudhari\Daisyconfeed\Helper\Data */
        $helper = $this->_daisyconfeedData;
        $storeId = $this->getStoreId();
        return (bool)$helper->getEnableSubmissionRobots($storeId);
    }

    /**
     * Add daisyconfeed file to robots.txt
     *
     * @param string $daisyconfeedFileName
     * @return void
     */
    protected function _addDaisyconfeedToRobotsTxt($daisyconfeedFileName)
    {
        $robotsDaisyconfeedLine = 'Daisyconfeed: ' . $this->getDaisyconfeedUrl($this->getDaisyconfeedPath(), $daisyconfeedFileName);

        $filename = 'robots.txt';
        $content = '';
        if ($this->_directory->isExist($filename)) {
            $content = $this->_directory->readFile($filename);
        }

        if (strpos($content, $robotsDaisyconfeedLine) === false) {
            if (!empty($content)) {
                $content .= $this->_findNewLinesDelimiter($content);
            }
            $content .= $robotsDaisyconfeedLine;
        }

        $this->_directory->writeFile($filename, $content);
    }

    /**
     * Find new lines delimiter
     *
     * @param string $text
     * @return string
     */
    private function _findNewLinesDelimiter($text)
    {
        foreach ($this->_crlf as $delimiter) {
            if (strpos($text, $delimiter) !== false) {
                return $delimiter;
            }
        }

        return PHP_EOL;
    }
}
