<?php

namespace Mehulchaudhari\Currency\Model;

class Api extends \Magento\Directory\Model\Currency\Import\AbstractImport
{
   const CURRENCY_CONVERTER_URL = 'https://currency-api.appspot.com/api/{{CURRENCY_FROM}}/{{CURRENCY_TO}}.json';
   
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
             $data = $this->_jsonDecoder->decode($response);
             if(!$data['success']) {
                $this->_messages[] = __('We can\'t retrieve a rate from %1.', $url);
                return null;
	    }
           return (float)$data['rate'];
        } catch (Exception $e) {
            if ($retry == 0) {
                $this->_convert($currencyFrom, $currencyTo, 1);
            } else {
                $this->_messages[] = __('We can\'t retrieve a rate from %1.', $url);
            }
        }
    }
}
