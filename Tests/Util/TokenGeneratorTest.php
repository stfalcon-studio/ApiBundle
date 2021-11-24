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

namespace StfalconStudio\ApiBundle\Tests\Util;

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Util\TokenGenerator;

final class TokenGeneratorTest extends TestCase
{
    public function testGenerateToken(): void
    {
        self::assertSame(40, mb_strlen(TokenGenerator::generateToken()));
    }
}
