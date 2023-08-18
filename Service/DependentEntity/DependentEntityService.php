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

namespace StfalconStudio\ApiBundle\Service\DependentEntity;

use StfalconStudio\ApiBundle\Attribute\DependentEntity;
use StfalconStudio\ApiBundle\Exception\LogicException;
use StfalconStudio\ApiBundle\Exception\RuntimeException;
use StfalconStudio\ApiBundle\Service\Repository\RepositoryService;
use StfalconStudio\ApiBundle\Traits\PropertyAccessorTrait;

/**
 * Dependent Entity Service.
 */
class DependentEntityService
{
    use PropertyAccessorTrait;

    /**
     * @param RepositoryService $repositoryService
     */
    public function __construct(private readonly RepositoryService $repositoryService)
    {
    }

    /**
     * @param DependentEntityInterface $entity
     *
     * @return void
     */
    public function setDependentEntities(DependentEntityInterface $entity): void
    {
        $class = new \ReflectionClass($entity);

        $properties = $class->getProperties();

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $attributes = $property->getAttributes(DependentEntity::class);

            if (\count($attributes) > 1) {
                throw new RuntimeException(\sprintf('Detected more than one DependentEntity attribute for property %s. Only one DependentEntity attribute allowed per property.', $propertyName));
            }

            if (empty($attributes)) {
                continue;
            }

            $propertyPath = $attributes[0]->getArguments()['propertypath'];

            $propertyPathValue = $this->propertyAccessor->getValue($entity, $propertyPath);

            if (\is_string($propertyPathValue)) {
                $reflectionType = $property->getType();

                if (!$reflectionType instanceof \ReflectionNamedType) {
                    throw new LogicException(\sprintf('Cannot get reflection type from property %s', $propertyName));
                }

                $class = $reflectionType->getName();

                $dependentEntity = $this->repositoryService->getEntityById($propertyPathValue, $class);
            } else {
                $dependentEntity = null;
            }

            $this->propertyAccessor->setValue($entity, $propertyName, $dependentEntity);
        }
    }
}
