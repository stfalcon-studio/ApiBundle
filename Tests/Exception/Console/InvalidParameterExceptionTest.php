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

namespace StfalconStudio\ApiBundle\Tests\Exception\Console;

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Exception\Console\CustomConsoleExceptionInterface;
use StfalconStudio\ApiBundle\Exception\Console\InvalidParameterException;

final class InvalidParameterExceptionTest extends TestCase
{
    public function testConstructor(): void
    {
        $exception = new InvalidParameterException();
        self::assertInstanceOf(CustomConsoleExceptionInterface::class, $exception);
    }
}
