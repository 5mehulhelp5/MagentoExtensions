<?php

namespace Mehulchaudhari\Currency\Model;

class Openapi extends \Magento\Directory\Model\Currency\Import\AbstractImport
{
   const CURRENCY_CONVERTER_URL = 'http://free.currencyconverterapi.com/api/v3/convert?q={{CURRENCY_FROM}}_{{CURRENCY_TO}}';
   
   protected $_jsonDecoder;
   
   protected $_httpClient;
   
   protected $_scopeConfig;
   
   public function __construct(
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Framework\Json\DecoderInterface $jsonDecoder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_jsonDecoder = $jsonDecoder;
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
             $resultKey = $currencyFrom.'_'.$currencyTo;
             $data = $this->_jsonDecoder->decode($response);
             $results = $data['results'][$resultKey];
             $queryCount = $data['query']['count'];
             if( !$queryCount &&  !isset($results)) {
                $this->_messages[] = __('We can\'t retrieve a rate from %1.', $url);
                return null;
	    }
           return (float)$results['val'];
        } catch (Exception $e) {
            if ($retry == 0) {
                $this->_convert($currencyFrom, $currencyTo, 1);
            } else {
                $this->_messages[] = __('We can\'t retrieve a rate from %1.', $url);
            }
        }
    }
}
