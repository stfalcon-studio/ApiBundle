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

namespace StfalconStudio\ApiBundle\Tests\Attribute;

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Attribute\JsonSchema;

/**
 * JsonSchemaTest.
 */
final class JsonSchemaTest extends TestCase
{
    public function testGetJsonSchemaName(): void
    {
        $annotation = new JsonSchema(jsonSchemaName: 'Device/IosDevice');
        self::assertEquals('Device/IosDevice', $annotation->getJsonSchemaName());
    }
}
