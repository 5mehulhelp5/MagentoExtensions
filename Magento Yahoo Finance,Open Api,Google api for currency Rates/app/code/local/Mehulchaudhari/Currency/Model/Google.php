<?php
class Mehulchaudhari_Currency_Model_Google extends Mage_Directory_Model_Currency_Import_Abstract
{
    protected $_url = 'https://finance.google.com/finance/converter?a=1&from={{CURRENCY_FROM}}&to={{CURRENCY_TO}}';
	
    protected $_messages = array();
	
    protected function _convert($currencyFrom, $currencyTo, $retry=0) {
        $url = str_replace('{{CURRENCY_FROM}}', $currencyFrom, $this->_url);
        $url = str_replace('{{CURRENCY_TO}}', $currencyTo, $url);
        try {
             $response = file_get_contents($url);
	     $regex 	= '#\<span class=bld\>(.+?)\<\/span\>#s';
	     preg_match($regex, $response, $converted);
             $final = Mage::helper('core')->stripTags($converted[0]);
	     $result = (float)explode(" ",$final)[0]; 
             if($result == "") {
                $this->_messages[] = Mage::helper('directory')->__('Cannot retrieve rate from %s.', $url);
                return null;
	     }
           return (float)$result;
        } catch (Exception $e) {
            if ($retry == 0) {
                $this->_convert($currencyFrom, $currencyTo, 1);
            } else {
                $this->_messages[] = Mage::helper('directory')->__('Cannot retrieve rate from %s', $url);
            }
        }
    }
}
