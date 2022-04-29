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
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class HttpKernelTraitTest extends TestCase
{
    /** @var HttpKernelInterface|MockObject */
    private HttpKernelInterface|MockObject $httpKernel;

    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->httpKernel = $this->createMock(HttpKernelInterface::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->httpKernel
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setHttpKernel($this->httpKernel);
        self::assertSame($this->httpKernel, $this->dummyClass->getHttpKernel());
    }
}
