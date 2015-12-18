<?php

namespace Mehulchaudhari\Geoip\Model;

class Geoip 
{
   
   protected $_geoipHelper;
   protected $_logger;
   protected $_scopeConfig;
   protected $_storeManager;
   protected $libpath;
    
   public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Module\Dir\Reader $configReader,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Mehulchaudhari\Geoip\Helper\Data $geoipHelper,
        array $data = []
    ) {
         $this->_geoipHelper = $geoipHelper;
         $this->_logger = $context->getLogger();
         $this->_scopeConfig = $scopeConfig;
         $this->_storeManager = $storeManager;
         $this->libpath = $configReader->getModuleDir('', 'Mehulchaudhari_Geoip');
         if(!function_exists('geoip_country_code_by_name') && $this->_geoipHelper->getConfig('general/apache_or_file') == 1){   
		  define('GEOIP_LOCAL',1);
		  $geoIpInc = $this->libpath.'/lib/geoip.inc';
		  include $geoIpInc;
         }
    }
    
    
    public function runGeoip(){
        
        $countryCode = $this->_getCountryCode();
        if(empty($countryCode)){
            $this->_logger->addDebug('Country code returned empty. Please ensure you have at least one GeoIP method installed/enabled');
        }
        $pairArr = $this->_getPairArray();
        
        foreach($pairArr as $searchArr){
            if(in_array($countryCode, $searchArr)){
                $this->_setCurrency($searchArr);
                return $this->_setStore($searchArr);
            }
        }
    }
    
    protected function _getCountryCode()
    {
        if($this->_geoipHelper->enableTestMode()){
            $overrideCountry = $this->_geoipHelper->testOverrideCountry();
            if(!empty($overrideCountry)){
                return $overrideCountry;
            }
        }            
        return $this->_getCountryCodeFromIp($this->_getIp());
    }
    
    protected function _getCountryCodeFromIp($ip){    
        //GeoIP .dat file
        $file = $this->libpath.'/lib/Data/'.$this->_geoipHelper->getConfig('general/file_location');
        try{
            if(file_exists($file)){
                if(defined('GEOIP_LOCAL')){
                    $gi=geoip_open($file,GEOIP_STANDARD);
                    $location = geoip_country_code_by_addr($gi, $ip);
                    geoip_close($gi);
                    return $location;
                }else{
                    $this->_logger->addDebug(".dat file detected, but you haven't enabled the option to use it ('Use my own GeoIP file' in config)");
                }
            }else{
                return geoip_country_code_by_name($ip);
            }
        }catch(Exception $e){
            $this->_logger->addDebug("Warning: Could not find GeoIP Country Code. Please check your GeoIP data - Have you included a geoip.dat file?");
            $this->_logger->addDebug($e->getMessage());
            return $this->_scopeConfig->getValue('general/country/default',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        }
    }
    
    
    protected function _getPairArray()
    {
        return unserialize($this->_geoipHelper->getConfig('geoipset/ippair'));
    }
    
    protected function _setCurrency($searchArr)
    {
        if($this->_geoipHelper->canSwitch("currency")){
            $this->_storeManager->getStore()->setCurrentCurrencyCode(next($searchArr));
        }
    }
    
    protected function _setStore($searchArr)
    {
        if($this->_geoipHelper->canSwitch("store")){
            $currentStoreName = $this->_storeManager->getStore()->getName();
            $storeCode = $this->_storeManager->getStore($searchArr['store'])->getCode(); 
            if ($storeCode) {
                $store = $this->_storeManager->getStore()->load($storeCode);
                if ($store->getName() != $currentStoreName) {
                    return $store->getCurrentUrl(false);
                }
            }
        }
    }
    
    protected function _getIp(){
        return $this->_geoipHelper->getRemoteAddr();
    }
    

    protected function _getModGeoIp(){
        if (function_exists('apache_get_modules')) {
            $modules = apache_get_modules();
            $mod_geoip = in_array('mod_geoip', $modules);
        } else {
            $mod_geoip =  getenv('HTTP_MOD_GEOIP')=='On' ? true : false ;
        }
        return $mod_geoip;
    }
    
    public function run(){
        return $this->runGeoip();
    }

}
