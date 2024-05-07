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

namespace StfalconStudio\ApiBundle\Request\ValueResolver;

use StfalconStudio\ApiBundle\Request\Filter\FilterExtractorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * FilterValueResolver.
 */
class FilterValueResolver implements ValueResolverInterface
{
    /**
     * @param iterable<FilterExtractorInterface> $filterExtractors
     */
    public function __construct(private readonly iterable $filterExtractors)
    {
    }

    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return iterable
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $extractor = $this->getSupportedExtractor($request, $argument);
        if (!$extractor instanceof FilterExtractorInterface) {
            return [];
        }

        yield $extractor->extractFilterFromRequest($request);
    }

    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return ?FilterExtractorInterface
     */
    private function getSupportedExtractor(Request $request, ArgumentMetadata $argument): ?FilterExtractorInterface
    {
        foreach ($this->filterExtractors as $extractor) {
            if ($extractor->supports($request, $argument)) {
                return $extractor;
            }
        }

        return null;
    }
}
