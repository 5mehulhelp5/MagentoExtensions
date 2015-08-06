<?php
/**
 * Mehulchaudhari FeedsGenerator Extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Mehulchaudhari
 * @package    Mehulchaudhari_FeedsGenerator
 * @author     Thai Phan
 * @copyright  Copyright (c) 2014 ; ;
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mehulchaudhari_FeedsGenerator_Model_Googleproducts_Source_Taxonomy extends Mage_Eav_Model_Entity_Attribute_Source_Table
{
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = Mage::getModel('feedsgenerator/googleproducts_taxonomy')->toOptionArray();
        }
        return $this->_options;
    }
}
