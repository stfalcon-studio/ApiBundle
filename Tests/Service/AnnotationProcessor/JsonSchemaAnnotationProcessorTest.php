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

use Doctrine\Common\Annotations\Reader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Annotation\JsonSchema;
use StfalconStudio\ApiBundle\Annotation\JsonSchemaAnnotationInterface;
use StfalconStudio\ApiBundle\Exception\InvalidArgumentException;
use StfalconStudio\ApiBundle\Exception\RuntimeException;
use StfalconStudio\ApiBundle\Service\AnnotationProcessor\DtoAnnotationProcessor;
use StfalconStudio\ApiBundle\Service\AnnotationProcessor\JsonSchemaAnnotationProcessor;
use StfalconStudio\ApiBundle\Util\File\FileReader;

/**
 * JsonSchemaAnnotationProcessorTest.
 */
final class JsonSchemaAnnotationProcessorTest extends TestCase
{
    /** @var Reader|MockObject */
    private $annotationReader;

    /** @var DtoAnnotationProcessor|MockObject */
    private $dtoAnnotationProcessor;

    /** @var FileReader|MockObject */
    private $fileReader;

    /** @var JsonSchemaAnnotationInterface|MockObject */
    private $jsonSchemaAnnotation;

    private JsonSchemaAnnotationProcessor $jsonSchemaAnnotationProcessor;

    protected function setUp(): void
    {
        $this->annotationReader = $this->createMock(Reader::class);
        $this->dtoAnnotationProcessor = $this->createMock(DtoAnnotationProcessor::class);
        $this->fileReader = $this->createMock(FileReader::class);
        $this->jsonSchemaAnnotation = $this->createMock(JsonSchemaAnnotationInterface::class);

        $this->jsonSchemaAnnotationProcessor = new JsonSchemaAnnotationProcessor(
            $this->dtoAnnotationProcessor,
            $this->fileReader,
            $this->annotationReader,
            __DIR__
        );
    }

    protected function tearDown(): void
    {
        unset(
            $this->annotationReader,
            $this->dtoAnnotationProcessor,
            $this->fileReader,
            $this->jsonSchemaAnnotation,
            $this->jsonSchemaAnnotationProcessor,
        );
    }

    public function testProcessAnnotationForControllerClass(): void
    {
        $className = 'TestClass';
        $classWithDtoAnnotation = \stdClass::class;

        $this->dtoAnnotationProcessor
            ->expects(self::once())
            ->method('processAnnotationForClass')
            ->with($className)
            ->willReturn($classWithDtoAnnotation)
        ;

        $filename = 'Dummy.json';
        $this->jsonSchemaAnnotation
            ->expects(self::once())
            ->method('getJsonSchemaFilename')
            ->willReturn($filename)
        ;

        $this->fileReader
            ->expects(self::once())
            ->method('getFileContents')
            ->with(__DIR__.\DIRECTORY_SEPARATOR.$filename)
            ->willReturn('[1, 2, 3]')
        ;

        $this->annotationReader
            ->expects(self::once())
            ->method('getClassAnnotation')
            ->with(new \ReflectionClass($classWithDtoAnnotation), JsonSchema::class)
            ->willReturn($this->jsonSchemaAnnotation)
        ;

        $decodedSchema = $this->jsonSchemaAnnotationProcessor->processAnnotationForControllerClass($className);
        self::assertSame([1, 2, 3], $decodedSchema);
    }

    public function testExceptionOnNotFoundDirectoryForJsonSchemaFiles(): void
    {
        $jsonSchemaAnnotationProcessor = new JsonSchemaAnnotationProcessor(
            $this->dtoAnnotationProcessor,
            $this->fileReader,
            $this->annotationReader,
            '/bla-bla-bla'
        );

        $className = 'TestClass';
        $classWithDtoAnnotation = \stdClass::class;

        $this->dtoAnnotationProcessor
            ->expects(self::once())
            ->method('processAnnotationForClass')
            ->with($className)
            ->willReturn($classWithDtoAnnotation)
        ;

        $this->annotationReader
            ->expects(self::once())
            ->method('getClassAnnotation')
            ->with(new \ReflectionClass($classWithDtoAnnotation), JsonSchema::class)
            ->willReturn($this->jsonSchemaAnnotation)
        ;

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Directory for json Schema files "/bla-bla-bla" is not found.');

        $jsonSchemaAnnotationProcessor->processAnnotationForControllerClass($className);
    }

    public function testExceptionOnMissingAnnotation(): void
    {
        $className = 'TestClass';
        $classWithDtoAnnotation = \stdClass::class;

        $this->dtoAnnotationProcessor
            ->expects(self::once())
            ->method('processAnnotationForClass')
            ->with($className)
            ->willReturn($classWithDtoAnnotation)
        ;

        $this->annotationReader
            ->expects(self::once())
            ->method('getClassAnnotation')
            ->with(new \ReflectionClass($classWithDtoAnnotation), JsonSchema::class)
            ->willReturn(null)
        ;

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Missing Json Schema annotation for class stdClass');

        $this->jsonSchemaAnnotationProcessor->processAnnotationForControllerClass($className);
    }

    public function testExceptionOnNotFoundJsonSchema(): void
    {
        $className = 'TestClass';
        $classWithDtoAnnotation = \stdClass::class;

        $this->dtoAnnotationProcessor
            ->expects(self::once())
            ->method('processAnnotationForClass')
            ->with($className)
            ->willReturn($classWithDtoAnnotation)
        ;

        $filename = 'Fake.json';
        $this->jsonSchemaAnnotation
            ->expects(self::once())
            ->method('getJsonSchemaFilename')
            ->willReturn($filename)
        ;

        $this->annotationReader
            ->expects(self::once())
            ->method('getClassAnnotation')
            ->with(new \ReflectionClass($classWithDtoAnnotation), JsonSchema::class)
            ->willReturn($this->jsonSchemaAnnotation)
        ;

        $jsonSchemaPath = __DIR__.\DIRECTORY_SEPARATOR.$filename;

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(\sprintf('Json Schema file "%s" is not found.', $jsonSchemaPath));

        $this->jsonSchemaAnnotationProcessor->processAnnotationForControllerClass($className);
    }

    public function testExceptionOnUnreadableFile(): void
    {
        $className = 'TestClass';
        $classWithDtoAnnotation = \stdClass::class;

        $this->dtoAnnotationProcessor
            ->expects(self::once())
            ->method('processAnnotationForClass')
            ->with($className)
            ->willReturn($classWithDtoAnnotation)
        ;

        $filename = 'Dummy.json';
        $this->jsonSchemaAnnotation
            ->expects(self::once())
            ->method('getJsonSchemaFilename')
            ->willReturn($filename)
        ;

        $realPathToFile = __DIR__.\DIRECTORY_SEPARATOR.$filename;
        $this->fileReader
            ->expects(self::once())
            ->method('getFileContents')
            ->with($realPathToFile)
            ->willReturn(false)
        ;

        $this->annotationReader
            ->expects(self::once())
            ->method('getClassAnnotation')
            ->with(new \ReflectionClass($classWithDtoAnnotation), JsonSchema::class)
            ->willReturn($this->jsonSchemaAnnotation)
        ;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf('Cannot read content of file %s', $realPathToFile));

        $this->jsonSchemaAnnotationProcessor->processAnnotationForControllerClass($className);
    }

    public function testCachedProcessedClasses(): void
    {
        $className = 'TestClass';
        $classWithDtoAnnotation = \stdClass::class;

        $this->dtoAnnotationProcessor
            ->expects(self::exactly(2))
            ->method('processAnnotationForClass')
            ->with($className)
            ->willReturn($classWithDtoAnnotation)
        ;

        $filename = 'Dummy.json';
        $this->jsonSchemaAnnotation
            ->expects(self::once())
            ->method('getJsonSchemaFilename')
            ->willReturn($filename)
        ;

        $this->fileReader
            ->expects(self::once())
            ->method('getFileContents')
            ->with(__DIR__.\DIRECTORY_SEPARATOR.$filename)
            ->willReturn('[1, 2, 3]')
        ;

        $this->annotationReader
            ->expects(self::once())
            ->method('getClassAnnotation')
            ->with(new \ReflectionClass($classWithDtoAnnotation), JsonSchema::class)
            ->willReturn($this->jsonSchemaAnnotation)
        ;

        $decodedSchema = $this->jsonSchemaAnnotationProcessor->processAnnotationForControllerClass($className);
        self::assertSame([1, 2, 3], $decodedSchema);

        $decodedSchema = $this->jsonSchemaAnnotationProcessor->processAnnotationForControllerClass($className);
        self::assertSame([1, 2, 3], $decodedSchema);
    }
}
