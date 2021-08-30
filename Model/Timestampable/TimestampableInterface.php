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

namespace StfalconStudio\ApiBundle\Model\Timestampable;

/**
 * TimestampableInterface.
 */
interface TimestampableInterface
{
    /**
     * @param \DateTimeImmutable $createdAt
     *
     * @return self
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt);

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable;

    /**
     * @param \DateTime $createdAt
     *
     * @return self
     */
    public function setUpdatedAt(\DateTime $createdAt);

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime;

    /**
     * Init timestampable fields.
     */
    public function initTimestampableFields(): void;
}
