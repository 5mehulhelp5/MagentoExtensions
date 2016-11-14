<?php
require_once 'Mage/Adminhtml/controllers/Cms/Wysiwyg/ImagesController.php';

class Pdf_Allow_Cms_Wysiwyg_ImagesController extends Mage_Adminhtml_Cms_Wysiwyg_ImagesController
{

    /**
     * Fire when select image
     */
    public function onInsertAction()
    {
        $helper = Mage::helper('cms/wysiwyg_images');
        $storeId = $this->getRequest()->getParam('store');

        $filename = $this->getRequest()->getParam('filename');
        $filename = $helper->idDecode($filename);
        $asIs = $this->getRequest()->getParam('as_is');

        Mage::helper('catalog')->setStoreId($storeId);
        $helper->setStoreId($storeId);

        $ext = substr($filename, strrpos($filename, '.') + 1);

        if ($ext == "gif" or $ext == "png" or $ext == "jpg" or $ext == "jpeg") {
            $image = $helper->getImageHtmlDeclaration($filename, $asIs);
        } else {
            $image = $helper->getFileHtmlDeclaration($filename, $asIs);
        }

        $this->getResponse()->setBody($image);
    }

}
