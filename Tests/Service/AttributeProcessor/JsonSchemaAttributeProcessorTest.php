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

namespace StfalconStudio\ApiBundle\Tests\Service\AttributeProcessor;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Exception\InvalidArgumentException;
use StfalconStudio\ApiBundle\Exception\RuntimeException;
use StfalconStudio\ApiBundle\Service\AttributeProcessor\DtoAttributeProcessor;
use StfalconStudio\ApiBundle\Service\AttributeProcessor\JsonSchemaAttributeProcessor;
use StfalconStudio\ApiBundle\Util\File\FileReader;

/**
 * JsonSchemaAttributeProcessorTest.
 */
final class JsonSchemaAttributeProcessorTest extends TestCase
{
    private DtoAttributeProcessor|MockObject $dtoAttributeProcessor;
    private FileReader|MockObject $fileReader;
    private JsonSchemaAttributeProcessor $jsonSchemaAttributeProcessor;

    protected function setUp(): void
    {
        $this->dtoAttributeProcessor = $this->createMock(DtoAttributeProcessor::class);
        $this->fileReader = $this->createMock(FileReader::class);

        $this->jsonSchemaAttributeProcessor = new JsonSchemaAttributeProcessor(
            $this->dtoAttributeProcessor,
            $this->fileReader,
            __DIR__
        );
    }

    protected function tearDown(): void
    {
        unset(
            $this->dtoAttributeProcessor,
            $this->fileReader,
            $this->jsonSchemaAttributeProcessor,
        );
    }

    public function testProcessAttributeForControllerClass(): void
    {
        $this->dtoAttributeProcessor
            ->expects(self::once())
            ->method('processAttributeForClass')
            ->with(TestController::class)
            ->willReturn(TestDto::class)
        ;

        $this->fileReader
            ->expects(self::once())
            ->method('getFileContents')
            ->with(__DIR__.\DIRECTORY_SEPARATOR.'Dummy.json')
            ->willReturn('[1, 2, 3]')
        ;

        $decodedSchema = $this->jsonSchemaAttributeProcessor->processAttributeForControllerClass(TestController::class);
        self::assertSame([1, 2, 3], $decodedSchema);
    }

    public function testExceptionOnNotFoundDirectoryForJsonSchemaFiles(): void
    {
        $jsonSchemaAttributeProcessor = new JsonSchemaAttributeProcessor(
            $this->dtoAttributeProcessor,
            $this->fileReader,
            '/bla-bla-bla'
        );

        $this->dtoAttributeProcessor
            ->expects(self::once())
            ->method('processAttributeForClass')
            ->with(TestController::class)
            ->willReturn(TestDto::class)
        ;

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Directory for json Schema files "/bla-bla-bla" is not found.');

        $jsonSchemaAttributeProcessor->processAttributeForControllerClass(TestController::class);
    }

    public function testExceptionOnMissingAttribute(): void
    {
        $this->dtoAttributeProcessor
            ->expects(self::once())
            ->method('processAttributeForClass')
            ->with(TestController::class)
            ->willReturn(TestDto2::class)
        ;

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Missing JsonSchema attribute for class StfalconStudio\ApiBundle\Tests\Service\AttributeProcessor\TestDto2');

        $this->jsonSchemaAttributeProcessor->processAttributeForControllerClass(TestController::class);
    }

    public function testExceptionOnNotFoundJsonSchema(): void
    {
        $this->dtoAttributeProcessor
            ->expects(self::once())
            ->method('processAttributeForClass')
            ->with(TestController::class)
            ->willReturn(TestDto3::class)
        ;

        $jsonSchemaPath = __DIR__.\DIRECTORY_SEPARATOR.'Fake.json';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(\sprintf('Json Schema file "%s" is not found.', $jsonSchemaPath));

        $this->jsonSchemaAttributeProcessor->processAttributeForControllerClass(TestController::class);
    }

    public function testExceptionOnUnreadableFile(): void
    {
        $this->dtoAttributeProcessor
            ->expects(self::once())
            ->method('processAttributeForClass')
            ->with(TestController::class)
            ->willReturn(TestDto::class)
        ;

        $filename = 'Dummy.json';

        $realPathToFile = __DIR__.\DIRECTORY_SEPARATOR.$filename;
        $this->fileReader
            ->expects(self::once())
            ->method('getFileContents')
            ->with($realPathToFile)
            ->willReturn(false)
        ;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf('Cannot read content of file %s', $realPathToFile));

        $this->jsonSchemaAttributeProcessor->processAttributeForControllerClass(TestController::class);
    }

    public function testCachedProcessedClasses(): void
    {
        $this->dtoAttributeProcessor
            ->expects(self::exactly(2))
            ->method('processAttributeForClass')
            ->with(TestController::class)
            ->willReturn(TestDto4::class)
        ;

        $this->fileReader
            ->expects(self::once())
            ->method('getFileContents')
            ->with(__DIR__.\DIRECTORY_SEPARATOR.'Dummy.json')
            ->willReturn('[1, 2, 3]')
        ;

        $decodedSchema = $this->jsonSchemaAttributeProcessor->processAttributeForControllerClass(TestController::class);
        self::assertSame([1, 2, 3], $decodedSchema);

        $decodedSchema = $this->jsonSchemaAttributeProcessor->processAttributeForControllerClass(TestController::class);
        self::assertSame([1, 2, 3], $decodedSchema);
    }

    public function testExceptionOnMoreThanOneJsonSchemaAttribute(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Detected more than one JsonSchema attribute for class StfalconStudio\ApiBundle\Tests\Service\AttributeProcessor\TestDto5. Only one JsonSchema attribute allowed per class.');

        $this->jsonSchemaAttributeProcessor->processAttributeForDtoClass(TestDto5::class);
    }
}
