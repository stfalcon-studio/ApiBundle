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

namespace StfalconStudio\ApiBundle\Security;

/**
 * JwtCacheHelper.
 */
class JwtCacheHelper
{
    /**
     * @param string $username
     * @param string $rawToken
     *
     * @return string
     */
    public function getRedisKeyForUserRawToken(string $username, string $rawToken): string
    {
        return implode('.', [$username, sha1($rawToken)]);
    }
}
