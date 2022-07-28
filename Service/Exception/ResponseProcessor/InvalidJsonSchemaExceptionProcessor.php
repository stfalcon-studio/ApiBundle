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

use JetBrains\PhpStorm\ArrayShape;
use StfalconStudio\ApiBundle\Exception\CustomAppExceptionInterface;
use StfalconStudio\ApiBundle\Exception\Http\Json\InvalidJsonSchemaException;
use StfalconStudio\ApiBundle\Exception\RuntimeException;

/**
 * InvalidJsonSchemaExceptionProcessor.
 */
class InvalidJsonSchemaExceptionProcessor implements CustomAppExceptionResponseProcessorInterface
{
    /**
     * @param string $environment
     */
    public function __construct(private readonly string $environment)
    {
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
    #[ArrayShape(['violations' => 'mixed', 'jsonSchema' => 'mixed'])]
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
