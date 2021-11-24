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

namespace StfalconStudio\ApiBundle\Validator;

use StfalconStudio\ApiBundle\Exception\Http\Validation\InvalidEntityException;
use StfalconStudio\ApiBundle\Traits\ValidatorTrait;
use Symfony\Component\Validator\Constraint;

/**
 * EntityValidator.
 */
class EntityValidator
{
    use ValidatorTrait;

    /**
     * @param mixed                        $entity
     * @param Constraint|Constraint[]|null $constraints
     * @param array|null                   $groups
     *
     * @throws InvalidEntityException
     */
    public function validate($entity, $constraints = null, array $groups = null): void
    {
        $errors = $this->validator->validate($entity, $constraints, $groups);

        if (\count($errors) > 0) {
            throw new InvalidEntityException($errors);
        }
    }
}
