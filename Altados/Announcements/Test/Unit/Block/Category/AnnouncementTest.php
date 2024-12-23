<?php
namespace Altados\Announcements\Test\Unit\Block\Category;

use Altados\Announcements\Block\Category\Announcement;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\CategoryRepository;
use Altados\Announcements\Model\ResourceModel\Announcement\CollectionFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\App\RequestInterface;

class AnnouncementTest extends TestCase
{
    /**
     * @var Announcement
     */
    protected $block;

    /**
     * @var CategoryRepository|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $categoryRepository;

    /**
     * @var CollectionFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $collectionFactory;

    /**
     * @var DateTime|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $dateTime;

    /**
     * @var RequestInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $request;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        // Create mock objects
        $context = $this->createMock(Context::class);
        $this->categoryRepository = $this->createMock(CategoryRepository::class);
        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->dateTime = $this->createMock(DateTime::class);
        $this->request = $this->createMock(RequestInterface::class);

        // Configure context mock to return request mock
        $context->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->request);

        $this->block = $objectManager->getObject(
            Announcement::class,
            [
                'context' => $context,
                'categoryRepository' => $this->categoryRepository,
                'announcementCollectionFactory' => $this->collectionFactory,
                'dateTime' => $this->dateTime
            ]
        );
    }

    public function testGetAnnouncements()
    {
        $categoryId = 2;
        $currentDate = '2024-01-01';

        // Mock request to return category ID
        $this->request->expects($this->once())
            ->method('getParam')
            ->with('id')
            ->willReturn($categoryId);

        // Mock datetime
        $this->dateTime->expects($this->once())
            ->method('gmtDate')
            ->willReturn($currentDate);

        // Create collection mock
        $collection = $this->createMock(\Altados\Announcements\Model\ResourceModel\Announcement\Collection::class);

        // Configure collection factory mock
        $this->collectionFactory->expects($this->once())
            ->method('create')
            ->willReturn($collection);

        // Configure collection mock expectations
        $collection->expects($this->exactly(3))
            ->method('addFieldToFilter')
            ->willReturnSelf();

        $result = $this->block->getAnnouncements();
        $this->assertSame($collection, $result);
    }

    public function testGetAnnouncementsWithNoCategoryId()
    {
        // Mock request to return null category ID
        $this->request->expects($this->once())
            ->method('getParam')
            ->with('id')
            ->willReturn(null);

        $result = $this->block->getAnnouncements();
        $this->assertEmpty($result);
    }
}
