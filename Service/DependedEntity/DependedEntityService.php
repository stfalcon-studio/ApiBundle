<?php

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Service\DependedEntity;

use StfalconStudio\ApiBundle\Attribute\DependedEntity;
use StfalconStudio\ApiBundle\Service\Repository\RepositoryService;
use StfalconStudio\ApiBundle\Traits\PropertyAccessorTrait;

class DependedEntityService
{
    use PropertyAccessorTrait;

    public function __construct(
        private readonly RepositoryService $repositoryService,
    ) {
    }

    public function setDependedEntities(DependedEntityInterface $entity): void
    {
        $class = new \ReflectionClass($entity);

        $properties = $class->getProperties();

        foreach ($properties as $property) {
            $attributes = $property->getAttributes(DependedEntity::class);

            if (empty($attributes)) {
                continue;
            }

            $attribute = $attributes[0];

            $propertyPath = $attribute->getArguments()['propertypath'];

            $propertyPathValue = $this->propertyAccessor->getValue($entity, $propertyPath);

            $dependedEntity = $this->repositoryService->getEntityById($propertyPathValue, $property->getType()->getName());

            $this->propertyAccessor->setValue($entity, $property->getName(), $dependedEntity);
        }
    }
}
