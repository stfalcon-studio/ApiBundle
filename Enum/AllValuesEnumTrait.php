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
 * AllValuesEnumTrait.
 */
trait AllValuesEnumTrait
{
    /**
     * @return string[]|int[]
     */
    public static function getAllValues(): array
    {
        return array_column(static::cases(), 'value');
    }
}
