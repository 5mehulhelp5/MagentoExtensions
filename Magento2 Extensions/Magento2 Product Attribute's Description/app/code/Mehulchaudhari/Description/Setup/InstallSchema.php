<?php
namespace Mehulchaudhari\Description\Setup;
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
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $installer->getConnection()->addColumn($installer->getTable('catalog_eav_attribute'),'description',
		    [
			'type'      => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			'nullable'  => true,
			'comment'   => 'Description'
		    ]
        );
        $installer->endSetup();
    }
}
