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

use StfalconStudio\ApiBundle\Controller\AbstractDtoBasedAction;
use StfalconStudio\ApiBundle\DTO\DtoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;

class DummyAction extends AbstractDtoBasedAction
{
    /**
     * @param Request $request
     */
    public function doValidateJsonSchema(Request $request): void
    {
        $this->validateJsonSchema($request);
    }

    /**
     * @param DtoInterface                 $dto
     * @param Constraint|Constraint[]|null $constraints
     * @param array|null                   $groups
     */
    public function doValidateDto(DtoInterface $dto, Constraint|array|null $constraints = null, array $groups = null): void
    {
        $this->validateDto($dto, $constraints, $groups);
    }

    /**
     * @param mixed                        $entity
     * @param Constraint|Constraint[]|null $constraints
     * @param array|null                   $groups
     */
    public function doValidateEntity(mixed $entity, Constraint|array|null $constraints = null, array $groups = null): void
    {
        $this->validateEntity($entity, $constraints, $groups);
    }

    /**
     * @param Request     $request
     * @param object|null $objectToPopulate
     *
     * @return DtoInterface
     */
    public function doGetDtoFromRequest(Request $request, object $objectToPopulate = null): DtoInterface
    {
        return $this->getDtoFromRequest($request, $objectToPopulate);
    }
}
