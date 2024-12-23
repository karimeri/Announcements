<?php
declare(strict_types=1);

namespace Altados\Announcements\Controller\Adminhtml\Announcement;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Altados\Announcements\Model\AnnouncementFactory;
use Altados\Announcements\Model\ResourceModel\Announcement as AnnouncementResource;

abstract class AbstractAction extends Action
{
    protected $coreRegistry;
    protected $announcementFactory;
    protected $announcementResource;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        AnnouncementFactory $announcementFactory,
        AnnouncementResource $announcementResource
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->announcementFactory = $announcementFactory;
        $this->announcementResource = $announcementResource;
    }

    protected function initAnnouncement()
    {
        $announcementId = (int)$this->getRequest()->getParam('announcement_id');
        $announcement = $this->announcementFactory->create();

        if ($announcementId) {
            $this->announcementResource->load($announcement, $announcementId);
            if (!$announcement->getId()) {
                $this->messageManager->addErrorMessage(__('This announcement no longer exists.'));
                return false;
            }
        }

        $this->coreRegistry->register('current_announcement', $announcement);
        return $announcement;
    }

    protected function _isAllowed()
    {
        $action = $this->getRequest()->getActionName();
        switch ($action) {
            case 'edit':
                return $this->_authorization->isAllowed('Altados_Announcements::announcement_edit');
            case 'delete':
                return $this->_authorization->isAllowed('Altados_Announcements::announcement_delete');
            default:
                return $this->_authorization->isAllowed('Altados_Announcements::announcement_create');
        }
    }
}
