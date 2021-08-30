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

namespace StfalconStudio\ApiBundle\Validator\Constraints\Password;

use Symfony\Component\Validator\Constraint;

/**
 * PasswordMeetSpecialRequirements.
 *
 * @Annotation
 */
class PasswordMeetSpecialRequirements extends Constraint
{
    public const PASSWORD_DOES_NOT_MEET_SPECIAL_REQUIREMENTS = 'PASSWORD_DOES_NOT_MEET_SPECIAL_REQUIREMENTS';

    public string $message = 'password_does_not_meet_special_requirements';

    /** @var string[] */
    protected static $errorNames = [
        self::PASSWORD_DOES_NOT_MEET_SPECIAL_REQUIREMENTS => self::PASSWORD_DOES_NOT_MEET_SPECIAL_REQUIREMENTS,
    ];
}
