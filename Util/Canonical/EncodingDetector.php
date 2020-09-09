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

namespace StfalconStudio\ApiBundle\Util\Canonical;

/**
 * EncodingDetector.
 */
class EncodingDetector
{
    /**
     * @param string $string
     *
     * @return string|false
     */
    public function detectEncoding(string $string)
    {
        return \mb_detect_encoding($string);
    }
}
