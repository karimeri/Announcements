<?php
declare(strict_types=1);

namespace Altados\Announcements\Block\Adminhtml\Announcement\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Save Button Provider
 *
 * Provides configuration for the primary "Save Announcement" button in the admin announcement edit form.
 * This button triggers the main save action for the announcement form.
 * Implements ButtonProviderInterface to integrate with Magento's UI component framework.
 */
class SaveButton implements ButtonProviderInterface
{
    /**
     * Get button configuration data
     *
     * Provides the configuration array for the primary save button including:
     * - Button label
     * - CSS classes (including primary button styling)
     * - JavaScript initialization and form role
     * - Sort order in the button group
     *
     * @return array Button configuration with the following structure:
     *               - label: Button text
     *               - class: CSS classes for styling
     *               - data_attribute: JavaScript and form role configuration
     *               - sort_order: Position in button group
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save Announcement'),     // Translatable button text
            'class' => 'save primary',              // CSS classes: 'save' for save action, 'primary' for primary button styling
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['event' => 'save'] // Binds to the save event handler
                ],
                'form-role' => 'save',             // Identifies this button as the form's save action
            ],
            'sort_order' => 90,                    // High sort order ensures it appears as the rightmost button
        ];
    }
}
