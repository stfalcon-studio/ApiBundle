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

namespace StfalconStudio\ApiBundle\Tests\Service\Exception\ResponseProcessor;

use Symfony\Component\Validator\ConstraintViolation;

class DummyViolation extends ConstraintViolation
{
    public const MESSAGE = 'Error message';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, '', [], null, '', null, null, DummyConstraint::INVALID_ERROR, new DummyConstraint());
    }
}
