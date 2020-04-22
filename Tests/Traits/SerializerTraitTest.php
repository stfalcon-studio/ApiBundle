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
use StfalconStudio\ApiBundle\Serializer\Serializer;

/**
 * SerializerTraitTest.
 */
final class SerializerTraitTest extends TestCase
{
    /** @var Serializer|MockObject */
    private $serializer;

    /** @var DummyClass */
    private $dummyClass;

    protected function setUp(): void
    {
        $this->serializer = $this->createStub(Serializer::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->serializer,
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setSerializer($this->serializer);
        self::assertSame($this->serializer, $this->dummyClass->getSerializer());
    }
}
