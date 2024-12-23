<?php

namespace Altados\Announcements\Model\Announcement;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Altados\Announcements\Model\ResourceModel\Announcement\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Announcement Data Provider
 *
 * Provides data for the announcement UI components in the admin panel.
 * Handles data loading, persistence, and format conversion for form elements.
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var array Cached loaded form data
     */
    protected $loadedData;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * Constructor
     *
     * Initializes the data provider with necessary dependencies and configuration
     *
     * @param string $name Component name
     * @param string $primaryFieldName Primary key field name
     * @param string $requestFieldName Request parameter name
     * @param CollectionFactory $collectionFactory Factory for announcement collection
     * @param DataPersistorInterface $dataPersistor Service for temporary data storage
     * @param array $meta Meta data for UI component
     * @param array $data Additional data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data for UI component
     *
     * Retrieves and formats announcement data for the admin form.
     * Handles both existing records and temporarily persisted data.
     * Converts comma-separated category IDs to arrays for multiselect fields.
     *
     * @return array
     */
    public function getData()
    {
        // Return cached data if available
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        // Load data from collection
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $data = $model->getData();

            // Convert comma-separated category_ids to array for multiselect field
            if (isset($data['category_ids'])) {
                $data['category_ids'] = explode(',', $data['category_ids']);
            }

            $this->loadedData[$model->getId()] = $data;
        }

        // Check for temporarily persisted data
        $data = $this->dataPersistor->get('announcement');
        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('announcement');
        }

        return $this->loadedData;
    }
}
