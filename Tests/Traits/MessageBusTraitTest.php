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
use Symfony\Component\Messenger\MessageBusInterface;

final class MessageBusTraitTest extends TestCase
{
    /** @var MessageBusInterface|MockObject */
    private $bus;

    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->bus = $this->createMock(MessageBusInterface::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->bus
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setBus($this->bus);
        self::assertSame($this->bus, $this->dummyClass->getBus());
    }
}
