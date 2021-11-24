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

use Doctrine\Common\Annotations\Reader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class AnnotationReaderTraitTest extends TestCase
{
    /** @var Reader|MockObject */
    private $reader;

    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->reader = $this->createMock(Reader::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->reader
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setAnnotationReader($this->reader);
        self::assertSame($this->reader, $this->dummyClass->getAnnotationReader());
    }
}
