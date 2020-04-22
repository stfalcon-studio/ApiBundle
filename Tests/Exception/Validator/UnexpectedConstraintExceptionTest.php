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

namespace StfalconStudio\ApiBundle\Tests\Exception\Validator;

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Exception\Validator\UnexpectedConstraintException;

/**
 * UnexpectedConstraintExceptionTest.
 */
final class UnexpectedConstraintExceptionTest extends TestCase
{
    public function testConstruct(): void
    {
        $exception = new UnexpectedConstraintException(new DummyConstraint(), \stdClass::class);

        self::assertSame('Object of class StfalconStudio\ApiBundle\Tests\Exception\Validator\DummyConstraint is not instance of stdClass', $exception->getMessage());
    }
}
