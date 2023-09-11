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

namespace StfalconStudio\ApiBundle\Tests\Service\Repository;

use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Exception\LogicException;
use StfalconStudio\ApiBundle\Service\Repository\RepositoryService;

final class RepositoryServiceTest extends TestCase
{
    /** @var EntityManager|MockObject */
    private EntityManager|MockObject $entityManager;

    private RepositoryService $repositoryService;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->repositoryService = new RepositoryService();
        $this->repositoryService->setEntityManager($this->entityManager);
    }

    protected function tearDown(): void
    {
        unset(
            $this->repositoryService,
            $this->entityManager,
        );
    }

    public function testGetEntityById(): void
    {
        $id = '123';
        $class = 'someClassName';
        $repository = $this->createMock(DummyRepository::class);

        $entity = new \stdClass();

        $this->entityManager
            ->expects(self::once())
            ->method('getRepository')
            ->with($class)
            ->willReturn($repository)
        ;

        $repository
            ->expects(self::once())
            ->method('getOneById')
            ->with($id)
            ->willReturn($entity)
        ;

        $result = $this->repositoryService->getEntityById($id, $class);
        self::assertSame($result, $entity);
    }

    public function testFindEntityById(): void
    {
        $id = '123';
        $class = 'someClassName';
        $repository = $this->createMock(DummyRepository::class);

        $entity = new \stdClass();

        $this->entityManager
            ->expects(self::once())
            ->method('getRepository')
            ->with($class)
            ->willReturn($repository)
        ;

        $repository
            ->expects(self::once())
            ->method('findOneById')
            ->with($id)
            ->willReturn($entity)
        ;

        $result = $this->repositoryService->findEntityById($id, $class);
        self::assertSame($result, $entity);
    }

    public function testGetEntityByIdException(): void
    {
        $id = '123';
        $class = 'someClassName';
        $repository = $this->createMock(DummyRepositoryWithOutInterface::class);

        $this->entityManager
            ->expects(self::once())
            ->method('getRepository')
            ->with($class)
            ->willReturn($repository)
        ;

        $this->expectException(LogicException::class);

        $this->repositoryService->getEntityById($id, $class);
    }

    public function testFindEntityByIdException(): void
    {
        $id = '123';
        $class = 'someClassName';
        $repository = $this->createMock(DummyRepositoryWithOutInterface::class);

        $this->entityManager
            ->expects(self::once())
            ->method('getRepository')
            ->with($class)
            ->willReturn($repository)
        ;

        $this->expectException(LogicException::class);

        $this->repositoryService->findEntityById($id, $class);
    }
}
