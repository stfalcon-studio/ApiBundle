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

namespace StfalconStudio\ApiBundle\Tests\Serializer\Normalizer;

use JsonSchema\Validator as JsonSchemaValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Serializer\Normalizer\JsonSchemaErrorNormalizer;

final class JsonSchemaErrorNormalizerTest extends TestCase
{
    private JsonSchemaValidator $jsonSchemaValidator;

    private JsonSchemaErrorNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->normalizer = new JsonSchemaErrorNormalizer();
        $this->jsonSchemaValidator = new JsonSchemaValidator();
    }

    protected function tearDown(): void
    {
        unset(
            $this->normalizer,
            $this->jsonSchemaValidator,
        );
    }

    public function testNormalizeWhenObjectIncorrect(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/^Object of class .* is not instance of .*$/');

        $this->normalizer->normalize($this->normalizer->normalize(new \stdClass()));
    }

    public function testNormalizeWhenObjectWithoutErrors(): void
    {
        self::assertEmpty($this->normalizer->normalize($this->jsonSchemaValidator));
    }

    #[DataProvider('dataProviderForTestNormalizeWhenObjectWithErrors')]
    public function testNormalizeWhenObjectWithErrors(array $errors, array $expected): void
    {
        $this->jsonSchemaValidator->addErrors($errors);

        self::assertSame($expected, $this->normalizer->normalize($this->jsonSchemaValidator));
    }

    public static function dataProviderForTestNormalizeWhenObjectWithErrors(): iterable
    {
        yield [
            'errors' => [
                [
                    'constraint' => 'NOT_NULL',
                    'property' => 'email',
                    'message' => 'This value should not be null.',
                ],
            ],
            'expected' => [
                'NOT_NULL' => [
                    ['email' => 'This value should not be null.'],
                ],
            ],
        ];

        yield [
            'errors' => [
                [
                    'constraint' => 'NOT_NULL',
                    'property' => 'email',
                    'message' => 'This value should not be null.',
                ],
                [
                    'constraint' => 'IsTrue',
                    'property' => 'is_active',
                    'message' => 'This value should be true.',
                ],
                [
                    'constraint' => 'NOT_NULL',
                    'property' => 'full_name',
                    'message' => 'This value should not be null.',
                ],
            ],
            'expected' => [
                'NOT_NULL' => [
                    ['email' => 'This value should not be null.'],
                    ['full_name' => 'This value should not be null.'],
                ],
                'IsTrue' => [
                    ['is_active' => 'This value should be true.'],
                ],
            ],
        ];
    }

    public function testDoesNotSupportNormalization(): void
    {
        self::assertFalse($this->normalizer->supportsNormalization(new \stdClass()));
    }

    public function testSupportsNormalization(): void
    {
        self::assertTrue($this->normalizer->supportsNormalization($this->jsonSchemaValidator));
    }
}
