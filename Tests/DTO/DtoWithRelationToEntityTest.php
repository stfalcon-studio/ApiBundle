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

namespace StfalconStudio\ApiBundle\Tests\DTO;

use PHPUnit\Framework\TestCase;

/**
 * DtoWithRelationToEntityTest.
 */
final class DtoWithRelationToEntityTest extends TestCase
{
    public function testGetSetEntityId(): void
    {
        $entity = new DtoWithRelationToEntity();
        $id = '123';
        $entity->setEntityId($id);
        self::assertSame($id, $entity->getEntityId());
    }
}
