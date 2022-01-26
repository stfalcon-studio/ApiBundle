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

namespace StfalconStudio\ApiBundle\Validator;

use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;
use StfalconStudio\ApiBundle\Exception\Http\Json\InvalidJsonSchemaException;
use StfalconStudio\ApiBundle\Exception\Http\Json\MalformedJsonException;
use StfalconStudio\ApiBundle\Exception\RuntimeException;
use StfalconStudio\ApiBundle\Service\AttributeProcessor\JsonSchemaAttributeProcessor;
use StfalconStudio\ApiBundle\Traits;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

/**
 * JsonSchemaValidator.
 */
class JsonSchemaValidator
{
    use Traits\SymfonySerializerTrait;

    private readonly Validator $validator;
    private readonly JsonSchemaAttributeProcessor $jsonSchemaAttributeProcessor;

    /**
     * @param Validator                    $validator
     * @param JsonSchemaAttributeProcessor $dtoAttributeProcessor
     */
    public function __construct(Validator $validator, JsonSchemaAttributeProcessor $dtoAttributeProcessor)
    {
        $this->validator = $validator;
        $this->jsonSchemaAttributeProcessor = $dtoAttributeProcessor;
    }

    /**
     * @param Request $request
     * @param string  $controllerClassName
     */
    public function validateRequestForControllerClass(Request $request, string $controllerClassName): void
    {
        $data = $this->decodeJsonFromRequest($request);
        $jsonSchema = $this->jsonSchemaAttributeProcessor->processAttributeForControllerClass($controllerClassName);
        $this->doValidateRequestData($data, $jsonSchema);
    }

    /**
     * @param Request $request
     * @param string  $dtoClassName
     */
    public function validateRequestDataForDtoClass(Request $request, string $dtoClassName): void
    {
        $data = $this->decodeJsonFromRequest($request);
        $jsonSchema = $this->jsonSchemaAttributeProcessor->processAttributeForDtoClass($dtoClassName);
        $this->doValidateRequestData($data, $jsonSchema);
    }

    /**
     * @param mixed $requestData
     * @param mixed $jsonSchema
     *
     * @throws RuntimeException
     * @throws InvalidJsonSchemaException
     */
    private function doValidateRequestData(mixed $requestData, mixed $jsonSchema): void
    {
        $this->validator->validate($requestData, $jsonSchema, Constraint::CHECK_MODE_NORMAL);

        if (!$this->validator->isValid()) {
            if (!$this->symfonySerializer instanceof Serializer) {
                throw new RuntimeException(sprintf('Serializer is not instance of %s', Serializer::class));
            }

            $violations = (array) $this->symfonySerializer->normalize($this->validator, 'json', ['jsonSchema' => $jsonSchema]);
            $normalizedJsonSchema = (array) $this->symfonySerializer->normalize($jsonSchema, 'object');

            throw new InvalidJsonSchemaException($violations, $normalizedJsonSchema);
        }
    }

    /**
     * @param Request $request
     *
     * @throws MalformedJsonException
     *
     * @return mixed
     */
    private function decodeJsonFromRequest(Request $request): mixed
    {
        $data = json_decode((string) $request->getContent());

        if (null === $data) {
            throw new MalformedJsonException(sprintf('Format of your request is not a valid JSON. Error: %s', json_last_error_msg()));
        }

        return $data;
    }
}
