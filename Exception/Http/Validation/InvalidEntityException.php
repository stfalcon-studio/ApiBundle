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

namespace StfalconStudio\ApiBundle\Exception\Http\Validation;

use StfalconStudio\ApiBundle\Error\BaseErrorNames;
use StfalconStudio\ApiBundle\Exception\AbstractCustomHttpAppException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * InvalidEntityException.
 */
class InvalidEntityException extends AbstractCustomHttpAppException
{
    private ConstraintViolationListInterface $errors;

    /**
     * {@inheritdoc}
     */
    public function __construct(ConstraintViolationListInterface $errors, \Exception $previous = null)
    {
        $this->errors = $errors;

        parent::__construct(Response::HTTP_UNPROCESSABLE_ENTITY, 'invalid_entity_exception_message', $previous);
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorName(): string
    {
        return BaseErrorNames::INVALID_ENTITY;
    }
}
