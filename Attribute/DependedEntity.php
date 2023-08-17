<?php

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Attribute;

use Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class DependedEntity
{
    public string $propertyPath;
}
