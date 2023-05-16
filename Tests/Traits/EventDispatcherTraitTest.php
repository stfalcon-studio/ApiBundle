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
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class EventDispatcherTraitTest extends TestCase
{
    private EventDispatcherInterface|MockObject $eventDispatcher;
    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->eventDispatcher
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setEventDispatcher($this->eventDispatcher);
        self::assertSame($this->eventDispatcher, $this->dummyClass->getEventDispatcher());
    }
}
