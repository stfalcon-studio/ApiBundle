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

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Exception\Http\Json\InvalidJsonSchemaException;
use StfalconStudio\ApiBundle\Service\Exception\ResponseProcessor\InvalidJsonSchemaExceptionProcessor;
use Symfony\Component\Validator\ConstraintViolationList;

final class InvalidJsonSchemaExceptionProcessorTest extends TestCase
{
    public function testSupports(): void
    {
        $violations = new ConstraintViolationList();
        $exception = new InvalidJsonSchemaException([$violations], []);

        $exceptionProcessor = new InvalidJsonSchemaExceptionProcessor('prod');

        self::assertTrue($exceptionProcessor->supports($exception));
    }

    public function testNotSupports(): void
    {
        $exception = new DummyCustomAppException();

        $exceptionProcessor = new InvalidJsonSchemaExceptionProcessor('prod');

        self::assertFalse($exceptionProcessor->supports($exception));
    }

    public function testProcessResponseIncorrectException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/^Object of class .* is not instance of .*$/');

        $exception = new DummyCustomAppException();

        $exceptionProcessor = new InvalidJsonSchemaExceptionProcessor('prod');
        $exceptionProcessor->processResponse($exception);
    }

    public function testProcessResponseForProd(): void
    {
        $violations = new ConstraintViolationList();
        $exception = new InvalidJsonSchemaException([$violations], []);

        $exceptionProcessor = new InvalidJsonSchemaExceptionProcessor('prod');
        $actual = $exceptionProcessor->processResponse($exception);

        self::assertArrayHasKey('violations', $actual);
        self::assertSame([$violations], $actual['violations']);
    }

    public function testProcessResponseForStag(): void
    {
        $jsonSchema = <<<'JSON'
{
    "schema": "http://json-schema.org/draft-06/schema#",
    "title": "DummyCustomer",
    "type": "object",
    "additionalProperties": false,
    "properties": {
        "first_name": {
            "type": "string",
            "minLength": 2,
            "maxLength": 40
        }
    },
    "required": [
        "first_name"
    ]
}
JSON;

        $jsonSchemaDecoded = json_decode($jsonSchema, true);

        $violations = new ConstraintViolationList();
        $exception = new InvalidJsonSchemaException([$violations], $jsonSchemaDecoded);

        $exceptionProcessor = new InvalidJsonSchemaExceptionProcessor('stag');
        $actual = $exceptionProcessor->processResponse($exception);

        self::assertArrayHasKey('violations', $actual);
        self::assertArrayHasKey('jsonSchema', $actual);
        self::assertSame([$violations], $actual['violations']);
        self::assertSame($jsonSchemaDecoded, $actual['jsonSchema']);
    }
}
