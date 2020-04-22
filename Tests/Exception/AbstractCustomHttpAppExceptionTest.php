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

namespace StfalconStudio\ApiBundle\Tests\Exception;

use PHPUnit\Framework\TestCase;

/**
 * AbstractCustomHttpAppExceptionTest.
 */
final class AbstractCustomHttpAppExceptionTest extends TestCase
{
    public function testConstructor(): void
    {
        $exception = new DummyCustomHttpAppException(500);
        self::assertFalse($exception->loggable());
    }
}
