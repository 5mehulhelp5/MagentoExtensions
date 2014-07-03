<?php

class MehulChaudhari_Theme_Helper_Data
  extends Mage_Core_Helper_Data
{
  public function getThemes()
  {
    $default_base = Mage_Core_Model_Design_Package::BASE_PACKAGE . '_' .
                    Mage_Core_Model_Design_Package::DEFAULT_THEME;
    $default_default = Mage_Core_Model_Design_Package::DEFAULT_PACKAGE . '_' .
                       Mage_Core_Model_Design_Package::DEFAULT_THEME;

    $themes = Mage::getDesign()->getThemeList();

    $result = array();
    foreach ($themes as $package => $theme) {
      if (is_array($theme) && count($theme)) {
        foreach ($theme as $t) {
          $key = $package . '_' . $t;
          if ($default_base == $key) {
            $result[$key] = 'Base';
          } elseif ($default_default == $key) {
            $result[$key] = 'Default';
          } else {
            $result[$key] = ucfirst($t);
          }
        }
      }
    }
    return $result;
  }

  public function getCurrentTheme()
  {
    $package = Mage::getDesign()->getPackageName();
    $theme = Mage::getDesign()->getTheme('template');
    return $package . '_' . $theme;
  }

  public function getCurrentUrl($theme)
  {
    $params = array('__theme' => $theme);
    $url = trim($this->testGetCurrentUrl(), '&?');
//    $url = Mage::app()->getStore()->getCurrentUrl();

    if (strpos($url, '?')) {
      $url .= '&';
    } else {
      $url .= '?';
    }

    $url .= http_build_query($params, '', '&amp;');
    return $url;
  }

  public function getExtBaseDir()
  {
    $dir = '';
    if(defined('__DIR__')) {
      $dir = __DIR__ ;
    }else {
      $dir = dirname(__FILE__);
    }
    $dir = realpath($dir . '/../');
    return rtrim($dir, DIRECTORY_SEPARATOR);
  }

  public function testGetCurrentUrl()
  {
    $domain = $_SERVER['SERVER_NAME'];
    $scheme = (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') ? 'http://' : 'https://';
    $req = $_SERVER['REQUEST_URI'];
    $req = preg_replace('/__theme=[^&]+&?/','', $req);

    return $scheme . $domain . $req;
  }

  public function getSwitcherContainer()
  {
    $block = Mage::getStoreConfig('design/theme_switcher/in_block');
    if(!$block) {
      $block = 'right';
    }

    return $block;
  }

  public function getSwitcherClass()
  {
    $class = Mage::getStoreConfig('design/theme_switcher/class_name');
    if(!$class) {
      $class = 'cm-switcher';
    }
    return $class;
  }
}
