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

namespace StfalconStudio\ApiBundle\Tests\Exception\Http\Json;

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Exception\Http\Json\MalformedJsonException;
use Symfony\Component\HttpFoundation\Response;

final class MalformedJsonExceptionTest extends TestCase
{
    public function testLoggable(): void
    {
        self::assertFalse((new MalformedJsonException())->loggable());
    }

    public function testDefaultMessage(): void
    {
        $exception = new MalformedJsonException();

        self::assertEmpty($exception->getMessage());
        self::assertSame(Response::HTTP_BAD_REQUEST, $exception->getStatusCode());
        self::assertSame('malformed_json', $exception->getErrorName());
    }
}
