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

namespace StfalconStudio\ApiBundle\Tests\Request;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\DTO\DtoInterface;
use StfalconStudio\ApiBundle\Exception\InvalidArgumentException;
use StfalconStudio\ApiBundle\Request\DtoExtractor;
use StfalconStudio\ApiBundle\Service\AttributeProcessor\DtoAttributeProcessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * DtoExtractorTest.
 */
final class DtoExtractorTest extends TestCase
{
    private SerializerInterface|MockObject $serializer;
    private DtoAttributeProcessor|MockObject $dtoAttributeProcessor;

    private DtoExtractor $dtoExtractor;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->dtoAttributeProcessor = $this->createMock(DtoAttributeProcessor::class);

        $this->dtoExtractor = new DtoExtractor($this->dtoAttributeProcessor, $this->serializer);
    }

    protected function tearDown(): void
    {
        unset(
            $this->serializer,
            $this->dtoAttributeProcessor,
            $this->dtoExtractor,
        );
    }

    #[DataProvider('dataProvider')]
    public function testGetDtoFromRequestWithoutPopulation(?object $objectToPopulate, array $context): void
    {
        $className = 'TestClassName';
        $dtoClassName = 'DtoTestClassName';

        $this->dtoAttributeProcessor
            ->expects(self::once())
            ->method('processAttributeForClass')
            ->with($className)
            ->willReturn($dtoClassName)
        ;

        $content = 'test_content';

        $request = $this->createMock(Request::class);
        $request
            ->expects(self::once())
            ->method('getContent')
            ->willReturn($content)
        ;

        $dtoMock = $this->createStub(DtoInterface::class);

        $this->serializer
            ->expects(self::once())
            ->method('deserialize')
            ->with($content, $dtoClassName, 'json', $context)
            ->willReturn($dtoMock)
        ;

        $dtoResult = $this->dtoExtractor->getDtoFromRequestForControllerClass($request, $className, $objectToPopulate);

        self::assertSame($dtoMock, $dtoResult);
    }

    public static function dataProvider(): iterable
    {
        yield [
            'object_to_populate' => null,
            'context' => [],
        ];
        yield [
            'object_to_populate' => new \stdClass(),
            'context' => [
                AbstractNormalizer::OBJECT_TO_POPULATE => new \stdClass(),
                AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE => true,
            ]
        ];
    }

    public function testExceptionOnDtoWithoutInterface(): void
    {
        $className = 'TestClassName';
        $dtoClassName = 'DtoTestClassName';

        $this->dtoAttributeProcessor
            ->expects(self::once())
            ->method('processAttributeForClass')
            ->with($className)
            ->willReturn($dtoClassName)
        ;

        $content = 'test_content';

        $request = $this->createMock(Request::class);

        $request
            ->expects(self::once())
            ->method('getContent')
            ->willReturn($content)
        ;

        $dtoMock = $this->createStub(\stdClass::class);

        $objectToPopulate = new \stdClass();
        $context = [
            AbstractNormalizer::OBJECT_TO_POPULATE => $objectToPopulate,
            AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE => true,
        ];

        $this->serializer
            ->expects(self::once())
            ->method('deserialize')
            ->with($content, $dtoClassName, 'json', $context)
            ->willReturn($dtoMock)
        ;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('DtoExtractor supports only classes which implement StfalconStudio\ApiBundle\DTO\DtoInterface');

        $dtoResult = $this->dtoExtractor->getDtoFromRequestForControllerClass($request, $className, $objectToPopulate);

        self::assertSame($dtoMock, $dtoResult);
    }
}
