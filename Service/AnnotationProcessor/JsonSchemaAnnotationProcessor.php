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
use StfalconStudio\ApiBundle\Annotation\JsonSchema;
use StfalconStudio\ApiBundle\Annotation\JsonSchemaAnnotationInterface;
use StfalconStudio\ApiBundle\Exception\InvalidArgumentException;
use StfalconStudio\ApiBundle\Exception\RuntimeException;
use StfalconStudio\ApiBundle\Util\File\FileReader;

/**
 * JsonSchemaAnnotationProcessor.
 */
class JsonSchemaAnnotationProcessor
{
    private readonly DtoAnnotationProcessor $dtoAnnotationProcessor;
    private readonly Reader $annotationReader;
    private readonly FileReader $fileReader;
    private readonly string $jsonSchemaDir;

    /** @var array<string> */
    private array $cachedClasses = [];

    /**
     * @param DtoAnnotationProcessor $dtoAnnotationProcessor
     * @param FileReader             $fileReader
     * @param Reader                 $annotationReader
     * @param string                 $jsonSchemaDir
     */
    public function __construct(DtoAnnotationProcessor $dtoAnnotationProcessor, FileReader $fileReader, Reader $annotationReader, string $jsonSchemaDir)
    {
        $this->dtoAnnotationProcessor = $dtoAnnotationProcessor;
        $this->fileReader = $fileReader;
        $this->annotationReader = $annotationReader;
        $this->jsonSchemaDir = $jsonSchemaDir;
    }

    /**
     * @param string $controllerClassName
     *
     * @return mixed
     */
    public function processAnnotationForControllerClass(string $controllerClassName): mixed
    {
        $dtoClass = $this->dtoAnnotationProcessor->processAnnotationForClass($controllerClassName);

        return $this->processAnnotationForDtoClass($dtoClass);
    }

    /**
     * @param string $dtoClassName
     *
     * @throws RuntimeException
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    public function processAnnotationForDtoClass(string $dtoClassName): mixed
    {
        /** @var class-string<object> $dtoClassName */
        if (\array_key_exists($dtoClassName, $this->cachedClasses)) {
            return $this->cachedClasses[$dtoClassName];
        }

        $classAnnotation = $this->annotationReader->getClassAnnotation(new \ReflectionClass($dtoClassName), JsonSchema::class);

        if (!$classAnnotation instanceof JsonSchemaAnnotationInterface) {
            throw new RuntimeException(\sprintf('Missing Json Schema annotation for class %s', $dtoClassName));
        }

        $jsonSchemaDirPath = \realpath($this->jsonSchemaDir);
        if (false === $jsonSchemaDirPath) {
            throw new RuntimeException(\sprintf('Directory for json Schema files "%s" is not found.', $this->jsonSchemaDir));
        }

        $path = $jsonSchemaDirPath.\DIRECTORY_SEPARATOR.$classAnnotation->getJsonSchemaFilename();
        $realPathToJsonSchemaFile = \realpath($path);
        if (false === $realPathToJsonSchemaFile) {
            throw new RuntimeException(\sprintf('Json Schema file "%s" is not found.', $path));
        }

        $jsonSchemaContent = $this->fileReader->getFileContents($realPathToJsonSchemaFile);
        if (false === $jsonSchemaContent) {
            throw new InvalidArgumentException(\sprintf('Cannot read content of file %s', $realPathToJsonSchemaFile));
        }

        $decodedSchema = \json_decode($jsonSchemaContent, true, 512, \JSON_THROW_ON_ERROR);

        $this->cachedClasses[$dtoClassName] = $decodedSchema;

        return $decodedSchema;
    }
}
