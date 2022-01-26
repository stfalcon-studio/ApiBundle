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

namespace StfalconStudio\ApiBundle\Exception\Http\Json;

use StfalconStudio\ApiBundle\Error\BaseErrorNames;
use StfalconStudio\ApiBundle\Exception\AbstractCustomHttpAppException;
use Symfony\Component\HttpFoundation\Response;

/**
 * InvalidJsonSchemaException.
 */
class InvalidJsonSchemaException extends AbstractCustomHttpAppException
{
    private readonly array $violations;
    private readonly array $jsonSchema;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $violations, array $jsonSchema, \Exception $previous = null)
    {
        $this->violations = $violations;
        $this->jsonSchema = $jsonSchema;

        parent::__construct(Response::HTTP_BAD_REQUEST, 'invalid_json_schema_exception_message', $previous);
    }

    /**
     * @return array
     */
    public function getViolations(): array
    {
        return $this->violations;
    }

    /**
     * @return array
     */
    public function getJsonSchema(): array
    {
        return $this->jsonSchema;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorName(): string
    {
        return BaseErrorNames::INVALID_JSON_SCHEMA;
    }
}
