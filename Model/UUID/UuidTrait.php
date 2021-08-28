<?php

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Model\UUID;

/**
 * UuidTrait.
 */
trait UuidTrait
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->id;
    }
}
