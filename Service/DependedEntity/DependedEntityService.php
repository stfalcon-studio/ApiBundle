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

namespace StfalconStudio\ApiBundle\Service\DependedEntity;

use StfalconStudio\ApiBundle\Attribute\DependedEntity;
use StfalconStudio\ApiBundle\Exception\RuntimeException;
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
            $propertyName = $property->getName();
            $attributes = $property->getAttributes(DependedEntity::class);

            if (\count($attributes) > 1) {
                throw new RuntimeException(\sprintf('Detected more than one DependedEntity attribute for property %s. Only one DependedEntity attribute allowed per property.', $propertyName));
            }

            if (empty($attributes)) {
                continue;
            }

            $propertyPath = $attributes[0]->getArguments()['propertypath'];

            $propertyPathValue = $this->propertyAccessor->getValue($entity, $propertyPath);

            $dependedEntity = $this->repositoryService->getEntityById($propertyPathValue, $property->getType()->getName());

            $this->propertyAccessor->setValue($entity, $propertyName, $dependedEntity);
        }
    }
}
