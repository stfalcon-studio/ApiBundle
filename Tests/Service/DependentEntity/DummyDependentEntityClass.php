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

namespace StfalconStudio\ApiBundle\Tests\Service\DependentEntity;

use StfalconStudio\ApiBundle\Attribute\DependentEntity;
use StfalconStudio\ApiBundle\Service\DependentEntity\DependentEntityInterface;

class DummyDependentEntityClass implements DependentEntityInterface
{
    #[DependentEntity(propertyPath: 'name')]
    private ?DummyDependentEntityClass $dependentEntity;

    public function __construct(private readonly string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDependentEntity(): ?DummyDependentEntityClass
    {
        return $this->dependentEntity;
    }

    public function setDependentEntity(?DummyDependentEntityClass $dependentEntity): self
    {
        $this->dependentEntity = $dependentEntity;

        return $this;
    }
}
