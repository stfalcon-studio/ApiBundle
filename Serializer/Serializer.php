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

namespace StfalconStudio\ApiBundle\Serializer;

use Symfony\Component\Serializer\Serializer as BaseSerializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Serializer.
 */
class Serializer
{
    public const DEFAULT_FORMAT = 'json';

    /**
     * @param SerializerInterface|BaseSerializer $symfonySerializer
     */
    public function __construct(protected readonly SerializerInterface|BaseSerializer $symfonySerializer)
    {
    }

    /**
     * @param object|array $object
     * @param string       $serializationGroup
     * @param mixed[]      $context
     *
     * @return string
     */
    public function serialize(object|array $object, string $serializationGroup, array $context = []): string
    {
        $preparedContext = \array_merge(
            $context,
            [
                'group' => $serializationGroup,
                'json_encode_options' => \JSON_UNESCAPED_SLASHES | \JSON_THROW_ON_ERROR | \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES,
            ]
        );

        return $this->symfonySerializer->serialize($object, self::DEFAULT_FORMAT, $preparedContext);
    }

    /**
     * @param mixed   $data
     * @param string  $type
     * @param string  $format
     * @param mixed[] $context
     *
     * @return object|array
     */
    public function deserialize(mixed $data, string $type, string $format, array $context = []): object|array
    {
        return $this->symfonySerializer->deserialize($data, $type, $format, $context);
    }
}
