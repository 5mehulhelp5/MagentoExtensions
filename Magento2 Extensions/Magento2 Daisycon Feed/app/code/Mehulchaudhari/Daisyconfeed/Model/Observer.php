<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mehulchaudhari\Daisyconfeed\Model;

/**
 * Daisyconfeed module observer
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Observer
{
    /**
     * Enable/disable configuration
     */
    const XML_PATH_GENERATION_ENABLED = 'daisyconfeed/generate/enabled';

    /**
     * Cronjob expression configuration
     */
    const XML_PATH_CRON_EXPR = 'crontab/default/jobs/generate_daisyconfeeds/schedule/cron_expr';

    /**
     * Error email template configuration
     */
    const XML_PATH_ERROR_TEMPLATE = 'daisyconfeed/generate/error_email_template';

    /**
     * Error email identity configuration
     */
    const XML_PATH_ERROR_IDENTITY = 'daisyconfeed/generate/error_email_identity';

    /**
     * 'Send error emails to' configuration
     */
    const XML_PATH_ERROR_RECIPIENT = 'daisyconfeed/generate/error_email';

    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Mehulchaudhari\Daisyconfeed\Model\ResourceModel\Daisyconfeed\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param Resource\Daisyconfeed\CollectionFactory $collectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Mehulchaudhari\Daisyconfeed\Model\ResourceModel\Daisyconfeed\CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_collectionFactory = $collectionFactory;
        $this->_storeManager = $storeManager;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
    }

    /**
     * Generate daisyconfeeds
     *
     * @param \Magento\Cron\Model\Schedule $schedule
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function scheduledGenerateDaisyconfeeds($schedule)
    {
        $errors = [];

        // check if scheduled generation enabled
        if (!$this->_scopeConfig->isSetFlag(
            self::XML_PATH_GENERATION_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        )
        ) {
            return;
        }

        $collection = $this->_collectionFactory->create();
        /* @var $collection \Mehulchaudhari\Daisyconfeed\Model\ResourceModel\Daisyconfeed\Collection */
        foreach ($collection as $daisyconfeed) {
            /* @var $daisyconfeed \Mehulchaudhari\Daisyconfeed\Model\Daisyconfeed */

            try {
                $daisyconfeed->generateXml();
            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        if ($errors && $this->_scopeConfig->getValue(
            self::XML_PATH_ERROR_RECIPIENT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        )
        ) {
            $translate = $this->_translateModel->getTranslateInline();
            $this->_translateModel->setTranslateInline(false);

            $this->_transportBuilder->setTemplateIdentifier(
                $this->_scopeConfig->getValue(
                    self::XML_PATH_ERROR_TEMPLATE,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            )->setTemplateOptions(
                [
                    'area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
            )->setTemplateVars(
                ['warnings' => join("\n", $errors)]
            )->setFrom(
                $this->_scopeConfig->getValue(
                    self::XML_PATH_ERROR_IDENTITY,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            )->addTo(
                $this->_scopeConfig->getValue(
                    self::XML_PATH_ERROR_RECIPIENT,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            );
            $transport = $this->_transportBuilder->getTransport();
            $transport->sendMessage();

            $this->inlineTranslation->resume();
        }
    }
}
