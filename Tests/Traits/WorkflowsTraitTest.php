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
use Symfony\Component\Workflow\Registry;

final class WorkflowsTraitTest extends TestCase
{
    private Registry|MockObject $registry;
    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(Registry::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->registry
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setWorkflows($this->registry);
        self::assertSame($this->registry, $this->dummyClass->getWorkflows());
    }
}
