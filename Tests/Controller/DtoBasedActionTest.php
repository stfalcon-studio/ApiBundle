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

namespace StfalconStudio\ApiBundle\Tests\Controller;

use StfalconStudio\ApiBundle\DTO\DtoInterface;
use Symfony\Component\HttpFoundation\Request;

final class DtoBasedActionTest extends AbstractDtoBasedActionTest
{
    /** @var DummyAction */
    protected $action;

    protected function setUp(): void
    {
        $this->action = new DummyAction();

        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset(
            $this->action,
        );
    }

    public function testValidateJsonSchema(): void
    {
        $request = $this->createMock(Request::class);

        $this->jsonSchemaValidator
            ->expects(self::once())
            ->method('validateRequestForControllerClass')
            ->with($request, DummyAction::class)
        ;

        $this->action->doValidateJsonSchema($request);
    }

    public function testValidateDto(): void
    {
        $dto = $this->createMock(DtoInterface::class);

        $this->entityValidator
            ->expects(self::once())
            ->method('validate')
            ->with($dto, null, null)
        ;

        $this->action->doValidateDto($dto);
    }

    public function testValidateEntity(): void
    {
        $entity = $this->createMock(\stdClass::class);

        $this->entityValidator
            ->expects(self::once())
            ->method('validate')
            ->with($entity, null, null)
        ;

        $this->action->doValidateEntity($entity);
    }

    public function testGetDtoFromRequest(): void
    {
        $request = $this->createMock(Request::class);

        $this->dtoExtractor
            ->expects(self::once())
            ->method('getDtoFromRequestForControllerClass')
            ->with($request)
        ;

        $this->action->doGetDtoFromRequest($request);
    }
}
