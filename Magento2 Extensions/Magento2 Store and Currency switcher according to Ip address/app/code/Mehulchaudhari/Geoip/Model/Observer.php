<?php
namespace Mehulchaudhari\Geoip\Model;
class Observer
{

    protected $_geoipHelper;
    protected $_session;
    protected $_geoipmodel;
    
     public function __construct(
        \Magento\Framework\Session\SessionManagerInterface $session,
        Geoip $geoipmodel,
        \Mehulchaudhari\Geoip\Helper\Data $geoipHelper
    ) {
         $this->_geoipHelper = $geoipHelper;
         $this->_session = $session;
         $this->_geoipmodel = $geoipmodel;
    }
    
    public function controllerActionPredispatch(\Magento\Framework\Event\Observer $observer)
    {   
        
       if($this->_geoipHelper->isEnabled() == 1 && !$this->_geoipHelper->isPrivateIp() && !$this->_geoipHelper->isCrawler() && !$this->_geoipHelper->isApi()){ 
            if($this->_geoipHelper->enableTestMode()){
                $this->_session->unsGeoipChecked();
            }
            $check = $this->_session->getGeoipChecked();
            if(!isset($check) || $check == false){
                $redirStore = $this->_geoipmodel->runGeoip();
                if($redirStore){
                    $observer->getEvent()->getControllerAction()->getResponse()->setRedirect($redirStore)->sendResponse();
                }
                $this->_session->setGeoipChecked(true);
            }
        }
    }
}
