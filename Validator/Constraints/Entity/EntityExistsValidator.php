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

namespace StfalconStudio\ApiBundle\Validator\Constraints\Entity;

use StfalconStudio\ApiBundle\Exception\LogicException;
use StfalconStudio\ApiBundle\Exception\Validator\UnexpectedConstraintException;
use StfalconStudio\ApiBundle\Traits\EntityManagerTrait;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Entity Exists Validator.
 */
class EntityExistsValidator extends ConstraintValidator
{
    use EntityManagerTrait;

    /**
     * @param mixed                   $value
     * @param Constraint|EntityExists $constraint
     *
     * @return void
     */
    public function validate(mixed $value, Constraint|EntityExists $constraint): void
    {
        if (!$constraint instanceof EntityExists) {
            throw new UnexpectedConstraintException($constraint, EntityExists::class);
        }

        $entityClass = $constraint->class;

        try {
            $this->em->getClassMetadata($entityClass);
        } catch (\Exception $exception) {
            throw new LogicException(sprintf('Class %s is not an Entity', $entityClass));
        }

        $repository = $this->em->getRepository($constraint->class);

        if (!$repository->findOneBy([$constraint->property => $value]) instanceof $constraint->class) {
            if (!(\is_int($value) || \is_string($value) || $value instanceof \Stringable)) {
                throw new LogicException(sprintf('Value expected to be int, string or implement %s to find cause of the problem', \Stringable::class));
            }

            $this->context
                ->buildViolation($constraint->message)
                ->setCode(EntityExists::ENTITY_DOES_NOT_EXIST)
                ->setParameter('%property%', $constraint->property)
                ->setParameter('%value%', (string) $value)
                ->setParameter('%class%', $constraint->class)
                ->addViolation()
            ;
        }
    }
}
