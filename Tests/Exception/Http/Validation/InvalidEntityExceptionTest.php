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

namespace StfalconStudio\ApiBundle\Tests\Exception\Http\Validation;

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Exception\Http\Validation\InvalidEntityException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class InvalidEntityExceptionTest extends TestCase
{
    public function testLoggable(): void
    {
        self::assertFalse((new InvalidEntityException($this->createStub(ConstraintViolationListInterface::class)))->loggable());
    }

    public function testConstruct(): void
    {
        $errors = $this->createStub(ConstraintViolationListInterface::class);

        $exception = new InvalidEntityException($errors);

        self::assertSame($errors, $exception->getErrors());
        self::assertSame('invalid_entity_exception_message', $exception->getMessage());
        self::assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $exception->getStatusCode());
        self::assertSame('invalid_entity', $exception->getErrorName());
    }
}
