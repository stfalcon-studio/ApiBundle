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

namespace StfalconStudio\ApiBundle\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\ConstraintViolationListNormalizer as SymfonyConstraintViolationListNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * ConstraintViolationListNormalizer.
 */
class ConstraintViolationListNormalizer implements NormalizerInterface
{
    private SymfonyConstraintViolationListNormalizer $symfonyConstraintViolationListNormalizer;

    /**
     * @param SymfonyConstraintViolationListNormalizer $symfonyConstraintViolationListNormalizer
     */
    public function __construct(SymfonyConstraintViolationListNormalizer $symfonyConstraintViolationListNormalizer)
    {
        $this->symfonyConstraintViolationListNormalizer = $symfonyConstraintViolationListNormalizer;
    }

    /**
     * @param mixed       $data
     * @param string|null $format
     *
     * @return bool
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof ConstraintViolationListInterface;
    }

    /**
     * Clear the "detail" field from prefixed property paths.
     *
     * From the parent class:
     * {
     *     "detail": "propertyPath1: Error description 1\npropertyPath2: Error description 2",
     * }
     * After additional processing:
     * {
     *     "detail": "Error description 1\nError description 2",
     * }
     *
     * {@inheritdoc}
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $result = $this->symfonyConstraintViolationListNormalizer->normalize($object, $format, $context);

        if (\is_array($result) && \array_key_exists('detail', $result) && $result['detail']) {
            $messages = explode("\n", $result['detail']);

            foreach ($messages as &$message) {
                $position = mb_strpos($message, ': ');
                if (\is_int($position)) {
                    $message = mb_substr($message, $position + 2);
                }
            }
            unset($message);

            $result['detail'] = implode("\n", $messages);
        }

        return $result;
    }
}
