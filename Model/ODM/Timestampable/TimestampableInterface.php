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

/**
 * TimestampableInterface.
 */
interface TimestampableInterface
{
    /**
     * @param \DateTimeImmutable|null $createdAt
     */
    public function setCreatedAt(\DateTimeInterface $createdAt = null): void;

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface;

    /**
     * @param \DateTimeInterface|null $createdAt
     */
    public function setUpdatedAt(\DateTimeInterface $createdAt = null): void;

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface;

    /**
     * Init timestampable fields.
     */
    public function initTimestampableFields(): void;
}
