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
use StfalconStudio\ApiBundle\Annotation\DTO;
use StfalconStudio\ApiBundle\Exception\InvalidArgumentException;

/**
 * DTOTest.
 */
final class DTOTest extends TestCase
{
    public function testMissingValue(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('DTO class must be set.');

        new DTO([]);
    }

    public function testNotExistingClass(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class SomeDummyClass does not exist.');

        new DTO(['value' => 'SomeDummyClass']);
    }

    public function testClassIsNotChildOfDtoInterface(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class stdClass does not implement StfalconStudio\ApiBundle\DTO\DtoInterface interface.');

        new DTO(['value' => \stdClass::class]);
    }

    public function testClassnameSuffix(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class name StfalconStudio\ApiBundle\Tests\Annotation\DummyDtoClass must be suffixed with "Dto".');

        new DTO(['value' => DummyDtoClass::class]);
    }

    public function testNotStringValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value should be string');

        new DTO(['value' => 123]);
    }

    public function testSuccessfulConstructor(): void
    {
        $annotation = new DTO(['value' => DummyDto::class]);
        self::assertSame(DummyDto::class, $annotation->getClass());
    }
}
