<?php

/**
 * GoogleShopping Products selection grid controller
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Weboffice_GoogleShoppingApi_Adminhtml_GoogleShoppingApi_TaxonomyController
    extends Mage_Adminhtml_Controller_Action
{
    const MIN_LENGTH = 2;

    /**
     * Search result grid with available products for Google Content
     */
    public function searchAction()
    {
        $q = $this->getRequest()->getParam('query', '');
		
        if (strlen($q) < self::MIN_LENGTH) {
            $this->getResponse()->setBody('');
            $this->getResponse()->sendResponse();
            return;
        }
       
        $taxonomyResults = Mage::getModel('googleshoppingapi/taxonomy')->getCollection();
		//echo '<pre>'; print_r($taxonomyResults); exit;  die;
        $taxonomyResults
            ->addLocaleFilter((int)$this->getRequest()->getParam('store', 0))
            ->searchByName($q);

        $block = $this->getLayout()->createBlock('adminhtml/template')
            ->setTemplate('googleshoppingapi/autocomplete.phtml')
            ->assign('items', $taxonomyResults);

        $this->getResponse()->setBody($block->toHtml());
    }
}
