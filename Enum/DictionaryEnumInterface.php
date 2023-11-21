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
 * DictionaryEnumInterface.
 */
interface DictionaryEnumInterface extends \BackedEnum
{
    /**
     * @return string|null
     */
    public function getPrefix(): ?string;

    /**
     * @return string
     */
    public static function getTranslatorDomain(): string;
}
