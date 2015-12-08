<?php

namespace Mehulchaudhari\Currency\Model;

class Yahoo extends \Magento\Directory\Model\Currency\Import\AbstractImport
{
   const CURRENCY_CONVERTER_URL = 'http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22{{CURRENCY_FROM}}{{CURRENCY_TO}}%22)&env=store://datatables.org/alltableswithkeys';
   //'http://quote.yahoo.com/d/quotes.csv?s={{CURRENCY_FROM}}{{CURRENCY_TO}}=X&f=l1&e=.csv';

   protected $_httpClient;
   
   protected $_scopeConfig;

   public function __construct(
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
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
             $xml = simplexml_load_string($response, null, LIBXML_NOERROR);
             if(!$xml) {
                $this->_messages[] = __('We can\'t retrieve a rate from %1.', $url);
                return null;
	     }
           return (float)$xml->results->rate->Rate;
        } catch (Exception $e) {
            if ($retry == 0) {
                $this->_convert($currencyFrom, $currencyTo, 1);
            } else {
                $this->_messages[] = __('We can\'t retrieve a rate from %1.', $url);
            }
        }
    }
}
