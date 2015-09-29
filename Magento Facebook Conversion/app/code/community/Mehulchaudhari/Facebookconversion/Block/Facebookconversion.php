<?php   
class Mehulchaudhari_Facebookconversion_Block_Facebookconversion extends Mage_Core_Block_Template{   



   public function getEnable(){
      return (bool)Mage::getStoreConfig('facebookconversion/settings/enable');
   }
   
   public function getPixelId(){
      return (string)Mage::getStoreConfig('facebookconversion/settings/pixelid');
   }

   public function getCustom(){
      return (bool)Mage::getStoreConfig('facebookconversion/settings/custom');
   }

}
