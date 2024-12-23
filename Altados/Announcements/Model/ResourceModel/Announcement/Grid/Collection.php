<?php
declare(strict_types=1);

namespace Altados\Announcements\Model\ResourceModel\Announcement\Grid;

use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Event\ManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

/**
 * Announcement Grid Collection
 *
 * Collection class specifically designed for the admin grid UI component.
 * Extends SearchResult to provide grid functionality including filtering, sorting, and pagination.
 */
class Collection extends \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
{
    /**
     * @var AggregationInterface
     */
    protected $aggregations;

    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * Collection constructor
     *
     * Initializes the collection with necessary dependencies and configuration
     *
     * @param EntityFactoryInterface $entityFactory Factory for creating entity objects
     * @param LoggerInterface $logger System logger
     * @param FetchStrategyInterface $fetchStrategy Strategy for fetching data
     * @param ManagerInterface $eventManager Event dispatcher
     * @param CategoryCollectionFactory $categoryCollectionFactory Factory for category collections
     * @param string $mainTable Name of the main table
     * @param string $resourceModel Resource model class name
     * @param string|null $identifierName Primary key field name
     * @param string|null $connectionName DB connection name
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        CategoryCollectionFactory $categoryCollectionFactory,
        $mainTable = 'altados_announcements',
        $resourceModel = \Altados\Announcements\Model\ResourceModel\Announcement::class,
        $identifierName = null,
        $connectionName = null
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $mainTable,
            $resourceModel,
            $identifierName,
            $connectionName
        );
    }

    /**
     * Get aggregations
     *
     * @return AggregationInterface
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * Set aggregations
     *
     * @param AggregationInterface $aggregations
     * @return $this
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
        return $this;
    }

    /**
     * Get search criteria
     *
     * Required by SearchResultInterface but not used in grid collections
     *
     * @return \Magento\Framework\Api\SearchCriteriaInterface|null
     */
    public function getSearchCriteria()
    {
        return null;
    }

    /**
     * Set search criteria
     *
     * Required by SearchResultInterface but not used in grid collections
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return $this
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
    {
        return $this;
    }

    /**
     * Get total count of records
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * Set total count
     *
     * Required by SearchResultInterface but not used in grid collections
     *
     * @param int $totalCount
     * @return $this
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * Set items list
     *
     * Required by SearchResultInterface but not used in grid collections
     *
     * @param \Magento\Framework\Api\ExtensibleDataInterface[] $items
     * @return $this
     */
    public function setItems(array $items = null)
    {
        return $this;
    }

    /**
     * Initialize select query
     *
     * Adds mapping for announcement_id to ensure proper filtering
     *
     * @return void
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->addFilterToMap('announcement_id', 'main_table.announcement_id');
    }

    /**
     * Process data after collection load
     *
     * Adds category names to each item based on category IDs
     *
     * @return Collection
     */
    protected function _afterLoad()
    {
        $items = $this->getItems();
        foreach ($items as $item) {
            $categoryIds = $item->getData('category_ids');
            if ($categoryIds) {
                $categoryNames = $this->getCategoryNames(explode(',', $categoryIds));
                $item->setData('category_names', implode(', ', $categoryNames));
            }
        }
        return parent::_afterLoad();
    }

    /**
     * Get category names by their IDs
     *
     * Fetches category names from the database using provided category IDs
     *
     * @param array $categoryIds Array of category IDs
     * @return array Array of category names
     */
    protected function getCategoryNames(array $categoryIds): array
    {
        $categoryCollection = $this->categoryCollectionFactory->create();
        $categoryCollection->addAttributeToSelect('name')
            ->addFieldToFilter('entity_id', ['in' => $categoryIds]);

        $categoryNames = [];
        foreach ($categoryCollection as $category) {
            $categoryNames[] = $category->getName();
        }

        return $categoryNames;
    }
}
