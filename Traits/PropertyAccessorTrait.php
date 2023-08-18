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

namespace StfalconStudio\ApiBundle\Traits;

use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Property Accessor Trait.
 */
trait PropertyAccessorTrait
{
    protected PropertyAccessor $propertyAccessor;

    /**
     * @param PropertyAccessor $propertyAccessor
     *
     * @return void
     */
    #[Required]
    public function setPropertyAccessor(PropertyAccessor $propertyAccessor): void
    {
        $this->propertyAccessor = $propertyAccessor;
    }
}
