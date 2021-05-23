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
 * JsonEncoder.
 */
class JsonEncoder
{
    /**
     * @param mixed[] $messageData
     *
     * @return string
     */
    public static function encodeMessage(array $messageData): string
    {
        return (string) \json_encode($messageData, \JSON_THROW_ON_ERROR | \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES);
    }
}
