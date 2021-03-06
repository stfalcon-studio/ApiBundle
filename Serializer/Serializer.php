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

    /** @var SerializerInterface|BaseSerializer */
    protected $symfonySerializer;

    /**
     * @param SerializerInterface|BaseSerializer $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->symfonySerializer = $serializer;
    }

    /**
     * @param object|array $object
     * @param string       $serializationGroup
     * @param mixed[]      $context
     *
     * @return string
     */
    public function serialize($object, string $serializationGroup, array $context = []): string
    {
        $preparedContext = \array_merge(
            $context,
            [
                'group' => $serializationGroup,
                'json_encode_options' => \JSON_UNESCAPED_SLASHES | \JSON_THROW_ON_ERROR | \JSON_UNESCAPED_UNICODE,
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
    public function deserialize($data, string $type, string $format, array $context = [])
    {
        return $this->symfonySerializer->deserialize($data, $type, $format, $context);
    }
}
