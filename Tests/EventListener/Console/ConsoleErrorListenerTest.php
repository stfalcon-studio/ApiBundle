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

namespace StfalconStudio\ApiBundle\Tests\EventListener\Console;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\EventListener\Console\ConsoleErrorListener;
use StfalconStudio\ApiBundle\Exception\Console\CustomConsoleExceptionInterface;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ConsoleErrorListenerTest extends TestCase
{
    /** @var InputInterface|MockObject */
    private InputInterface|MockObject $input;

    /** @var OutputInterface|MockObject */
    private OutputInterface|MockObject $output;

    private ConsoleErrorListener $subscriber;

    protected function setUp(): void
    {
        $this->input = $this->createMock(InputInterface::class);
        $this->output = $this->createMock(OutputInterface::class);
        $this->subscriber = new ConsoleErrorListener();
    }

    protected function tearDown(): void
    {
        unset(
            $this->input,
            $this->output,
            $this->subscriber
        );
    }

    public function testGetSubscribedEvents(): void
    {
        $event = ConsoleErrorListener::getSubscribedEvents();
        self::assertEquals(ConsoleErrorEvent::class, $event->key());
        self::assertEquals('onConsoleError', $event->current());
        $event->next();
        self::assertFalse($event->valid());
    }

    public function testOnUnmatchedConsoleError(): void
    {
        $event = new ConsoleErrorEvent(
            $this->input,
            $this->output,
            $this->createStub(\Exception::class)
        );
        $event->setExitCode(123);
        $this->subscriber->onConsoleError($event);

        self::assertSame(123, $event->getExitCode());
    }

    public function testOnMatchedConsoleError(): void
    {
        $event = new ConsoleErrorEvent(
            $this->input,
            $this->output,
            $this->createStub(CustomConsoleExceptionInterface::class)
        );
        $event->setExitCode(123);
        $this->subscriber->onConsoleError($event);

        self::assertSame(0, $event->getExitCode());
    }
}
