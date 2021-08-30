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

namespace StfalconStudio\ApiBundle\Tests\Util\Canonical;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Util\Canonical\Canonicalizer;
use StfalconStudio\ApiBundle\Util\Canonical\EncodingDetector;

final class CanonicalizerTest extends TestCase
{
    /** @var EncodingDetector|MockObject */
    private $encodingDetector;

    private Canonicalizer $canonicalizer;

    protected function setUp(): void
    {
        $this->encodingDetector = $this->createMock(EncodingDetector::class);
        $this->canonicalizer = new Canonicalizer($this->encodingDetector);
    }

    protected function tearDown(): void
    {
        unset(
            $this->encodingDetector,
            $this->canonicalizer
        );
    }

    /**
     * @param string $rawEmail
     * @param string $canonicalEmail
     *
     * @dataProvider dataProvider
     */
    public function testCanonicalizeWithoutDetectedEncoding(string $rawEmail, string $canonicalEmail): void
    {
        $this->encodingDetector
            ->expects(self::once())
            ->method('detectEncoding')
            ->with($rawEmail)
            ->willReturn(false)
        ;

        self::assertSame($canonicalEmail, $this->canonicalizer->canonicalize($rawEmail));
    }

    /**
     * @param string $rawEmail
     * @param string $canonicalEmail
     *
     * @dataProvider dataProvider
     */
    public function testCanonicalizeWithDetectedEncoding(string $rawEmail, string $canonicalEmail): void
    {
        $this->encodingDetector
            ->expects(self::once())
            ->method('detectEncoding')
            ->with($rawEmail)
            ->willReturn('UTF-8')
        ;

        self::assertSame($canonicalEmail, $this->canonicalizer->canonicalize($rawEmail));
    }

    public static function dataProvider(): iterable
    {
        yield ['TeSt@TeSt.com', 'test@test.com'];
        yield ['test@test.com', 'test@test.com'];
    }
}
