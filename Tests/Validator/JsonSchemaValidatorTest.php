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

namespace StfalconStudio\ApiBundle\Tests\Validator;

use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Exception\Http\Json\InvalidJsonSchemaException;
use StfalconStudio\ApiBundle\Exception\Http\Json\MalformedJsonException;
use StfalconStudio\ApiBundle\Service\AttributeProcessor\JsonSchemaAttributeProcessor;
use StfalconStudio\ApiBundle\Validator\JsonSchemaValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

final class JsonSchemaValidatorTest extends TestCase
{
    private Validator|MockObject $validator;
    private JsonSchemaAttributeProcessor|MockObject $jsonSchemaAttributeProcessor;
    private Serializer|MockObject $serializer;
    private JsonSchemaValidator $jsonSchemaValidator;

    protected function setUp(): void
    {
        $this->validator = $this->createMock(Validator::class);
        $this->jsonSchemaAttributeProcessor = $this->createMock(JsonSchemaAttributeProcessor::class);
        $this->serializer = $this->createMock(Serializer::class);

        $this->jsonSchemaValidator = new JsonSchemaValidator($this->validator, $this->jsonSchemaAttributeProcessor);
        $this->jsonSchemaValidator->setSymfonySerializer($this->serializer);
    }

    protected function tearDown(): void
    {
        unset(
            $this->validator,
            $this->jsonSchemaAttributeProcessor,
            $this->serializer,
            $this->jsonSchemaValidator,
        );
    }

    public function testMalformedJson(): void
    {
        $request = $this->createMock(Request::class);
        $request
            ->expects(self::once())
            ->method('getContent')
            ->willReturn('invalid json')
        ;

        $this->expectException(MalformedJsonException::class);
        $this->expectExceptionMessageMatches('/Format of your request is not a valid JSON. Error: .*/');

        $this->jsonSchemaValidator->validateRequestForControllerClass($request, \stdClass::class);
    }

    public function testInvalidJsonSchemaException(): void
    {
        $request = $this->createMock(Request::class);
        $request
            ->expects(self::once())
            ->method('getContent')
            ->willReturn('[1, 2, 3]')
        ;

        $dummyJsonSchema = new \stdClass();

        $this->jsonSchemaAttributeProcessor
            ->expects(self::once())
            ->method('processAttributeForControllerClass')
            ->willReturn($dummyJsonSchema)
        ;

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with([1, 2, 3], $dummyJsonSchema, Constraint::CHECK_MODE_NORMAL)
        ;

        $this->validator
            ->expects(self::once())
            ->method('isValid')
            ->willReturn(false)
        ;

        $violations = [];
        $normalizedJsonSchema = [];

        $matcher = $this->exactly(2);

        $this->serializer
            ->expects(self::exactly(2))
            ->method('normalize')
            ->willReturnCallback(function () use ($matcher, $dummyJsonSchema) {
                return match ($matcher->numberOfInvocations()) {
                    1 => [$this->validator, 'json', ['jsonSchema' => $dummyJsonSchema]],
                    2 => [$dummyJsonSchema, 'object']
                };
            })
            ->will(self::onConsecutiveCalls($violations, $normalizedJsonSchema))
        ;

        $this->expectException(InvalidJsonSchemaException::class);
        $this->expectExceptionObject(new InvalidJsonSchemaException($violations, $normalizedJsonSchema));

        $this->jsonSchemaValidator->validateRequestForControllerClass($request, \stdClass::class);
    }

    public function testExceptionForWrongSerializer(): void
    {
        $serializer = $this->createMock(SerializerInterface::class);
        $this->jsonSchemaValidator->setSymfonySerializer($serializer);

        $request = $this->createMock(Request::class);
        $request
            ->expects(self::once())
            ->method('getContent')
            ->willReturn('[1, 2, 3]')
        ;

        $dummyJsonSchema = new \stdClass();

        $this->jsonSchemaAttributeProcessor
            ->expects(self::once())
            ->method('processAttributeForControllerClass')
            ->willReturn($dummyJsonSchema)
        ;

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with([1, 2, 3], $dummyJsonSchema, Constraint::CHECK_MODE_NORMAL)
        ;

        $this->validator
            ->expects(self::once())
            ->method('isValid')
            ->willReturn(false)
        ;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Serializer is not instance of Symfony\Component\Serializer\Serializer');

        $this->jsonSchemaValidator->validateRequestForControllerClass($request, \stdClass::class);
    }

    public function testValidRequestForControllerClass(): void
    {
        $request = $this->createMock(Request::class);
        $request
            ->expects(self::once())
            ->method('getContent')
            ->willReturn('[1, 2, 3]')
        ;

        $dummyJsonSchema = new \stdClass();

        $this->jsonSchemaAttributeProcessor
            ->expects(self::once())
            ->method('processAttributeForControllerClass')
            ->willReturn($dummyJsonSchema)
        ;

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with([1, 2, 3], $dummyJsonSchema, Constraint::CHECK_MODE_NORMAL)
        ;

        $this->validator
            ->expects(self::once())
            ->method('isValid')
            ->willReturn(true)
        ;

        $this->serializer
            ->expects(self::never())
            ->method('normalize')
        ;

        $this->jsonSchemaValidator->validateRequestForControllerClass($request, \stdClass::class);
    }

    public function testValidRequestForDtoClass(): void
    {
        $request = $this->createMock(Request::class);
        $request
            ->expects(self::once())
            ->method('getContent')
            ->willReturn('[1, 2, 3]')
        ;

        $dummyJsonSchema = new \stdClass();

        $this->jsonSchemaAttributeProcessor
            ->expects(self::once())
            ->method('processAttributeForDtoClass')
            ->willReturn($dummyJsonSchema)
        ;

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with([1, 2, 3], $dummyJsonSchema, Constraint::CHECK_MODE_NORMAL)
        ;

        $this->validator
            ->expects(self::once())
            ->method('isValid')
            ->willReturn(true)
        ;

        $this->serializer
            ->expects(self::never())
            ->method('normalize')
        ;

        $this->jsonSchemaValidator->validateRequestDataForDtoClass($request, \stdClass::class);
    }
}
