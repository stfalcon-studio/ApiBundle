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

namespace StfalconStudio\ApiBundle\Tests\Util\File;

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Util\File\FileReader;

/**
 * FileReaderTest.
 */
final class FileReaderTest extends TestCase
{
    public function testGetFileContents(): void
    {
        $fileReader = new FileReader();
        self::assertSame("test file reading\n", $fileReader->getFileContents(__DIR__.'/dummy.txt'));
    }
}
