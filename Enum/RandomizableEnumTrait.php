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

namespace StfalconStudio\ApiBundle\Enum;

/**
 * RandomizableEnumTrait.
 */
trait RandomizableEnumTrait
{
    /**
     * @return static
     */
    public static function random(): static
    {
        $randomIndex = random_int(1, \count(static::cases()));
        --$randomIndex;

        return static::cases()[$randomIndex];
    }
}
