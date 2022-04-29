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

namespace StfalconStudio\ApiBundle\Serializer;

use StfalconStudio\ApiBundle\Model\UUID\UuidInterface;

/**
 * CircularReferenceHandler.
 */
class CircularReferenceHandler
{
    /**
     * @param UuidInterface $object
     *
     * @return callable
     */
    public function __invoke(UuidInterface $object): callable
    {
        return static function () use ($object) {
            return $object->getId();
        };
    }
}
