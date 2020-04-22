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

namespace StfalconStudio\ApiBundle\Exception\Validator;

use StfalconStudio\ApiBundle\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Constraint;

/**
 * UnexpectedConstraintException.
 */
class UnexpectedConstraintException extends InvalidArgumentException
{
    /**
     * @param Constraint $constraint
     * @param string     $expectedClass
     */
    public function __construct(Constraint $constraint, string $expectedClass)
    {
        parent::__construct(\sprintf('Object of class %s is not instance of %s', \get_class($constraint), $expectedClass));
    }
}
