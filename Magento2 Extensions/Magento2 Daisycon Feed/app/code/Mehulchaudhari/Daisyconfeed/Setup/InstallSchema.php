<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mehulchaudhari\Daisyconfeed\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'daisyconfeed'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('daisyconfeed')
        )->addColumn(
            'daisyconfeed_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Daisyconfeed Id'
        )->addColumn(
            'daisyconfeed_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            32,
            [],
            'Daisyconfeed Type'
        )->addColumn(
            'daisyconfeed_filename',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            32,
            [],
            'Daisyconfeed Filename'
        )->addColumn(
            'daisyconfeed_path',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Daisyconfeed Path'
        )->addColumn(
            'daisyconfeed_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => true],
            'Daisyconfeed Time'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Store id'
        )->addIndex(
            $installer->getIdxName('daisyconfeed', ['store_id']),
            ['store_id']
        )->addForeignKey(
            $installer->getFkName('daisyconfeed', 'store_id', 'store', 'store_id'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'XML Daisyconfeed'
        );

        $installer->getConnection()->createTable($table);

        $installer->endSetup();

    }
}
