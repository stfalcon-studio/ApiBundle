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

use App\Entity\Order\Transaction\OrderTransaction;
use App\Exception\Http\Payment\InvalidPaymentException;
use App\Service\Exception\ResponseProcessor\InvalidPaymentExceptionProcessor;
use PHPUnit\Framework\TestCase;

final class InvalidPaymentExceptionProcessorTest extends TestCase
{
    public function testSupports(): void
    {
        $exception = new InvalidPaymentException($this->createStub(OrderTransaction::class));

        $exceptionProcessor = new InvalidPaymentExceptionProcessor();

        self::assertTrue($exceptionProcessor->supports($exception));
    }

    public function testNotSupports(): void
    {
        $exception = new DummyCustomAppException();

        $exceptionProcessor = new InvalidPaymentExceptionProcessor();

        self::assertFalse($exceptionProcessor->supports($exception));
    }

    public function testProcessResponseIncorrectException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/^Object of class .* is not instance of .*$/');

        $exception = new DummyCustomAppException();

        $exceptionProcessor = new InvalidPaymentExceptionProcessor();
        $exceptionProcessor->processResponse($exception);
    }

    public function testProcessResponse(): void
    {
        $orderTransaction = $this->createMock(OrderTransaction::class);
        $exception = new InvalidPaymentException($orderTransaction);

        $exceptionProcessor = new InvalidPaymentExceptionProcessor();

        self::assertSame([], $exceptionProcessor->processResponse($exception));
    }
}
