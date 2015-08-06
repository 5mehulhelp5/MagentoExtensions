<?php
class Mehulchaudhari_FeedsGenerator_Adminhtml_FeedsGeneratorController extends Mage_Adminhtml_Controller_Action
 {
    /**
     * Return some checking result
     *
     * @return void
     */
     public function feedAction()
     {
          Mage::getModel('feedsgenerator/googleproducts_cron')->generateFeed();
		  $result = 'Feed Generation is Done Please check your magento Root folder';
          Mage::app()->getResponse()->setBody($result);
     }
}