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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class LoggerTraitTest extends TestCase
{
    /** @var LoggerInterface|MockObject */
    private $logger;

    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->logger
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setLogger($this->logger);
        self::assertSame($this->logger, $this->dummyClass->getLogger());
    }
}
