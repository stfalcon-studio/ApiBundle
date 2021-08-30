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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\DTO\DtoInterface;
use StfalconStudio\ApiBundle\Exception\InvalidArgumentException;
use StfalconStudio\ApiBundle\Request\DtoExtractor;
use StfalconStudio\ApiBundle\Service\AnnotationProcessor\DtoAnnotationProcessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * DtoExtractorTest.
 */
final class DtoExtractorTest extends TestCase
{
    /** @var SerializerInterface|MockObject */
    private $serializer;

    /** @var DtoAnnotationProcessor|MockObject */
    private $dtoAnnotationProcessor;

    private DtoExtractor $dtoExtractor;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->dtoAnnotationProcessor = $this->createMock(DtoAnnotationProcessor::class);

        $this->dtoExtractor = new DtoExtractor($this->dtoAnnotationProcessor, $this->serializer);
    }

    protected function tearDown(): void
    {
        unset(
            $this->serializer,
            $this->dtoAnnotationProcessor,
            $this->dtoExtractor,
        );
    }

    /**
     * @param object|null $objectToPopulate
     * @param array       $context
     *
     * @dataProvider dataProvider
     */
    public function testGetDtoFromRequestWithoutPopulation(?object $objectToPopulate, array $context): void
    {
        $className = 'TestClassName';
        $dtoClassName = 'DtoTestClassName';

        $this->dtoAnnotationProcessor
            ->expects(self::once())
            ->method('processAnnotationForClass')
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
        yield [null, []];
        yield [new \stdClass(), ['object_to_populate' => new \stdClass()]];
    }

    public function testExceptionOnDtoWithoutInterface(): void
    {
        $className = 'TestClassName';
        $dtoClassName = 'DtoTestClassName';

        $this->dtoAnnotationProcessor
            ->expects(self::once())
            ->method('processAnnotationForClass')
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
        $context = ['object_to_populate' => $objectToPopulate];

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
