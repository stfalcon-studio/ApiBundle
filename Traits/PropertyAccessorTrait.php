<?php

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Traits;

use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Contracts\Service\Attribute\Required;

trait PropertyAccessorTrait
{
    protected PropertyAccessor $propertyAccessor;

    #[Required]
    public function setPropertyAccessor(PropertyAccessor $propertyAccessor): void
    {
        $this->propertyAccessor = $propertyAccessor;
    }
}
