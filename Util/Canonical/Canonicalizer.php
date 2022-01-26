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
 * Canonicalizer.
 */
class Canonicalizer
{
    private readonly EncodingDetector $encodingDetector;

    /**
     * @param EncodingDetector $encodingDetector
     */
    public function __construct(EncodingDetector $encodingDetector)
    {
        $this->encodingDetector = $encodingDetector;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function canonicalize(string $string): string
    {
        $encoding = $this->encodingDetector->detectEncoding($string);

        if (\is_string($encoding)) {
            $result = \mb_convert_case($string, \MB_CASE_LOWER, $encoding);
        } else {
            $result = \mb_convert_case($string, \MB_CASE_LOWER);
        }

        return $result;
    }
}
