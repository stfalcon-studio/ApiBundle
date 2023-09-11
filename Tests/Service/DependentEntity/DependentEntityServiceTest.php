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

namespace StfalconStudio\ApiBundle\Tests\Service\DependentEntity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Service\DependentEntity\DependentEntityService;
use StfalconStudio\ApiBundle\Service\Repository\RepositoryService;
use Symfony\Component\PropertyAccess\PropertyAccessor;

final class DependentEntityServiceTest extends TestCase
{
    /** @var PropertyAccessor|MockObject */
    private PropertyAccessor|MockObject $propertyAccessor;

    /** @var RepositoryService|MockObject */
    private RepositoryService|MockObject $repositoryService;

    private DependentEntityService $dependentEntityService;

    protected function setUp(): void
    {
        $this->repositoryService = $this->createMock(RepositoryService::class);
        $this->propertyAccessor = $this->createMock(PropertyAccessor::class);
        $this->dependentEntityService = new DependentEntityService($this->repositoryService);
        $this->dependentEntityService->setPropertyAccessor($this->propertyAccessor);
    }

    protected function tearDown(): void
    {
        unset(
            $this->repositoryService,
            $this->dependentEntityService,
            $this->propertyAccessor,
        );
    }

    public function testSetDependentEntities(): void
    {
        $entity = new DummyDependentEntityClass(name: 'some name');

        $dependentEntity = $this->createMock(DummyDependentEntityClass::class);

        $this->propertyAccessor
            ->expects(self::once())
            ->method('getValue')
            ->with($entity, 'name')
            ->willReturn('some name')
        ;

        $this->repositoryService
            ->expects(self::once())
            ->method('getEntityById')
            ->with('some name', DummyDependentEntityClass::class)
            ->willReturn($dependentEntity)
        ;

        $this->propertyAccessor
            ->expects(self::once())
            ->method('setValue')
            ->with($entity, 'dependentEntity', $dependentEntity)
        ;

        $this->dependentEntityService->setDependentEntities($entity);
    }

    public function testSetNull(): void
    {
        $entity = new DummyDependentEntityClass(name: 'some name');

        $this->propertyAccessor
            ->expects(self::once())
            ->method('getValue')
            ->with($entity, 'name')
            ->willReturn(null)
        ;

        $this->repositoryService
            ->expects(self::never())
            ->method('getEntityById')
        ;

        $this->propertyAccessor
            ->expects(self::once())
            ->method('setValue')
            ->with($entity, 'dependentEntity', null)
        ;

        $this->dependentEntityService->setDependentEntities($entity);
    }

    public function testEmptyAttribute(): void
    {
        $entity = new DummyDependentEntityClassEmptyAttribute(name: 'some name');

        $this->propertyAccessor
            ->expects(self::never())
            ->method('getValue')
            ->with($entity, 'name')
            ->willReturn(null)
        ;

        $this->repositoryService
            ->expects(self::never())
            ->method('getEntityById')
        ;

        $this->propertyAccessor
            ->expects(self::never())
            ->method('setValue')
            ->with($entity, 'dependentEntity', null)
        ;

        $this->dependentEntityService->setDependentEntities($entity);
    }
}
