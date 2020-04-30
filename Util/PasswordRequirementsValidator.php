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
 * PasswordRequirementsValidator.
 */
class PasswordRequirementsValidator
{
    public const NUMBER_REGEXP = '/[\d]{1,}/';

    public const LOWERCASE_LETTER_REGEXP = '/\p{Ll}{1,}/u';

    public const UPPERCASE_LETTER_REGEXP = '/\p{Lu}{1,}/u';

    /**
     * @param string $password
     *
     * @return bool
     */
    public function isValid(string $password): bool
    {
        return $this->hasAtLeastOneNumber($password)
            && $this->hasAtLeastOneUnicodeUpperCaseLetter($password)
            && $this->hasAtLeastOneUnicodeLowerCaseLetter($password)
        ;
    }

    /**
     * @param string $password
     *
     * @return bool
     */
    private function hasAtLeastOneNumber(string $password): bool
    {
        return (bool) \preg_match(self::NUMBER_REGEXP, $password);
    }

    /**
     * @param string $password
     *
     * @return bool
     */
    private function hasAtLeastOneUnicodeUpperCaseLetter(string $password): bool
    {
        return (bool) \preg_match(self::UPPERCASE_LETTER_REGEXP, $password);
    }

    /**
     * @param string $password
     *
     * @return bool
     */
    private function hasAtLeastOneUnicodeLowerCaseLetter(string $password): bool
    {
        return (bool) \preg_match(self::LOWERCASE_LETTER_REGEXP, $password);
    }
}
