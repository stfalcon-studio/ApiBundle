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
use Symfony\Component\HttpFoundation\RequestStack;

final class RequestStackTraitTest extends TestCase
{
    /** @var RequestStack|MockObject */
    private RequestStack|MockObject $requestStack;

    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->requestStack
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setRequestStack($this->requestStack);
        self::assertSame($this->requestStack, $this->dummyClass->getRequestStack());
    }
}
