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

use App\Exception\Http\Validation\InvalidEntityException;
use App\Service\Exception\ResponseProcessor\InvalidEntityExceptionProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationList;

final class InvalidEntityExceptionProcessorTest extends TestCase
{
    public function testSupports(): void
    {
        $violations = new ConstraintViolationList();
        $exception = new InvalidEntityException($violations);

        $exceptionProcessor = new InvalidEntityExceptionProcessor();

        self::assertTrue($exceptionProcessor->supports($exception));
    }

    public function testNotSupports(): void
    {
        $exception = new DummyCustomAppException();

        $exceptionProcessor = new InvalidEntityExceptionProcessor();

        self::assertFalse($exceptionProcessor->supports($exception));
    }

    public function testProcessResponseIncorrectException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/^Object of class .* is not instance of .*$/');

        $exception = new DummyCustomAppException();

        $exceptionProcessor = new InvalidEntityExceptionProcessor();
        $exceptionProcessor->processResponse($exception);
    }

    public function testProcessResponse(): void
    {
        $violations = new ConstraintViolationList();
        $exception = new InvalidEntityException($violations);

        $exceptionProcessor = new InvalidEntityExceptionProcessor();
        $actual = $exceptionProcessor->processResponse($exception);

        self::assertArrayHasKey('violations', $actual);
        self::assertSame($violations, $actual['violations']);
    }
}
