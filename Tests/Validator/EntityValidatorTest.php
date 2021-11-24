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

namespace StfalconStudio\ApiBundle\Tests\Validator;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Exception\Http\Validation\InvalidEntityException;
use StfalconStudio\ApiBundle\Validator\EntityValidator;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class EntityValidatorTest extends TestCase
{
    /** @var ValidatorInterface|MockObject */
    private $validator;

    private EntityValidator $entityValidator;

    protected function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->entityValidator = new EntityValidator();
        $this->entityValidator->setValidator($this->validator);
    }

    protected function tearDown(): void
    {
        unset(
            $this->validator,
            $this->entityValidator,
        );
    }

    public function testValidateWithoutErrors(): void
    {
        $entity = new \stdClass();
        $constraints = null;
        $groups = ['test'];

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with($entity, $constraints, $groups)
            ->willReturn([])
        ;

        $this->entityValidator->validate($entity, $constraints, $groups);
    }

    public function testValidateWithErrors(): void
    {
        $entity = new \stdClass();
        $constraints = null;
        $groups = ['test'];

        $violations = new ConstraintViolationList();
        $violations->add(new DummyViolation());

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with($entity, $constraints, $groups)
            ->willReturn($violations)
        ;

        $this->expectException(InvalidEntityException::class);

        $this->entityValidator->validate($entity, $constraints, $groups);
    }
}
