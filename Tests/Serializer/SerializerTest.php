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

namespace StfalconStudio\ApiBundle\Tests\Serializer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * SerializerTest.
 */
final class SerializerTest extends TestCase
{
    /** @var Serializer */
    private $serializer;

    /** @var SerializerInterface|MockObject */
    private $symfonySerializer;

    protected function setUp(): void
    {
        $this->symfonySerializer = $this->createMock(SerializerInterface::class);
        $this->serializer = new Serializer($this->symfonySerializer);
    }

    protected function tearDown(): void
    {
        unset(
            $this->symfonySerializer,
            $this->serializer,
        );
    }

    public function testSerialize(): void
    {
        $object = new \stdClass();
        $serializationGroup = 'group';
        $context = ['test' => 'test'];
        $internalContext = ['test' => 'test', 'group' => 'group', 'json_encode_options' => 4194624];
        $serializedData = 'serialized data';

        $this->symfonySerializer
            ->expects(self::once())
            ->method('serialize')
            ->with($object, 'json', $internalContext)
            ->willReturn($serializedData)
        ;

        $result = $this->serializer->serialize($object, $serializationGroup, $context);

        self::assertSame($serializedData, $result);
    }

    public function testDeserialize(): void
    {
        $deserializedObject = new \stdClass();
        $data = [];
        $type = \stdClass::class;
        $format = 'json';
        $context = [];

        $this->symfonySerializer
            ->expects(self::once())
            ->method('deserialize')
            ->with($data, $type, $format, $context)
            ->willReturn($deserializedObject)
        ;

        $result = $this->serializer->deserialize($data, $type, $format, $context);

        self::assertSame($deserializedObject, $result);
    }
}
