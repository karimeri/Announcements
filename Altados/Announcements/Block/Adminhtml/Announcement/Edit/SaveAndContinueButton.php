<?php
declare(strict_types=1);

namespace Altados\Announcements\Block\Adminhtml\Announcement\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Save and Continue Button Provider
 *
 * Provides configuration for the "Save and Continue Edit" button in the admin announcement edit form.
 * This button allows administrators to save their changes and remain on the edit page.
 * Implements ButtonProviderInterface to integrate with Magento's UI components.
 */
class SaveAndContinueButton implements ButtonProviderInterface
{
    /**
     * Get button configuration data
     *
     * Provides the configuration array for the "Save and Continue Edit" button including:
     * - Button label
     * - CSS class
     * - JavaScript initialization data
     * - Sort order in the button group
     *
     * @return array Button configuration with the following structure:
     *               - label: Button text
     *               - class: CSS class for styling
     *               - data_attribute: JavaScript initialization configuration
     *               - sort_order: Position in button group
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save and Continue Edit'),  // Button text displayed to user
            'class' => 'save',                        // CSS class for styling
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['event' => 'saveAndContinueEdit'],  // JavaScript event binding
                ],
            ],
            'sort_order' => 80,  // Position relative to other buttons (higher numbers appear later)
        ];
    }
}
