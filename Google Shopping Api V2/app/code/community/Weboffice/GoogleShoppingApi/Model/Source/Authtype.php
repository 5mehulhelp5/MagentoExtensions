<?php
/**
 * @category	Weboffice
 * @package     Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Google Data Api authorization types Source
 *
 * @category	Weboffice
 * @package    Weboffice_GoogleShoppingApi
 * @author     Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
class Weboffice_GoogleShoppingApi_Model_Source_Authtype
{
    /**
     * Retrieve option array with authentification types
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'authsub', 'label' => Mage::helper('googleshoppingapi')->__('AuthSub')),
            array('value' => 'clientlogin', 'label' => Mage::helper('googleshoppingapi')->__('ClientLogin'))
        );
    }
}
