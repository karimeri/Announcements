<?php
declare(strict_types=1);

namespace Altados\Announcements\Api\Data;

/**
 * Interface AnnouncementInterface
 *
 * Defines the contract for Announcement entities in the system.
 * This interface provides methods for accessing and modifying announcement data.
 */
interface AnnouncementInterface
{
    /**
     * Constants for announcement data keys
     */
    const ANNOUNCEMENT_ID = 'announcement_id';  // Primary key
    const LABEL = 'label';                      // Announcement label/title
    const CONTENT = 'content';                  // Main content of the announcement
    const REDIRECT_URL = 'redirect_url';        // Optional URL for redirection
    const CATEGORY_IDS = 'category_ids';        // Associated category IDs
    const START_DATE = 'start_date';            // Start date of the announcement
    const END_DATE = 'end_date';                // End date of the announcement

    /**
     * Get announcement label
     *
     * @return string The announcement label
     */
    public function getLabel(): string;

    /**
     * Set announcement label
     *
     * @param string $label The label to set
     * @return void
     */
    public function setLabel(string $label): void;

    /**
     * Get announcement content
     *
     * @return string The announcement content
     */
    public function getContent(): string;

    /**
     * Set announcement content
     *
     * @param string $content The content to set
     * @return void
     */
    public function setContent(string $content): void;

    /**
     * Get redirect URL
     *
     * @return string|null The redirect URL or null if not set
     */
    public function getRedirectUrl(): ?string;

    /**
     * Set redirect URL
     *
     * @param string|null $url The URL to set or null to remove
     * @return void
     */
    public function setRedirectUrl(?string $url): void;

    /**
     * Get category IDs
     *
     * @return array Array of category IDs associated with the announcement
     */
    public function getCategoryIds(): array;

    /**
     * Set category IDs
     *
     * @param array $categoryIds Array of category IDs to associate with the announcement
     * @return void
     */
    public function setCategoryIds(array $categoryIds): void;

    /**
     * Get start date
     *
     * @return string The start date of the announcement
     */
    public function getStartDate(): string;

    /**
     * Set start date
     *
     * @param string $date The start date to set
     * @return void
     */
    public function setStartDate(string $date): void;

    /**
     * Get end date
     *
     * @return string The end date of the announcement
     */
    public function getEndDate(): string;

    /**
     * Set end date
     *
     * @param string $date The end date to set
     * @return void
     */
    public function setEndDate(string $date): void;
}
