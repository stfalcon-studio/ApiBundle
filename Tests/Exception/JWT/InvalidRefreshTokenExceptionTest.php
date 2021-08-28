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

namespace StfalconStudio\ApiBundle\Tests\Exception\Validator;

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Exception\JWT\InvalidRefreshTokenException;
use Symfony\Component\HttpFoundation\Response;

final class InvalidRefreshTokenExceptionTest extends TestCase
{
    public function testLoggable(): void
    {
        self::assertFalse((new InvalidRefreshTokenException())->loggable());
    }

    public function testDefaultMessage(): void
    {
        $exception = new InvalidRefreshTokenException();

        self::assertSame('invalid_refresh_token_exception_message', $exception->getMessage());
        self::assertSame(Response::HTTP_UNAUTHORIZED, $exception->getStatusCode());
        self::assertSame('invalid_refresh_token', $exception->getErrorName());
    }
}
