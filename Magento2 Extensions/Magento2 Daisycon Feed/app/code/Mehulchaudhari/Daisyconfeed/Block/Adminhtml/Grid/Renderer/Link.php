<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Daisyconfeed grid link column renderer
 *
 */
namespace Mehulchaudhari\Daisyconfeed\Block\Adminhtml\Grid\Renderer;

use Magento\Framework\App\Filesystem\DirectoryList;

class Link extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @var \Magento\Framework\Filesystem $filesystem
     */
    protected $_filesystem;

    /**
     * @var \Mehulchaudhari\Daisyconfeed\Model\DaisyconfeedFactory
     */
    protected $_daisyconfeedFactory;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param \Mehulchaudhari\Daisyconfeed\Model\DaisyconfeedFactory $daisyconfeedFactory
     * @param \Magento\Framework\Filesystem $filesystem
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Mehulchaudhari\Daisyconfeed\Model\DaisyconfeedFactory $daisyconfeedFactory,
        \Magento\Framework\Filesystem $filesystem,
        array $data = []
    ) {
        $this->_daisyconfeedFactory = $daisyconfeedFactory;
        $this->_filesystem = $filesystem;
        parent::__construct($context, $data);
    }

    /**
     * Prepare link to display in grid
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        /** @var $daisyconfeed \Mehulchaudhari\Daisyconfeed\Model\Daisyconfeed */
        $daisyconfeed = $this->_daisyconfeedFactory->create();
        $url = $this->escapeHtml($daisyconfeed->getDaisyconfeedUrl($row->getDaisyconfeedPath(), $row->getDaisyconfeedFilename()));

        $fileName = preg_replace('/^\//', '', $row->getDaisyconfeedPath() . $row->getDaisyconfeedFilename());
        $directory = $this->_filesystem->getDirectoryRead(DirectoryList::ROOT);
        if ($directory->isFile($fileName)) {
            return sprintf('<a href="%1$s">%1$s</a>', $url);
        }

        return $url;
    }
}
