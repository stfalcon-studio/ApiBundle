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

namespace StfalconStudio\ApiBundle\Tests\Service\AnnotationProcessor;

use StfalconStudio\ApiBundle\Annotation\DTO;
use StfalconStudio\ApiBundle\Annotation\DtoAnnotationInterface;
use StfalconStudio\ApiBundle\Service\AnnotationProcessor\DtoAnnotationProcessor;
use Doctrine\Common\Annotations\Reader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * DtoAnnotationProcessorTest.
 */
final class DtoAnnotationProcessorTest extends TestCase
{
    /** @var Reader|MockObject */
    private $annotationReader;

    /** @var DtoAnnotationProcessor */
    private $dtoAnnotationProcessor;

    protected function setUp(): void
    {
        $this->annotationReader = $this->createMock(Reader::class);
        $this->dtoAnnotationProcessor = new DtoAnnotationProcessor($this->annotationReader);
    }

    protected function tearDown(): void
    {
        unset(
            $this->annotationReader,
            $this->dtoAnnotationProcessor,
        );
    }

    public function testProcessAnnotationForClass(): void
    {
        $dtoAnnotation = $this->createMock(DtoAnnotationInterface::class);
        $dtoAnnotation
            ->expects(self::exactly(2))
            ->method('getClass')
            ->willReturn('DtoClass')
        ;

        $className = \stdClass::class;

        $this->annotationReader
            ->expects(self::once())
            ->method('getClassAnnotation')
            ->with(new \ReflectionClass($className), DTO::class)
            ->willReturn($dtoAnnotation)
        ;

        $classWithDtoAnnotation = $this->dtoAnnotationProcessor->processAnnotationForClass($className);

        self::assertSame('DtoClass', $classWithDtoAnnotation);
    }

    public function testExceptionOnMissingAnnotation(): void
    {
        $className = \stdClass::class;

        $this->annotationReader
            ->expects(self::once())
            ->method('getClassAnnotation')
            ->with(new \ReflectionClass($className), DTO::class)
            ->willReturn(null)
        ;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Missing DTO annotation for class stdClass');

        $this->dtoAnnotationProcessor->processAnnotationForClass($className);
    }

    public function testCachedProcessedClasses(): void
    {
        $dtoAnnotation = $this->createMock(DtoAnnotationInterface::class);
        $dtoAnnotation
            ->expects(self::exactly(2))
            ->method('getClass')
            ->willReturn('DtoClass')
        ;

        $className = \stdClass::class;

        $this->annotationReader
            ->expects(self::once())
            ->method('getClassAnnotation')
            ->with(new \ReflectionClass($className), DTO::class)
            ->willReturn($dtoAnnotation)
        ;

        $classWithDtoAnnotation = $this->dtoAnnotationProcessor->processAnnotationForClass($className);
        self::assertSame('DtoClass', $classWithDtoAnnotation);

        $classWithDtoAnnotation = $this->dtoAnnotationProcessor->processAnnotationForClass($className);
        self::assertSame('DtoClass', $classWithDtoAnnotation);
    }
}
