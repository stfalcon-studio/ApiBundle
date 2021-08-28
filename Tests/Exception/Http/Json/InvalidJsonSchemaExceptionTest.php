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
use StfalconStudio\ApiBundle\Exception\Http\Json\InvalidJsonSchemaException;
use Symfony\Component\HttpFoundation\Response;

final class InvalidJsonSchemaExceptionTest extends TestCase
{
    public function testLoggable(): void
    {
        self::assertFalse((new InvalidJsonSchemaException([], []))->loggable());
    }

    public function testConstruct(): void
    {
        $violations = ['first', 'second'];
        $jsonSchema = ['first', 'second'];

        $exception = new InvalidJsonSchemaException($violations, $jsonSchema);

        self::assertSame('invalid_json_schema_exception_message', $exception->getMessage());
        self::assertSame(Response::HTTP_BAD_REQUEST, $exception->getStatusCode());
        self::assertSame('invalid_json_schema', $exception->getErrorName());
        self::assertSame($violations, $exception->getViolations());
        self::assertSame($jsonSchema, $exception->getJsonSchema());
    }
}
