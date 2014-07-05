<?php

class MehulChaudhari_Theme_Model_Source_Themes
{
  public function toOptionArray()
  {
    $themes = Mage::helper('themeswitch')->getThemes();
    foreach ($themes as $code => $name) {
      $themes[$code] = array('value' => $code, 'label' => $name);
    }
    return $themes;
  }
}
