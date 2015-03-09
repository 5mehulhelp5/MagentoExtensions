<?php
/**
 * @category	Weboffice
 * @package     Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Google Content Target country Source
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author     Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
class Weboffice_GoogleShoppingApi_Model_Source_Country
{
    /**
     * Retrieve option array with allowed countries
     *
     * @return array
     */
    public function toOptionArray()
    {
        $_allowed = Mage::getSingleton('googleshoppingapi/config')->getAllowedCountries();
        $result = array();
        foreach ($_allowed as $iso => $info) {
            $result[] = array('value' => $iso, 'label' => $info['name']);
        }
        return $result;
    }
}
