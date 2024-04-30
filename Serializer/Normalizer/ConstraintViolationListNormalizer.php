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

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * ConstraintViolationListNormalizer.
 */
class ConstraintViolationListNormalizer implements NormalizerInterface
{
    /**
     * @param NormalizerInterface $symfonyConstraintViolationListNormalizer
     */
    public function __construct(private readonly NormalizerInterface $symfonyConstraintViolationListNormalizer)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof ConstraintViolationListInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            ConstraintViolationListInterface::class => true,
        ];
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
    public function normalize(mixed $object, string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $result = $this->symfonyConstraintViolationListNormalizer->normalize($object, $format, $context);

        $this->removeInternalViolationFields($result);

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

    /**
     * @param &array $data
     */
    private function removeInternalViolationFields(array &$data): void
    {
        if (isset($data['violations'])) {
            foreach ($data['violations'] as &$violation) {
                unset($violation['template'], $violation['parameters']);
                $this->removeInternalViolationFields($violation);
            }
            unset($violation);
        }
    }
}
