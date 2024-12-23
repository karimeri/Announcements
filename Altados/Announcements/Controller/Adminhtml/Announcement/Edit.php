<?php
declare(strict_types=1);

namespace Altados\Announcements\Controller\Adminhtml\Announcement;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Altados\Announcements\Model\AnnouncementFactory;
use Altados\Announcements\Model\ResourceModel\Announcement as AnnouncementResource;

class Edit extends AbstractAction
{
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        AnnouncementFactory $announcementFactory,
        AnnouncementResource $announcementResource
    ) {
        parent::__construct($context, $coreRegistry, $announcementFactory, $announcementResource);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $announcement = $this->initAnnouncement();
        if (!$announcement) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Altados_Announcements::announcements');

        $title = $announcement->getId() ? __('Edit Announcement') : __('New Announcement');
        $resultPage->getConfig()->getTitle()->prepend($title);

        return $resultPage;
    }
}
