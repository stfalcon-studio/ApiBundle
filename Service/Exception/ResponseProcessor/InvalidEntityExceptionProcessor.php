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
use StfalconStudio\ApiBundle\Exception\Http\Validation\InvalidEntityException;
use StfalconStudio\ApiBundle\Exception\RuntimeException;

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
