<?php
declare(strict_types=1);

namespace Altados\Announcements\Model\ResourceModel\Announcement;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Altados\Announcements\Model\Announcement;
use Altados\Announcements\Model\ResourceModel\Announcement as AnnouncementResource;

/**
 * Announcement Collection Class
 *
 * This class represents a collection of Announcement entities.
 * It provides methods to fetch and filter announcements from the database.
 */
class Collection extends AbstractCollection
{
    /**
     * Initialize collection
     *
     * Define the model and resource model classes for the collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Announcement::class, AnnouncementResource::class);
    }

    /**
     * Add filter for category IDs
     *
     * Filters announcements based on whether they belong to a specific category.
     * Uses MySQL's FIND_IN_SET function to search within the comma-separated category_ids field.
     *
     * @param int $categoryId The category ID to filter by
     * @return $this Returns the collection instance for chaining
     */
    public function addCategoryFilter($categoryId)
    {
        $this->getSelect()->where('FIND_IN_SET(?, category_ids)', $categoryId);
        return $this;
    }
}
