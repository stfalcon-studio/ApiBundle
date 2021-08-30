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
use StfalconStudio\ApiBundle\Exception\Http\Json\InvalidJsonSchemaException;
use StfalconStudio\ApiBundle\Exception\RuntimeException;

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
