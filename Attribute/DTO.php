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

namespace StfalconStudio\ApiBundle\Attribute;

use Attribute;
use StfalconStudio\ApiBundle\DTO\DtoInterface;
use StfalconStudio\ApiBundle\Exception\InvalidArgumentException;

/**
 * Data Transfer Object Annotation.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class DTO
{
    private const DTO_SUFFIX = 'Dto';

    private string $class;

    /**
     * @param string $class
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $class)
    {
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
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }
}
