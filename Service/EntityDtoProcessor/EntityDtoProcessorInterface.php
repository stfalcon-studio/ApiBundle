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

namespace StfalconStudio\ApiBundle\Service\EntityDtoProcessor;

use StfalconStudio\ApiBundle\DTO\DtoInterface;
use StfalconStudio\ApiBundle\DTO\UpdateDtoInterface;

/**
 * EntityDtoProcessorInterface.
 */
interface EntityDtoProcessorInterface
{
    public function createEntityFromDto(DtoInterface $dto): object;

    public function createUpdateDtoFromEntity(object $object): UpdateDtoInterface;

    public function updateEntityFromDto(object $object, UpdateDtoInterface $dto): void;
}
