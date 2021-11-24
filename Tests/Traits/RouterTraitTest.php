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
use Symfony\Component\Routing\RouterInterface;

final class RouterTraitTest extends TestCase
{
    /** @var RouterInterface|MockObject */
    private $router;

    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->router
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setRouter($this->router);
        self::assertSame($this->router, $this->dummyClass->getRouter());
    }
}
