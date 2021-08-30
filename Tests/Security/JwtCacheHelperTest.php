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

namespace StfalconStudio\ApiBundle\Tests\Security;

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Security\JwtCacheHelper;

final class JwtCacheHelperTest extends TestCase
{
    public function testGetRedisKeyForUserRawToken(): void
    {
        $jwtCacheHelper = new JwtCacheHelper();
        $key = $jwtCacheHelper->getRedisKeyForUserRawToken('username', 'raw token');
        self::assertIsString($key);
        self::assertStringStartsWith('username.', $key);
        self::assertSame(40, \strlen(substr($key, 9)));
    }
}
