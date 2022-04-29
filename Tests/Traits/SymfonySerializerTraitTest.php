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
use StfalconStudio\ApiBundle\Exception\RuntimeException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

final class SymfonySerializerTraitTest extends TestCase
{
    /** @var SerializerInterface|MockObject */
    private SerializerInterface|MockObject $symfonySerializer;

    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->symfonySerializer = $this->createMock(Serializer::class);
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
        $this->dummyClass->setSymfonySerializer($this->symfonySerializer);
        self::assertSame($this->symfonySerializer, $this->dummyClass->getSymfonySerializer());
    }

    public function testGetterWithException(): void
    {
        $symfonySerializer = $this->createMock(SerializerInterface::class);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Serializer is not instance of Symfony\Component\Serializer\Serializer');

        $this->dummyClass->setSymfonySerializer($symfonySerializer);
        $this->dummyClass->getSymfonySerializer();
    }
}
