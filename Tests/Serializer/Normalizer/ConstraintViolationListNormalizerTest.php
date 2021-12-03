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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Serializer\Normalizer\ConstraintViolationListNormalizer;
use Symfony\Component\Serializer\Normalizer\ConstraintViolationListNormalizer as SymfonyConstraintViolationListNormalizer;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ConstraintViolationListNormalizerTest extends TestCase
{
    /** @var SymfonyConstraintViolationListNormalizer|MockObject */
    private $symfonyNormalizer;

    private ConstraintViolationListNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->symfonyNormalizer = $this->createMock(SymfonyConstraintViolationListNormalizer::class);
        $this->normalizer = new ConstraintViolationListNormalizer($this->symfonyNormalizer);
    }

    protected function tearDown(): void
    {
        unset(
            $this->symfonyNormalizer,
            $this->normalizer,
        );
    }

    /**
     * @param string $originDetail
     * @param string $resultDetail
     *
     * @dataProvider dataProviderForTestNormalize
     */
    public function testNormalize(string $originDetail, string $resultDetail): void
    {
        $object = new \stdClass();
        $format = 'json';
        $context = ['some'];

        $this->symfonyNormalizer
            ->expects(self::once())
            ->method('normalize')
            ->with($object, $format, $context)
            ->willReturn(['detail' => $originDetail])
        ;

        $result = (array) $this->normalizer->normalize($object, $format, $context);

        self::assertArrayHasKey('detail', $result);
        self::assertSame($resultDetail, $result['detail']);
    }

    public static function dataProviderForTestNormalize(): iterable
    {
        yield [
            'origin_detail' => 'field1: Error description.',
            'result_detail' => 'Error description.',
        ];
        yield [
            'origin_detail' => "field1: Error description 1.\nfield2: Error description 2.",
            'result_detail' => "Error description 1.\nError description 2.",
        ];
        yield [
            'origin_detail' => 'Error description.',
            'result_detail' => 'Error description.',
        ];
        yield [
            'origin_detail' => "field1: Error :description 1.\nfield2: Error :description 2.",
            'result_detail' => "Error :description 1.\nError :description 2.",
        ];
    }

    public function testNotSupportsNormalization(): void
    {
        self::assertFalse($this->normalizer->supportsNormalization(new \stdClass()));
    }

    public function testSupportsNormalization(): void
    {
        $error = $this->createMock(ConstraintViolationListInterface::class);

        self::assertTrue($this->normalizer->supportsNormalization($error));
    }
}
