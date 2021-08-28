<?php

declare(strict_types=1);

namespace App\Service\Exception\ResponseProcessor;

use App\Exception\Http\Json\InvalidJsonSchemaException;
use App\Exception\RuntimeException;
use StfalconStudio\ApiBundle\Exception\CustomAppExceptionInterface;
use StfalconStudio\ApiBundle\Service\Exception\ResponseProcessor\CustomAppExceptionResponseProcessorInterface;

/**
 * InvalidJsonSchemaExceptionProcessor.
 */
class InvalidJsonSchemaExceptionProcessor implements CustomAppExceptionResponseProcessorInterface
{
    private string $environment;

    /**
     * @param string $environment
     */
    public function __construct(string $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(CustomAppExceptionInterface $exception): bool
    {
        return $exception instanceof InvalidJsonSchemaException;
    }

    /**
     * {@inheritdoc}
     */
    public function processResponse(CustomAppExceptionInterface $exception): array
    {
        if (!$exception instanceof InvalidJsonSchemaException) {
            throw new RuntimeException(sprintf('Object of class %s is not instance of %s', \get_class($exception), InvalidJsonSchemaException::class));
        }

        $result = ['violations' => $exception->getViolations()];

        if ('prod' !== $this->environment) {
            $result['jsonSchema'] = $exception->getJsonSchema();
        }

        return $result;
    }
}
