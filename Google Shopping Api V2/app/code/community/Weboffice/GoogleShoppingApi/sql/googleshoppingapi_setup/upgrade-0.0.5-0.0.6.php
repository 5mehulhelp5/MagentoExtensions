<?php
/**
 * NOTICE OF LICENSE
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright  Copyright (c) Zookal Services Pte Ltd
 * @author     Cyrill Schumacher @schumacherfm
 * @license    See LICENSE.txt
 */

/** @var $installer Zookal_GShoppingV2_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

/** @var Mage_Catalog_Model_Resource_Setup $catalogSetup */
$catalogSetup = Mage::getResourceModel('catalog/setup', 'core_setup');

$catalogSetup->updateAttribute(
    'catalog_product',
    'google_shopping_category',
    'source_model',
    ''
);

$catalogSetup->updateAttribute(
    'catalog_product',
    'google_shopping_category',
    'frontend_input',
    'googleshoppingautocomplete' // @see Varien_Data_Form_Element_Gsaautocomplete
);

$installer->endSetup();
