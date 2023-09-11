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
use StfalconStudio\ApiBundle\Attribute\DependentEntity;
use StfalconStudio\ApiBundle\Exception\InvalidArgumentException;

/**
 * DependedEntityTest.
 */
final class DependentEntityTest extends TestCase
{
    public function testNotEmptyPropertyPath(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The "propertyPath" parameter can not be empty.');

        new DependentEntity(propertyPath: '');
    }

    public function testGetPropertyPath(): void
    {
        $propertyPath = 'someField';
        $attribute = new DependentEntity(propertyPath: $propertyPath);

        self::assertSame($propertyPath, $attribute->getPropertyPath());
    }
}
