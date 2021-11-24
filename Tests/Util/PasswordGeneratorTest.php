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

namespace StfalconStudio\ApiBundle\Tests\Util;

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Util\PasswordGenerator;
use StfalconStudio\ApiBundle\Util\PasswordRequirementsValidator;

final class PasswordGeneratorTest extends TestCase
{
    public function testGenerateRandomPassword(): void
    {
        $generator = new PasswordGenerator();

        $password = $generator->generateRandomPassword();
        self::assertIsString($password);
        self::assertMatchesRegularExpression(PasswordRequirementsValidator::LOWERCASE_LETTER_REGEXP, $password, 'Does not contain a lowercase letter');
        self::assertMatchesRegularExpression(PasswordRequirementsValidator::UPPERCASE_LETTER_REGEXP, $password, 'Does not contain a uppercase letter');
        self::assertMatchesRegularExpression(PasswordRequirementsValidator::NUMBER_REGEXP, $password, 'Does not contain a number');
    }
}
