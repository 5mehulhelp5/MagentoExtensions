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

$input = '';
do {
    $input .= fgets(STDIN);
} while (!feof(STDIN));

$config = json_decode($input);

// Start-up Magento stack
require_once $config->magento_path . '/app/Mage.php';

$_SERVER['SCRIPT_NAME'] = $_SERVER['PHP_SELF'] = $config->magento_path .
        '/app/code/community/' . str_replace('_', '/', $config->child_class) . '.php';

$storeId = $config->store_id;
Mage::app($storeId);

$class = $config->child_class;
$child = new $class($config);
$child->produceBatch();
