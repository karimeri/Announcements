<?php
declare(strict_types=1);

namespace Altados\Announcements\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Announcement Resource Model
 *
 * This class handles database operations for the Announcement entity.
 * It maps the model to the database table 'altados_announcements'
 * and defines 'announcement_id' as the primary key.
 */
class Announcement extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * Define main table name and primary key field
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('altados_announcements', 'announcement_id');
    }
}
