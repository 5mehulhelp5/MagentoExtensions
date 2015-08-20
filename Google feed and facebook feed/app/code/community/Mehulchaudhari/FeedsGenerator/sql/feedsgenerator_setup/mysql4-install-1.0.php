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

/** @var $installer Mage_Eav_Model_Entity_Setup */

$installer = $this;
$installer->startSetup();

$installer->run("
  UPDATE {$this->getTable('core_config_data')}
  SET path = REPLACE(path, 'mehulchaudhari_feeds', 'mehulchaudhari_feedsgenerator')
  WHERE path LIKE 'mehulchaudhari_feeds/%';
");

$installer->endSetup();
