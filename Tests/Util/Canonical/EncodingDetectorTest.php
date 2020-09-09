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

namespace StfalconStudio\ApiBundle\Tests\Util\Canonical;

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Util\Canonical\EncodingDetector;

final class EncodingDetectorTest extends TestCase
{
    public function testDetectEncoding(): void
    {
        $encodingDetector = new EncodingDetector();

        self::assertIsString($encodingDetector->detectEncoding('TeSt@TeSt.com'));
    }
}
