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

namespace StfalconStudio\ApiBundle\Service\Exception\ResponseProcessor;

use StfalconStudio\ApiBundle\Exception\CustomAppExceptionInterface;

/**
 * ExceptionResponseProcessor.
 */
class ExceptionResponseProcessor implements ExceptionResponseProcessorInterface
{
    /**
     * @param iterable|CustomAppExceptionResponseProcessorInterface[] $errorResponseProcessors
     */
    public function __construct(private readonly iterable $errorResponseProcessors)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function processResponseForException(CustomAppExceptionInterface $exception): array
    {
        $responseProcessor = $this->findAppropriateErrorResponseProcessorForException($exception);

        if ($responseProcessor instanceof CustomAppExceptionResponseProcessorInterface) {
            return $responseProcessor->processResponse($exception);
        }

        return [];
    }

    /**
     * @param CustomAppExceptionInterface $exception
     *
     * @return CustomAppExceptionResponseProcessorInterface|null
     */
    private function findAppropriateErrorResponseProcessorForException(CustomAppExceptionInterface $exception): ?CustomAppExceptionResponseProcessorInterface
    {
        foreach ($this->errorResponseProcessors as $errorResponseProcessor) {
            /** @var CustomAppExceptionResponseProcessorInterface $errorResponseProcessor */
            if ($errorResponseProcessor->supports($exception)) {
                return $errorResponseProcessor;
            }
        }

        return null;
    }
}
