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
    /**
     * @param DtoInterface $dto
     *
     * @return object
     */
    public function createEntityFromDto(DtoInterface $dto): object;

    /**
     * @param object $object
     *
     * @return UpdateDtoInterface
     */
    public function createUpdateDtoFromEntity(object $object): UpdateDtoInterface;

    /**
     * @param object             $object
     * @param UpdateDtoInterface $dto
     *
     * @return void
     */
    public function updateEntityFromDto(object $object, UpdateDtoInterface $dto): void;
}
