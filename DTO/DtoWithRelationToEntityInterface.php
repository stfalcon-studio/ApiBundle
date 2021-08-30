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

namespace StfalconStudio\ApiBundle\DTO;

/**
 * DtoWithRelationToEntityInterface.
 */
interface DtoWithRelationToEntityInterface extends DtoInterface
{
    /**
     * @return string|null
     */
    public function getEntityId(): ?string;

    /**
     * @param string $entityId
     *
     * @return self
     */
    public function setEntityId(string $entityId): self;
}
