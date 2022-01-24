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

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Service\AttributeProcessor\DtoAttributeProcessor;
use StfalconStudio\ApiBundle\Tests\Attribute\DummyDto;

/**
 * DtoAttributeProcessorTest.
 */
final class DtoAttributeProcessorTest extends TestCase
{
    private DtoAttributeProcessor $dtoAttributeProcessor;

    protected function setUp(): void
    {
        $this->dtoAttributeProcessor = new DtoAttributeProcessor();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dtoAttributeProcessor,
        );
    }

    public function testProcessAnnotationForClass(): void
    {
        $dtoClass = $this->dtoAttributeProcessor->processAttributeForClass(TestClass::class);
        self::assertSame(DummyDto::class, $dtoClass);
    }

    public function testCachedProcessedClasses(): void
    {
        $dtoClass = $this->dtoAttributeProcessor->processAttributeForClass(TestClass::class);
        self::assertSame(DummyDto::class, $dtoClass);

        $dtoClass = $this->dtoAttributeProcessor->processAttributeForClass(TestClass::class);
        self::assertSame(DummyDto::class, $dtoClass);
    }

    public function testExceptionOnMoreThanOneDtoAnnotation(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Detected more than one DTO attribute for class StfalconStudio\ApiBundle\Tests\Service\AttributeProcessor\TestClass2. Only one DTO attribute allowed per class.');

        $this->dtoAttributeProcessor->processAttributeForClass(TestClass2::class);
    }

    public function testExceptionOnMissingDtoAttribute(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Missing DTO attribute for class StfalconStudio\ApiBundle\Tests\Service\AttributeProcessor\TestClass3.');

        $this->dtoAttributeProcessor->processAttributeForClass(TestClass3::class);
    }
}
