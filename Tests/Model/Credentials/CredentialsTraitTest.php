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

namespace StfalconStudio\ApiBundle\Tests\Model\Credentials;

use PHPUnit\Framework\TestCase;

final class CredentialsTraitTest extends TestCase
{
    public function testCreatedBy(): void
    {
        $createdBy = new \DateTime('now');

        $entity = new DummyCredentialsEntity();
        $entity->setCredentialsLastChangedAt($createdBy);

        self::assertSame($createdBy, $entity->getCredentialsLastChangedAt());
    }
}
