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

namespace StfalconStudio\ApiBundle\Tests\Validator\Constraints\Password;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Exception\Validator\UnexpectedConstraintException;
use StfalconStudio\ApiBundle\Tests\Exception\Validator\DummyConstraint;
use StfalconStudio\ApiBundle\Util\PasswordRequirementsValidator;
use StfalconStudio\ApiBundle\Validator\Constraints\Password\PasswordMeetSpecialRequirements;
use StfalconStudio\ApiBundle\Validator\Constraints\Password\PasswordMeetSpecialRequirementsValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

final class PasswordMeetSpecialRequirementsValidatorTest extends TestCase
{
    /** @var ExecutionContextInterface|MockObject */
    private $context;

    /** @var PasswordRequirementsValidator|MockObject */
    private $passwordValidator;

    /** @var PasswordMeetSpecialRequirementsValidator */
    private $validator;

    protected function setUp(): void
    {
        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->passwordValidator = $this->createMock(PasswordRequirementsValidator::class);

        $this->validator = new PasswordMeetSpecialRequirementsValidator($this->passwordValidator);
        $this->validator->initialize($this->context);
    }

    protected function tearDown(): void
    {
        unset(
            $this->context,
            $this->passwordValidator,
            $this->validator,
        );
    }

    public function testValidateIncorrectConstraintClass(): void
    {
        $this->context
            ->expects(self::never())
            ->method('buildViolation')
        ;

        $this->passwordValidator
            ->expects(self::never())
            ->method('isValid')
        ;

        $this->expectException(UnexpectedConstraintException::class);
        $this->expectExceptionMessageMatches('/^Object of class .* is not instance of .*$/');

        $this->validator->validate('foo_password', new DummyConstraint());
    }

    /**
     * @param string|null $password
     *
     * @dataProvider dataProviderForTestValidateWhenPasswordIsEmpty
     */
    public function testValidateWhenPasswordIsEmpty(?string $password): void
    {
        $this->context
            ->expects(self::never())
            ->method('buildViolation')
        ;

        $this->passwordValidator
            ->expects(self::never())
            ->method('isValid')
        ;

        $this->validator->validate($password, new PasswordMeetSpecialRequirements());
    }

    public static function dataProviderForTestValidateWhenPasswordIsEmpty(): iterable
    {
        yield [null];
        yield [''];
    }

    public function testValidateWhenPasswordIsValid(): void
    {
        $password = 'foo_password';
        $this->context
            ->expects(self::never())
            ->method('buildViolation')
        ;

        $this->passwordValidator
            ->expects(self::once())
            ->method('isValid')
            ->with($password)
            ->willReturn(true)
        ;

        $this->validator->validate($password, new PasswordMeetSpecialRequirements());
    }

    public function testValidateWhenPasswordIsInvalid(): void
    {
        $password = 'foo_password';

        $this->passwordValidator
            ->expects(self::once())
            ->method('isValid')
            ->with($password)
            ->willReturn(false)
        ;

        $constraintViolationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $this->context
            ->expects(self::once())
            ->method('buildViolation')
            ->with('password_does_not_meet_special_requirements')
            ->willReturn($constraintViolationBuilder)
        ;

        $constraintViolationBuilder
            ->expects(self::once())
            ->method('setCode')
            ->with('PASSWORD_DOES_NOT_MEET_SPECIAL_REQUIREMENTS')
            ->willReturnSelf()
        ;

        $constraintViolationBuilder
            ->expects(self::once())
            ->method('addViolation')
        ;

        $this->validator->validate($password, new PasswordMeetSpecialRequirements());
    }
}
