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

namespace StfalconStudio\ApiBundle\Tests\Model\Timestampable;

use PHPUnit\Framework\TestCase;

final class TimestampableTraitTest extends TestCase
{
    public function testInitTimestampableFields(): void
    {
        $entity = new DummyTimestampableEntity();

        $entity->initTimestampableFields();

        self::assertInstanceOf(\DateTimeImmutable::class, $entity->getCreatedAt());
        self::assertInstanceOf(\DateTime::class, $entity->getUpdatedAt());
    }

    public function testCreatedAt(): void
    {
        $createdAt = new \DateTimeImmutable();

        $entity = new DummyTimestampableEntity();
        $entity->setCreatedAt($createdAt);

        self::assertSame($createdAt, $entity->getCreatedAt());
    }

    public function testUpdatedAt(): void
    {
        $updatedAt = new \DateTime();

        $entity = new DummyTimestampableEntity();
        $entity->setUpdatedAt($updatedAt);

        self::assertSame($updatedAt, $entity->getUpdatedAt());
    }
}
