<?php

namespace Altados\Announcements\Block\Category;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\CategoryRepository;
use Altados\Announcements\Model\ResourceModel\Announcement\CollectionFactory as AnnouncementCollectionFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Altados\Announcements\Helper\Data as AnnouncementsHelper;

class Announcement extends Template
{
    protected $categoryRepository;
    protected $announcementCollectionFactory;
    protected $dateTime;
    protected $announcementsHelper;

    public function __construct(
        Template\Context $context,
        CategoryRepository $categoryRepository,
        AnnouncementCollectionFactory $announcementCollectionFactory,
        DateTime $dateTime,
        AnnouncementsHelper $announcementsHelper,
        array $data = []
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->announcementCollectionFactory = $announcementCollectionFactory;
        $this->dateTime = $dateTime;
        $this->announcementsHelper = $announcementsHelper;
        parent::__construct($context, $data);
    }

    /**
     * Get announcements for the current category
     *
     * @return \Altados\Announcements\Model\ResourceModel\Announcement\Collection
     */
    public function getAnnouncements()
    {
        $categoryId = $this->getCurrentCategoryId();
        if (!$categoryId) {
            return [];
        }

        $currentDate = $this->dateTime->gmtDate();

        $collection = $this->announcementCollectionFactory->create();
        $collection->addFieldToFilter('category_ids', ['finset' => $categoryId])
            ->addFieldToFilter('start_date', ['lteq' => $currentDate])
            ->addFieldToFilter('end_date', ['gteq' => $currentDate]);

        return $collection;
    }

    /**
     * Get the current category ID
     *
     * @return int|null
     */
    protected function getCurrentCategoryId()
    {
        $category = $this->_request->getParam('id');
        return $category ? (int)$category : null;
    }

    /**
     * Check if announcements are enabled
     *
     * @return bool
     */
    public function canShowAnnouncements()
    {
        return $this->announcementsHelper->isAnnouncementsEnabled();
    }
}
