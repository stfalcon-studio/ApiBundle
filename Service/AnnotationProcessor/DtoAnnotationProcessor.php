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

namespace StfalconStudio\ApiBundle\Service\AnnotationProcessor;

use Doctrine\Common\Annotations\Reader;
use StfalconStudio\ApiBundle\Annotation\DTO;
use StfalconStudio\ApiBundle\Annotation\DtoAnnotationInterface;
use StfalconStudio\ApiBundle\Exception\RuntimeException;

/**
 * DtoAnnotationProcessor.
 */
class DtoAnnotationProcessor
{
    /** @var array */
    private $cachedClasses = [];

    /** @var Reader */
    private $annotationReader;

    /**
     * @param Reader $annotationReader
     */
    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param string $className
     *
     * @throws RuntimeException
     *
     * @return string
     */
    public function processAnnotationForClass(string $className): string
    {
        /** @var class-string<object> $className */
        if (\array_key_exists($className, $this->cachedClasses)) {
            return $this->cachedClasses[$className];
        }

        $classAnnotation = $this->annotationReader->getClassAnnotation(new \ReflectionClass($className), DTO::class);
        if (!$classAnnotation instanceof DtoAnnotationInterface) {
            throw new RuntimeException(\sprintf('Missing DTO annotation for class %s', $className));
        }

        $this->cachedClasses[$className] = $classAnnotation->getClass();

        return $classAnnotation->getClass();
    }
}
