<?php
class Mehulchaudhari_Currency_Model_Yahoo extends Mage_Directory_Model_Currency_Import_Abstract
{
    //protected $_url = 'http://quote.yahoo.com/d/quotes.csv?s={{CURRENCY_FROM}}{{CURRENCY_TO}}=X&f=l1&e=.csv';
	protected $_url = 'http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22{{CURRENCY_FROM}}{{CURRENCY_TO}}%22)&env=store://datatables.org/alltableswithkeys';
	
    protected $_messages = array();
	
    protected function _convert($currencyFrom, $currencyTo, $retry=0) {
        $url = str_replace('{{CURRENCY_FROM}}', $currencyFrom, $this->_url);
        $url = str_replace('{{CURRENCY_TO}}', $currencyTo, $url);
        try {
             $response = file_get_contents($url);
		     $xml = simplexml_load_string($response, null, LIBXML_NOERROR);
             if( !$xml ) {
                $this->_messages[] = Mage::helper('directory')->__('Cannot retrieve rate from %s.', $url);
                return null;
		    }
           return (float)$xml->results->rate->Rate;
        } catch (Exception $e) {
            if ($retry == 0) {
                $this->_convert($currencyFrom, $currencyTo, 1);
            } else {
                $this->_messages[] = Mage::helper('directory')->__('Cannot retrieve rate from %s', $url);
            }
        }
    }
}
