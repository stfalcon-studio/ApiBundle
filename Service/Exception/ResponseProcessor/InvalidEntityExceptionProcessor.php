<?php

declare(strict_types=1);

namespace App\Service\Exception\ResponseProcessor;

use App\Exception\Http\Validation\InvalidEntityException;
use App\Exception\RuntimeException;
use StfalconStudio\ApiBundle\Exception\CustomAppExceptionInterface;
use StfalconStudio\ApiBundle\Service\Exception\ResponseProcessor\CustomAppExceptionResponseProcessorInterface;

/**
 * InvalidEntityExceptionProcessor.
 */
class InvalidEntityExceptionProcessor implements CustomAppExceptionResponseProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(CustomAppExceptionInterface $exception): bool
    {
        return $exception instanceof InvalidEntityException;
    }

    /**
     * {@inheritdoc}
     */
    public function processResponse(CustomAppExceptionInterface $exception): array
    {
        if (!$exception instanceof InvalidEntityException) {
            throw new RuntimeException(sprintf('Object of class %s is not instance of %s', \get_class($exception), InvalidEntityException::class));
        }

        return [
            'violations' => $exception->getErrors(),
        ];
    }
}
