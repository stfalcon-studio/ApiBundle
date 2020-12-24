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

namespace StfalconStudio\ApiBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;
use StfalconStudio\ApiBundle\Exception\LogicException;

/**
 * JsonSchema Annotation.
 *
 * @Annotation
 *
 * @Target({"CLASS"})
 */
class JsonSchema implements JsonSchemaAnnotationInterface
{
    private const JSON_FILE_EXTENSION = '.json';

    /** @var string */
    private $jsonSchemaName;

    /**
     * @param mixed[] $options
     */
    public function __construct(array $options)
    {
        $options = $this->assertJsonSchemaFileIsSet($options);

        $this->jsonSchemaName = $options['value'];

        $this->getJsonSchemaFilename(); // if file not found exception will be thrown
    }

    /**
     * {@inheritdoc}
     */
    public function getJsonSchemaFilename(): string
    {
        $result = $this->jsonSchemaName;

        if (self::JSON_FILE_EXTENSION !== \mb_substr($result, -5)) {
            $result .= self::JSON_FILE_EXTENSION;
        }

        return $result;
    }

    /**
     * @param mixed[] $options
     *
     * @throws LogicException
     *
     * @return array
     */
    private function assertJsonSchemaFileIsSet(array $options): array
    {
        if (!\array_key_exists('value', $options)) {
            throw new LogicException('Json Schema file must be set.');
        }

        return $options;
    }
}
