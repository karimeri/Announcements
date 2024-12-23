<?php

namespace Altados\Announcements\Controller\Adminhtml\Announcement;

use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Altados\Announcements\Model\AnnouncementFactory;
use Altados\Announcements\Model\ResourceModel\Announcement as AnnouncementResource;

class Save extends Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var AnnouncementFactory
     */
    private $announcementFactory;

    /**
     * @var AnnouncementResource
     */
    private $announcementResource;

    /**
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param AnnouncementFactory $announcementFactory
     * @param AnnouncementResource $announcementResource
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        AnnouncementFactory $announcementFactory,
        AnnouncementResource $announcementResource
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->announcementFactory = $announcementFactory;
        $this->announcementResource = $announcementResource;
        parent::__construct($context);
    }

    /**
     * Execute method
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            // Validate dates before proceeding
            if (!$this->validateDates($data)) {
                $this->dataPersistor->set('announcement', $data);
                return $resultRedirect->setPath('*/*/edit', [
                    'announcement_id' => $this->getRequest()->getParam('announcement_id')
                ]);
            }

            // Validate URL if provided
            if (!empty($data['redirect_url']) && !filter_var($data['redirect_url'], FILTER_VALIDATE_URL)) {
                $this->messageManager->addErrorMessage(__('Invalid URL format'));
                return $resultRedirect->setPath('*/*/edit', ['announcement_id' => $this->getRequest()->getParam('announcement_id')]);
            }

            if (empty($data['announcement_id'])) {
                $data['announcement_id'] = null;
            }

            // Convert category_ids array to a comma-separated string
            if (isset($data['category_ids']) && is_array($data['category_ids'])) {
                $data['category_ids'] = implode(',', $data['category_ids']);
            }

            $model = $this->announcementFactory->create();
            $id = $this->getRequest()->getParam('announcement_id');

            if ($id) {
                $this->announcementResource->load($model, $id);
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This announcement no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $this->announcementResource->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the announcement.'));
                $this->dataPersistor->clear('announcement');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['announcement_id' => $model->getId()]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->dataPersistor->set('announcement', $data);

                return $resultRedirect->setPath('*/*/edit', ['announcement_id' => $id]);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Validate dates
     *
     * @param array $data
     * @return bool
     */
    protected function validateDates(array $data): bool
    {
        // Check if dates are present
        if (empty($data['start_date']) || empty($data['end_date'])) {
            $this->messageManager->addErrorMessage(__('Start date and end date are required.'));
            return false;
        }

        try {
            // Convert dates to DateTime objects for proper comparison
            $startDate = new \DateTime($data['start_date']);
            $endDate = new \DateTime($data['end_date']);
            // Get yesterday's date
            $yesterday = new \DateTime('yesterday');
            $yesterday->setTime(23, 59, 59); // Set to end of yesterday

            // Validate start date is not in the past
            if ($startDate < $yesterday) {
                $this->messageManager->addErrorMessage(__('Start date cannot be in the past.'));
                return false;
            }

            // Validate end date is after start date
            if ($endDate < $startDate) {
                $this->messageManager->addErrorMessage(__('End date cannot be earlier than start date.'));
                return false;
            }

            return true;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Invalid date format.'));
            return false;
        }
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Altados_Announcements::announcement_save');
    }
}
