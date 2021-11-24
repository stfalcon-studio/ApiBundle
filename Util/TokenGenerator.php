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

namespace StfalconStudio\ApiBundle\Util;

/**
 * TokenGenerator.
 */
class TokenGenerator
{
    /**
     * @return string
     */
    public static function generateToken(): string
    {
        return sha1(uniqid((string) random_int(1, \PHP_INT_MAX), true));
    }
}
