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
use StfalconStudio\ApiBundle\Attribute\DTO;
use StfalconStudio\ApiBundle\Exception\InvalidArgumentException;

/**
 * DTOTest.
 */
final class DTOTest extends TestCase
{
    public function testNotExistingClass(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class SomeDummyClass does not exist.');

        new DTO(class: 'SomeDummyClass');
    }

    public function testClassIsNotChildOfDtoInterface(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class stdClass does not implement StfalconStudio\ApiBundle\DTO\DtoInterface interface.');

        new DTO(class: \stdClass::class);
    }

    public function testClassnameSuffix(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class name StfalconStudio\ApiBundle\Tests\Attribute\DummyDtoClass must be suffixed with "Dto".');

        new DTO(class: DummyDtoClass::class);
    }

    public function testSuccessfulConstructor(): void
    {
        $attribute = new DTO(class: DummyDto::class);
        self::assertSame(DummyDto::class, $attribute->getClass());
    }
}
