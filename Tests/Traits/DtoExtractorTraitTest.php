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

namespace StfalconStudio\ApiBundle\Tests\Traits;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Request\DtoExtractor;

/**
 * DtoExtractorTraitTest.
 */
final class DtoExtractorTraitTest extends TestCase
{
    /** @var DtoExtractor|MockObject */
    private DtoExtractor|MockObject $dtoExtractor;

    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->dtoExtractor = $this->createStub(DtoExtractor::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->dtoExtractor,
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setDtoExtractor($this->dtoExtractor);
        self::assertSame($this->dtoExtractor, $this->dummyClass->getDtoExtractor());
    }
}
