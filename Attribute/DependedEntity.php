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

namespace StfalconStudio\ApiBundle\Attribute;

use StfalconStudio\ApiBundle\Exception\InvalidArgumentException;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class DependedEntity
{
    private string $propertyPath;

    public function __construct(string $propertyPath)
    {
        if (empty($propertyPath)) {
            throw new InvalidArgumentException('The "propertyPath" parameter can not be empty.');
        }

        $this->propertyPath = $propertyPath;
    }

    public function getPropertyPath(): string
    {
        return $this->propertyPath;
    }
}
