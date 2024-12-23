<?php
declare(strict_types=1);

namespace Altados\Announcements\Block\Adminhtml\Announcement\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Delete Button Block
 *
 * Provides the configuration for the "Delete" button in the admin announcement edit form.
 * Implements the ButtonProviderInterface to supply button data for the UI component.
 */
class DeleteButton implements ButtonProviderInterface
{
    /**
     * @var \Magento\Backend\Block\Widget\Context
     */
    protected $context;

    /**
     * Constructor
     *
     * Initializes the class with the context dependency.
     *
     * @param \Magento\Backend\Block\Widget\Context $context Provides access to request and URL builder
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context
    ) {
        $this->context = $context;
    }

    /**
     * Get button configuration data
     *
     * Returns the configuration for the "Delete" button, including label, CSS class,
     * JavaScript confirmation dialog, and sort order.
     *
     * @return array Button configuration data
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getAnnouncementId()) {
            $data = [
                'label' => __('Delete Announcement'), // Button label
                'class' => 'delete', // CSS class for styling
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to delete this announcement?'
                    ) . '\', \'' . $this->getDeleteUrl() . '\')', // Confirmation dialog
                'sort_order' => 20, // Sort order for button placement
            ];
        }
        return $data;
    }

    /**
     * Get delete URL
     *
     * Generates the URL for the delete action, including the announcement ID as a parameter.
     *
     * @return string The delete URL
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['announcement_id' => $this->getAnnouncementId()]);
    }

    /**
     * Get announcement ID
     *
     * Retrieves the announcement ID from the request parameters.
     *
     * @return int|null The announcement ID or null if not set
     */
    public function getAnnouncementId()
    {
        return $this->context->getRequest()->getParam('announcement_id');
    }

    /**
     * Generate a URL
     *
     * Helper method to generate a URL using the context's URL builder.
     *
     * @param string $route The route for the URL
     * @param array $params Additional parameters for the URL
     * @return string The generated URL
     */
    protected function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
