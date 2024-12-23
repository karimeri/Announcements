<?php

namespace Altados\Announcements\Setup\Patch\Schema;

use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class RemoveTitleFromAnnouncements implements SchemaPatchInterface
{
    /**
     * @var SchemaSetupInterface
     */
    private $schemaSetup;

    /**
     * Constructor
     *
     * @param SchemaSetupInterface $schemaSetup
     */
    public function __construct(SchemaSetupInterface $schemaSetup)
    {
        $this->schemaSetup = $schemaSetup;
    }

    /**
     * Apply the patch to remove the field
     *
     * @return void
     */
    public function apply()
    {
        $this->schemaSetup->startSetup();

        $connection = $this->schemaSetup->getConnection();
        $tableName = $this->schemaSetup->getTable('altados_announcements');

        if ($connection->isTableExists($tableName)) {
            $connection->dropColumn($tableName, 'title');
        }

        $this->schemaSetup->endSetup();
    }

    /**
     * Get dependencies for this patch
     *
     * @return array
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * Get aliases for this patch
     *
     * @return array
     */
    public function getAliases()
    {
        return [];
    }
}
