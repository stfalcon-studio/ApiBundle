<?php

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Tests\Command\JWT;

use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Command\JWT\ClearInvalidRefreshTokensCommand;
use StfalconStudio\ApiBundle\Repository\JWT\RefreshTokenRepository;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\SemaphoreStore;

final class ClearInvalidRefreshTokensCommandTest extends TestCase
{
    private EntityManager|MockObject $entityManager;
    private RefreshTokenRepository|MockObject $refreshTokenRepository;
    private Command $command;
    private Application $application;
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->refreshTokenRepository = $this->createMock(RefreshTokenRepository::class);

        $command = new ClearInvalidRefreshTokensCommand($this->refreshTokenRepository);
        $command->setEntityManager($this->entityManager);

        $this->application = new Application();
        $this->application->add($command);
        $this->command = $this->application->find('stfalcon-api-bundle:jwt:clear-refresh-token');
        $this->commandTester = new CommandTester($this->command);
    }

    protected function tearDown(): void
    {
        unset(
            $this->entityManager,
            $this->refreshTokenRepository,
            $this->command,
            $this->application,
            $this->commandTester,
        );
    }

    public function testLock(): void
    {
        $lock = (new LockFactory(new SemaphoreStore()))->createLock('stfalcon-api-bundle:jwt:clear-refresh-token');
        $lock->acquire(true);

        $result = $this->commandTester->execute(['command' => $this->command->getName()]);

        self::assertSame(0, $result);
        $output = $this->commandTester->getDisplay();
        self::assertStringContainsString('The command is already running in another process.', $output);

        $lock->release();
    }

    public function testExecuteWithNoInvalidTokensSkipsBatching(): void
    {
        $this->refreshTokenRepository
            ->expects(self::once())
            ->method('invalidTokensCount')
            ->with(self::isInstanceOf(\DateTimeInterface::class))
            ->willReturn(0)
        ;

        $this->refreshTokenRepository->expects(self::never())->method('deleteInvalid');
        $this->refreshTokenRepository->expects(self::never())->method('findInvalid');
        $this->entityManager->expects(self::never())->method('remove');

        $result = $this->commandTester->execute(['command' => $this->command->getName()]);

        self::assertSame(0, $result);
        $output = $this->commandTester->getDisplay();
        self::assertStringContainsString('Total: 0', $output);
        self::assertStringContainsString('There were no invalid tokens to revoke.', $output);
        self::assertStringContainsString('DONE', $output);
    }

    public function testExecuteWithSqlDeleteRunsBatchedSqlDeleteUntilPartialBatch(): void
    {
        $this->refreshTokenRepository
            ->expects(self::once())
            ->method('invalidTokensCount')
            ->with(self::isInstanceOf(\DateTimeInterface::class))
            ->willReturn(5)
        ;

        $this->refreshTokenRepository
            ->expects(self::exactly(3))
            ->method('deleteInvalid')
            ->with(self::isInstanceOf(\DateTimeInterface::class), 2)
            ->willReturnOnConsecutiveCalls(2, 2, 1)
        ;

        $this->refreshTokenRepository->expects(self::never())->method('findInvalid');
        $this->entityManager->expects(self::never())->method('remove');

        $result = $this->commandTester->execute([
            'command' => $this->command->getName(),
            '--sql-delete' => 'true',
            '--batch-size' => 2,
        ]);

        self::assertSame(0, $result);
        $output = $this->commandTester->getDisplay();
        self::assertStringContainsString('Total: 5', $output);
        self::assertStringContainsString('DONE', $output);
    }

    public function testExecuteWithoutSqlDeleteRemovesEntitiesInBatches(): void
    {
        $this->refreshTokenRepository
            ->expects(self::once())
            ->method('invalidTokensCount')
            ->with(self::isInstanceOf(\DateTimeInterface::class))
            ->willReturn(3)
        ;

        $tokenA = new \stdClass();
        $tokenB = new \stdClass();
        $tokenC = new \stdClass();

        $this->refreshTokenRepository
            ->expects(self::exactly(2))
            ->method('findInvalid')
            ->with(self::isInstanceOf(\DateTimeInterface::class), 2, 0)
            ->willReturnOnConsecutiveCalls(
                [$tokenA, $tokenB],
                [$tokenC],
            )
        ;

        $this->entityManager->expects(self::exactly(3))->method('remove');
        $this->entityManager->expects(self::exactly(2))->method('flush');
        $this->entityManager->expects(self::exactly(2))->method('clear');

        $this->refreshTokenRepository->expects(self::never())->method('deleteInvalid');

        $result = $this->commandTester->execute([
            'command' => $this->command->getName(),
            '--batch-size' => 2,
        ]);

        self::assertSame(0, $result);
        $output = $this->commandTester->getDisplay();
        self::assertStringContainsString('Total: 3', $output);
        self::assertStringContainsString('DONE', $output);
    }

    public function testInitializeFallsBackToDefaultBatchSizeWhenOptionIsNotNumeric(): void
    {
        $this->refreshTokenRepository
            ->expects(self::once())
            ->method('invalidTokensCount')
            ->willReturn(1)
        ;

        $this->refreshTokenRepository
            ->expects(self::once())
            ->method('findInvalid')
            ->with(self::isInstanceOf(\DateTimeInterface::class), 500, 0)
            ->willReturn([])
        ;

        $result = $this->commandTester->execute([
            'command' => $this->command->getName(),
            '--batch-size' => 'not-a-number',
        ]);

        self::assertSame(0, $result);
    }
}
