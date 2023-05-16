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

namespace StfalconStudio\ApiBundle\Tests\Traits;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\Repository\RepositoryFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class EntityManagerTraitTest extends TestCase
{
    private EntityManager|MockObject $entityManager;
    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->entityManager,
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setEntityManager($this->entityManager);
        self::assertSame($this->entityManager, $this->dummyClass->getEntityManager());
    }

    public function testReopenClosedEntityManagerForClosedEntityManager(): void
    {
        $this->entityManager
            ->expects(self::once())
            ->method('isOpen')
            ->willReturn(false)
        ;

        $connection = $this->createMock(Connection::class);
        $connection
            ->expects(self::once())
            ->method('getEventManager')
            ->willReturn($this->createStub(EventManager::class))
        ;

        $this->entityManager
            ->expects(self::once())
            ->method('getConnection')
            ->willReturn($connection)
        ;

        $config = $this->createMock(Configuration::class);
        $config
            ->expects(self::once())
            ->method('getMetadataDriverImpl')
            ->willReturn($this->createStub(Cache::class))
        ;
        $config
            ->expects(self::once())
            ->method('getClassMetadataFactoryName')
            ->willReturn($this->createStub(ClassMetadataFactory::class))
        ;
        $config
            ->expects(self::once())
            ->method('getRepositoryFactory')
            ->willReturn($this->createStub(RepositoryFactory::class))
        ;
        $config
            ->expects(self::once())
            ->method('getProxyDir')
            ->willReturn('/tmp')
        ;
        $config
            ->expects(self::once())
            ->method('getProxyNamespace')
            ->willReturn('namespace')
        ;
        $config
            ->expects(self::once())
            ->method('getAutoGenerateProxyClasses')
            ->willReturn(1)
        ;
        $config
            ->method('isSecondLevelCacheEnabled')
            ->willReturn(false)
        ;

        $this->entityManager
            ->expects(self::once())
            ->method('getConfiguration')
            ->willReturn($config)
        ;

        $this->dummyClass->setEntityManager($this->entityManager);
        $this->dummyClass->reopenClosedEntityManager();
        self::assertNotSame($this->entityManager, $this->dummyClass->getEntityManager());
    }

    public function testReopenClosedEntityManagerForOpenEntityManager(): void
    {
        $this->entityManager
            ->expects(self::once())
            ->method('isOpen')
            ->willReturn(true)
        ;

        $this->dummyClass->setEntityManager($this->entityManager);
        $this->dummyClass->reopenClosedEntityManager();
        self::assertSame($this->entityManager, $this->dummyClass->getEntityManager());
    }
}
