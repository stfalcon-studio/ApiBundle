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
use StfalconStudio\ApiBundle\Validator\JsonSchemaValidator;

final class JsonSchemaValidatorTraitTest extends TestCase
{
    private JsonSchemaValidator|MockObject $jsonSchemaValidator;
    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->jsonSchemaValidator = $this->createMock(JsonSchemaValidator::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->jsonSchemaValidator,
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setJsonSchemaValidator($this->jsonSchemaValidator);
        self::assertSame($this->jsonSchemaValidator, $this->dummyClass->getJsonSchemaValidator());
    }
}
