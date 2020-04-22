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

namespace StfalconStudio\ApiBundle\Tests\Entity\JWT;

use StfalconStudio\ApiBundle\Entity\JWT\RefreshToken;
use PHPUnit\Framework\TestCase;

/**
 * RefreshTokenTest.
 */
final class RefreshTokenTest extends TestCase
{
    /** @var RefreshToken */
    private $refreshToken;

    protected function setUp(): void
    {
        $this->refreshToken = new RefreshToken();
    }

    protected function tearDown(): void
    {
        unset(
            $this->refreshToken,
        );
    }

    public function testConstructor(): void
    {
        self::assertEmpty($this->refreshToken->getId());
        self::assertInstanceOf(\DateTimeImmutable::class, $this->refreshToken->getCreatedAt());
    }
}
