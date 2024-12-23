<?php
declare(strict_types=1);

namespace Altados\Announcements\Model;

use Magento\Framework\Model\AbstractModel;
use Altados\Announcements\Api\Data\AnnouncementInterface;

/**
 * Announcement Model Class
 *
 * This class represents the Announcement entity in the system and handles all related data operations
 */
class Announcement extends AbstractModel implements AnnouncementInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Altados\Announcements\Model\ResourceModel\Announcement::class);
    }

    /**
     * Get announcement label
     *
     * @return string
     */
    public function getLabel(): string
    {
        return (string)$this->getData(self::LABEL);
    }

    /**
     * Set announcement label
     *
     * @param string $label
     * @return void
     */
    public function setLabel(string $label): void
    {
        $this->setData(self::LABEL, $label);
    }

    /**
     * Get announcement content
     *
     * @return string
     */
    public function getContent(): string
    {
        return (string)$this->getData(self::CONTENT);
    }

    /**
     * Set announcement content
     *
     * @param string $content
     * @return void
     */
    public function setContent(string $content): void
    {
        $this->setData(self::CONTENT, $content);
    }

    /**
     * Get redirect URL
     *
     * @return string|null
     */
    public function getRedirectUrl(): ?string
    {
        return $this->getData(self::REDIRECT_URL);
    }

    /**
     * Set redirect URL
     *
     * @param string|null $url
     * @return void
     */
    public function setRedirectUrl(?string $url): void
    {
        $this->setData(self::REDIRECT_URL, $url);
    }

    /**
     * Get category IDs
     *
     * @return array Returns an array of category IDs, empty array if none set
     */
    public function getCategoryIds(): array
    {
        $categoryIds = $this->getData(self::CATEGORY_IDS);
        return $categoryIds ? explode(',', $categoryIds) : [];
    }

    /**
     * Set category IDs
     *
     * @param array $categoryIds Array of category IDs to be stored
     * @return void
     */
    public function setCategoryIds(array $categoryIds): void
    {
        $this->setData(self::CATEGORY_IDS, implode(',', $categoryIds));
    }

    /**
     * Get start date
     *
     * @return string
     */
    public function getStartDate(): string
    {
        return (string)$this->getData(self::START_DATE);
    }

    /**
     * Set start date
     *
     * @param string $date
     * @return void
     */
    public function setStartDate(string $date): void
    {
        $this->setData(self::START_DATE, $date);
    }

    /**
     * Get end date
     *
     * @return string
     */
    public function getEndDate(): string
    {
        return (string)$this->getData(self::END_DATE);
    }

    /**
     * Set end date
     *
     * @param string $date
     * @return void
     */
    public function setEndDate(string $date): void
    {
        $this->setData(self::END_DATE, $date);
    }

    /**
     * Perform actions before saving the model
     * Validates URL format if redirect URL is set
     *
     * @return AbstractModel
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    public function beforeSave()
    {
        if ($this->getRedirectUrl() && !filter_var($this->getRedirectUrl(), FILTER_VALIDATE_URL)) {
            throw new \Magento\Framework\Exception\ValidatorException(__('Invalid URL format'));
        }
        return parent::beforeSave();
    }
}
