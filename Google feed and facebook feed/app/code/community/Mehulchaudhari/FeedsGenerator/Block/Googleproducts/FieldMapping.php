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
* @author     Mehul Chaudhari
* @copyright  Copyright (c) 2014 ; ;
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
class Mehulchaudhari_FeedsGenerator_Block_Googleproducts_FieldMapping extends Mehulchaudhari_FeedsGenerator_Block_FieldMapping
{
    /**
     * Label for feed attribute column on admin page
     *
     * @var string
     */
    protected $_feedFieldLabel = 'Google Products feed tag';

    /**
     * Specifier for model that feed attributes are taken from
     *
     * @var string
     */
    protected $_feedAttributesModelSpecifier = 'feedsgenerator/googleproducts_config_feedAttributes';

    /**
     * Map in some of the values not normally visible
     *
     * @var array
     */
    protected $_magentoOptions = array(
        'is_salable'        => 'is_saleable',
        'manufacturer_name' => 'manufacturer_name',
        'final_price'       => 'final_price'
    );
}
