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

use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ManagerRegistryTraitTest extends TestCase
{
    /** @var ManagerRegistry|MockObject */
    private ManagerRegistry|MockObject $managerRegistry;

    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->managerRegistry = $this->createMock(ManagerRegistry::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->managerRegistry,
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setManagerRegistry($this->managerRegistry);
        self::assertSame($this->managerRegistry, $this->dummyClass->getManagerRegistry());
    }
}
