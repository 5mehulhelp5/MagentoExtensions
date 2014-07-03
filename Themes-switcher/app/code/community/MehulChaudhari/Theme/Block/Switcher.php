<?php

class MehulChaudhari_Theme_Block_Switcher extends Mage_Core_Block_Template {

  protected $helper = null;

  public function _construct () {
    parent::_construct();

    $this->setTemplate('themeswitch/switcher.phtml');

    $this->helper = Mage::helper('themeswitch');
  }

  /**
   * @return array;
   */
  public function getThemes()
  {
    $themes =  $this->helper->getThemes();
    $allowed = Mage::getStoreConfig('design/theme_switcher/allowed_themes');
    if(!is_array($allowed)) {
      $allowed = explode(',', $allowed);
    }
    foreach ($themes as $key => $val) {
      if (!in_array($key, $allowed)) {
        unset($themes[$key]);
      }
    }
    return $themes;
  }

  public function getCurrentTheme()
  {
    return $this->helper->getCurrentTheme();
  }

  public function getCurrentUrl($theme)
  {
    return $this->helper->getCurrentUrl($theme);
  }
}
