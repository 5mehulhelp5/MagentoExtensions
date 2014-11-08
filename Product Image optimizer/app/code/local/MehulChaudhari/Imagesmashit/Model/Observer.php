<?php
set_time_limit(0);
$path = Mage::getModuleDir('Model', 'MehulChaudhari_Imagesmashit').DS.'Model'.DS.'lib'.DS.'SmushIt.class.php';
require $path;
class MehulChaudhari_Imagesmashit_Model_Observer
{

			public function smashit(Varien_Event_Observer $observer)
			{
			   if(Mage::getStoreConfig('imagesmashit/setting/enable')){
					$ProductImageDir = Mage::getBaseDir('media').DS.'catalog'.DS.'product';
					$productMediaGallery = $observer->getProduct()->getMediaGallery();
					$images = $productMediaGallery['images'];
					foreach($images as $image){
						  $pImage = $ProductImageDir.$image['file'];
						  $default = new SmushIt($pImage, SmushIt::KEEP_ERRORS);
						  $error = $default->error;
						  $src = pathinfo($default->source, PATHINFO_EXTENSION);
						  $dst = pathinfo($default->destination, PATHINFO_EXTENSION);
						  if ($src == $dst AND is_null($error) AND copy($default->destination, $default->source)) {
								 Mage::log($default, null, 'imagesmushit.log');          
						  }
					}
			   }		
			}
		
}
