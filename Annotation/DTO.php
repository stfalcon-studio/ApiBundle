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
use StfalconStudio\ApiBundle\DTO\DtoInterface;
use StfalconStudio\ApiBundle\Exception\InvalidArgumentException;
use StfalconStudio\ApiBundle\Exception\LogicException;

/**
 * Data Transfer Object Annotation.
 *
 * @Annotation
 *
 * @Target({"CLASS"})
 */
class DTO implements DtoAnnotationInterface
{
    private const DTO_SUFFIX = 'Dto';

    /** @var string */
    private $class;

    /**
     * @param mixed[] $options
     *
     * @throws LogicException
     * @throws InvalidArgumentException
     */
    public function __construct(array $options)
    {
        if (!\array_key_exists('value', $options)) {
            throw new LogicException('DTO class must be set.');
        }

        $class = $options['value'];

        if (!\is_string($class)) {
            throw new InvalidArgumentException('Value should be string');
        }

        if (!\class_exists($class)) {
            throw new InvalidArgumentException(\sprintf('Class %s does not exist.', $class));
        }

        if (!\is_subclass_of($class, DtoInterface::class)) {
            throw new InvalidArgumentException(\sprintf('Class %s does not implement %s interface.', $class, DtoInterface::class));
        }

        if (self::DTO_SUFFIX !== \mb_substr($class, -3)) {
            throw new InvalidArgumentException(\sprintf('Class name %s must be suffixed with "%s".', $class, self::DTO_SUFFIX));
        }

        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function getClass(): string
    {
        return $this->class;
    }
}
