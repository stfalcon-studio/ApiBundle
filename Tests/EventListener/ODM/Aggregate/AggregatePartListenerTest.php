<?php
/*
 * This file is part of the StfalconApiBundle.
 *
 * (c) Stfalcon LLC <stfalcon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Tests\EventListener\ODM\Aggregate;

use Doctrine\ODM\EntityManager;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\UnitOfWork;
use Fresh\DateTime\DateTimeHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\EventListener\ORM\Aggregate\AggregatePartListener;
use StfalconStudio\ApiBundle\Model\ODM\Aggregate\AggregatePartInterface;
use StfalconStudio\ApiBundle\Model\ODM\Aggregate\AggregateRootInterface;

final class AggregatePartListenerTest extends TestCase
{
    private DateTimeHelper|MockObject $dateTimeHelper;
    private AggregatePartListener $listener;

    protected function setUp(): void
    {
        $this->dateTimeHelper = $this->createMock(DateTimeHelper::class);
        $this->listener = new AggregatePartListener($this->dateTimeHelper);
    }

    protected function tearDown(): void
    {
        unset(
            $this->dateTimeHelper,
            $this->listener,
        );
    }

    public function testOnFlush(): void
    {
        $eventArgs = $this->createMock(OnFlushEventArgs::class);

        $em = $this->createMock(EntityManager::class);
        $eventArgs
            ->expects(self::once())
            ->method('getEntityManager')
            ->willReturn($em)
        ;

        $uow = $this->createMock(UnitOfWork::class);
        $em
            ->expects(self::once())
            ->method('getUnitOfWork')
            ->willReturn($uow)
        ;

        $updatedEntity = new \stdClass();
        $insertedEntity = $this->createMock(AggregatePartInterface::class);
        $deletedEntity = new \stdClass();

        $uow
            ->expects(self::once())
            ->method('getScheduledEntityUpdates')
            ->willReturn([$updatedEntity])
        ;

        $uow
            ->expects(self::once())
            ->method('getScheduledEntityInsertions')
            ->willReturn([$insertedEntity])
        ;

        $uow
            ->expects(self::once())
            ->method('getScheduledEntityDeletions')
            ->willReturn([$deletedEntity])
        ;

        $insertedEntityAggregatedRoot = $this->createMock(AggregateRootInterface::class);
        $insertedEntity
            ->expects(self::once())
            ->method('getAggregateRoot')
            ->willReturn($insertedEntityAggregatedRoot)
        ;

        $insertedEntityAggregatedRootId = '123';
        $insertedEntityAggregatedRoot
            ->expects(self::once())
            ->method('getId')
            ->willReturn($insertedEntityAggregatedRootId)
        ;

        $now = new \DateTime();
        $this->dateTimeHelper
            ->expects(self::once())
            ->method('getCurrentDatetime')
            ->willReturn($now)
        ;

        $insertedEntityAggregatedRoot
            ->expects(self::once())
            ->method('setUpdatedAt')
            ->with($now)
        ;

        $classMetadata = $this->createMock(ClassMetadata::class);
        $uow
            ->expects(self::once())
            ->method('recomputeSingleEntityChangeSet')
            ->with($classMetadata, $insertedEntityAggregatedRoot)
        ;

        $em
            ->expects(self::once())
            ->method('getClassMetadata')
            ->with(\get_class($insertedEntityAggregatedRoot))
            ->willReturn($classMetadata)
        ;

        $this->listener->onFlush($eventArgs);
    }
}
