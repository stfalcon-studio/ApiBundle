<?php

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Tests\Model\UUID;

use PHPUnit\Framework\TestCase;

final class UuidTraitTest extends TestCase
{
    public function testGetId(): void
    {
        $id = '25769c6c-d34d-4bfe-ba98-e0ee856f3e7a';

        $entity = new DummyUuidEntity();
        $entity->setId($id);

        self::assertSame($id, $entity->getId());
    }
}
