<?php

class MehulChaudhari_Theme_Model_Switcher
  extends Mage_Core_Model_Abstract
{

  public function switchTheme($observer)
  {
    $this->setTheme();
  }

  protected function setTheme()
  {
    $session = Mage::getSingleton('customer/session');
    $theme = $session->getData('customer_theme');
    $request = Mage::app()->getRequest();
    if ($request->getParam('__theme')) {
      $theme = $request->getParam('__theme');
      $session->setData('customer_theme', $theme);
    }

    $designInfo = explode('_', $theme);
    if (count($designInfo) != 2) {
      return false;
    }
    $package = $designInfo[0];
    $theme = $designInfo[1];
    $this->_apply($package, $theme);
  }

  public function addSwitcher($observer)
  {
    /** @var $helper MehulChaudhari_Theme_Helper_Data */
    $helper = Mage::helper('themeswitch');
    $in_block = $helper->getSwitcherContainer();
    $className = $helper->getSwitcherClass();
    $layout = Mage::app()->getLayout(); // get main layout
    $container = $layout->getBlock($in_block); // get container block as preferred - right
    if (!$container) { // if no block found
      Mage::log('here');
      return;
    }
    /** @var $switcher MehulChaudhari_Theme_Block_Switcher */
    $switcher = $layout->createBlock('themeswitch/switcher', 'theme_switcher');
    $switcher->assign('class', $className);
    $container->insert($switcher, '', false);
  }

  private function _apply($package, $theme)
  {
    Mage::getSingleton('core/design_package')
        ->setPackageName($package)
        ->setTheme($theme);
  }
}
