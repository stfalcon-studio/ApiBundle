<?php

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Tests\Request\ValueResolver;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Request\ValueResolver\FilterValueResolver;
use StfalconStudio\ApiBundle\Tests\Request\Filter\DummyFilterExtractor;
use StfalconStudio\ApiBundle\Tests\Request\Filter\DummyFilterModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class FilterValueResolverTest extends TestCase
{
    private FilterValueResolver $filterValueResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->argument = self::createMock(ArgumentMetadata::class);

        $filterValueResolver = new FilterValueResolver([new DummyFilterExtractor()]);

        $this->filterValueResolver = $filterValueResolver;
    }

    public function testFilterExtractorFound(): void
    {
        /** @var Request|MockObject $request */
        $request = self::createMock(Request::class);
        /** @var ArgumentMetadata|MockObject $request */
        $argument = self::createMock(ArgumentMetadata::class);

        $argument
            ->method('getType')
            ->willReturn(DummyFilterModel::class);

        foreach ($this->filterValueResolver->resolve($request, $argument) as $item) {
            self::assertInstanceOf(DummyFilterModel::class, $item);
            self::assertSame('foo', $item->getFoo());
        }
    }

    public function testFilterExtractorNotFound(): void
    {
        /** @var Request|MockObject $request */
        $request = self::createMock(Request::class);
        /** @var ArgumentMetadata|MockObject $request */
        $argument = self::createMock(ArgumentMetadata::class);

        $argument
            ->method('getType')
            ->willReturn('Foo\\Bar');

        $numberOfFoundModels = 0;
        foreach ($this->filterValueResolver->resolve($request, $argument) as $item) {
            ++$numberOfFoundModels;
        }

        self::assertEquals(0, $numberOfFoundModels);
    }
}
