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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Util\PasswordRequirementsValidator;

/**
 * PasswordRequirementsValidatorTest.
 */
final class PasswordRequirementsValidatorTest extends TestCase
{
    private PasswordRequirementsValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new PasswordRequirementsValidator();
    }

    protected function tearDown(): void
    {
        unset(
            $this->validator,
        );
    }

    #[DataProvider('dataProviderForTestPasswordIsValid')]
    public function testPasswordIsValid(string $password): void
    {
        self::assertTrue($this->validator->isValid($password));
    }

    public static function dataProviderForTestPasswordIsValid(): iterable
    {
        yield ['qwertY1'];
        yield ['QWERTy1'];
        yield ['QWERTy1'];
        yield ['Пароль1'];
        yield ['ПАРОЛь1'];
    }

    #[DataProvider('dataProviderForTestPasswordIsNotValid')]
    public function testPasswordIsNotValid(string $password): void
    {
        self::assertFalse($this->validator->isValid($password));
    }

    public static function dataProviderForTestPasswordIsNotValid(): iterable
    {
        yield ['PASSWORD'];
        yield ['password'];
        yield ['passWORD'];
        yield ['password1'];
        yield ['PASSWORD1'];
        yield ['парольчик'];
        yield ['ПАРОЛЬЧИК'];
    }
}
