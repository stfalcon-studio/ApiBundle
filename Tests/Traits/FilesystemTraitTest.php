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
use Symfony\Component\Filesystem\Filesystem;

final class FilesystemTraitTest extends TestCase
{
    /** @var Filesystem|MockObject */
    private $filesystem;

    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->filesystem,
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setFilesystem($this->filesystem);
        self::assertSame($this->filesystem, $this->dummyClass->getFilesystem());
    }
}
