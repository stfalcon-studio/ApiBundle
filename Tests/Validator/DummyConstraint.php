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

namespace StfalconStudio\ApiBundle\Tests\Validator;

use Symfony\Component\Validator\Constraint;

class DummyConstraint extends Constraint
{
    public const INVALID_ERROR = 'cc34-d445';
    public const INVALID_ERROR_NAME = 'INVALID_ERROR';

    protected static $errorNames = [
        self::INVALID_ERROR => self::INVALID_ERROR_NAME,
    ];
}
