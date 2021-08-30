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
 * DtoWithRelationToEntityTrait.
 */
trait DtoWithRelationToEntityTrait
{
    private ?string $entityId = null;

    /**
     * @return string|null
     */
    public function getEntityId(): ?string
    {
        return $this->entityId;
    }

    /**
     * @param string $entityId
     *
     * @return self
     */
    public function setEntityId(string $entityId): self
    {
        $this->entityId = \trim($entityId);

        return $this;
    }
}
