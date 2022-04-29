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

namespace StfalconStudio\ApiBundle\Tests\Service\Exception\ResponseProcessor;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Exception\CustomAppExceptionInterface;
use StfalconStudio\ApiBundle\Service\Exception\ResponseProcessor\CustomAppExceptionResponseProcessorInterface;
use StfalconStudio\ApiBundle\Service\Exception\ResponseProcessor\ExceptionResponseProcessor;

/**
 * ExceptionResponseProcessorTest.
 */
final class ExceptionResponseProcessorTest extends TestCase
{
    /** @var CustomAppExceptionInterface|MockObject */
    private CustomAppExceptionInterface|MockObject $customAppException;

    /** @var CustomAppExceptionResponseProcessorInterface|MockObject */
    private CustomAppExceptionResponseProcessorInterface|MockObject $errorResponseProcessor;

    private ExceptionResponseProcessor $exceptionResponseProcessor;

    protected function setUp(): void
    {
        $this->customAppException = $this->createMock(CustomAppExceptionInterface::class);
        $this->errorResponseProcessor = $this->createMock(CustomAppExceptionResponseProcessorInterface::class);
        $this->exceptionResponseProcessor = new ExceptionResponseProcessor([$this->errorResponseProcessor]);
    }

    protected function tearDown(): void
    {
        unset(
            $this->customAppException,
            $this->errorResponseProcessor,
            $this->exceptionResponseProcessor,
        );
    }

    public function testProcessResponseWithoutProcessors(): void
    {
        $exceptionResponseProcessor = new ExceptionResponseProcessor([]);
        $actual = $exceptionResponseProcessor->processResponseForException($this->customAppException);

        self::assertEmpty($actual);
    }

    public function testProcessResponseIfProcessorIsNotSupported(): void
    {
        $this->errorResponseProcessor
            ->expects(self::once())
            ->method('supports')
            ->willReturn(false)
        ;

        $actual = $this->exceptionResponseProcessor->processResponseForException($this->customAppException);

        self::assertEmpty($actual);
    }

    public function testProcessResponseIfProcessorIsSupported(): void
    {
        $this->errorResponseProcessor
            ->expects(self::once())
            ->method('supports')
            ->willReturn(true)
        ;

        $this->errorResponseProcessor
            ->expects(self::once())
            ->method('processResponse')
        ;

        $actual = $this->exceptionResponseProcessor->processResponseForException($this->customAppException);

        self::assertEmpty($actual);
    }
}
