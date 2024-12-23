<?php
declare(strict_types=1);

namespace Altados\Announcements\Controller\Adminhtml\Announcement;

class Delete extends AbstractAction
{
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $id = $this->getRequest()->getParam('announcement_id');
        if ($id) {
            try {
                $model = $this->announcementFactory->create();
                $this->announcementResource->load($model, $id);
                $this->announcementResource->delete($model);

                $this->messageManager->addSuccessMessage(__('The announcement has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['announcement_id' => $id]);
            }
        }

        $this->messageManager->addErrorMessage(__('We can\'t find an announcement to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
