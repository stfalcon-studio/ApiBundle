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
use Sentry\FlushableClientInterface;

final class SentryClientTraitTest extends TestCase
{
    /** @var FlushableClientInterface|MockObject */
    private $sentryClient;

    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->sentryClient = $this->createMock(FlushableClientInterface::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->sentryClient,
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setSentryClient($this->sentryClient);
        self::assertSame($this->sentryClient, $this->dummyClass->getSentryClient());
    }
}
