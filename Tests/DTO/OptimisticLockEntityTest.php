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
 * OptimisticLockEntityTest.
 */
final class OptimisticLockEntityTest extends TestCase
{
    public function testGetSetEditVersion(): void
    {
        $entity = new OptimisticLockEntity();
        $version = 123;
        $entity->setEditVersion($version);
        self::assertSame($version, $entity->getEditVersion());
    }
}
