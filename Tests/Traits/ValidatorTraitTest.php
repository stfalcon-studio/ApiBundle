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
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ValidatorTraitTest extends TestCase
{
    private ValidatorInterface|MockObject $validator;

    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->validator
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setValidator($this->validator);
        self::assertSame($this->validator, $this->dummyClass->getValidator());
    }
}
