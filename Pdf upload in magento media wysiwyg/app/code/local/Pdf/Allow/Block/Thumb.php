<?php

class Pdf_Allow_Block_Thumb extends Mage_Adminhtml_Block_Cms_Wysiwyg_Images_Content_Files
{

    /**
     * File thumb URL getter
     *
     * @param  Varien_Object $file
     * @return string
     */
    public function getFileThumbUrl(Varien_Object $file)
    {
        $ext = substr($file->getFilename(), strrpos($file->getFilename(), '.') + 1);

        if ($ext == "gif" or $ext == "png" or $ext == "jpg" or $ext == "jpeg") {
            return $file->getThumbUrl();
        } else {
            return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . 'adminhtml/default/default/images/thumb/' . $ext . '.png';
        }

        return $file->getThumbUrl();
    }

}
