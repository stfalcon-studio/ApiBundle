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

namespace StfalconStudio\ApiBundle\Tests\Util;

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Util\JsonEncoder;

final class JsonEncoderTest extends TestCase
{
    public function testEncodeMessage(): void
    {
        self::assertSame('{"url":"http://test.com"}', JsonEncoder::encodeMessage(['url' => 'http://test.com']));
    }
}
