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

use StfalconStudio\ApiBundle\Enum\DictionaryEnumInterface;
use StfalconStudio\ApiBundle\Traits\TranslatorTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * DictionaryEnumNormalizer.
 */
class DictionaryEnumNormalizer implements NormalizerInterface
{
    use TranslatorTrait;

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof DictionaryEnumInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            DictionaryEnumInterface::class => true,
        ];
    }

    /**
     * @param DictionaryEnumInterface $object
     * @param string|null             $format
     * @param array                   $context
     *
     * @return array
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $data = [];

        $this->normalizeId($object, $data);
        $this->normalizeValue($object, $data);

        return $data;
    }

    /**
     * @param DictionaryEnumInterface $object
     * @param array                   $data
     *
     * @return void
     */
    protected function normalizeId(DictionaryEnumInterface $object, array &$data): void
    {
        $data['id'] = $object->value;
    }

    /**
     * @param DictionaryEnumInterface $object
     * @param array                   $data
     *
     * @return void
     */
    protected function normalizeValue(DictionaryEnumInterface $object, array &$data): void
    {
        $data['value'] = $this->translator->trans(
            id: null === $object->getPrefix() ? (string) $object->value : sprintf('%s.%s', $object->getPrefix(), $object->value),
            domain: $object::getTranslatorDomain(),
        );
    }
}
