<?php
declare(strict_types=1);

namespace Altados\Announcements\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

/**
 * Categories Source Model
 *
 * Provides a list of categories as options for dropdowns or other UI components.
 * Implements the OptionSourceInterface to supply category data in the required format.
 */
class Categories implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * Constructor
     *
     * Initializes the class with the category collection factory dependency.
     *
     * @param CollectionFactory $categoryCollectionFactory Factory for creating category collections
     */
    public function __construct(
        CollectionFactory $categoryCollectionFactory
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * Get options for dropdown or other UI components
     *
     * Fetches all categories, formats them as an array of options with 'value' and 'label' keys.
     * Categories are indented based on their level in the hierarchy.
     *
     * @return array Array of options, each containing 'value' (category ID) and 'label' (category name)
     */
    public function toOptionArray()
    {
        // Create a category collection
        $collection = $this->categoryCollectionFactory->create();

        // Select the 'name' attribute for categories
        $collection->addAttributeToSelect('name');

        // Filter out root categories (level > 0)
        $collection->addFieldToFilter('level', ['gt' => 0]);

        $options = [];

        // Iterate through the collection and build the options array
        foreach ($collection as $category) {
            $options[] = [
                'value' => $category->getId(), // Category ID as the value
                'label' => str_repeat('—', (int)$category->getLevel()) . ' ' . $category->getName() // Indent based on level
            ];
        }

        return $options;
    }
}
