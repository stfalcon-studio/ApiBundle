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

namespace StfalconStudio\ApiBundle\Tests\Annotation;

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Annotation\JsonSchema;
use StfalconStudio\ApiBundle\Exception\LogicException;

/**
 * JsonSchemaTest.
 */
final class JsonSchemaTest extends TestCase
{
    public function testMissingValue(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Json Schema file must be set.');

        new JsonSchema([]);
    }

    public function testGetJsonSchemaFilename(): void
    {
        $annotation = new JsonSchema(['value' => 'Device/IosDevice']);
        self::assertEquals('Device/IosDevice.json', $annotation->getJsonSchemaFilename());
    }

    public function testGetJsonSchemaFilenameWithoutJsonFileExtension(): void
    {
        $annotation = new JsonSchema(['value' => 'Device/AndroidDevice']);
        self::assertEquals('Device/AndroidDevice.json', $annotation->getJsonSchemaFilename());
    }
}
