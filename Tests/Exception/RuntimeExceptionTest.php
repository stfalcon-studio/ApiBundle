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
use StfalconStudio\ApiBundle\Exception\ExceptionInterface;
use StfalconStudio\ApiBundle\Exception\RuntimeException;

/**
 * RuntimeExceptionTest.
 */
final class RuntimeExceptionTest extends TestCase
{
    public function testConstructor(): void
    {
        $exception = new RuntimeException();

        self::assertInstanceOf(ExceptionInterface::class, $exception);
        self::assertInstanceOf(\RuntimeException::class, $exception);
    }
}
