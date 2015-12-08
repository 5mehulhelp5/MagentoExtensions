<?php

namespace Mehulchaudhari\Currency\Model;

class Google extends \Magento\Directory\Model\Currency\Import\AbstractImport
{
   const CURRENCY_CONVERTER_URL = 'http://www.google.com/finance/converter?a=1&from={{CURRENCY_FROM}}&to={{CURRENCY_TO}}';
   
   protected $filterManager;
   
   protected $_httpClient;
   
   protected $_scopeConfig;
   
   public function __construct(
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\Element\Context $context
    ) {
        $this->filterManager = $context->getFilterManager();
        parent::__construct($currencyFactory);
        $this->_scopeConfig = $scopeConfig;
        $this->_httpClient = new \Magento\Framework\HTTP\ZendClient();
    }
    
    protected function _convert($currencyFrom, $currencyTo, $retry=0) {
        $url = str_replace('{{CURRENCY_FROM}}', $currencyFrom, self::CURRENCY_CONVERTER_URL);
        $url = str_replace('{{CURRENCY_TO}}', $currencyTo, $url);
 
        try {
             $response = $this->_httpClient->setUri(
                $url
		    )->setConfig(
		        [
		            'timeout' => $this->_scopeConfig->getValue(
		                'currency/webservicex/timeout',
		                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
		            ),
		        ]
		    )->request(
		        'GET'
		    )->getBody();
	     $regex 	= '#\<span class=bld\>(.+?)\<\/span\>#s';
	     preg_match($regex, $response, $converted);
             $final = $this->filterManager->stripTags($converted[0]);
	     $result = (float)explode(" ",$final)[0]; 
             if($result == "") {
                $this->_messages[] = __('We can\'t retrieve a rate from %1.', $url);
                return null;
	     }
           return (float)$result;
        } catch (Exception $e) {
            if ($retry == 0) {
                $this->_convert($currencyFrom, $currencyTo, 1);
            } else {
                $this->_messages[] = __('We can\'t retrieve a rate from %1.', $url);
            }
        }
    }
}
