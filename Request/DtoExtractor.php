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

namespace StfalconStudio\ApiBundle\Request;

use StfalconStudio\ApiBundle\DTO\DtoInterface;
use StfalconStudio\ApiBundle\Exception\InvalidArgumentException;
use StfalconStudio\ApiBundle\Service\AnnotationProcessor\DtoAnnotationProcessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * DtoExtractor.
 */
class DtoExtractor
{
    private readonly DtoAnnotationProcessor $dtoAnnotationProcessor;
    private readonly SerializerInterface $serializer;

    /**
     * @param DtoAnnotationProcessor $dtoAnnotationProcessor
     * @param SerializerInterface    $serializer
     */
    public function __construct(DtoAnnotationProcessor $dtoAnnotationProcessor, SerializerInterface $serializer)
    {
        $this->dtoAnnotationProcessor = $dtoAnnotationProcessor;
        $this->serializer = $serializer;
    }

    /**
     * @param Request     $request
     * @param string      $controllerClassName
     * @param object|null $objectToPopulate
     *
     * @return DtoInterface
     */
    public function getDtoFromRequestForControllerClass(Request $request, string $controllerClassName, ?object $objectToPopulate = null): DtoInterface
    {
        $dtoClassName = $this->dtoAnnotationProcessor->processAnnotationForClass($controllerClassName);

        return $this->getDtoFromRequestForDtoClass($request, $dtoClassName, $objectToPopulate);
    }

    /**
     * @param Request     $request
     * @param string      $dtoClassName
     * @param object|null $objectToPopulate
     *
     * @throws InvalidArgumentException
     *
     * @return DtoInterface
     */
    public function getDtoFromRequestForDtoClass(Request $request, string $dtoClassName, ?object $objectToPopulate = null): DtoInterface
    {
        $context = [];
        if (null !== $objectToPopulate) {
            $context = [AbstractNormalizer::OBJECT_TO_POPULATE => $objectToPopulate];
        }

        $object = $this->serializer->deserialize($request->getContent(), $dtoClassName, 'json', $context);

        if (!$object instanceof DtoInterface) {
            throw new InvalidArgumentException(\sprintf('DtoExtractor supports only classes which implement %s', DtoInterface::class));
        }

        return $object;
    }
}
