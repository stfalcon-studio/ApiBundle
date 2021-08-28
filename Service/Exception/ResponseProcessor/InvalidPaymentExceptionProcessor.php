<?php

declare(strict_types=1);

namespace App\Service\Exception\ResponseProcessor;

use App\Exception\Http\Payment\InvalidPaymentException;
use App\Exception\RuntimeException;
use StfalconStudio\ApiBundle\Exception\CustomAppExceptionInterface;
use StfalconStudio\ApiBundle\Service\Exception\ResponseProcessor\CustomAppExceptionResponseProcessorInterface;

/**
 * InvalidPaymentExceptionProcessor.
 */
class InvalidPaymentExceptionProcessor implements CustomAppExceptionResponseProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(CustomAppExceptionInterface $exception): bool
    {
        return $exception instanceof InvalidPaymentException;
    }

    /**
     * {@inheritdoc}
     */
    public function processResponse(CustomAppExceptionInterface $exception): array
    {
        if (!$exception instanceof InvalidPaymentException) {
            throw new RuntimeException(sprintf('Object of class %s is not instance of %s', \get_class($exception), InvalidPaymentException::class));
        }

        return [];
    }
}
