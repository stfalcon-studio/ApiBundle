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
use StfalconStudio\ApiBundle\Validator\EntityValidator;

final class EntityValidatorTraitTest extends TestCase
{
    private EntityValidator|MockObject $entityValidator;
    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->entityValidator = $this->createMock(EntityValidator::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->entityValidator
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setEntityValidator($this->entityValidator);
        self::assertSame($this->entityValidator, $this->dummyClass->getEntityValidator());
    }
}
