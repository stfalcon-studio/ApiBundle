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

namespace StfalconStudio\ApiBundle\Service\AttributeProcessor;

use StfalconStudio\ApiBundle\Attribute\DTO;
use StfalconStudio\ApiBundle\Exception\RuntimeException;

/**
 * DtoAttributeProcessor.
 */
class DtoAttributeProcessor
{
    private array $cachedClasses = [];

    /**
     * @param string $className
     *
     * @throws RuntimeException
     *
     * @return string
     */
    public function processAttributeForClass(string $className): string
    {
        /** @var class-string<object> $className */
        if (\array_key_exists($className, $this->cachedClasses)) {
            return $this->cachedClasses[$className];
        }

        $reflector = new \ReflectionClass($className);
        $attributes = $reflector->getAttributes(DTO::class, \ReflectionAttribute::IS_INSTANCEOF);

        if (\count($attributes) > 1) {
            throw new RuntimeException(\sprintf('Detected more than one DTO attribute for class %s. Only one DTO attribute allowed per class.', $className));
        }
        if (\count($attributes) !== 1) {
            throw new RuntimeException(\sprintf('Missing DTO attribute for class %s.', $className));
        }

        $class = $attributes[0]->getArguments()['class'];

        $this->cachedClasses[$className] = $class;

        return $class;
    }
}
