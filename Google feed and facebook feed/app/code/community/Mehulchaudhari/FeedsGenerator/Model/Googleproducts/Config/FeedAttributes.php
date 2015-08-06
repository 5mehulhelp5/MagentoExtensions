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
 * @author     Jeremy Champion
 * @copyright  Copyright (c) 2014 ; ;
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mehulchaudhari_FeedsGenerator_Model_Googleproducts_Config_FeedAttributes
{
    public $availableFields = array(
        'g:id',
        'g:title',
        'g:link',
		'g:mobile_link',
        'g:price',
		'g:sale_price',
		'g:sale_price_effective_date',
        'g:description',
        'g:condition',
		'g:age_group',
        'g:gtin',
        'g:brand',
        'g:mpn',
        'g:image_link',
		'g:additional_image_link',
        'g:product_type',
        'g:quantity',
        'g:availability',
		'g:availability_date',
		'g:item_group_id',
        'g:feature',
		'g:material',
		'g:pattern',
        'g:online_only',
        'g:manufacturer',
        'g:expiration_date',
		'g:item_group_id',
        'g:shipping_weight',
		'g:shipping_label',
		'g:multipack',
		'g:is_bundle',
		'g:adult',
		'g:adwords_redirect',
		'g:gender',
        'g:product_review_average',
        'g:product_review_count',
        'g:genre',
        'g:featured_product',
        'g:color',
        'g:size',
        'g:year',
        'g:author',
        'g:edition',
		'g:size_type',
		'g:size_system',
		'g:excluded_destination',
        'g:custom_label_0',
        'g:custom_label_1',
        'g:custom_label_2',
        'g:custom_label_3',
        'g:custom_label_4',
		'shippingcountry',
		'shippingservice',
		'shippingprice'
    );

    public $identifierFields = array(
        'g:gtin',
        'g:brand',
        'g:mpn',
    );
}
