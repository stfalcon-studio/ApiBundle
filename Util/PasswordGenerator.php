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
 * PasswordGenerator.
 */
class PasswordGenerator
{
    public const DEFAULT_PASSWORD_LENGTH = 12;

    private const LOWERCASE_LETTERS = 'abcdefghijklmnopqrstuvwxyz';
    private const UPPERCASE_LETTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const NUMBERS = '1234567890';

    /**
     * @param int $length
     *
     * @return string
     */
    public function generateRandomPassword(int $length = self::DEFAULT_PASSWORD_LENGTH): string
    {
        $sets = [
            self::LOWERCASE_LETTERS,
            self::UPPERCASE_LETTERS,
            self::NUMBERS,
        ];

        $all = '';
        $password = '';
        foreach ($sets as $set) {
            $arrayOfCharsForSet = str_split($set);

            $randomIndex = random_int(0, \count($arrayOfCharsForSet) - 1);
            $password .= $set[$randomIndex];

            $all .= $set;
        }

        $all = str_split($all);
        $numberOfSets = \count($sets);

        $numberOfIterations = $length - $numberOfSets;
        for ($i = 0; $i < $numberOfIterations; ++$i) {
            $randomIndex = random_int(0, \count($all) - 1);
            $password .= $all[$randomIndex];
        }

        return str_shuffle($password);
    }
}
