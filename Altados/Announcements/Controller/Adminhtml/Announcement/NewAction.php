<?php
declare(strict_types=1);

namespace Altados\Announcements\Controller\Adminhtml\Announcement;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Altados\Announcements\Model\AnnouncementFactory;
use Altados\Announcements\Model\ResourceModel\Announcement as AnnouncementResource;

class NewAction extends AbstractAction
{
    protected $resultForwardFactory;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        AnnouncementFactory $announcementFactory,
        AnnouncementResource $announcementResource,
        ForwardFactory $resultForwardFactory
    ) {
        parent::__construct($context, $coreRegistry, $announcementFactory, $announcementResource);
        $this->resultForwardFactory = $resultForwardFactory;
    }

    public function execute()
    {
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}
