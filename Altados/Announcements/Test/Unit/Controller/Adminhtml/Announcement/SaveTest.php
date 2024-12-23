<?php

namespace Altados\Announcements\Test\Unit\Controller\Adminhtml\Announcement;

use Altados\Announcements\Controller\Adminhtml\Announcement\Save;
use Altados\Announcements\Model\AnnouncementFactory;
use Altados\Announcements\Model\Announcement;
use Altados\Announcements\Model\ResourceModel\Announcement as AnnouncementResource;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\Framework\Authorization;

class SaveTest extends TestCase
{
    /** @var Save */
    protected Save $saveController;

    /** @var ObjectManager */
    protected ObjectManager $objectManager;

    /** @var Context|\PHPUnit\Framework\MockObject\MockObject */
    protected $context;

    /** @var Http|\PHPUnit\Framework\MockObject\MockObject */
    protected Http $request;

    /** @var RedirectFactory|\PHPUnit\Framework\MockObject\MockObject */
    protected RedirectFactory $resultRedirectFactory;

    /** @var Redirect|\PHPUnit\Framework\MockObject\MockObject */
    protected Redirect $resultRedirect;

    /** @var DataPersistorInterface|\PHPUnit\Framework\MockObject\MockObject */
    protected DataPersistorInterface $dataPersistor;

    /** @var AnnouncementFactory|\PHPUnit\Framework\MockObject\MockObject */
    protected AnnouncementFactory $announcementFactory;

    /** @var AnnouncementResource|\PHPUnit\Framework\MockObject\MockObject */
    protected AnnouncementResource $announcementResource;

    /** @var ManagerInterface|\PHPUnit\Framework\MockObject\MockObject */
    protected ManagerInterface $messageManager;

    /** @var Authorization|\PHPUnit\Framework\MockObject\MockObject */
    protected Authorization $authorization;

    /**
     * Set up test environment.
     */
    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->request = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultRedirectFactory = $this->getMockBuilder(RedirectFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->resultRedirect = $this->getMockBuilder(Redirect::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dataPersistor = $this->getMockBuilder(DataPersistorInterface::class)
            ->getMockForAbstractClass();

        $this->announcementFactory = $this->getMockBuilder(AnnouncementFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->announcementResource = $this->getMockBuilder(AnnouncementResource::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->messageManager = $this->getMockBuilder(ManagerInterface::class)
            ->getMockForAbstractClass();

        $this->authorization = $this->getMockBuilder(Authorization::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->context->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->request);
        $this->context->expects($this->any())
            ->method('getResultRedirectFactory')
            ->willReturn($this->resultRedirectFactory);
        $this->context->expects($this->any())
            ->method('getMessageManager')
            ->willReturn($this->messageManager);
        $this->context->expects($this->any())
            ->method('getAuthorization')
            ->willReturn($this->authorization);

        $this->saveController = $this->objectManager->getObject(
            Save::class,
            [
                'context' => $this->context,
                'dataPersistor' => $this->dataPersistor,
                'announcementFactory' => $this->announcementFactory,
                'announcementResource' => $this->announcementResource
            ]
        );
    }

    public function testExecuteWithInvalidDates()
    {
        $postData = [
            'announcement_id' => null,
            'title' => 'Test Announcement',
            'start_date' => date('Y-m-d', strtotime('-1 day')),
            'end_date' => date('Y-m-d', strtotime('+2 days')),
        ];

        $this->resultRedirectFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->resultRedirect);

        $this->request->expects($this->any())
            ->method('getPostValue')
            ->willReturn($postData);

        $this->request->expects($this->any())
            ->method('getParam')
            ->willReturnMap([
                ['announcement_id', null, null]
            ]);

        $this->messageManager->expects($this->once())
            ->method('addErrorMessage')
            ->with(__('Start date cannot be in the past.'));

        $this->dataPersistor->expects($this->once())
            ->method('set')
            ->with('announcement', $postData);

        $this->resultRedirect->expects($this->once())
            ->method('setPath')
            ->with('*/*/edit', ['announcement_id' => null])
            ->willReturnSelf();

        $this->assertEquals($this->resultRedirect, $this->saveController->execute());
    }

    public function testExecuteWithInvalidUrl()
    {
        $postData = [
            'announcement_id' => null,
            'title' => 'Test Announcement',
            'start_date' => date('Y-m-d', strtotime('+1 day')),
            'end_date' => date('Y-m-d', strtotime('+2 days')),
            'redirect_url' => 'invalid-url'
        ];

        $this->resultRedirectFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->resultRedirect);

        $this->request->expects($this->any())
            ->method('getPostValue')
            ->willReturn($postData);

        $this->request->expects($this->any())
            ->method('getParam')
            ->willReturnMap([
                ['announcement_id', null, null]
            ]);

        $this->messageManager->expects($this->once())
            ->method('addErrorMessage')
            ->with(__('Invalid URL format'));

        $this->resultRedirect->expects($this->once())
            ->method('setPath')
            ->with('*/*/edit', ['announcement_id' => null])
            ->willReturnSelf();

        $this->assertEquals($this->resultRedirect, $this->saveController->execute());
    }

    public function testExecuteWithException()
    {
        $postData = [
            'announcement_id' => null,
            'title' => 'Test Announcement',
            'start_date' => date('Y-m-d', strtotime('+1 day')),
            'end_date' => date('Y-m-d', strtotime('+2 days')),
        ];

        $this->resultRedirectFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->resultRedirect);

        $this->request->expects($this->any())
            ->method('getPostValue')
            ->willReturn($postData);

        $this->request->expects($this->any())
            ->method('getParam')
            ->willReturnMap([
                ['announcement_id', null, null]
            ]);

        $announcement = $this->getMockBuilder(Announcement::class)
            ->disableOriginalConstructor()
            ->setMethods(['setData', 'getId'])
            ->getMock();

        $this->announcementFactory->expects($this->once())
            ->method('create')
            ->willReturn($announcement);

        $exception = new \Exception('Save error');
        $this->announcementResource->expects($this->once())
            ->method('save')
            ->willThrowException($exception);

        $this->messageManager->expects($this->once())
            ->method('addErrorMessage')
            ->with($exception->getMessage());

        $this->dataPersistor->expects($this->once())
            ->method('set')
            ->with('announcement', $postData);

        $this->resultRedirect->expects($this->once())
            ->method('setPath')
            ->with('*/*/edit', ['announcement_id' => null])
            ->willReturnSelf();

        $this->assertEquals($this->resultRedirect, $this->saveController->execute());
    }

    public function testExecuteWithValidData()
    {
        $postData = [
            'announcement_id' => null,
            'title' => 'Test Announcement',
            'start_date' => date('Y-m-d', strtotime('+1 day')),
            'end_date' => date('Y-m-d', strtotime('+2 days')),
            'category_ids' => ['1', '2'],
            'redirect_url' => 'https://example.com'
        ];

        $this->resultRedirectFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->resultRedirect);

        $this->request->expects($this->any())
            ->method('getPostValue')
            ->willReturn($postData);

        $this->request->expects($this->any())
            ->method('getParam')
            ->willReturnMap([
                ['announcement_id', null, null],
                ['back', null, false]
            ]);

        $announcement = $this->getMockBuilder(Announcement::class)
            ->disableOriginalConstructor()
            ->setMethods(['setData', 'getId'])
            ->getMock();

        $announcement->expects($this->once())
            ->method('setData')
            ->willReturnSelf();

        $announcement->expects($this->any())
            ->method('getId')
            ->willReturn(1);

        $this->announcementFactory->expects($this->once())
            ->method('create')
            ->willReturn($announcement);

        $this->messageManager->expects($this->once())
            ->method('addSuccessMessage')
            ->with(__('You saved the announcement.'));

        $this->dataPersistor->expects($this->once())
            ->method('clear')
            ->with('announcement');

        $this->resultRedirect->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $this->assertEquals($this->resultRedirect, $this->saveController->execute());
    }
}
