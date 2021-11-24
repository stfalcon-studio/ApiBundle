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

namespace StfalconStudio\ApiBundle\Controller;

use StfalconStudio\ApiBundle\DTO\DtoInterface;
use StfalconStudio\ApiBundle\Traits;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;

/**
 * AbstractDtoBasedAction.
 *
 * This file is just a simple wrapper over the common actions during POST/PUT requests.
 * It allows using "static::class" to transfer the class name of the end controller into service's methods.
 */
abstract class AbstractDtoBasedAction
{
    use Traits\AuthorizationCheckerTrait;
    use Traits\DtoExtractorTrait;
    use Traits\EntityManagerTrait;
    use Traits\EntityValidatorTrait;
    use Traits\EventDispatcherTrait;
    use Traits\JsonSchemaValidatorTrait;
    use Traits\SerializerTrait;

    /**
     * @param Request $request
     */
    protected function validateJsonSchema(Request $request): void
    {
        $this->jsonSchemaValidator->validateRequestForControllerClass($request, static::class);
    }

    /**
     * @param DtoInterface                 $dto
     * @param Constraint|Constraint[]|null $constraints
     * @param array|null                   $groups
     */
    protected function validateDto(DtoInterface $dto, $constraints = null, array $groups = null): void
    {
        $this->entityValidator->validate($dto, $constraints, $groups);
    }

    /**
     * @param mixed                        $entity
     * @param Constraint|Constraint[]|null $constraints
     * @param array|null                   $groups
     */
    protected function validateEntity($entity, $constraints = null, array $groups = null): void
    {
        $this->entityValidator->validate($entity, $constraints, $groups);
    }

    /**
     * @param Request     $request
     * @param object|null $objectToPopulate
     *
     * @return DtoInterface
     */
    protected function getDtoFromRequest(Request $request, object $objectToPopulate = null): DtoInterface
    {
        return $this->dtoExtractor->getDtoFromRequestForControllerClass($request, static::class, $objectToPopulate);
    }
}
