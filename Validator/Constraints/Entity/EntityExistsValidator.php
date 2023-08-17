<?php

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Validator\Constraints\Entity;

use StfalconStudio\ApiBundle\Exception\Validator\UnexpectedConstraintException;
use StfalconStudio\ApiBundle\Service\Repository\FindOneByIdInterface;
use StfalconStudio\ApiBundle\Traits\EntityManagerTrait;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EntityExistsValidator extends ConstraintValidator
{
    use EntityManagerTrait;

    public function validate(mixed $value, Constraint|EntityExists $constraint): void
    {
        if (!$constraint instanceof EntityExists) {
            throw new UnexpectedConstraintException($constraint, EntityExists::class);
        }

        if (!\is_string($value)) {
            return;
        }

        $repository = $this->em->getRepository($constraint->class); // @phpstan-ignore-line

        if (!$repository instanceof FindOneByIdInterface) {
            return;
        }

        if (!$repository->findOneById($value) instanceof $constraint->class) {
            $this->context
                ->buildViolation($constraint->message)
                ->setCode(EntityExists::ENTITY_DOES_NOT_EXIST)
                ->setParameter('%id%', $value)
                ->addViolation()
            ;
        }
    }
}
