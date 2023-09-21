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

namespace StfalconStudio\ApiBundle\Model\ODM\Timestampable;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TimestampableTrait.
 */
trait TimestampableTrait
{
    #[MongoDB\Field(type: 'date_immutable')]
    protected \DateTimeInterface|null $createdAt = null;

    #[MongoDB\Field(type: 'date')]
    #[Gedmo\Timestampable(on: 'update')]
    protected \DateTimeInterface|null $updatedAt = null;

    /**
     * @param \DateTimeImmutable|null $createdAt
     */
    public function setCreatedAt(\DateTimeInterface $createdAt = null): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface|null $updatedAt
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt = null): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Init timestampable fields.
     */
    public function initTimestampableFields(): void
    {
        $this->createdAt = new \DateTimeImmutable('now');
        $this->updatedAt = new \DateTime('now');
    }
}
